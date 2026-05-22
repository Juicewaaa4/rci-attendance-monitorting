@extends('layout.app')

@section('title', 'Year Level Management | RCI AMS')

@section('content')

@vite(['resources/css/yearlevels.blade.css'])

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('year-level') }}" class="d-flex gap-2">
                <select name="sortBy" id="year-level-sort-by" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Sort By</option>
                    <option value="year_level_name" {{ request('sortBy') == 'year_level_name' ? 'selected' : '' }}>Name</option>
                    <option value="year_level_code" {{ request('sortBy') == 'year_level_code' ? 'selected' : '' }}>Code</option>
                    <option value="department_name" {{ request('sortBy') == 'department_name' ? 'selected' : '' }}>Department</option>
                </select>
    
                <select name="order" id="year-level-order" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Order By</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>

                <select name="count" id="year-level-count" class="form-select w-auto" onchange="this.form.submit()">
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
        <a href="#" class="btn btn-primary float-end me-2" data-bs-toggle="modal" data-bs-target="#create-year-level-modal">
            <i class="bi bi-pencil-square"></i>
            Create Year Level
        </a>
    </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-borderless align-middle">
                <thead class="table-primary">
                    <tr>
                        <th scope="col" class="col-3">Name</th>
                        <th scope="col" class="col-3">Description</th>
                        <th scope="col" class="col-3">Department</th>
                        <th scope="col" class="col-1">Code</th>
                        <th scope="col" class="col-2">Action</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @if ($yearlevels->isNotEmpty())
                        @foreach ($yearlevels as $year_level)
                            <tr>
                                <td>{{ $year_level->year_level_name }}</td>
                                <td>{{ $year_level->year_level_description ?? 'N/A'}}</td>
                                <td>{{ $year_level->department->department_name ?? "Doesn't belong in a department" }}</td>
                                <td>{{ $year_level->year_level_code}}</td>
                                <td class="text-center align-middle">

                                    <a href="#" class="btn btn-primary edit-year-level-btn" data-bs-toggle="modal"
                                        data-bs-target="#edit-year-level-modal" data-id="{{ $year_level->year_level_id }}"
                                        data-name="{{ $year_level->year_level_name }}"
                                        data-description="{{ $year_level->year_level_description }}">
                                         <i class="bi bi-pen"></i>
                                        Edit
                                    </a> |
                                    <a href="#" class="btn btn-danger delete-year-level-btn" data-bs-toggle="modal"
                                        data-bs-target="#delete-year-level-modal" data-id="{{ $year_level->year_level_id }}">
                                         <i class="bi bi-trash"></i>
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">No Year Levels Found</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $yearlevels->appends(request()->query())->links('vendor.pagination.simple-bootstrap-5')}}
        </div>

@endsection