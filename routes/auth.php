<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\Password as PasswordBroker;

Route::middleware('guest')->group(function () {
    // Use custom Blade views for auth pages (replaces Volt defaults)
    Route::view('register', 'auth.register')
        ->name('register');

    Route::view('login', 'auth.login')
        ->name('login');

    // Handle login POST (since Volt route was replaced)
    Route::post('login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(Route::has('dashboard') ? route('dashboard') : '/');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    });

    // Handle register POST (since Volt route was replaced)
    Route::post('register', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'min:2', 'max:20', 'unique:users,username'],
            'password' => ['required', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->intended(Route::has('dashboard') ? route('dashboard') : '/');
    });

    // Forgot Password (GET form + POST send link)
    Route::view('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Route::post('forgot-password', function (Request $request) {
        $request->validate(['email' => ['required','email']]);

        $status = PasswordBroker::sendResetLink(
            ['email' => $request->input('email')]
        );

        return $status === PasswordBroker::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    })->name('password.email');

    // Reset Password (GET with token + POST to reset)
    Route::get('reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('reset-password', function (Request $request) {
        $request->validate([
            'token' => ['required'],
            'email' => ['required','email'],
            'password' => ['required', Password::min(8), 'confirmed'],
        ]);

        $status = PasswordBroker::reset(
            $request->only('email','password','password_confirmation','token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === PasswordBroker::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    })->name('password.update');
});

Route::middleware('auth')->group(function () {
    // Verify Email notice page
    Route::view('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Confirm Password (GET + POST)
    Route::view('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');

    Route::post('confirm-password', function (Request $request) {
        $request->validate(['password' => ['required']]);

        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors(['password' => __('auth.password')]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(Route::has('dashboard') ? route('dashboard') : '/');
    })->name('password.confirm.store');

    // Logout route
    Route::post('logout', function (Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});
