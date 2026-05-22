@extends('layout.app')

@section('content')

@vite(['resources/css/confirm-otp-blade.css'])

<div class="recover-container">
    <div class="recover-card">
        <div class="recover-left">
            <h2>Welcome to the RCI AMS Recover Account!</h2>
            <p>Let’s recover your account! We’ve sent a <em>One-Time Password (OTP)</em> to your verified email. Please check your inbox and enter the code below to proceed.</p>
        </div>

        <div class="recover-right">
            <h4>Fill the following</h4>

            <!-- Email Form -->
            <form action="{{ route('process-send-otp') }}" method="POST" class="recover-form">
                @csrf
                <label for="email">Email</label>
                <input type="email" name="email" required value="{{ session('recovery_email', old('email')) }}" placeholder="Enter your email">
                <button type="submit" id="submitButton">Send</button>
            </form>
            <!-- OTP Form -->
            <form action="{{ route('process-confirm-otp') }}" method="POST" class="recover-form otp-form">
                @csrf
                <label for="otp">OTP</label>
                <div class="otp-boxes">
                    <input type="text" name="otp[]" maxlength="1" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <input type="text" name="otp[]" maxlength="1" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <input type="text" name="otp[]" maxlength="1" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <input type="text" name="otp[]" maxlength="1" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <input type="text" name="otp[]" maxlength="1" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <input type="text" name="otp[]" maxlength="1" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>

                <input type="hidden" name="otp_combined" value="">

                <div class="form-buttons">
                    <a href="{{ route('login') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelector('form.otp-form').addEventListener('submit', function() {
        let otpInputs = document.querySelectorAll('input[name="otp[]"]');
        let otpValue = '';

        otpInputs.forEach(input => {
            otpValue += input.value;
        });

        // Set the combined OTP value into a hidden input field to send in the request
        document.querySelector('input[name="otp_combined"]').value = otpValue;
    });

    // Function to handle success and modify button text to "Resend"
    function handleSuccess() {
        const submitButton = document.getElementById("submitButton");
        submitButton.innerText = "Resend";  // Change button text
        submitButton.style.backgroundColor = "#f39c12";  // Change button color to indicate OTP was sent
        submitButton.disabled = false;  // Enable the button for resending the OTP
    }
</script>

@endsection
