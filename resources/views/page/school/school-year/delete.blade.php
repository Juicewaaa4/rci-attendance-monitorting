<!-- Delete school-year Modal -->


<div class="modal fade" id="delete-school-year-modal" tabindex="-1" aria-labelledby="delete-school-year-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg border-0">
            <!-- Red Warning Header -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="delete-school-year-modal-label">
                    <i class="bx bx-error-circle bx-sm"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <p class="fs-5">Are you sure you want to delete this school-year?</p>
                <p class="text-danger fw-bold">This action cannot be undone.</p>

                <form id="delete-school-year-form" action="{{ route('process-delete-school-year') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="school-year-id" id="delete-school-year-id">

                    <!-- Password Confirmation -->
                    <div class="mb-3 position-relative">
                        <label for="confirm-school-year-password" class="form-label fw-bold">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="confirm-password" id="confirm-school-year-password" required>
                            <button type="button" class="btn btn-outline-secondary" id="toggle-school-year-password">
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
                <button type="submit" class="btn btn-danger" form="delete-school-year-form">
                    <i class="bx bx-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

