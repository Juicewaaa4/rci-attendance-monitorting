@extends('layout.app')

@section('content')

@vite(['resources/css/confirm-password.blade.css'])

<div class="change-password-container">
    <div class="form-wrapper">
        <!-- Left Panel -->
        <div class="left-panel">
            <p class="instruction-text">Input your current password for confirmation</p>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <h3 class="form-title">Change Your Password</h3>
            <form action="{{ route('process-confirm-password') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="current-password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" name="current-password" required>
                </div>

                <div class="button-group">
                    <a href="#" class="btn cancel-btn">Cancel</a>
                    <button type="submit" class="btn submit-btn">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
