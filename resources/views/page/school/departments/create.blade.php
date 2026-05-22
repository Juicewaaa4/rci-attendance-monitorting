<div class="modal fade" id="create-department-modal" tabindex="-1" aria-labelledby="create-department-modal-Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create-department-modal-label">Create Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('process-create-department') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="department-name" class="form-label">Department Name</label>
                        <input type="text" class="form-control" id="department-name" name="department-name" list="department-suggestions"
                            value="{{ old('department-name') }}"
                            required>
                    </div>

                    <datalist id="department-suggestions">
                        <option value="(DEPED) Senior High School"></option>
                        <option value="(TESDA )Technical Education and Skills Development Authority"></option>
                        <option value="(CHED) Commission on Higher Education"></option>
                    </datalist>

                    <div class="mb-3">
                        <label for="department-description" class="form-label">Department Description</label>
                        <input type="text" class="form-control" id="department-description" name="department-description"
                            value="{{ old('department-description') }}"
                            >
                    </div>
                    <div class="mb-3">
                        <label for="department-code" class="form-label">Department Code</label>
                        <input type="text" class="form-control" id="department-code" name="department-code"
                            value="{{ old('department-code') }}"
                            required>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary float-end ms-5">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>