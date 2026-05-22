<!-- Delete instructor Modal -->

<div class="modal fade" id="delete-instructor-modal" tabindex="-1" aria-labelledby="delete-instructor-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg border-0">
            <!-- Red Warning Header -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="delete-instructor-modal-label">
                    <i class="bx bx-error-circle bx-sm"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <p class="fs-5">Are you sure you want to delete this Instructor Account?</p>
                <p class="text-danger fw-bold">This action cannot be undone.</p>

                <form id="delete-instructor-form" action="{{ route('process-delete-instructor') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="instructor-id" id="delete-instructor-id">

                    <!-- Password Confirmation -->
                    <div class="mb-3 position-relative">
                        <label for="confirm-instructor-password" class="form-label fw-bold">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="confirm-password" id="confirm-instructor-password" required>
                            <button type="button" class="btn btn-outline-secondary" id="toggle-instructor-password">
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
                <button type="submit" class="btn btn-danger" form="delete-instructor-form">
                    <i class="bx bx-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

