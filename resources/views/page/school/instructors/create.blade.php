@extends('layout.app')

@section('title','Create Account | RCI AMS')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="w-50">
        <form id="multiStepForm" action="{{ route('process-create-account') }}" method="post">
            @csrf <!-- CSRF Token -->

            <!-- Step 1: Personal Information -->
            <div class="form-step {{ session('step', 1) == 1 ? 'd-block' : 'd-none' }}" id="step1">
                <h4>Personal Information</h4>
                <div class="mb-2">
                    <label for="first-name" class="form-label">First Name</label>
                    <input type="text" class="form-control form-control-sm @error('first-name') is-invalid @enderror"
                        id="first-name" name="first-name" value="{{ old('first-name') }}" required>
                    @error('first-name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-2">
                    <label for="middle-name" class="form-label">Middle Name</label>
                    <input type="text" class="form-control form-control-sm" id="middle-name" name="middle-name"
                        value="{{ old('middle-name') }}">
                </div>
                <div class="mb-2">
                    <label for="last-name" class="form-label">Last Name</label>
                    <input type="text" class="form-control form-control-sm @error('last-name') is-invalid @enderror"
                        id="last-name" name="last-name" value="{{ old('last-name') }}" required>
                    @error('last-name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <a href="{{ route('instructor') }}" class="btn btn-secondary btn-sm">Cancel</a>
                <button type="button" class="btn btn-primary btn-sm" onclick="nextStep(1, 2)">Next</button>
            </div>

            <div class="form-step {{ session('step') == 2 ? 'd-block' : 'd-none' }}" id="step2">
                <h4>Assign Department & Program</h4>
                <div class="row">
                   <div class="col-md-6">
    <div class="mb-3">
        <label for="department" class="form-label">Department</label>
        <select name="department_id" id="department"
            class="form-control form-control-sm @error('department_id') is-invalid @enderror" required>
            <option value="" disabled selected>Select Department</option>
            @foreach($departments as $department)
                <option value="{{ $department->department_id }}" {{ old('department_id') == $department->department_id ? 'selected' : '' }}>
                    {{ $department->department_name }}
                </option>
            @endforeach
        </select>
        @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="mb-3">
        <label for="program" class="form-label">Program</label>
        <select name="program_id" id="program"
            class="form-control form-control-sm @error('program_id') is-invalid @enderror" required>
            <option value="" disabled selected>Select Program</option>
            @foreach($programs as $program)
                <option value="{{ $program->program_id }}"
                    data-department-id="{{ $program->department_id }}"
                    data-year-levels="{{ json_encode($program->year_levels ?? [1,2,3,4]) }}"
                    {{ old('program_id') == $program->program_id ? 'selected' : '' }}>
                    {{ $program->program_name }}
                </option>
            @endforeach
        </select>
        @error('program_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="mb-3">
        <label for="year-level" class="form-label">Year Level</label>
        <select name="year_level_id" id="year-level"
            class="form-control form-control-sm @error('year_level_id') is-invalid @enderror" required>
            <option value="" disabled selected>Select Year Level</option>
            @foreach($yearLevels as $year)
                <option value="{{ $year->year_level_id }}"
                    data-department-id="{{ $year->department_id }}"
                    {{ old('year_level_id') == $year->year_level_id ? 'selected' : '' }}>
                    {{ $year->year_level_name }}
                </option>
            @endforeach
        </select>
        @error('year_level_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>


<div class="col-md-12">
    <div class="mb-3">
        <label for="sections" class="form-label">Select Sections</label>
        <div id="section-checkboxes" class="form-group @error('section_ids') is-invalid @enderror"></div>
        @error('section_ids') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        <small class="text-muted">Sections depend on selected Program and Year Level.</small>
    </div>
</div>


                </div>

                <button type="button" class="btn btn-secondary btn-sm" onclick="prevStep(2, 1)">Previous</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="nextStep(2, 3)">Next</button>
            </div>


            <!-- Step 3: Account Information -->
            <div class="form-step {{ session('step', 1) == 3 ? 'd-block' : 'd-none' }}" id="step3">
                <h4>Account Information</h4>
                <div class="mb-2">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-sm" id="password" name="password" value="{{ old('password') }}" required>
                </div>
                <div class="mb-2">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" value="{{ old('password') }}" value="{{ old('password') }}" required>
                </div>


    
                <button type="button" class="btn btn-secondary btn-sm" onclick="prevStep(3, 2)">Previous</button>
                <button type="submit" class="btn btn-primary btn-sm">Create Account</button>
            </div>
        </form>
    </div>
</div>

<script>
    function nextStep(current, next) {
        const currentStep = document.getElementById(`step${current}`);
        const inputs = currentStep.querySelectorAll('input[required], select[required]');

        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) return; // Stop if any required field is empty

        currentStep.classList.remove('d-block');
        currentStep.classList.add('d-none');
        document.getElementById(`step${next}`).classList.remove('d-none');
        document.getElementById(`step${next}`).classList.add('d-block');
    }

    function prevStep(current, prev) {
        document.getElementById(`step${current}`).classList.remove('d-block');
        document.getElementById(`step${current}`).classList.add('d-none');
        document.getElementById(`step${prev}`).classList.remove('d-none');
        document.getElementById(`step${prev}`).classList.add('d-block');
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const departmentSelect = document.getElementById('department');
    const programSelect = document.getElementById('program');
    const yearLevelSelect = document.getElementById('year-level');
    const sectionContainer = document.getElementById('section-checkboxes');

    // Cache data passed from Laravel
    const programs = @json($programs);
    const yearLevels = @json($yearLevels);
    const sections = @json($sections);
    const oldSections = @json(old('section_ids', []));
    const oldProgramId = @json(old('program_id'));
    const oldYearLevelId = @json(old('year_level_id'));

    // 🔹 When Department changes → Filter Programs
    departmentSelect.addEventListener('change', function () {
        const deptId = this.value;
        departmentSelect.dataset.userChanged = true;

        // Reset the program dropdown
        programSelect.innerHTML = '<option value="" disabled selected>Select Program</option>';

        // Append programs that belong to this department
        programs.forEach(program => {
            if (String(program.department_id) === String(deptId)) {
                const opt = document.createElement('option');
                opt.value = program.program_id;
                opt.textContent = program.program_name;
                opt.dataset.departmentId = program.department_id;
                if (!departmentSelect.dataset.userChanged && program.program_id === oldProgramId) {
    opt.selected = true;
}
                programSelect.appendChild(opt);
            }
        });

        // Reset year levels and sections
        yearLevelSelect.innerHTML = '<option value="" disabled selected>Select Year Level</option>';
        sectionContainer.innerHTML = "";
    });

    // 🔹 When Program changes → Render Year Levels
    programSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        if (!selectedOption) return;

        const deptId = selectedOption.dataset.departmentId;

        // Rebuild year level dropdown based on department
        yearLevelSelect.innerHTML = '<option value="" disabled selected>Select Year Level</option>';

        yearLevels.forEach(lv => {
            if (String(lv.department_id) === String(deptId)) {
                const opt = document.createElement('option');
                opt.value = lv.year_level_id;
                opt.textContent = lv.year_level_name;
                if (lv.year_level_id === oldYearLevelId) opt.selected = true;
                yearLevelSelect.appendChild(opt);
            }
        });

        sectionContainer.innerHTML = ""; // reset sections until year level chosen
    });

    // 🔹 When Year Level changes → Show Sections
    yearLevelSelect.addEventListener('change', function () {
        const programId = programSelect.value;
        const yearLevelId = this.value;

        sectionContainer.innerHTML = '';

        const filtered = sections.filter(s =>
            String(s.program_id) === String(programId) &&
            String(s.year_level_id) === String(yearLevelId)
        );

        // Sort alphabetically by section name
        filtered.sort((a, b) => a.section_name.localeCompare(b.section_name, undefined, { numeric: true }));

        if (filtered.length === 0) {
            sectionContainer.innerHTML = '<p class="text-muted">No sections found for this Year Level.</p>';
            return;
        }

        // Render section checkboxes
        filtered.forEach(section => {
            const div = document.createElement('div');
            div.classList.add('form-check');

            const input = document.createElement('input');
            input.type = 'checkbox';
            input.name = 'section_ids[]';
            input.value = section.section_id;
            input.id = `section-${section.section_id}`;
            input.classList.add('form-check-input');
            if (oldSections.includes(section.section_id)) input.checked = true;

            const label = document.createElement('label');
            label.classList.add('form-check-label');
            label.setAttribute('for', `section-${section.section_id}`);
            label.textContent = section.section_name;

            div.appendChild(input);
            div.appendChild(label);
            sectionContainer.appendChild(div);
        });
    });

    // 🔹 Initialize defaults if user is returning after validation error
    if (oldProgramId) {
        const selectedProgram = programs.find(p => p.program_id === oldProgramId);
        if (selectedProgram) {
            // Pre-fill department
            departmentSelect.value = selectedProgram.department_id;

            // Trigger department change to repopulate programs
            departmentSelect.dispatchEvent(new Event('change'));

            // Trigger program change to repopulate year levels
            programSelect.value = oldProgramId;
            programSelect.dispatchEvent(new Event('change'));

            // Trigger year level change to repopulate sections
            if (oldYearLevelId) {
                yearLevelSelect.value = oldYearLevelId;
                yearLevelSelect.dispatchEvent(new Event('change'));
            }
        }
    }
});
</script>








@endsection