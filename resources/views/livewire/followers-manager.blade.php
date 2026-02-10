<a href="{{ route('user.profile', $follower->id) }}">
    <img src="{{ $follower->profile_photo_path ? asset('storage/' . $follower->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($follower->name) . '&background=8b5cf6&color=fff' }}" alt="{{ $follower->name }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0 hover:scale-105 transition">
</a>