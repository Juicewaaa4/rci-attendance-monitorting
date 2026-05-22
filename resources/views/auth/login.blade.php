@extends('layout.app')

@section('title', 'Login | RCI AMS')

@vite(['resources/css/login.blade.css'])

@section('content')
    <div class="login-container">
        <div class="image-section" style="background-image: url('/images/rci1.jpg');"></div>
        <div class="login-section">
            <h2 class="logo">Login</h2>
            <form method="POST" action="{{ route('process-login') }}">
                @csrf
                <div class="input-group">
                    <input type="text" name="identifier" class="form-control" placeholder="Email or Machine Name" required
                        autocomplete="username">
                </div>
                <div class="input-group">
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password"
                            required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="bx bx-show"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn login-btn">Login</button>
            </form>
            {{-- <a href="{{ route('recover') }}" class="forgot-password">Forgot Password?</a> --}}
        </div>
    </div>

    @section('scripts')
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            passwordInput.type = passwordInput.type === "password" ? "text" : "password";
            icon.classList.toggle('bx-show');
            icon.classList.toggle('bx-hide');
        });
    </script>
    @endsection
@endsection