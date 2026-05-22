@extends('layout.app')

@section('title', 'Account Management | RCI AMS')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('instructor') }}" class="d-flex gap-2">
                <select name="sortBy" id="instructor-sort-by" class="form-select w-auto ms-2" onchange="this.form.submit()">
                    <option value="" disabled selected>Sort By</option>
                    <option value="last_name" {{ request('sortBy') == 'last_name' ? 'selected' : '' }}>Last Name</option>
                    <option value="first_name" {{ request('sortBy') == 'first_name' ? 'selected' : '' }}>First Name</option>
                    <option value="middle_name" {{ request('sortBy') == 'middle_name' ? 'selected' : '' }}>Middle Name
                    </option>
                    <option value="department" {{ request('sortBy') == 'department' ? 'selected' : '' }}>Department</option>
                    <option value="program" {{ request('sortBy') == 'program' ? 'selected' : '' }}>Program</option>
                    <option value="section" {{ request('sortBy') == 'section' ? 'selected' : '' }}>Section</option>
                </select>

                <select name="order" id="instructor-order" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Order</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>

                <select name="count" id="instructor-count" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Items per page</option>
                    <option value="5" {{ request('count') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('count') == 10 ? 'selected' : '' }}>10</option>
                    <option value="50" {{ request('count') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('count') == 100 ? 'selected' : '' }}>100</option>
                    <option value="250" {{ request('count') == 250 ? 'selected' : '' }}>250</option>
                </select>
            </form>
        </div>
        {{-- <a href="#" id="delete-multiple-instructors" class="btn btn-danger float-end me-2">Delete Selected</a>
        <a href="#" id="edit-multiple-instructors" class="btn btn-warning float-end me-2">Edit Selected</a> --}}
        <a href="{{ route('create-instructor') }}" class="btn btn-primary float-end me-2">Create Account</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    {{-- <th class="text-center"><input type="checkbox" name="select-all-instructors"
                            id="select-all-instructors"></th> --}}
                    <th scope="col" class="col-3">Full Name</th>
                    <th scope="col" class="col-2">Department</th>
                    <th scope="col" class="col-3">Program</th>
                    <th scope="col" class="col-2">Sections</th>
                    <th scope="col" class="col-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($instructors as $instructor)
                    <tr>
                        @if (request('sortBy') == 'first_name')
                            <td class="align-middle">
                                {{ $instructor->first_name . ' ' . $instructor->middle_name . ' ' . $instructor->last_name }}
                            </td>
                        @elseif (request('sortBy') == 'middle_name')
                            <td class="align-middle">
                                {{ $instructor->middle_name . ' ' . $instructor->first_name . ' ' . $instructor->last_name }}
                            </td>    
                        @else
                            <td class="align-middle">
                                {{ $instructor->last_name . ', ' . $instructor->first_name . ' ' . $instructor->middle_name }}
                            </td>
                        @endif

                        <td class="align-middle">
                            {{ $instructor->assignments->first()->department->department_name ?? 'N/A' }}
                        </td>

                        <td class="align-middle">
                            @php
                                $programs = $instructor->assignments->pluck('program.program_name')->filter()->unique();
                            @endphp
                            {{ $programs->isNotEmpty() ? $programs->implode(', ') : 'N/A' }}
                        </td>

                        <td class="align-middle">
                            @php
                                $sections = $instructor->assignments->pluck('section.section_name')->filter()->unique();
                            @endphp
                            {{ $sections->isNotEmpty() ? $sections->implode(', ') : 'N/A' }}
                        </td>

                        <td class="text-center align-middle">
                            <a href="{{ route('edit-instructor-account', $instructor->account_id) }}"
                                class="btn btn-primary">Edit</a>
                            <a href="#" class="btn btn-danger delete-instructor-btn" data-bs-toggle="modal"
                                data-bs-target="#delete-instructor-modal" data-id="{{ $instructor->account_id }}">
                                Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $instructors->appends(request()->query())->links('vendor.pagination.simple-bootstrap-5')}}
    </div>
@endsection