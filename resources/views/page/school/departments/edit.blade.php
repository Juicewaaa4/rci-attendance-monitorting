<!-- Edit Department Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDepartmentForm" action="{{ route('process-edit-department') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit-id" name="department_id">

                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Department Name</label>
                        <input type="text" class="form-control" id="edit-name" name="department-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="edit-description" name="department-description"></input>
                    </div>

                    <div class="mb-3">
                        <label for="edit-code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="edit-code" name="department-code" required>
                    </div>

                    <button type="button" class="btn btn-secondary float-start">Cancel</button>
                    <button type="submit" class="btn btn-success float-end">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


