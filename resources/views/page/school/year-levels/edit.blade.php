<!-- Edit Year Level Modal -->
<div class="modal fade" id="edit-year-level-modal" tabindex="-1" aria-labelledby="edit-year-level-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-year-level-modal-label">Edit Year Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>
            <div class="modal-body">
                <form id="editYearLevelForm" action="{{route('process-edit-year-level')}}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit-year-level-id" name="year-level-id">

                    <div class="mb-3">
                        <label for="edit-year-level-name" class="form-label">Year Level Name</label>
                        <input type="text" class="form-control" id="edit-year-level-name" name="year-level-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-year-level-description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="edit-year-level-description" name="year-level-description">
                    </div>

                    <div class="mb-3">
                        <label for="edit-year-level-department" class="form-label">Department</label>
                        <select name="department-id" id="edit-year-level-department" class="form-control">
                            <option value="" disabled>Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" class="btn btn-secondary float-start" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>