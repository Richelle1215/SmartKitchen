<!-- Profile Header Component -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-lg p-8 mb-6">
    <div class="flex flex-col md:flex-row items-center md:items-end gap-6">
        <!-- Profile Picture -->
        <div class="flex-shrink-0">
            @if ($user->profile_picture)
                <img src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->name }}" 
                     class="w-24 h-24 rounded-full border-4 border-white object-cover">
            @else
                <div class="w-24 h-24 rounded-full border-4 border-white bg-blue-400 flex items-center justify-center">
                    <span class="text-4xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
            @endif
        </div>

        <!-- User Info -->
        <div class="flex-grow text-center md:text-left">
            <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
            <p class="text-blue-100 mt-1">{{ $user->email }}</p>
            @if ($user->bio)
                <p class="text-blue-100 mt-2">{{ $user->bio }}</p>
            @else
                <p class="text-blue-100 mt-2 italic">No bio yet</p>
            @endif
            <p class="text-sm text-blue-100 mt-2">Joined {{ $user->created_at->format('F Y') }}</p>
        </div>

        <!-- Edit Button -->
        @if (isset($showEditButton) && $showEditButton)
            <div class="flex-shrink-0">
                <a href="{{ route('profile.edit') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                    Edit Profile
                </a>
            </div>
        @endif
    </div>
</div>
