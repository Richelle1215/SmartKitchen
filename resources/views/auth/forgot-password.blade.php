<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Forgot Password?</h1>
        <p class="text-gray-600 text-sm mt-1">No problem! Enter your email and we'll send you a reset link.</p>
    </div>

    <!-- Status -->
    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-700 text-sm">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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

        <!-- Send Button -->
        <button 
            type="submit" 
            class="w-full mt-6 bg-orange-500 text-white font-semibold py-2 rounded-lg hover:bg-orange-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
        >
            Send Reset Link
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
        Remember your password?
        <a href="{{ route('login') }}" class="font-semibold text-orange-600 hover:text-orange-700">Sign in</a>
    </div>
</x-guest-layout>
