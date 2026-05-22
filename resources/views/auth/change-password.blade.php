@extends('layout.app')

@section('content')

@vite(['resources/css/change-password.blade.css'])

<div class="container">
    <div class="card">
        <!-- Left Panel -->
        <div class="info-panel">
            <p>Enter your new password and confirm it below to update your credentials.</p>
        </div>

        <!-- Right Panel -->
        <div class="form-panel">
            <h4 class="card-title">Change Password</h4>
            <form action="{{ route('process-change-password') }}" method="post">
                @csrf
                @method('PUT')

                <label for="password" class="form-label">New Password</label>
                <input type="password" name="password" id="password" class="form-control" required>

                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
