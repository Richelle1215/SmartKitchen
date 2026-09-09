<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="smart-auth-form">
        @csrf

        <div class="smart-auth-field">
            <label for="name" class="smart-auth-label">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="smart-auth-input" />
            @error('name')
                <p class="smart-auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="smart-auth-field">
            <label for="email" class="smart-auth-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="smart-auth-input" />
            @error('email')
                <p class="smart-auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="smart-auth-field">
            <label for="password" class="smart-auth-label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="smart-auth-input" />
            @error('password')
                <p class="smart-auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="smart-auth-field">
            <label for="password_confirmation" class="smart-auth-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="smart-auth-input" />
            @error('password_confirmation')
                <p class="smart-auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="smart-auth-actions">
            <a class="smart-auth-link" href="{{ route('login') }}">Already registered?</a>
            <button type="submit" class="smart-auth-button">Register</button>
        </div>
    </form>
</x-guest-layout>
