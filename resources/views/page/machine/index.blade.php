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
                    <video id="videoElement" autoplay playsinline muted style="width: 100%; border-radius: 8px;"></video>

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

<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.13/dist/face-api.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async function () {
    const video = document.getElementById('videoElement');
    const rfidInput = document.getElementById('rfidInput');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');

    // Autofocus RFID field
    rfidInput.focus();

    // Load face-api.js models
    const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.13/model';
    async function loadModels() {
        console.log('Loading face models...');
        try {
            await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            console.log('Face models loaded successfully');
        } catch(e) {
            console.error('Face models failed to load: ', e);
        }
    }
    await loadModels();

    // Function to start a specific camera
    function startCamera(deviceId) {
        const constraints = deviceId ? { video: { deviceId: { exact: deviceId } } } : { video: true };
        
        navigator.mediaDevices.getUserMedia(constraints)
            .then(stream => video.srcObject = stream)
            .catch(error => {
                console.warn("Preferred camera failed, trying default camera...", error);
                // Fallback to any available camera if the specific one fails
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(stream => video.srcObject = stream)
                    .catch(err => alert("Camera error: " + err.name + " - " + err.message));
            });
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

    async function processAttendance(rfid) {
        // Capture image
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL('image/png');

        Swal.fire({
            title: 'Processing...',
            text: 'Scanning face and verifying RFID...',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        // Generate face encoding
        let faceEncoding = null;
        try {
            const detection = await faceapi.detectSingleFace(canvas).withFaceLandmarks().withFaceDescriptor();
            if (detection) {
                faceEncoding = Array.from(detection.descriptor);
            }
        } catch(e) {
            console.error('Face detection failed:', e);
        }

        fetch('/process/login/rfid', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ rfid: rfid, image: imageData, face_encoding: faceEncoding })
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
