<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Reset Password</h1>
        <p class="text-gray-600 text-sm mt-1">Enter your new password below</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Input (Hidden but Required) -->
        <div class="hidden">
            <input 
                type="email" 
                name="email" 
                value="{{ $request->email }}" 
                required 
            />
        </div>

        <!-- Email Display -->
        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 text-sm text-gray-600">
            Resetting password for: <strong>{{ $request->email }}</strong>
        </div>

        <!-- New Password Input -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="••••••••"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('password') border-red-500 @enderror"
            />
            <p class="mt-1 text-xs text-gray-500">At least 8 characters, numbers, and symbols</p>
            @error('password')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    <span>✗</span> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Confirm Password Input -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="••••••••"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('password_confirmation') border-red-500 @enderror"
            />
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    <span>✗</span> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Reset Button -->
        <button 
            type="submit" 
            class="w-full mt-6 bg-orange-500 text-white font-semibold py-2 rounded-lg hover:bg-orange-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
        >
            Reset Password
        </button>
    </form>
</x-guest-layout>
