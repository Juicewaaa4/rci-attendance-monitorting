<!-- Edit school-year Modal -->
<div class="modal fade" id="edit-school-year-modal" tabindex="-1" aria-labelledby="editschool-yearModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editschool-yearModalLabel">Edit school-year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editschool-yearForm" action="{{ route('process-edit-school-year') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit-school-year-id" name="school-year-id">

                    <div class="mb-3">
                        <label for="edit-name" class="form-label">school-year Name</label>
                        <input type="text" class="form-control" id="edit-school-year-name" name="school-year-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="edit-school-year-description" name="school-year-description"></input>
                    </div>

                    <div class="mb-3">
                        <label for="edit-code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="edit-school-year-code" name="school-year-code" required>
                    </div>

                    <button type="button" class="btn btn-secondary float-start">Cancel</button>
                    <button type="submit" class="btn btn-success float-end">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


