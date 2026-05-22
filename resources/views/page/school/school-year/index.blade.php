@extends('layout.app')

@section('title', 'School Year Management | RCI AMS')

@section('content')

@vite(['resources/css/school-year.blade.css'])

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('school-year') }}" class="d-flex gap-2">
                <select name="sortBy" id="school-year-sort-by" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Sort By</option>
                    <option value="school_year_name" {{ request('sortBy') == 'school_year_name' ? 'selected' : '' }}>Name</option>
                    <option value="school_year_code" {{ request('sortBy') == 'school_year_code' ? 'selected' : '' }}>Code</option>               
                 </select>
    
                <select name="order" id="school-year-order" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Order By</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>

                <select name="count" id="school-year-count" class="form-select w-auto" onchange="this.form.submit()">
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
        <a href="#" class="btn btn-primary float-end me-2" data-bs-toggle="modal"
            data-bs-target="#create-school-year-modal">
            <i class="bi bi-pencil-square"></i>
            Create School Year
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-borderless align-middle">
            <thead class="table-primary">
                <tr>
                    <th scope="col" class="col-3">Name</th>
                    <th scope="col" class="col-3">Description</th>
                    <th scope="col" class="col-1">Code</th>
                    <th scope="col" class="col-2">Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @if ($schoolyears->isNotEmpty())
                    @foreach ($schoolyears as $schoolyear)
                        <tr>
                            <td>{{ $schoolyear->school_year_name }}</td>
                            <td>{{ $schoolyear->school_year_description ?? 'N/A'}}</td>
                            <td>{{ $schoolyear->school_year_code }}</td>
                            <td class="text-center align-middle">
                                <a href="#" class="btn btn-primary edit-school-year-btn" data-bs-toggle="modal"
                                    data-bs-target="#edit-school-year-modal" data-id="{{ $schoolyear->school_year_id }}"
                                    data-name="{{ $schoolyear->school_year_name }}"
                                    data-description="{{ $schoolyear->school_year_description }}"
                                    data-code="{{ $schoolyear->school_year_code }}">
                                    <i class="bi bi-pen"></i>
                                    Edit
                                </a> |
                                <a href="#" class="btn btn-danger delete-school-year-btn" data-bs-toggle="modal"
                                    data-bs-target="#delete-school-year-modal" data-id="{{ $schoolyear->school_year_id }}">
                                     <i class="bi bi-trash"></i>
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">No School Years Found</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $schoolyears->appends(request()->query())->links('vendor.pagination.simple-bootstrap-5')}}
    </div>

@endsection