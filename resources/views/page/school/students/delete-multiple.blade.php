<!-- Delete Student Modal (Multiple Records) -->
<div class="modal fade" id="delete-multiple-student-modal" tabindex="-1" aria-labelledby="delete-multiple-student-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg border-0">
            <!-- Red Warning Header -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="delete-multiple-student-modal-label">
                    <i class="bx bx-error-circle bx-sm"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <p class="fs-5">Are you sure you want to delete the selected records?</p>
                <p class="text-danger fw-bold">This action cannot be undone.</p>

                <div id="selected-records-list">
                    <!-- Records will be populated here -->
                </div>

                <!-- Pagination controls -->
                <div id="pagination-controls" class="d-flex justify-content-center mt-3">
                    <!-- Pagination buttons will be dynamically added here -->
                </div>


                <form id="delete-multiple-student-form" action="{{-- route('process-delete-multiple-student-records') --}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="multiple-record-ids" id="delete-multiple-record-ids">

                    <!-- Password Confirmation -->
                    <div class="mb-3 position-relative">
                        <label for="confirm-multiple-student-password" class="form-label fw-bold">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="confirm-password" id="confirm-multiple-student-password" required>
                            <button type="button" class="btn btn-outline-secondary" id="toggle-multiple-student-password">
                                <i class="bx bx-hide"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x-circle"></i> Cancel
                </button>
                <button type="submit" class="btn btn-danger" form="delete-multiple-student-form">
                    <i class="bx bx-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>
