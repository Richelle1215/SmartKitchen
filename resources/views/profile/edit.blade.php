@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-600 mt-2">Update your profile information</p>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 mb-6">
        <!-- Status Messages -->
        @if (session('status') === 'profile-updated')
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="font-medium text-green-800">Success!</h3>
                    <p class="text-sm text-green-700 mt-1">Your profile has been updated successfully.</p>
                </div>
            </div>
        @endif

        @if (session('status') === 'profile-picture-updated')
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="font-medium text-green-800">Success!</h3>
                    <p class="text-sm text-green-700 mt-1">Your profile picture has been updated successfully.</p>
                </div>
            </div>
        @endif

        <!-- Profile Form -->
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

        <!-- Profile Picture Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
            <div class="flex items-center gap-6">
                <!-- Current Picture -->
                <div class="flex-shrink-0">
                    @if ($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->name }}" 
                             class="w-24 h-24 rounded-full object-cover border-2 border-gray-300">
                    @else
                        <div class="w-24 h-24 rounded-full border-2 border-gray-300 bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-600">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- Upload Form -->
                <div class="flex-grow">
                    <form action="{{ route('profile.upload-picture') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                        @csrf
                        <input 
                            type="file"
                            name="profile_picture"
                            id="profile_picture"
                            accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            required
                        />
                        @error('profile_picture')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <span>✗</span> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-gray-500">PNG, JPG or GIF (max. 2MB)</p>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-fit"
                        >
                            Upload Picture
                        </button>
                    </form>
                </div>
            </div>
        </div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input 
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('name') border-red-500 @enderror"
                />
                @error('name')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span>✗</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input 
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('email') border-red-500 @enderror"
                />
                @error('email')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span>✗</span> {{ $message }}
                    </p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">If you change your email, you'll need to verify it again</p>
            </div>

            <!-- Bio Field -->
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                <textarea 
                    id="bio"
                    name="bio"
                    rows="4"
                    placeholder="Tell us about yourself..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('bio') border-red-500 @enderror"
                >{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span>✗</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-4 border-t">
                <button 
                    type="submit"
                    class="px-6 py-2 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                >
                    Save Changes
                </button>
                <a 
                    href="{{ route('profile.dashboard') }}"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Password Section -->
    <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Password & Security</h2>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600">Change your password to keep your account secure</p>
                <p class="text-sm text-gray-500 mt-1">Last changed: Unknown</p>
            </div>
            <a 
                href="{{ route('profile.change-password') }}"
                class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200"
            >
                Change Password
            </a>
        </div>
    </div>

    <!-- Delete Account Section -->
    <div class="bg-white rounded-lg shadow-md p-8 border border-red-200 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 text-red-600">Danger Zone</h2>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600">Delete your account permanently</p>
                <p class="text-sm text-gray-500 mt-1">This action cannot be undone. All your data will be deleted.</p>
            </div>
            <button 
                onclick="if(confirm('Are you sure? This cannot be undone.')) { document.getElementById('deleteForm').submit(); }"
                class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-200"
            >
                Delete Account
            </button>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="deleteForm" action="{{ route('profile.destroy') }}" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
        <input type="password" name="password" placeholder="Confirm password" required>
    </form>
</div>
@endsection
