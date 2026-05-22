@extends('layout.app')

@section('title', 'Home | RCI AMS') <!-- Card-based Human Monitoring Framework -->

@vite(['resources/css/home.blade.css'])

@section('content')
<div class="welcome-container">
    <div class="welcome-left">
        <h1><span class="bold-text">WELCOME TO</span><br><span class="RCI AMS-text">RCI AMS</span></h1>
        <p class="description">
            A card-based student attendance monitoring system is an automated system that monitors
            student attendance via RFID. Students may tap or scan their cards at the designated readers,
            which is logging real-time attendance.
        </p>
        <a href="{{ route('login') }}" class="login-btn">LOG IN</a>
    </div>
    <div class="welcome-right">
        <img src="{{ asset('images/rci.jpg') }}" alt="Richwell Colleges Logo" class="logo-img">
    </div>
</div>
@endsection
