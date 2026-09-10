<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Verify Email Address</h1>
        <p class="text-gray-600 text-sm mt-1">We sent a verification link to your email</p>
    </div>

    <!-- Status Message -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <div>
                <h3 class="font-medium text-green-800">Email Sent!</h3>
                <p class="text-sm text-green-700 mt-1">A new verification link has been sent to your email address.</p>
            </div>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-blue-800 text-sm">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? 
                If you didn't receive the email, we will gladly send you another.
            </p>
        </div>

        <!-- Resend Verification Form -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button 
                type="submit"
                class="w-full bg-orange-500 text-white font-semibold py-2 rounded-lg hover:bg-orange-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
            >
                Resend Verification Email
            </button>
        </form>

        <!-- Logout Form -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button 
                type="submit"
                class="w-full border border-gray-300 text-gray-700 font-semibold py-2 rounded-lg hover:bg-gray-50 transition duration-200"
            >
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
