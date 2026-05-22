@extends('layout.app')

@section('title','Settings | RCI AMS')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title mb-3">Account Settings</h4>
            <form action="{{ route('process-edit-instructor-account') }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="account_id" value="{{ $account->account_id }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first-name" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first-name" value="{{ $account->first_name }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="middle-name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middle-name" value="{{ $account->middle_name ?? old('middle-name') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="last-name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last-name" value="{{ $account->last_name }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="section_ids" class="form-label">Sections</label>
                        <select multiple class="form-select" name="section_ids[]" id="sections">
                            @foreach($sections as $section)
                            <option 
                                value="{{ $section->section_id }}" 
                                data-program-id="{{ $section->program_id }}" 
                                {{ collect(old('section_ids', $account->assignments->pluck('section_id') ?? []))->contains($section->section_id) ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                            @endforeach   
                        </select>
                        <div class="form-text">Hold Ctrl (Windows) or Command (Mac) to select multiple</div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="program" class="form-label">Program</label>
                        <select class="form-select" name="program-id" id="program">
                            <option value="" disabled selected>Select Program</option>
                            @foreach($programs as $program)
                            <option value="{{ $program->program_id }}"
                                data-department-id="{{ $program->department_id }}"
                                {{ $account->assignments->firstWhere('program_id', $program->program_id) ? 'selected' : '' }}>
                                {{ $program->program_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select" name="department-id" id="department">
                            <option value="" disabled selected>Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->department_id }}"
                                {{ $account->assignments->firstWhere('department_id', $department->department_id) ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="d-flex justify-content-between gap-5">
                        <a href="{{ route('instructor') }}" class="btn btn-secondary flex-fill">Return</a>
                        <button type="submit" class="btn btn-primary flex-fill">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const departmentSelect = document.getElementById('department');
        const programSelect = document.getElementById('program');
        const sectionSelect = document.getElementById('sections');

        const allProgramOptions = Array.from(programSelect.options);
        const allSectionOptions = Array.from(sectionSelect.options);

        const selectedSectionIds = Array.from(sectionSelect.selectedOptions).map(opt => opt.value);

        const placeholderOption = document.createElement('option');
        placeholderOption.disabled = true;
        placeholderOption.selected = true;
        placeholderOption.hidden = true;
        placeholderOption.text = 'Select Sections';
        placeholderOption.value = '';
        sectionSelect.innerHTML = '';
        sectionSelect.appendChild(placeholderOption);

        function filterProgramsByDepartment(departmentId) {
            programSelect.innerHTML = '';
            const defaultProgramOption = document.createElement('option');
            defaultProgramOption.value = '';
            defaultProgramOption.disabled = true;
            defaultProgramOption.selected = true;
            defaultProgramOption.text = 'Select Program';
            programSelect.appendChild(defaultProgramOption);

            allProgramOptions.forEach(option => {
                if (option.dataset.departmentId === departmentId) {
                    programSelect.appendChild(option.cloneNode(true));
                }
            });
        }

        function filterSectionsByProgram(programId) {
            sectionSelect.innerHTML = '';
            sectionSelect.appendChild(placeholderOption);

            allSectionOptions.forEach(option => {
                if (option.dataset.programId === programId) {
                    if (selectedSectionIds.includes(option.value)) {
                        option.selected = true;
                    }
                    sectionSelect.appendChild(option.cloneNode(true));
                }
            });
        }

        departmentSelect.addEventListener('change', function () {
            const selectedDeptId = this.value;
            filterProgramsByDepartment(selectedDeptId);

            programSelect.value = '';
            sectionSelect.innerHTML = '';
            sectionSelect.appendChild(placeholderOption);
        });

        programSelect.addEventListener('change', function () {
            const selectedProgramId = this.value;
            const selectedProgram = this.options[this.selectedIndex];
            const departmentId = selectedProgram.dataset.departmentId;

            if (departmentId) {
                departmentSelect.value = departmentId;
            }

            filterSectionsByProgram(selectedProgramId);
        });

        if (programSelect.value) {
            filterProgramsByDepartment(departmentSelect.value);
            filterSectionsByProgram(programSelect.value);
        }
    });
</script>


@endsection
