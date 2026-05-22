
<div class="modal fade" id="create-school-year-modal" tabindex="-1" aria-labelledby="create-school-year-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create-school-year-modal-label">Create school-year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('process-create-school-year') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="school-year-name" class="form-label">School Year Name</label>
                        <input type="text" class="form-control" id="school-year-name" name="school-year-name"
                            value="{{ old('school-year-name') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="school-year-description" class="form-label">school-year Description</label>
                        <input type="text" class="form-control" id="school-year-description" name="school-year-description"
                            value="{{ old('school-year-description') }}"
                            >
                    </div>
                    <div class="mb-3">
                        <label for="school-year-code" class="form-label">school-year Code</label>
                        <input type="text" class="form-control" id="school-year-code" name="school-year-code"
                            value="{{ old('school-year-code') }}"
                            required>
                        <p class="text-muted small">Alphanumeric: ABC123</p>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary float-end ms-5">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>