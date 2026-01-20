<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up - InnoveraOne</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(168, 85, 247, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(236, 72, 153, 0.4);
            }
        }
        .animate-in {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }
        .delay-6 { animation-delay: 0.6s; }
        .glow-effect {
            animation: glow 3s ease-in-out infinite;
        }
        .animate-blob { animation: blob 8s ease-in-out infinite; }
        .delay-2000 { animation-delay: 2s; }
        .delay-4000 { animation-delay: 4s; }
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px rgba(55, 65, 81, 0.5) inset !important;
            -webkit-text-fill-color: white !important;
        }
        .glass {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(168, 85, 247, 0.2);
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.05); }
            66% { transform: translate(-20px, 20px) scale(0.98); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
    </style>
</head>
<body class="antialiased bg-slate-950">
    <!-- Animated Background Blobs -->
    <div class="fixed inset-0 -z-20 overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-600/20 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-pink-600/20 rounded-full mix-blend-multiply filter blur-3xl animate-blob delay-2000"></div>
        <div class="absolute -bottom-8 left-1/2 w-96 h-96 bg-blue-600/20 rounded-full mix-blend-multiply filter blur-3xl animate-blob delay-4000"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8 relative">
        <div class="w-full max-w-md">
            <!-- Logo Section -->
            <div class="text-center mb-12 animate-in delay-1">
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 rounded-3xl blur-2xl opacity-75 glow-effect"></div>
                        <div class="relative w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-500 rounded-3xl flex items-center justify-center shadow-2xl">
                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <h1 class="text-6xl font-black bg-gradient-to-r from-purple-300 via-pink-300 to-purple-300 bg-clip-text text-transparent mb-3">InnoveraOne</h1>
                <p class="text-gray-300 text-lg font-medium">Join millions connecting and innovating</p>
            </div>

            <!-- Register Card -->
            <div class="glass rounded-3xl p-8 space-y-6 shadow-2xl animate-in delay-2">
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="p-4 bg-gradient-to-r from-red-500/10 to-pink-500/10 border border-red-500/30 rounded-2xl backdrop-blur-sm">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl flex-shrink-0">⚠️</span>
                            <div>
                                <p class="font-bold text-red-300 mb-2">Registration Error</p>
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-200/80 text-sm">• {{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div class="animate-in delay-2 group">
                        <label for="name" class="block text-sm font-bold text-gray-200 mb-3 flex items-center gap-2">
                            <span class="text-lg">👤</span>
                            <span>Full Name</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            class="w-full px-6 py-3.5 rounded-xl bg-slate-700/50 text-white placeholder-gray-400 border-2 border-purple-500/30 focus:border-purple-400 focus:outline-none focus:ring-4 focus:ring-purple-500/20 transition-all duration-300 backdrop-blur hover:bg-slate-700/70"
                            placeholder="John Doe"
                        >
                    </div>

                    <!-- Email -->
                    <div class="animate-in delay-2 group">
                        <label for="email" class="block text-sm font-bold text-gray-200 mb-3 flex items-center gap-2">
                            <span class="text-lg">📧</span>
                            <span>Email Address</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-6 py-3.5 rounded-xl bg-slate-700/50 text-white placeholder-gray-400 border-2 border-purple-500/30 focus:border-purple-400 focus:outline-none focus:ring-4 focus:ring-purple-500/20 transition-all duration-300 backdrop-blur hover:bg-slate-700/70"
                            placeholder="you@innovera.com"
                        >
                    </div>

                    <!-- Username -->
                    <div class="animate-in delay-3 group">
                        <label for="username" class="block text-sm font-bold text-gray-200 mb-3 flex items-center gap-2">
                            <span class="text-lg">🎯</span>
                            <span>Username</span>
                            <span class="text-xs text-gray-400 ml-auto font-normal">2-20 characters</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 transform -translate-y-1/2 text-purple-400 font-bold">@</span>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                class="w-full pl-14 pr-6 py-3.5 rounded-xl bg-slate-700/50 text-white placeholder-gray-400 border-2 border-purple-500/30 focus:border-purple-400 focus:outline-none focus:ring-4 focus:ring-purple-500/20 transition-all duration-300 backdrop-blur hover:bg-slate-700/70"
                                placeholder="johndoe"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="animate-in delay-3 group">
                        <label for="password" class="block text-sm font-bold text-gray-200 mb-3 flex items-center gap-2">
                            <span class="text-lg">🔐</span>
                            <span>Password</span>
                            <span class="text-xs text-gray-400 ml-auto font-normal">Min 8 characters</span>
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full px-6 py-3.5 rounded-xl bg-slate-700/50 text-white placeholder-gray-400 border-2 border-purple-500/30 focus:border-purple-400 focus:outline-none focus:ring-4 focus:ring-purple-500/20 transition-all duration-300 backdrop-blur hover:bg-slate-700/70"
                                placeholder="Create a strong password"
                            >
                            <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-purple-400 hover:text-purple-300 transition" onclick="togglePassword('password')">
                                👁️
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="animate-in delay-4 group">
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-200 mb-3 flex items-center gap-2">
                            <span class="text-lg">✔️</span>
                            <span>Confirm Password</span>
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                class="w-full px-6 py-3.5 rounded-xl bg-slate-700/50 text-white placeholder-gray-400 border-2 border-purple-500/30 focus:border-purple-400 focus:outline-none focus:ring-4 focus:ring-purple-500/20 transition-all duration-300 backdrop-blur hover:bg-slate-700/70"
                                placeholder="Repeat your password"
                            >
                            <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-purple-400 hover:text-purple-300 transition" onclick="togglePassword('password_confirmation')">
                                👁️
                            </button>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="animate-in delay-4 flex items-start gap-3 bg-purple-500/10 rounded-xl p-4 backdrop-blur-sm border border-purple-500/20">
                        <input
                            type="checkbox"
                            id="agree"
                            name="agree"
                            required
                            class="w-5 h-5 rounded-lg border-purple-500/30 bg-slate-700/50 text-purple-500 focus:ring-purple-500 cursor-pointer accent-purple-500 transition mt-1 flex-shrink-0"
                        >
                        <label for="agree" class="text-sm text-gray-300 cursor-pointer">
                            I agree to the <a href="#" class="text-purple-400 hover:text-pink-400 font-semibold transition">Terms of Service</a> and <a href="#" class="text-purple-400 hover:text-pink-400 font-semibold transition">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Sign Up Button -->
                    <button
                        type="submit"
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-purple-500 via-pink-500 to-purple-500 text-white font-bold text-lg rounded-xl hover:shadow-2xl hover:shadow-purple-500/50 transition-all duration-300 transform hover:scale-105 active:scale-95 animate-in delay-5"
                    >
                        🎉 Create My Account
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative py-3">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-purple-500/20"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-3 bg-slate-950 text-xs text-gray-400 font-bold uppercase tracking-wider">Have Account?</span>
                    </div>
                </div>

                <!-- Login Link -->
                <a
                    href="{{ route('login') }}"
                    class="w-full block py-3.5 px-4 bg-gradient-to-r from-slate-700/80 to-slate-800/80 text-white font-bold rounded-xl border border-purple-500/30 hover:border-purple-500/60 hover:from-slate-700 hover:to-slate-700 transition-all duration-300 text-center backdrop-blur-sm hover:shadow-lg animate-in delay-5"
                >
                    🔑 Login Instead
                </a>

                <!-- Guest Access -->
                <a
                    href="{{ route('guest.feed') }}"
                    class="w-full block py-2.5 px-4 text-center text-purple-300 hover:text-pink-300 text-sm font-bold transition duration-300 rounded-lg hover:bg-purple-500/10 animate-in delay-6"
                >
                    👀 Browse as Guest
                </a>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-center text-gray-400 text-xs animate-in delay-6">
                <p class="font-medium mb-2">✨ What's Included</p>
                <div class="flex items-center justify-center gap-4 text-xs text-gray-500">
                    <span>📱 All Features</span>
                    <span>•</span>
                    <span>🔒 Secure</span>
                    <span>•</span>
                    <span>⚡ Instant Access</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = event.target;
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '🙈';
            } else {
                input.type = 'password';
                button.textContent = '👁️';
            }
        }
    </script>
</body>
</html>

