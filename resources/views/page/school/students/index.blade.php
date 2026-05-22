@extends('layout.app')

@section('title', 'Student Management | RCI AMS')
@section('content')

@vite(['resources/css/student.blade.css'])

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('student') }}" class="d-flex gap-2">
                <select name="sortBy" id="student-sort-by" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Sort By</option>
                    <option value="last_name" {{ request('sortBy') == 'last_name' ? 'selected' : '' }}>Last Name</option>
                    <option value="first_name" {{ request('sortBy') == 'first_name' ? 'selected' : '' }}>First Name </option>
                    <option value="middle_name" {{ request('sortBy') == 'middle_name' ? 'selected' : '' }}>Middle Name
                    </option>
                    <option value="student_id" {{ request('sortBy') == 'student_id' ? 'selected' : '' }}>Student ID</option>
                    <option value="rfid" {{ request('sortBy') == 'rfid' ? 'selected' : '' }}>RFID</option>
                    <option value="section_name" {{ request('sortBy') == 'section_name' ? 'selected' : '' }}>Section</option>
                    <option value="program_name" {{ request('sortBy') == 'program_name' ? 'selected' : '' }}>Program</option>
                    <option value="department_name" {{ request('sortBy') == 'department_name' ? 'selected' : '' }}>Department
                    </option>
                    <option value="year_level_name" {{ request('sortBy') == 'year_level_name' ? 'selected' : '' }}>Year Level
                    </option>
                    <option value="school_year_name" {{ request('sortBy') == 'school_year_name' ? 'selected' : '' }}>School Year
                    </option>
                </select>

                <select name="order" id="student-order" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Order By</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>

                <select name="count" id="student-count" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Items per page</option>
                    <option value="5" {{ request('count') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('count') == 10 ? 'selected' : '' }}>10</option>
                    <option value="50" {{ request('count') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('count') == 100 ? 'selected' : '' }}>100</option>
                    <option value="250" {{ request('count') == 250 ? 'selected' : '' }}>250</option>
                </select>
            </form>
        </div>
        {{-- <input type="text" name="search" id="search" class="form-control-sm w-5 ms-2"
            placeholder="Search ID, Name, or RFID"> --}}
        {{-- <select name="" id="">
            <option value="">Sort By</option>
        </select> --}}
        {{-- <a href="#" class="btn  bg-black text-gray float-end me-2 disabled" id="select--all-btn"
            onclick="event.preventDefault();">Select All</a>
        <a href="#" class="btn  bg-black text-gray float-end me-2 disabled" id="delete-multiple-btn"
            onclick="event.preventDefault();">Delete Selected</a>
        <a href="#" class="btn  bg-black text-gray  float-end me-2 disabled" id="edit-selected-btn"
            onclick="event.preventDefault();">Edit Selected</a> --}}
        <div class="d-flex gap-2">
            <a href="" class="btn btn-primary float-end" data-bs-toggle="modal"
                data-bs-target="#upload-student-modal">
                <i class="bi bi-file-earmark-arrow-up"></i>
                Upload Student Record</a>
            <a href="" class="btn btn-primary float-end me-2" data-bs-toggle="modal"
                data-bs-target="#create-student-modal">
                <i class="bi bi-pencil-square"></i>
                Create
                Student Record</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-borderless align-middle">
            <thead class="table-primary">
                <tr>
                    {{-- <th><label for="select-all-checkbox">Select All</label> <input type="checkbox"
                            id="select-all-checkbox"></th> ← Added select-all checkbox --}}
                    <th class="col-2">Name</th>
                    <th>ID</th>
                    <th>Section</th>
                    <th>Year Level</th>
                    <th>School Year</th>
                    <th>Program</th>
                    <th>Department</th>
                    <th class="col-1">RFID</th>
                    <th class="col-2">Actions</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @if ($students->isNotEmpty())
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $student->last_name . ', ' . $student->first_name . ' ' . $student->middle_name}}</td>
                            <td>{{ $student->student_id ?? 'N/A'}}</td>
                            <td>{{ $student->section->section_name ?? 'N/A' }}</td>
                            <td>{{ $student->yearLevel->year_level_name ?? 'N/A' }}</td>
                            <td>{{ $student->schoolYear->school_year_name ?? 'N/A' }}</td>
                            <td>{{ $student->program->program_name ?? 'N/A' }}</td>
                            <td>{{ $student->department->department_name ?? 'N/A' }}</td>
                            <td>{{ $student->rfid ?? 'N/A' }}</td>
                            <td class="text-center align-middle">
                                <a href="#" class="btn btn-primary edit-student-btn" data-bs-toggle="modal"
                                    data-bs-target="#edit-student-modal" data-record-id="{{ $student->record_id }}"
                                    data-id="{{ $student->student_id }}" data-first-name="{{ $student->first_name }}"
                                    data-last-name="{{ $student->last_name }}" data-middle-name="{{ $student->middle_name }}"
                                    data-rfid="{{ $student->rfid }}" data-school-year-id="{{ $student->school_year_id }}"
                                    data-section-id="{{ $student->section_id }}" data-year-level-id="{{ $student->year_level_id }}"
                                    data-department-id="{{ $student->department_id }}" data-program-id="{{ $student->program_id }}">
                                      <i class="bi bi-pen"></i>
                                    Edit
                                </a> |
                                <a href="#" class="btn btn-danger delete-record-btn" data-bs-toggle="modal"
                                    data-bs-target="#delete-student-modal" data-id="{{ $student->record_id }}">
                                     <i class="bi bi-trash"></i>
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center">No Students found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $students->appends(request()->query())->links('vendor.pagination.simple-bootstrap-5')}}
    </div>


@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const tableRows = document.querySelectorAll('tbody tr');

            searchInput.addEventListener('keyup', function () {
                const query = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    if (rowText.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection