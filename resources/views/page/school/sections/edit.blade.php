<!-- Edit Program Modal -->
<div class="modal fade" id="edit-section-modal" tabindex="-1" aria-labelledby="edit-section-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-section-modal-label">Edit Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-section-form" action="{{ route('process-edit-section') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit-section-id" name="section_id">

                    <div class="mb-3">
                        <label for="edit-section-name" class="form-label">Section Name</label>
                        <input type="text" class="form-control" id="edit-section-name" name="section-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-section-description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="edit-section-description" name="section-description">
                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="section-code" class="form-label">Section Code</label>
                                <input type="text" class="form-control" id="edit-section-code" name="section-code">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit-section-department" class="form-label">Department</label>
                                <select name="department-id" id="edit-section-department" class="form-control">
                                    <option value="" disabled selected>Select Department</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit-section-program" class="form-label">Program</label>
                                <select name="program-id" id="edit-section-program" class="form-control">
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
                                <label for="edit-section-year-level" class="form-label">Year Level</label>
                                <select name="year-level-id" id="edit-section-year-level" class="form-control">
                                    <option value="" disabled selected>Select Year Level</option>
                                    @foreach($yearlevels as $yearlevel)
                                    <option value="{{ $yearlevel->year_level_id }}">{{ $yearlevel->year_level_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary float-start" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>