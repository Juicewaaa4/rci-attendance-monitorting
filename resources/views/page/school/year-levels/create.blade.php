<!-- Create Year Level Modal -->
<div class="modal fade" 
id="create-year-level-modal" 
tabindex="-1" 
aria-labelledby="create-year-level-modal-label" 
aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create-year-level-modal-label">Create Year Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('process-create-year-level') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="year-level-name" class="form-label">Year Level</label>
                        <input type="text" class="form-control" id="year-level-name" name="year-level-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="year-level-description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="year-level-description" name="year-level-description">
                    </div>

                    <div class="mb-3">
                        <label for="year-level-code" class="form-label">Year Level Code</label>
                        <input type="text" class="form-control" id="year-level-code" name="year-level-code" required>
                    </div>

                    <div class="mb-3">
                        <label for="department"  class="form-label">Department</label>
                        <select name="department-id" id="department" class="form-control">
                            <option value="" disabled selected>Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary float-end ms-5">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>