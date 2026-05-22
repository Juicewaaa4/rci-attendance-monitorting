<!-- Edit Student Record Modal -->
<div class="modal fade" id="edit-student-modal" tabindex="-1" aria-labelledby="edit-student-modal-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-student-modal-label">Edit Student Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-student-form" action="{{route('process-edit-student-record')}}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit-record-id" name="record_id">

                    <!-- Name Fields -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-student-first-name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="edit-student-first-name" name="first-name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-student-last-name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="edit-student-last-name" name="last-name" required>
                        </div>
                    </div>

                    <!-- Middle Name & Student ID -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-student-middle-name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="edit-student-middle-name" name="middle-name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-student-id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="edit-student-id" name="student-id" pattern="\d+"
                                title="Only numbers are allowed" required>
                            <label for="edit-student-id" class="form-label text-muted">The Student ID must be unique</label>
                        </div>
                    </div>

                    <!-- Department & RFID -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit-student-department" class="form-label">Department</label>
                                <select name="department-id" id="edit-student-department" class="form-control">
                                    <option value="" disabled selected>Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student-rfid" class="form-label">RFID</label>
                                <input type="text" class="form-control" id="edit-student-rfid" name="rfid" pattern="\d+"
                                    title="Only numbers are allowed" required>
                            </div>
                        </div>
                    </div>

                    <!-- Program & Section -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit-student-program" class="form-label">Program</label>
                                <select name="program-id" id="edit-student-program" class="form-control">
                                    <option value="" disabled selected>Select Program</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->program_id }}" data-department-id="{{ $program->department_id }}">
                                            {{ $program->program_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit-student-section" class="form-label">Section</label>
                                <select name="section-id" id="edit-student-section" class="form-control">
                                    <option value="" disabled selected>Select Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->section_id }}" data-department-id="{{ $section->department_id }}">
                                            {{ $section->section_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Year Level & School Year -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit-student-year-level" class="form-label">Year Level</label>
                                <select name="year-level-id" id="edit-student-year-level" class="form-control">
                                    <option value="" disabled selected>Select Year Level</option>
                                    @foreach($yearlevels as $yearlevel)
                                        <option value="{{ $yearlevel->year_level_id }}" data-department-id="{{ $yearlevel->department_id }}">
                                            {{ $yearlevel->year_level_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="school-year" class="form-label">School Year</label>
                                <select name="school_year_id" id="edit-student-school-year" class="form-control">
                                    <option value="" disabled selected>Select School Year</option>
                                    @foreach($schoolyears as $schoolyear)
                                        <option value="{{ $schoolyear->school_year_id }}">{{ $schoolyear->school_year_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end ms-5">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Filtering Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const departmentSelect = document.getElementById('edit-student-department');
    const sectionSelect = document.getElementById('edit-student-section');
    const yearLevelSelect = document.getElementById('edit-student-year-level');

    const allSections = Array.from(sectionSelect.options);
    const allYearLevels = Array.from(yearLevelSelect.options);

    function filterOptions() {
        const selectedDept = departmentSelect.value;

        // Filter Sections
        sectionSelect.innerHTML = '';
        sectionSelect.appendChild(new Option('Select Section', '', true, true)).disabled = true;
        allSections.forEach(option => {
            if (!option.value || option.dataset.departmentId === selectedDept) {
                sectionSelect.appendChild(option.cloneNode(true));
            }
        });

        // Filter Year Levels
        yearLevelSelect.innerHTML = '';
        yearLevelSelect.appendChild(new Option('Select Year Level', '', true, true)).disabled = true;
        allYearLevels.forEach(option => {
            if (!option.value || option.dataset.departmentId === selectedDept) {
                yearLevelSelect.appendChild(option.cloneNode(true));
            }
        });
    }

    departmentSelect.addEventListener('change', filterOptions);

    // Run filter automatically when modal opens
    $('#edit-student-modal').on('shown.bs.modal', function () {
        filterOptions();
    });
});
</script>
