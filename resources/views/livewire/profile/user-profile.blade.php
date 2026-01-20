<div class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 rounded-2xl border border-blue-700/20 p-6 space-y-6">
    @if(session()->has('success'))
        <div class="p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col md:flex-row gap-6">
        <!-- User Info -->
        <div class="flex-1">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                User Profile
            </h3>

            <form class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                        <div class="px-4 py-2 bg-slate-800 rounded-lg text-white">{{ auth()->user()->name }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                        <div class="px-4 py-2 bg-slate-800 rounded-lg text-white">@{{ auth()->user()->username }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <div class="px-4 py-2 bg-slate-800 rounded-lg text-white">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Bio</label>
                        <textarea class="w-full px-4 py-2 rounded-lg bg-slate-800 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition" rows="3" placeholder="Tell us about yourself...">{{ auth()->user()->bio }}</textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Stats -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl p-6 space-y-4 md:w-64">
            <h4 class="font-bold text-white mb-4">Quick Stats</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Posts</span>
                    <span class="text-2xl font-bold text-blue-600">{{ auth()->user()->posts()->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Albums</span>
                    <span class="text-2xl font-bold text-blue-600">{{ auth()->user()->albums()->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Followers</span>
                    <span class="text-2xl font-bold text-blue-600">{{ auth()->user()->followers()->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Following</span>
                    <span class="text-2xl font-bold text-blue-600">{{ auth()->user()->following()->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Contacts</span>
                    <span class="text-2xl font-bold text-blue-600">{{ auth()->user()->contacts()->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="border-t border-blue-700/20 pt-6">
        <h4 class="font-bold text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
            Last Updated
        </h4>
        <p class="text-gray-400 text-sm">{{ auth()->user()->updated_at->diffForHumans() }}</p>
    </div>
</div>

