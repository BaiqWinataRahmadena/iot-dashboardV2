@extends('layouts.auth')

@section('content')
<div class="login-container">

    <h2>Selamat Datang</h2>
    <p>Silakan login untuk mengakses dashboard.</p>

    <!-- Form Login -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
            @error('email')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            @error('password')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me Checkbox -->
        <div class="form-group" style="display: flex; align-items: center;">
            <input id="remember_me" type="checkbox" name="remember" style="width: auto; margin-right: 10px;">
            <label for="remember_me" style="margin: 0; cursor: pointer;">Ingat Saya</label>
        </div>

        <!-- Tombol Login -->
        <button type="submit" class="btn-login">
            Masuk
        </button>

        <!-- Footer Link (Lupa Password) -->
        <div class="footer-links">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Lupa password?</a>
            @endif
        </div>
    </form>
</div>
@endsection