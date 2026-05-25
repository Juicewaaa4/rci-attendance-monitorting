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
    // Start loading models in the background (do not await)
    loadModels();

    let currentStream = null;

    // Stop any existing camera stream
    function stopCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(t => t.stop());
            currentStream = null;
        }
        video.srcObject = null;
    }

    // Sample actual pixels to detect a black/dead stream
    function isCameraBlack() {
        try {
            const testCanvas = document.createElement('canvas');
            testCanvas.width = 64;
            testCanvas.height = 64;
            const testCtx = testCanvas.getContext('2d');
            testCtx.drawImage(video, 0, 0, 64, 64);
            const pixels = testCtx.getImageData(0, 0, 64, 64).data;
            let total = 0;
            for (let i = 0; i < pixels.length; i += 4) {
                total += pixels[i] + pixels[i + 1] + pixels[i + 2];
            }
            const avg = total / (pixels.length / 4 * 3);
            return avg < 8; // brightness below 8/255 = effectively black
        } catch (e) {
            return false;
        }
    }

    // Start camera with retry logic + black-frame detection
    async function startCamera(attempt = 1, maxAttempts = 4) {
        stopCamera();
        cameraStatus.textContent = attempt > 1 ? `🔄 Retrying camera (attempt ${attempt} of ${maxAttempts})...` : '⏳ Starting camera...';
        cameraStatus.style.color = '#888';
        retryCameraBtn.style.display = 'none';

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            cameraStatus.textContent = '❌ Browser does not support camera access.';
            cameraStatus.style.color = 'red';
            retryCameraBtn.style.display = 'inline-block';
            return;
        }

        // Check permission state
        if (navigator.permissions) {
            try {
                const perm = await navigator.permissions.query({ name: 'camera' });
                if (perm.state === 'denied') {
                    cameraStatus.innerHTML = '❌ Camera blocked. Click the 🔒 icon in the address bar and allow camera, then refresh.';
                    cameraStatus.style.color = 'red';
                    retryCameraBtn.style.display = 'inline-block';
                    return;
                }
            } catch(e) { /* permissions API not supported */ }
        }

        // Drop constraints on later attempts (fallback to any camera)
        const videoConstraints = attempt <= 2
            ? { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' }
            : true;

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: videoConstraints,
                audio: false
            });

            currentStream = stream;
            video.srcObject = null; // clear stale srcObject
            await new Promise(r => setTimeout(r, 100));
            video.srcObject = stream;

            // Wait for metadata + play
            await new Promise((resolve, reject) => {
                const timeout = setTimeout(() => reject(new Error('Camera timed out after 10s')), 10000);
                video.onloadedmetadata = () => {
                    clearTimeout(timeout);
                    video.play().then(resolve).catch(reject);
                };
                video.onerror = () => { clearTimeout(timeout); reject(new Error('Video element error')); };
            });

            // Longer warm-up so camera sensor has time to produce real light
            await new Promise(r => setTimeout(r, 1200));

            if (!video.videoWidth || !video.videoHeight) {
                throw new Error('Camera reported 0×0 — stream invalid');
            }
            if (isCameraBlack()) {
                throw new Error('Camera is black (no light). May be covered or used by another app.');
            }

            cameraStatus.textContent = '✅ Camera active.';
            cameraStatus.style.color = 'green';
            rfidInput.focus();
        } catch (err) {
            console.error(`Camera attempt ${attempt} failed:`, err);
            stopCamera();

            if (attempt < maxAttempts) {
                await new Promise(r => setTimeout(r, 2000));
                return startCamera(attempt + 1, maxAttempts);
            }

            cameraStatus.innerHTML = '❌ Camera failed: ' + err.message +
                '<br><small>Close other apps/tabs using the camera, then click Retry. Or check camera permissions in the browser address bar (🔒).</small>';
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
