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
                    <div id="camera-status" style="font-size:13px; color:#888; margin-top:4px;">Starting camera...</div>
                    <button id="retry-camera-btn" class="btn btn-warning btn-sm mt-2" style="display:none;">🔄 Retry Camera</button>

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
    const cameraStatus = document.getElementById('camera-status');
    const retryCameraBtn = document.getElementById('retry-camera-btn');

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

    let currentStream = null;

    // Stop any existing camera stream
    function stopCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(t => t.stop());
            currentStream = null;
        }
        video.srcObject = null;
    }

    // Check if the video is producing real frames (not black/frozen)
    function isVideoActive() {
        return video.readyState >= 2 && video.videoWidth > 0 && video.videoHeight > 0;
    }

    // Start camera with retry logic
    async function startCamera(attempt = 1, maxAttempts = 3) {
        stopCamera();
        cameraStatus.textContent = attempt > 1 ? `Retrying camera (attempt ${attempt})...` : 'Starting camera...';
        cameraStatus.style.color = '#888';
        retryCameraBtn.style.display = 'none';

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            cameraStatus.textContent = '❌ Browser does not support camera access.';
            cameraStatus.style.color = 'red';
            retryCameraBtn.style.display = 'inline-block';
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' },
                audio: false
            });

            currentStream = stream;
            video.srcObject = stream;

            // Wait for metadata and verify frames are coming through
            await new Promise((resolve, reject) => {
                const timeout = setTimeout(() => reject(new Error('Camera stream timed out')), 8000);
                video.onloadedmetadata = () => {
                    clearTimeout(timeout);
                    video.play().then(resolve).catch(reject);
                };
                video.onerror = (e) => { clearTimeout(timeout); reject(new Error('Video element error')); };
            });

            // Extra check: give a moment then verify actual frame data
            await new Promise(r => setTimeout(r, 500));
            if (!isVideoActive()) {
                throw new Error('Camera stream is black or inactive');
            }

            cameraStatus.textContent = '✅ Camera active.';
            cameraStatus.style.color = 'green';
            rfidInput.focus();
        } catch (err) {
            console.error(`Camera attempt ${attempt} failed:`, err);
            stopCamera();

            if (attempt < maxAttempts) {
                // Wait 1.5s between retries
                await new Promise(r => setTimeout(r, 1500));
                return startCamera(attempt + 1, maxAttempts);
            }

            cameraStatus.textContent = '❌ Camera failed: ' + err.message + '. Click Retry below.';
            cameraStatus.style.color = 'red';
            retryCameraBtn.style.display = 'inline-block';
        }
    }

    retryCameraBtn.addEventListener('click', () => startCamera());
    startCamera();

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
