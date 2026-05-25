<!-- Create Student Modal -->
<div class="modal fade" id="create-student-modal" tabindex="-1" aria-labelledby="create-student-modal-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create-student-modal-label">Create Student Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>
            <div class="modal-body">

    {{-- 🔹 Show error message for duplicate student_id or RFID --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('process-create-student-record') }}" method="POST" enctype="multipart/form-data">

    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="last-name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last-name" name="last_name" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="first-name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first-name" name="first_name" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="middle-name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middle-name" name="middle_name">
                        </div>

                        <div class="col-md-6 mb-3">
    <label for="student-id" class="form-label">Student ID</label>
    <input type="text" class="form-control @error('student_id') is-invalid @enderror"
           id="student-id" name="student_id" pattern="\d+"
           title="Only numbers are allowed" required value="{{ old('student_id') }}">
    @error('student_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student-department" class="form-label">Department</label>
                                <select name="department_id" id="student-department" class="form-control">
                                    <option value="" disabled selected>Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->department_id }}">
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
    <div class="mb-3">
        <label for="student-rfid" class="form-label">RFID</label>
        <input type="text" class="form-control @error('rfid') is-invalid @enderror"
               id="student-rfid" name="rfid" pattern="\d+"
               title="Only numbers are allowed" value="{{ old('rfid') }}">
        @error('rfid')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student-program" class="form-label">Program</label>
                                <select name="program_id" id="student-program" class="form-control">
                                    <option value="" disabled selected>Select Program</option>
                                    <!-- options loaded dynamically via AJAX -->
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student-year-level" class="form-label">Year Level</label>
                                <select name="year_level_id" id="student-year-level" class="form-control">
                                    <option value="" disabled selected>Select Year Level</option>
                                    <!-- options loaded dynamically via AJAX -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student-section" class="form-label">Section</label>
                                <select name="section_id" id="student-section" class="form-control">
                                    <option value="" disabled selected>Select Section</option>
                                    <!-- options loaded dynamically via AJAX -->
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="school-year" class="form-label">School Year</label>
                                <select name="school_year_id" id="school-year" class="form-control">
                                    <option value="" disabled selected>Select School Year</option>
                                    @foreach($schoolyears as $schoolyear)
                                        <option value="{{ $schoolyear->school_year_id }}">
                                            {{ $schoolyear->school_year_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="row">
    <<div class="col-md-12 mb-3">
    <label class="form-label">Face Image</label>
    <video id="camera" width="100%" autoplay playsinline muted style="border:1px solid #ccc; border-radius:5px;"></video>
    <canvas id="snapshot" style="display:none;"></canvas>
    <input type="hidden" name="face_image" id="face_image_base64">
    <input type="hidden" name="face_encoding" id="face_encoding_data">
    <div id="face-status" style="margin-top:5px; font-size:12px; color:#888;">Waiting for capture...</div>
    <button type="button" class="btn btn-primary mt-2" id="capture-btn">Capture Photo</button>
    
    <!-- Placeholder container -->
    <div id="captured-preview-container" 
         style="margin-top:10px; border:1px solid #ccc; border-radius:5px; 
                height:200px; display:flex; align-items:center; justify-content:center; 
                color:#888; font-style:italic; overflow:hidden;">
        No photo taken
    </div>
</div>
</div>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary float-end ms-5" 
        onclick="if(!faceImageInput.value){alert('Please capture photo'); return false;}">
    Create
</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery only once -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- face-api.js for browser-based face encoding -->
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.13/dist/face-api.js"></script>

<script>
$(document).ready(function () {

    // Prevent Enter in RFID input from submitting form
    $('#student-rfid').on('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            return false;
        }
    });

    // Store all sections from Laravel
    let allSections = @json($sections); // Make sure $sections contains program_id and year_level_id

    // Function to sort sections numerically by section_name
    function sortSectionsNumerically(sectionsArray) {
        return sectionsArray.sort((a, b) => {
            const aNum = parseInt(a.section_name.replace(/\D/g,'')) || 0;
            const bNum = parseInt(b.section_name.replace(/\D/g,'')) || 0;
            return aNum - bNum;
        });
    }

    // Render sections based on selected program and year level
    function renderSections() {
    const programId = $('#student-program').val();
    const yearLevelId = $('#student-year-level').val();

    if (!programId || !yearLevelId) {
        $('#student-section').html('<option disabled selected>Select Section</option>');
        return;
    }

    // Filter by both program and year level
    let filteredSections = allSections.filter(s => 
        String(s.program_id) === String(programId) &&
        String(s.year_level_id) === String(yearLevelId)
    );

    filteredSections = sortSectionsNumerically(filteredSections);

    let options = '<option disabled selected>Select Section</option>';
    if (filteredSections.length > 0) {
        filteredSections.forEach(s => {
            options += `<option value="${s.section_id}">${s.section_name}</option>`;
        });
    } else {
        options += '<option disabled>No sections found</option>';
    }

    $('#student-section').html(options);
}


    // Department change -> Load programs and year levels
    $('#student-department').on('change', function () {
        let departmentId = $(this).val();
        if (!departmentId) return;

        // Programs
        $('#student-program').html('<option disabled selected>Loading programs...</option>');
        $.get("{{ route('get-programs', '') }}/" + departmentId, function (data) {
            let options = '<option disabled selected>Select Program</option>';
            if (data && data.length) data.forEach(p => { options += `<option value="${p.program_id}">${p.program_name}</option>`; });
            else options += '<option disabled>No programs found</option>';
            $('#student-program').html(options);
        }).fail(function () { $('#student-program').html('<option disabled selected>Error loading programs</option>'); });

        // Year Levels
        $('#student-year-level').html('<option disabled selected>Loading year levels...</option>');
        $.get("{{ route('get-year-levels', '') }}/" + departmentId, function (data) {
            let options = '<option disabled selected>Select Year Level</option>';
            if (data && data.length) {
                data.sort((a,b) => parseInt(a.year_level_name) - parseInt(b.year_level_name));
                data.forEach(y => { options += `<option value="${y.year_level_id}">${y.year_level_name}</option>`; });
            } else options += '<option disabled>No year levels found</option>';
            $('#student-year-level').html(options);
        }).fail(function () { $('#student-year-level').html('<option disabled selected>Error loading year levels</option>'); });

        // Reset sections
        $('#student-section').html('<option disabled selected>Select Section</option>');
    });

    // Program or Year Level change -> render sections
    $('#student-program, #student-year-level').on('change', renderSections);

    let video = document.getElementById('camera');
    let canvas = document.getElementById('snapshot');
    let captureBtn = document.getElementById('capture-btn');
    let faceImageInput = document.getElementById('face_image_base64');
    let faceEncodingInput = document.getElementById('face_encoding_data');
    let faceStatus = document.getElementById('face-status');

    // Load face-api.js models
    const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.13/model';
    async function loadModels() {
        faceStatus.textContent = 'Loading face models...';
        faceStatus.style.color = '#888';
        try {
            await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            faceStatus.textContent = 'Face models ready. Capture photo to proceed.';
            faceStatus.style.color = 'green';
        } catch(e) {
            faceStatus.textContent = 'Warning: Face models failed to load. ' + e.message;
            faceStatus.style.color = 'orange';
        }
    }
    loadModels();

    let currentStream = null;
    let cameraRetryBtn = null;

    // Inject a retry button below the video
    (function () {
        cameraRetryBtn = document.createElement('button');
        cameraRetryBtn.type = 'button';
        cameraRetryBtn.className = 'btn btn-warning btn-sm mt-1';
        cameraRetryBtn.textContent = '\uD83D\uDD04 Retry Camera';
        cameraRetryBtn.style.display = 'none';
        video.parentNode.insertBefore(cameraRetryBtn, video.nextSibling);
        cameraRetryBtn.addEventListener('click', () => initCamera());
    })();

    // Check if video is producing actual frames
    function isVideoActive() {
        return video.readyState >= 2 && video.videoWidth > 0 && video.videoHeight > 0;
    }

    function stopCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
            currentStream = null;
        }
        video.srcObject = null;
    }

    async function initCamera(attempt = 1, maxAttempts = 3) {
        stopCamera();
        cameraRetryBtn.style.display = 'none';

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            faceStatus.textContent = 'Browser does not support camera access.';
            faceStatus.style.color = 'red';
            cameraRetryBtn.style.display = 'inline-block';
            return;
        }

        faceStatus.textContent = attempt > 1 ? `Retrying camera (attempt ${attempt})...` : 'Starting camera...';
        faceStatus.style.color = '#888';

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' },
                audio: false
            });

            currentStream = stream;
            video.srcObject = stream;

            // Wait for metadata + play
            await new Promise((resolve, reject) => {
                const timeout = setTimeout(() => reject(new Error('Camera stream timed out')), 8000);
                video.onloadedmetadata = () => {
                    clearTimeout(timeout);
                    video.play().then(resolve).catch(reject);
                };
                video.onerror = () => { clearTimeout(timeout); reject(new Error('Video element error')); };
            });

            // Give a moment then verify real frames
            await new Promise(r => setTimeout(r, 500));
            if (!isVideoActive()) {
                throw new Error('Camera stream is black or inactive');
            }

            console.log('Camera started. Resolution:', video.videoWidth, 'x', video.videoHeight);
            faceStatus.textContent = 'Camera active. Capture photo to proceed.';
            faceStatus.style.color = 'green';
        } catch (err) {
            console.error(`Camera attempt ${attempt} failed:`, err);
            stopCamera();

            if (attempt < maxAttempts) {
                await new Promise(r => setTimeout(r, 1500));
                return initCamera(attempt + 1, maxAttempts);
            }

            faceStatus.textContent = 'Cannot access camera: ' + err.message + '. Click Retry.';
            faceStatus.style.color = 'red';
            cameraRetryBtn.style.display = 'inline-block';
        }
    }
    
    // Start camera only when modal opens
    $('#create-student-modal').on('shown.bs.modal', function () {
        initCamera();
    });

    // Capture photo and generate face encoding
    captureBtn.addEventListener('click', async function () {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        let dataUrl = canvas.toDataURL('image/png');
        faceImageInput.value = dataUrl;

        document.getElementById('captured-preview-container').innerHTML =
            `<img src="${dataUrl}" alt="Captured Image" style="display:block; max-width:100%; border-radius:5px;" />`;

        // Generate face encoding using face-api.js
        faceStatus.textContent = 'Detecting face and generating encoding...';
        faceStatus.style.color = '#888';
        try {
            const detection = await faceapi
                .detectSingleFace(canvas)
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (detection) {
                const descriptor = Array.from(detection.descriptor);
                faceEncodingInput.value = JSON.stringify(descriptor);
                faceStatus.textContent = '✅ Face detected! Encoding generated successfully.';
                faceStatus.style.color = 'green';
            } else {
                faceEncodingInput.value = '';
                faceStatus.textContent = '⚠️ No face detected. Please retake photo facing the camera.';
                faceStatus.style.color = 'red';
            }
        } catch(e) {
            faceEncodingInput.value = '';
            faceStatus.textContent = '⚠️ Face encoding failed: ' + e.message;
            faceStatus.style.color = 'orange';
        }
    });

    // Blur any focused element inside modal BEFORE it gets aria-hidden (fixes Edge warning)
    $('#create-student-modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });

    // Reset when modal closes
    $('#create-student-modal').on('hidden.bs.modal', function () {
        stopCamera();
        cameraRetryBtn.style.display = 'none';

        faceImageInput.value = '';
        faceEncodingInput.value = '';
        faceStatus.textContent = 'Waiting for capture...';
        faceStatus.style.color = '#888';
        document.getElementById('captured-preview-container').innerHTML = 'No photo taken';
    });

});
</script>

