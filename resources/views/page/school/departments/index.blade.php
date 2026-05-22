@extends('layout.app')

@section('title','Department Management | RCI AMS')

@section('content')

@vite(['resources/css/department.blade.css'])

<div class="d-flex justify-content-between align-items-center mb-2">
    <div class="d-flex gap-2">
        <form method="GET" action="{{ route('department') }}" class="d-flex gap-2">
            <select name="sortBy" id="department-sort-by" class="form-select w-auto" onchange="this.form.submit()">
                <option value="" disabled selected>Sort By</option>
                <option value="department_name" {{ request('sortBy') == 'department_name' ? 'selected' : '' }}>Name</option>
                <option value="department_code" {{ request('sortBy') == 'department_code' ? 'selected' : '' }}>Code</option>
            </select>

            <select name="order" id="department-order" class="form-select w-auto ms-2" onchange="this.form.submit()">
                <option value="" disabled selected>Order By</option>
                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
            </select>
        
            <select name="count" id="department-count" class="form-select w-auto" onchange="this.form.submit()">
                <option value="" disabled selected>Items per page</option>
                <option value="5" {{ request('count') == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('count') == 10 ? 'selected' : '' }}>10</option>
                <option value="50" {{ request('count') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('count') == 100 ? 'selected' : '' }}>100</option>
                <option value="250" {{ request('count') == 250 ? 'selected' : '' }}>250</option>
            </select>
        </form>           
    </div>

    {{-- <input type="text" id="search" placeholder="Search by name or code" class="form-control mb-3" onkeyup="filterTable()"> --}}

    <a href="#" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#create-department-modal">
    <i class="bi bi-pencil-square"></i>
        Create Program
    </a>
</div>


<div
    class="table-responsive">
    <table
        class="table table-bordered table-hover table-borderless align-middle">
        <thead class="table-primary">
            <tr>
                <th scope="col" class="col-4">Name</th>
                <th scope="col" class="col-5">Description</th>
                <th scope="col" class="col-1">Code </th>
                <th scope="col" class="col-3">Action</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">

            @if ($departments ->isNotEmpty())
            @foreach ($departments as $department)
            <tr>
                <td>{{ $department->department_name }}</td>
                <td>{{ $department->department_description }}</td>
                <td>{{ $department->department_code }}</td>
                <td class="text-center align-middle">
                    <a href="#" class="btn btn-success edit-department-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#editDepartmentModal"
                        data-id="{{ $department->department_id }}"
                        data-name="{{ $department->department_name }}"
                        data-description="{{ $department->department_description }}"
                        data-code="{{ $department->department_code }}">
                        <i class="bi bi-pen"></i>
                        Edit
                    </a> |
                    <a href="#" class="btn btn-danger delete-department-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#delete-department-modal"
                        data-id="{{ $department->department_id }}">
                        <i class="bi bi-trash"></i>
                        Delete
                    </a>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="4" class="text-center">No departments found.</td>
            </tr>
            @endif
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>

<div class="d-flex justify-content-center">
    {{ $departments->appends(request()->query())->links('vendor.pagination.simple-bootstrap-5') }}
</div>

@endsection