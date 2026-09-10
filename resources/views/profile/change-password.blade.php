@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Change Password</h1>
        <p class="text-gray-600 mt-2">Update your password to keep your account secure</p>
    </div>

    <!-- Change Password Card -->
    <div class="bg-white rounded-lg shadow-md p-8 border border-gray-200">
        <!-- Status Messages -->
        @if (session('status') === 'password-updated')
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="font-medium text-green-800">Success!</h3>
                    <p class="text-sm text-green-700 mt-1">Your password has been changed successfully.</p>
                </div>
            </div>
        @endif

        <!-- Change Password Form -->
        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input 
                    id="current_password"
                    type="password"
                    name="current_password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your current password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('current_password') border-red-500 @enderror"
                />
                @error('current_password')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span>✗</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input 
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Enter your new password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('password') border-red-500 @enderror"
                />
                <p class="mt-1 text-xs text-gray-500">At least 8 characters, mix of numbers and symbols</p>
                @error('password')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span>✗</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <input 
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your new password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('password_confirmation') border-red-500 @enderror"
                />
                @error('password_confirmation')
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
                    Update Password
                </button>
                <a 
                    href="{{ route('profile.edit') }}"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Security Tips -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="font-semibold text-blue-900 mb-3">🔐 Security Tips</h3>
        <ul class="space-y-2 text-sm text-blue-800">
            <li class="flex gap-2">
                <span>•</span>
                <span>Use a strong password with a mix of uppercase, lowercase, numbers, and symbols</span>
            </li>
            <li class="flex gap-2">
                <span>•</span>
                <span>Don't reuse passwords from other accounts</span>
            </li>
            <li class="flex gap-2">
                <span>•</span>
                <span>Change your password regularly (at least every 6 months)</span>
            </li>
            <li class="flex gap-2">
                <span>•</span>
                <span>Never share your password with anyone</span>
            </li>
        </ul>
    </div>
</div>
@endsection
