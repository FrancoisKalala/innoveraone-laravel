<div class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 min-h-screen">
    <div class="max-w-3xl mx-auto p-8 mt-12 rounded-2xl shadow-2xl bg-slate-900/80 backdrop-blur">
        <div class="flex items-center gap-6 mb-8">
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-blue-700/50 bg-slate-700/50 flex items-center justify-center">
                @if($contact->profile_photo_path)
                    <img src="{{ asset('storage/' . $contact->profile_photo_path) }}" alt="Profile" class="w-full h-full object-cover">
                @else
                    <span class="text-5xl font-bold text-white">{{ substr($contact->name, 0, 1) }}</span>
                @endif
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">{{ $contact->name }}</h2>
                <div class="text-blue-400 text-lg font-mono">@{{ $contact->username ?? strtolower(str_replace(' ', '', $contact->name)) }}</div>
                <p class="text-gray-400 mt-2">{{ $contact->bio ?? 'No bio provided.' }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Email</label>
                <div class="px-3 py-2 bg-slate-800 rounded text-white text-sm">{{ $contact->email }}</div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Last seen</label>
                <div class="px-3 py-2 bg-slate-800 rounded text-white text-sm">{{ $contact->updated_at->diffForHumans() }}</div>
            </div>
        </div>
        <div class="flex gap-4 mt-6">
            <button class="px-6 py-2 rounded-full bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 transition">Send Message</button>
            <button class="px-6 py-2 rounded-full bg-slate-700 text-white font-semibold shadow hover:bg-slate-800 transition">Add to Contacts</button>
        </div>
        <div class="mt-10">
            <h3 class="mb-4 text-xl font-bold text-white">Mutual Contacts</h3>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach($mutualContacts as $mutual)
                    <div class="p-4 bg-slate-800 rounded-xl shadow">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold text-blue-400">{{ substr($mutual->name, 0, 1) }}</span>
                            <span class="text-white">{{ $mutual->name }}</span>
                        </div>
                        <div class="text-xs text-gray-400">@{{ $mutual->username ?? strtolower(str_replace(' ', '', $mutual->name)) }}</div>
                    </div>
                @endforeach
                @if($mutualContacts->count() === 0)
                    <div class="text-gray-400">No mutual contacts.</div>
                @endif
            </div>
        </div>
    </div>
</div>
