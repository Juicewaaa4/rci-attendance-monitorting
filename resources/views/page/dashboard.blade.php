@extends('layout.app')

@section('title', 'Dashboard | RCI AMS')

@section('content')

@vite(['resources/css/dashboard.blade.css'])

<div class="container">
    <div class="left-section">
        <h1>WELCOME</h1>
        <h2>{{ Auth::user()->first_name }} {{ Auth::user()->middle_name ?? '' }} {{ Auth::user()->last_name }}</h2>
        <p>
            A card-based student attendance monitoring system is an automated system that monitors student attendance via RFID.
            Students may tap or scan their cards at the designated readers, which is logging real-time attendance.
        </p>

        <div class="buttons">
            {{-- Admin-only buttons --}}
            @if (Auth::check() && Auth::user()->role === 'Admin')
                <a class="btn btn-primary" href="{{ route('instructor') }}">Manage Accounts</a>

                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="manageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Manage School
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="manageDropdown">
                        <li><a class="dropdown-item" href="{{ route('department') }}">Manage Departments</a></li>
                        <li><a class="dropdown-item" href="{{ route('program') }}">Manage Programs</a></li>
                        <li><a class="dropdown-item" href="{{ route('year-level') }}">Manage Year Levels</a></li>
                        <li><a class="dropdown-item" href="{{ route('section') }}">Manage Sections</a></li>
                        <li><a class="dropdown-item" href="{{ route('student') }}">Manage Students</a></li>
                    </ul>
                </div>
            @endif

            {{-- //Instructor-only button// --}}
            @if (Auth::check() && Auth::user()->role === 'Instructor')
                <a class="btn btn-primary" href="{{ route('instructor.record') }}">Instructor Record</a>
            @endif

            {{-- Common buttons --}}
            <a class="btn btn-primary" href="{{ route('attendance') }}">View Student Attendance</a>
            <a class="btn btn-primary" href="{{ route('report') }}">Attendance Analytics</a>
        </div>
    </div>

    <div class="right-section">
        <img src="{{ asset('images/rci.jpg') }}" alt="School Image">
    </div>
</div>

@endsection
