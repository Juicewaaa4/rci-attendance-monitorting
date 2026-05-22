@extends('layout.app')

@section('title', 'Section Management | RCI AMS')

@section('content')

@vite(['resources/css/sections.blade.css'])

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('section') }}" class="d-flex gap-2">
                <select name="sortBy" id="section-sort-by" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Sort By</option>
                    <option value="section_name" {{ request('sortBy') == 'section_name' ? 'selected' : '' }}>Name</option>
                    <option value="section_code" {{ request('sortBy') == 'section_code' ? 'selected' : '' }}>Code</option>
                    <option value="program_name" {{ request('sortBy') == 'program_name' ? 'selected' : '' }}>Program</option>
                    <option value="department_name" {{ request('sortBy') == 'department_name' ? 'selected' : '' }}>Department
                    </option>
                    <option value="year_level_name" {{ request('sortBy') == 'year_level_name' ? 'selected' : '' }}>Year Level
                    </option>
                    <option value="school_year_name" {{ request('sortBy') == 'school_year_name' ? 'selected' : '' }}>School Year
                    </option>
                </select>

                <select name="order" id="section-order" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="" disabled selected>Order By</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>

                <select name="count" id="section-count" class="form-select w-auto" onchange="this.form.submit()">
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
        <a href="#" class="btn bg-black text-gray disabled float-end me-2" id="edit-selected-btn"
            onclick="event.preventDefault();">Edit Selected</a> --}}
        <a href="#" class="btn btn-primary float-end me-2" data-bs-toggle="modal" data-bs-target="#create-section-modal">
            <i class="bi bi-pencil-square"></i>
            Create Section
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-borderless align-middle">
            <thead class="table-primary">
                <tr>
                    <th scope="col" class="col-1">Name</th>
                    <th scope="col" class="col-2">Description</th>
                    <th scope="col" class="col-2">Program</th>
                    <th scope="col" class="col-2">Department</th>
                    <th scope="col" class="col-1">Year Level</th>
                    <th>School Year</th>
                    <th>Code</th>
                    <th scope="col" class="col-2">Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @php
    $sortedSections = $sections->sort(function ($a, $b) {
        return (int) filter_var($a->section_name, FILTER_SANITIZE_NUMBER_INT) <=> (int) filter_var($b->section_name, FILTER_SANITIZE_NUMBER_INT);
    });
@endphp


                @if ($sortedSections->isNotEmpty())
                    @foreach ($sortedSections as $section)
                        <tr>
                            <td>{{ $section->section_name }}</td>
                            <td>{{ $section->section_description ?? 'N/A'}}</td>
                            <td>{{ $section->program->program_name ?? 'N/A' }}</td>
                            <td>{{ $section->department->department_name ?? 'N/A' }}</td>
                            <td>{{ $section->yearLevel->year_level_name ?? 'N/A' }}</td>
                            <td>{{ $section->schoolYear->school_year_name ?? 'N/A' }}</td>
                            <td>{{ $section->section_code ?? 'N/A' }}</td>
                            <td class="text-center align-middle">

                                <a href="#" class="btn btn-primary edit-section-btn" data-bs-toggle="modal"
                                    data-bs-target="#edit-section-modal" data-id="{{ $section->section_id }}"
                                    data-name="{{ $section->section_name }}" data-description="{{ $section->section_description }}"
                                    data-code="{{ $section->section_code }}" data-program-id="{{ $section->program_id }}"
                                    data-year-level-id="{{ $section->year_level_id }}"
                                    data-department-id="{{ $section->department_id }}">
                                    <i class="bi bi-pen"></i>
                                    Edit
                                </a> |
                                <a href="#" class="btn btn-danger delete-section-btn" data-bs-toggle="modal"
                                    data-bs-target="#delete-section-modal" data-id="{{ $section->section_id }}">
                                     <i class="bi bi-trash"></i>
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center">No sections found.</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $sections->appends(request()->query())->links('vendor.pagination.simple-bootstrap-5')}}
    </div>

@endsection