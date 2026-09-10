<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Welcome Back</h1>
        <p class="text-gray-600 text-sm mt-1">Sign in to your SmartKitchen account</p>
    </div>

    <!-- Session Status -->
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-700 text-sm font-medium">Login failed. Please try again.</p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Input -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
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
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <a href="{{ route('password.request') }}" class="text-sm text-orange-600 hover:text-orange-700">Forgot password?</a>
            </div>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                placeholder="••••••••"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('password') border-red-500 @enderror"
            />
            @error('password')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    <span>✗</span> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input 
                id="remember_me" 
                type="checkbox" 
                name="remember" 
                class="rounded border-gray-300 text-orange-600 focus:ring-orange-500"
            />
            <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
        </div>

        <!-- Sign In Button -->
        <button 
            type="submit" 
            class="w-full mt-6 bg-orange-500 text-white font-semibold py-2 rounded-lg hover:bg-orange-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
        >
            Sign In
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-semibold text-orange-600 hover:text-orange-700">Sign up</a>
    </div>
</x-guest-layout>
