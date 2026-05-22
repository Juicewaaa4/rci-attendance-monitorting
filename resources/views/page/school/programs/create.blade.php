<!-- Create Program Modal -->
<div class="modal fade" id="createProgramModal" tabindex="-1" aria-labelledby="createProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProgramModalLabel">Create Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('process-create-program') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="program-name" class="form-label">Program Name</label>
                        <input type="text" class="form-control" id="program-name" name="program-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="program-description" class="form-label">Program Description</label>
                        <input type="text" class="form-control" id="program-description" name="program-description">
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


                    <div class="mb-3">
                        <label for="program-code" class="form-label">Program Code</label>
                        <input type="text" class="form-control" id="program-code" name="program-code" required>
                    </div>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary float-end ms-5">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>