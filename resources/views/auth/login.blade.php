<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="smart-auth-form">
        @csrf

        <div class="smart-auth-field">
            <label for="email" class="smart-auth-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="smart-auth-input" />
            @error('email')
                <p class="smart-auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="smart-auth-field">
            <label for="password" class="smart-auth-label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="smart-auth-input" />
            @error('password')
                <p class="smart-auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="smart-auth-meta">
            <label for="remember_me" class="smart-auth-remember">
                <input id="remember_me" type="checkbox" class="smart-auth-checkbox" name="remember">
                <span>Remember me</span>
            </label>
        </div>

        <div class="smart-auth-actions">
            @if (Route::has('password.request'))
                <a class="smart-auth-link" href="{{ route('password.request') }}">Forgot your password?</a>
            @else
                <span></span>
            @endif

            <button type="submit" class="smart-auth-button">Log In</button>
        </div>
    </form>
</x-guest-layout>
