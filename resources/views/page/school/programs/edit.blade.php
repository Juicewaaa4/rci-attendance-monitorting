<!-- Edit Program Modal -->
<div class="modal fade" id="editProgramModal" tabindex="-1" aria-labelledby="editProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProgramModalLabel">Edit Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProgramForm" action="{{ route('process-edit-program') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit-program-id" name="program_id">


                    <div class="mb-3">
                        <label for="edit-program-name" class="form-label">Program Name</label>
                        <input type="text" class="form-control" id="edit-program-name" name="program-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-program-description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="edit-program-description" name="program-description">
                    </div>

                    <!-- Department Dropdown -->
                    <div class="mb-3">
                        <label for="edit-program-department" class="form-label">Department</label>
                        <select name="department-id" id="edit-program-department" class="form-control">
                            <option value="" disabled>Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit-program-code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="edit-program-code" name="program-code" required>
                    </div>

                    <button type="button" class="btn btn-secondary float-start" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
