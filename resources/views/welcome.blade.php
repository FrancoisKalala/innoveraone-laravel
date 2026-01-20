<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InnoveraOne - Social Network</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(20px, -50px) scale(1.1); }
            50% { transform: translate(-20px, 20px) scale(0.9); }
            75% { transform: translate(50px, 50px) scale(1.05); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .blob { animation: blob 15s ease-in-out infinite; }
        .float { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 via-30% via-blue-900 via-60% to-slate-900 text-white overflow-hidden relative">
        <!-- Animated Background Blobs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="blob absolute top-0 -left-4 w-96 h-96 bg-blue-700 rounded-full mix-blend-multiply filter blur-3xl opacity-15"></div>
            <div class="blob absolute top-0 -right-4 w-96 h-96 bg-blue-700 rounded-full mix-blend-multiply filter blur-3xl opacity-15" style="animation-delay: 2s;"></div>
            <div class="blob absolute -bottom-8 left-20 w-96 h-96 bg-blue-700 rounded-full mix-blend-multiply filter blur-3xl opacity-15" style="animation-delay: 4s;"></div>
        </div>

        <!-- Navigation -->
        <nav class="relative z-50 px-6 py-6">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-700 to-black rounded-2xl flex items-center justify-center shadow-lg shadow-blue-700/50 float">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">InnoveraOne</h1>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-gradient-to-r from-blue-700 to-black rounded-xl hover:shadow-lg hover:shadow-blue-700/50 transition-all transform hover:scale-105 font-semibold">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 text-gray-300 hover:text-white transition-all hover:bg-white/5 rounded-xl">Login</a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gradient-to-r from-blue-700 to-black rounded-xl hover:shadow-lg hover:shadow-blue-700/50 transition-all transform hover:scale-105 font-semibold">
                            Sign Up
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative px-6 py-20">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Side - Content -->
                    <div class="space-y-8" x-data="{}" x-init="setTimeout(() => $el.classList.add('opacity-100', 'translate-y-0'), 100)" class="opacity-0 translate-y-10 transition-all duration-1000">
                        <div>
                            <h2 class="text-5xl lg:text-7xl font-bold mb-6 leading-tight">
                                Connect. Share. <span class="bg-gradient-to-r from-blue-600 via-blue-700 to-black bg-clip-text text-transparent animate-pulse">Inspire.</span>
                            </h2>
                            <p class="text-xl lg:text-2xl text-gray-300 leading-relaxed">
                                Join thousands of creators sharing their moments, thoughts, and stories in real-time. Express yourself without limits.
                            </p>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="group px-8 py-4 bg-gradient-to-r from-blue-700 to-black rounded-xl font-bold text-center hover:shadow-2xl hover:shadow-blue-700/50 transition-all transform hover:scale-105">
                                    <span class="flex items-center justify-center gap-2">
                                        🚀 Go to Dashboard
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </span>
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="group px-8 py-4 bg-gradient-to-r from-blue-700 to-black rounded-xl font-bold text-center hover:shadow-2xl hover:shadow-blue-700/50 transition-all transform hover:scale-105">
                                    <span class="flex items-center justify-center gap-2">
                                        ✨ Create Account Free
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </span>
                                </a>
                                <a href="{{ route('guest.feed') }}" class="px-8 py-4 bg-white/5 backdrop-blur-sm border border-blue-700/30 rounded-xl font-bold text-center hover:border-blue-700/70 hover:bg-white/10 transition-all">
                                    👀 Browse as Guest
                                </a>
                            @endauth
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-6 pt-8 border-t border-blue-700/20">
                            <div class="text-center lg:text-left">
                                <p class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">50K+</p>
                                <p class="text-gray-400 text-sm mt-1">Active Users</p>
                            </div>
                            <div class="text-center lg:text-left">
                                <p class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">500K+</p>
                                <p class="text-gray-400 text-sm mt-1">Posts Shared</p>
                            </div>
                            <div class="text-center lg:text-left">
                                <p class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">100%</p>
                                <p class="text-gray-400 text-sm mt-1">Free Forever</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Visual -->
                    <div class="relative h-96 lg:h-[600px]" x-data="{}" x-init="setTimeout(() => $el.classList.add('opacity-100', 'translate-x-0'), 300)" class="opacity-0 translate-x-10 transition-all duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-700/20 to-black/20 rounded-3xl blur-3xl"></div>
                        <div class="relative bg-gradient-to-br from-slate-800/80 to-slate-900/80 rounded-3xl border border-blue-700/30 p-6 lg:p-8 backdrop-blur-xl overflow-hidden shadow-2xl h-full">
                            <!-- Decorative elements -->
                            <div class="absolute top-0 right-0 w-40 h-40 bg-blue-700/10 rounded-full blur-2xl"></div>
                            <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-700/10 rounded-full blur-2xl"></div>

                            <div class="relative space-y-4 h-full overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: rgba(168, 85, 247, 0.5) transparent;">
                                <!-- Mock Post 1 -->
                                <div class="bg-slate-700/40 backdrop-blur-sm rounded-xl p-4 border border-blue-700/20 hover:border-blue-700/40 transition-all hover:scale-[1.02] transform">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-700 to-black shadow-lg"></div>
                                        <div>
                                            <p class="font-semibold text-sm">Sarah Mitchell</p>
                                            <p class="text-xs text-gray-400">@sarahm • 2m ago</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-300 mb-3">Just launched my new project! 🚀 Feeling amazing about the progress</p>
                                    <div class="flex gap-2 text-xs">
                                        <span class="px-3 py-1.5 bg-blue-700/20 text-blue-300 rounded-lg backdrop-blur">❤️ 234 Likes</span>
                                        <span class="px-3 py-1.5 bg-blue-500/20 text-blue-300 rounded-lg backdrop-blur">💬 45 Comments</span>
                                    </div>
                                </div>

                                <!-- Mock Post 2 -->
                                <div class="bg-slate-700/40 backdrop-blur-sm rounded-xl p-4 border border-blue-700/20 hover:border-blue-700/40 transition-all hover:scale-[1.02] transform">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 shadow-lg"></div>
                                        <div>
                                            <p class="font-semibold text-sm">Alex Rivera</p>
                                            <p class="text-xs text-gray-400">@alexrivera • 5m ago</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-300 mb-3">Beautiful sunset today. Nature is incredible 🌅</p>
                                    <div class="flex gap-2 text-xs">
                                        <span class="px-3 py-1.5 bg-blue-700/20 text-blue-300 rounded-lg backdrop-blur">❤️ 512 Likes</span>
                                        <span class="px-3 py-1.5 bg-blue-500/20 text-blue-300 rounded-lg backdrop-blur">💬 89 Comments</span>
                                    </div>
                                </div>

                                <!-- Mock Post 3 -->
                                <div class="bg-slate-700/40 backdrop-blur-sm rounded-xl p-4 border border-blue-700/20 hover:border-blue-700/40 transition-all hover:scale-[1.02] transform">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 shadow-lg"></div>
                                        <div>
                                            <p class="font-semibold text-sm">Jordan Lee</p>
                                            <p class="text-xs text-gray-400">@jordanlee • 8m ago</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-300">Anyone want to collaborate on a new startup? 💡</p>
                                    <div class="flex gap-2 text-xs mt-3">
                                        <span class="px-3 py-1.5 bg-blue-700/20 text-blue-300 rounded-lg backdrop-blur">❤️ 156 Likes</span>
                                        <span class="px-3 py-1.5 bg-blue-500/20 text-blue-300 rounded-lg backdrop-blur">💬 42 Comments</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="relative px-6 py-20 border-t border-blue-700/20">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h3 class="text-4xl lg:text-5xl font-bold mb-4">Why Choose InnoveraOne?</h3>
                    <p class="text-xl text-gray-400">Everything you need to connect and share</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Feature 1 -->
                    <div class="group cursor-pointer">
                        <div class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-2xl border border-blue-700/20 p-6 group-hover:border-blue-700/50 group-hover:shadow-xl group-hover:shadow-blue-700/20 transition-all transform group-hover:-translate-y-2 h-full">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-black rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all shadow-lg">
                                <span class="text-2xl">📝</span>
                            </div>
                            <h4 class="font-bold text-lg mb-3">Share Posts</h4>
                            <p class="text-gray-400 text-sm leading-relaxed">Express yourself with text, images, and videos. Your voice matters.</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group cursor-pointer">
                        <div class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-2xl border border-blue-700/20 p-6 group-hover:border-cyan-500/50 group-hover:shadow-xl group-hover:shadow-cyan-500/20 transition-all transform group-hover:-translate-y-2 h-full">
                            <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all shadow-lg">
                                <span class="text-2xl">💬</span>
                            </div>
                            <h4 class="font-bold text-lg mb-3">Instant Messaging</h4>
                            <p class="text-gray-400 text-sm leading-relaxed">Connect directly with your friends via real-time messages.</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group cursor-pointer">
                        <div class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-2xl border border-blue-700/20 p-6 group-hover:border-green-500/50 group-hover:shadow-xl group-hover:shadow-green-500/20 transition-all transform group-hover:-translate-y-2 h-full">
                            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all shadow-lg">
                                <span class="text-2xl">👥</span>
                            </div>
                            <h4 class="font-bold text-lg mb-3">Join Groups</h4>
                            <p class="text-gray-400 text-sm leading-relaxed">Create or join communities around your interests.</p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="group cursor-pointer">
                        <div class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-2xl border border-blue-700/20 p-6 group-hover:border-orange-500/50 group-hover:shadow-xl group-hover:shadow-orange-500/20 transition-all transform group-hover:-translate-y-2 h-full">
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all shadow-lg">
                                <span class="text-2xl">🎵</span>
                            </div>
                            <h4 class="font-bold text-lg mb-3">Share Media</h4>
                            <p class="text-gray-400 text-sm leading-relaxed">Create beautiful albums and share your favorite moments.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative px-6 py-20 border-t border-blue-700/20">
            <div class="max-w-4xl mx-auto text-center">
                <div class="bg-gradient-to-r from-blue-700/20 via-blue-800/20 to-black/20 border border-blue-700/40 rounded-3xl p-12 backdrop-blur-xl shadow-2xl shadow-blue-700/20">
                    <h3 class="text-4xl lg:text-5xl font-bold mb-4">Ready to join the community?</h3>
                    <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">Start sharing your story with InnoveraOne today. No credit card required, always free.</p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="group px-10 py-4 bg-gradient-to-r from-blue-700 to-black rounded-xl font-bold hover:shadow-2xl hover:shadow-blue-700/50 transition-all transform hover:scale-105">
                                <span class="flex items-center justify-center gap-2">
                                    Go to Dashboard
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </span>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="group px-10 py-4 bg-gradient-to-r from-blue-700 to-black rounded-xl font-bold hover:shadow-2xl hover:shadow-blue-700/50 transition-all transform hover:scale-105">
                                <span class="flex items-center justify-center gap-2">
                                    Create Free Account
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </span>
                            </a>
                            <a href="{{ route('guest.feed') }}" class="px-10 py-4 bg-white/5 backdrop-blur-sm border border-blue-700/40 rounded-xl font-bold hover:border-blue-700/70 hover:bg-white/10 transition-all">
                                Explore First
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative px-6 py-12 border-t border-blue-700/20 mt-12">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-700 to-black rounded-xl flex items-center justify-center shadow-lg shadow-blue-700/50">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-lg">InnoveraOne</h4>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed">Connecting people, sharing stories, building communities.</p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4 text-blue-400">Product</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors hover:pl-1 inline-block transition-all">Features</a></li>
                            <li><a href="#" class="hover:text-white transition-colors hover:pl-1 inline-block transition-all">Pricing</a></li>
                            <li><a href="#" class="hover:text-white transition-colors hover:pl-1 inline-block transition-all">Security</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4 text-blue-400">Company</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors hover:pl-1 inline-block transition-all">About</a></li>
                            <li><a href="#" class="hover:text-white transition-colors hover:pl-1 inline-block transition-all">Blog</a></li>
                            <li><a href="#" class="hover:text-white transition-colors hover:pl-1 inline-block transition-all">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4 text-blue-400">Legal</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors hover:pl-1 inline-block transition-all">Privacy</a></li>
                            <li><a href="#" class="hover:text-white transition-colors hover:pl-1 inline-block transition-all">Terms</a></li>
                            <li><a href="#" class="hover:text-white transition-colors hover:pl-1 inline-block transition-all">Cookies</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-blue-700/20 pt-8 text-center">
                    <p class="text-gray-500 text-sm mb-2">&copy; 2024 InnoveraOne. All rights reserved. 🚀</p>
                    <p class="text-gray-600 text-xs">Made with ❤️ for the community</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

