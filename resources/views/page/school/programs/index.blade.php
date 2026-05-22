@extends('layout.app')

@section('title', 'Program Management | RCI AMS')

@section('content')

@vite(['resources/css/programs.blade.css'])

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('program') }}" class="d-flex gap-2">
                <select name="sortBy" id="program-sort-by" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Sort By</option>
                    <option value="program_name" {{ request('sortBy') == 'program_name' ? 'selected' : '' }}>Name</option>
                    <option value="program_code" {{ request('sortBy') == 'program_code' ? 'selected' : '' }}>Code</option>
                    <option value="department_name" {{ request('sortBy') == 'department_name' ? 'selected' : '' }}>Department</option>
                </select>
    
                <select name="order" id="program-order" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Order By</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>

                <select name="count" id="program-count" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Items per page</option>
                    <option value="5" {{ request('count') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('count') == 10 ? 'selected' : '' }}>10</option>
                    <option value="50" {{ request('count') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('count') == 100 ? 'selected' : '' }}>100</option>
                    <option value="250" {{ request('count') == 250 ? 'selected' : '' }}>250</option>
                </select>
            </form>
        </div>
        {{-- <a href="#" class="btn bg-black text-gray disabled  float-end me-2" id="delete-multiple-btn"
            onclick="event.preventDefault();">Delete Selected</a>
        <a href="#" class="btn bg-black text-gray disabled  float-end me-2" id="edit-selected-btn"
            onclick="event.preventDefault();">Edit Selected</a> --}}
        <a href="#" class="btn btn-primary float-end me-2" data-bs-toggle="modal" data-bs-target="#createProgramModal">
            <i class="bi bi-pencil-square"></i>
            Create Program
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-borderless align-middle">
            <thead class="table-primary">
                <tr>
                    <th scope="col" class="col-1">Name</th>
                    <th scope="col" class="col-3">Description</th>
                    <th scope="col" class="col-2">Department</th>
                    <th scope="col" class="col-1">Code</th>
                    <th scope="col" class="col-2">Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @if ($programs->isNotEmpty())
                    @foreach ($programs as $program)
                        <tr>
                            <td>{{ $program->program_name }}</td>
                            <td>{{ $program->program_description ?? 'N/A'}}</td>
                            <td>{{ $program->department->department_name ?? "Doesn't belong in a department" }}</td>
                            <td>{{ $program->program_code }}</td>
                            <td>
                                <a href="#" class="btn btn-primary edit-program-btn" data-bs-toggle="modal"
                                    data-bs-target="#editProgramModal" data-id="{{ $program->program_id }}"
                                    data-name="{{ $program->program_name }}" data-description="{{ $program->program_description}}"
                                    data-code="{{ $program->program_code }}"
                                    data-department-id="{{ $program->department->department_id ?? '' }}">
                                     <i class="bi bi-pen"></i>
                                    Edit
                                </a> |
                                <a href="#" class="btn btn-danger delete-program-btn" data-bs-toggle="modal"
                                    data-bs-target="#delete-program-modal" data-id="{{ $program->program_id }}">
                                     <i class="bi bi-trash"></i>
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No programs found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $programs->appends(request()->query())->links('vendor.pagination.simple-bootstrap-5')}}
    </div>




@endsection