@extends('layout.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Attendance Capture</div>
                <div class="card-body">
                    
                    <!-- Logo at top-left above camera -->
                    <div class="mb-3 d-flex align-items-center">
                        <div style="width: 100px; height: 100px;
                                    background-image: url('/images/rci1.jpg');
                                    background-size: cover;
                                    background-position: center;
                                    border-radius: 8px;
                                    border: 2px solid #dee2e6;">
                        </div>
                       <div class="fw-bold" style="font-size: 32px; white-space: nowrap; position: absolute; left: 50%; transform: translateX(-50%);">
            Welcome Students
        </div>
                    </div>

                    <!-- Camera video -->
                    <video id="videoElement" autoplay style="width: 100%; border-radius: 8px;"></video>

                    <div class="mt-3">
                        <!-- RFID field is always focused -->
                        <input type="text" id="rfidInput" class="form-control" placeholder="Scan RFID">
                    </div>
                    <canvas id="canvas" style="display: none;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('videoElement');
    const rfidInput = document.getElementById('rfidInput');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');

    // Autofocus RFID field
    rfidInput.focus();

    // Function to start a specific camera
    function startCamera(deviceId) {
        navigator.mediaDevices.getUserMedia({ video: { deviceId: { exact: deviceId } } })
            .then(stream => video.srcObject = stream)
            .catch(error => alert("Camera error: " + error));
    }

    // Enumerate devices and pick USB webcam
    navigator.mediaDevices.enumerateDevices()
        .then(devices => {
            const videoDevices = devices.filter(d => d.kind === 'videoinput');
            if (videoDevices.length === 0) {
                alert('No camera found!');
                return;
            }

            console.log('Available cameras:', videoDevices);

            // Look for a device whose label includes "USB"
            const usbCam = videoDevices.find(d => d.label.toLowerCase().includes('usb')) || videoDevices[0];

            startCamera(usbCam.deviceId);
        })
        .catch(err => alert("Error enumerating devices: " + err));

    // Detect RFID scan (Enter key triggers process)
    rfidInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const rfid = rfidInput.value.trim();
            if (!rfid) return;

            processAttendance(rfid);
        }
    });

    function processAttendance(rfid) {
        // Capture image
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL('image/png');

        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we process the data.',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('/process/login/rfid', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ rfid: rfid, image: imageData })
        })
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            return res.json();
        })
        .then(data => {
            Swal.close();
            Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.success ? 'Success!' : 'Error',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
        })
        .catch(err => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: err.message,
                timer: 2000,
                showConfirmButton: false
            });
        })
        .finally(() => {
            rfidInput.value = '';
            rfidInput.focus(); // ready for next scan
        });
    }
});
</script>

{{-- logout shortcut --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    let keysPressed = {};
    document.addEventListener('keydown', function (event) {
        keysPressed[event.key.toLowerCase()] = true;
        if (keysPressed['r'] && keysPressed['c'] && keysPressed['i']) {
            window.location.href = "/";
        }
    });
    document.addEventListener('keyup', function (event) {
        keysPressed[event.key.toLowerCase()] = false;
    });
});
</script>
@endsection
