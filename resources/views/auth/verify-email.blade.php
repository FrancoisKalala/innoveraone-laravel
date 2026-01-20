<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verify Email - InnoveraOne</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .animate-in{animation:fadeInUp .6s ease-out forwards;opacity:0}
    .delay-1{animation-delay:.1s}.delay-2{animation-delay:.2s}
    .glass{background:rgba(15,23,42,.7);backdrop-filter:blur(10px);border:1px solid rgba(59,130,246,.2)}
  </style>
</head>
<body class="antialiased bg-slate-950 min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-md">
    <div class="text-center mb-10 animate-in">
      <h1 class="text-4xl font-black bg-gradient-to-r from-blue-400 via-blue-500 to-black bg-clip-text text-transparent">InnoveraOne</h1>
      <p class="text-gray-400 mt-2">Verify your email address</p>
    </div>

    <div class="glass rounded-3xl p-8 shadow-2xl animate-in delay-1">
      @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-xl border border-green-500/40 bg-green-500/10 text-green-300 text-sm">
          A new verification link has been sent to your email address.
        </div>
      @endif

      <p class="text-gray-300 text-sm leading-relaxed">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn’t receive the email, we will gladly send you another.
      </p>

      <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
        @csrf
        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 via-blue-700 to-black text-white font-bold rounded-xl hover:shadow-2xl hover:shadow-blue-500/50 transition">Resend Verification Email</button>
      </form>

      <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="w-full py-3.5 bg-slate-800 text-white font-bold rounded-xl border border-blue-500/30 hover:border-blue-500/60 transition">Log Out</button>
      </form>
    </div>
  </div>
</body>
</html>

