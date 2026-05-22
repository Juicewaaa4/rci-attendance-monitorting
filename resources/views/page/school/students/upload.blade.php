<!-- upload Student Modal -->
<div class="modal fade" id="upload-student-modal" tabindex="-1" aria-labelledby="upload-student-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Added 'modal-lg' for larger width -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="upload-student-modal-label">Upload Student Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('process-import-student-record') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <img src="{{ asset('images/format.png') }}" alt="">
                        <p class="text-muted">Please ensure the department, year level, school year, program, and section code <span class="fw-bold">exists</span> to avoid any issues during upload.</p>
                    </div>
                    
                    <br>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-block">Format</label>
                            <a href="{{ asset('format\Format.xlsx') }}" target="_blank" class="btn btn-outline-success">
                                Copy XLS/XLSX Format
                            </a>
                        </div>                        

                        <div class="col-md-6 mb-3">
                            <label for="upload-file" class="form-label">Upload</label>
                            <input type="file" class="form-control" id="upload-file" name="upload_file" accept=".xls,.xlsx" required>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary float-end ms-5">upload</button>
                </form>
            </div>
        </div>
    </div>
</div>