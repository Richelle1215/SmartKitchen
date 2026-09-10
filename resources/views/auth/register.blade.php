<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create Account</h1>
        <p class="text-gray-600 text-sm mt-1">Join SmartKitchen and start sharing recipes</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name Input -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="John Doe"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('name') border-red-500 @enderror"
            />
            @error('name')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    <span>✗</span> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Email Input -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autocomplete="email"
                placeholder="you@example.com"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('email') border-red-500 @enderror"
            />
            @error('email')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    <span>✗</span> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password Input -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
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

        <!-- Register Button -->
        <button 
            type="submit" 
            class="w-full mt-6 bg-orange-500 text-white font-semibold py-2 rounded-lg hover:bg-orange-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
        >
            Create Account
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-orange-600 hover:text-orange-700">Sign in</a>
    </div>
</x-guest-layout>
