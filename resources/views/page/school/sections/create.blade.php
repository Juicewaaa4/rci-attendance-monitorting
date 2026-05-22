<!-- Create Year Level Modal -->
<div class="modal fade" id="create-section-modal" tabindex="-1" aria-labelledby="create-section-modal-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create-section-modal-label">Create Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('process-create-section')}}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="section-name" class="form-label">Section</label>
                        <input type="text" class="form-control" id="section-name" name="section-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="section-description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="section-description" name="section-description">
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="section-code" class="form-label">Section Code</label>
                                <input type="text" class="form-control" id="section-code" name="section-code">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select name="department-id" id="section-department" class="form-control">
                                    <option value="" disabled selected>Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->department_id }}">{{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="program" class="form-label">Program</label>
                                <select name="program-id" id="program" class="form-control">
                                    <option value="" disabled selected>Select Program</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->program_id }}"
                                            data-department-id="{{ $program->department_id }}">
                                            {{ $program->program_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="year-level" class="form-label">Year Level</label>
                                <select name="year-level-id" id="year-level" class="form-control" disabled>
                                    <option value="" disabled selected>Select Year Level</option>
                                    @foreach($yearlevels as $yearlevel)
                                        <option value="{{ $yearlevel->year_level_id }}"
                                            data-department-id="{{ $yearlevel->department_id }}">
                                            {{ $yearlevel->year_level_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary float-end ms-5">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const departmentSelect = document.getElementById('section-department');
        const programSelect = document.getElementById('program');
        const yearLevelSelect = document.getElementById('year-level');

        const allProgramOptions = Array.from(programSelect.options);
        const allYearLevelOptions = Array.from(yearLevelSelect.options);

        function resetSelect(selectElement, message = 'Select an option') {
            selectElement.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = message;
            defaultOption.disabled = true;
            defaultOption.selected = true;
            selectElement.appendChild(defaultOption);
            selectElement.disabled = true;
        }

        function populateSelect(selectElement, options, defaultText) {
            resetSelect(selectElement, defaultText);
            if (options.length > 0) {
                options.forEach(opt => selectElement.appendChild(opt));
                selectElement.disabled = false;
                if (options.length === 1) {
                    options[0].selected = true;
                }
            } else {
                const noOption = document.createElement('option');
                noOption.value = '';
                noOption.text = 'No options available';
                noOption.disabled = true;
                noOption.selected = true;
                selectElement.appendChild(noOption);
                selectElement.disabled = true;
            }
        }

        function filterOptionsByDepartment(options, deptId) {
            return options.filter(option => option.dataset.departmentId === deptId);
        }

        departmentSelect.addEventListener('change', function () {
            const deptId = this.value;
            const filteredPrograms = filterOptionsByDepartment(allProgramOptions, deptId);
            const filteredYearLevels = filterOptionsByDepartment(allYearLevelOptions, deptId);

            populateSelect(programSelect, filteredPrograms, 'Select Program');
            populateSelect(yearLevelSelect, filteredYearLevels, 'Select Year Level');
        });

        programSelect.addEventListener('change', function () {
            const selectedOption = this.selectedOptions[0];
            const deptId = selectedOption?.dataset?.departmentId;

            if (deptId && departmentSelect.value !== deptId) {
                departmentSelect.value = deptId;
                departmentSelect.dispatchEvent(new Event('change'));
            }
        });

        // On page load
        resetSelect(yearLevelSelect, 'Select a Department first');

        if (programSelect.value) {
            programSelect.dispatchEvent(new Event('change'));
        } else if (departmentSelect.value) {
            departmentSelect.dispatchEvent(new Event('change'));
        }
    });
</script>