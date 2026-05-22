@extends('layout.app')

@section('title','Settings | RCI AMS')

@section('content')

@vite(['resources/css/edit-account.blade.css'])

<div class="container mt-4">
    <div class="card">
        <div class="info-panel">
            <p>
                This page helps you update and control your account information. Keeping this correct allows us to keep records accurately and communicate with you effectively.
            </p>
        </div>
        <div class="form-panel">
            <h4 class="card-title">Account Settings</h4>
            <form action="{{ route('process-edit-account') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first-name" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first-name" value="{{ Auth::user()->first_name }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="middle-name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middle-name" value="{{ Auth::user()->middle_name }}">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="last-name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last-name" value="{{ Auth::user()->last_name }}">
                </div>

                <hr>
                <p class="text-muted">Input Current Password for Confirmation</p>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation">
                    </div>
                </div>

                <div class="d-flex justify-content-between gap-5 ">
                    <a href="{{ url()->current() }}" class="btn btn-secondary2 flex-fill">Cancel</a>
                    <button type="submit" class="btn btn-primary1 flex-fill">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection