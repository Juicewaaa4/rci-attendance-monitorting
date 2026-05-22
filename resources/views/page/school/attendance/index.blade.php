@extends('layout.app')
@section('title', 'Attendance')
@section('content')

<form method="GET" class="d-flex align-items-center">
    <select name="sortBy" id="sortBy" class="form-select w-auto me-2" onchange="this.form.submit()">
        <option disabled selected value="">Sort By:</option>
        <option value="scanned_at" {{ request('sortBy') == 'scanned_at' ? 'selected' : '' }}>Scan Time</option>
        <option value="last_name" {{ request('sortBy') == 'last_name' ? 'selected' : '' }}>Last Name</option>
        <option value="first_name" {{ request('sortBy') == 'first_name' ? 'selected' : '' }}>First Name</option>
        <option value="department" {{ request('sortBy') == 'department' ? 'selected' : '' }}>Department</option>
        <option value="program" {{ request('sortBy') == 'program' ? 'selected' : '' }}>Program</option>
        <option value="section" {{ request('sortBy') == 'section' ? 'selected' : '' }}>Section</option>
        <option value="year_level" {{ request('sortBy') == 'year_level' ? 'selected' : '' }}>Year Level</option> <!-- added -->
    </select>

    <select name="filterBy" id="filterBy" class="form-select w-auto me-2" onchange="this.form.submit()">
        <option disabled selected value="">Filter By:</option>
        <option value="department" {{ request('filterBy') == 'department' ? 'selected' : '' }}>Department</option>
        <option value="program" {{ request('filterBy') == 'program' ? 'selected' : '' }}>Program</option>
        <option value="section" {{ request('filterBy') == 'section' ? 'selected' : '' }}>Section</option>
        <option value="year_level" {{ request('filterBy') == 'year_level' ? 'selected' : '' }}>Year Level</option> <!-- added -->
    </select>

    @if(request('filterBy') == 'department')
        <label for="filter" class="me-2">Department:</label>
        <select name="filter" id="filter" class="form-select w-auto me-2" onchange="this.form.submit()">
            <option disabled selected value="">Choose Department...</option>
            @foreach($departments as $department)
                <option value="{{ $department->department_id }}" {{ request('filter') == $department->department_id ? 'selected' : '' }}>
                    {{ $department->department_name }}
                </option>
            @endforeach
        </select>
    @elseif(request('filterBy') == 'program')
        <label for="filter" class="me-2">Program:</label>
        <select name="filter" id="filter" class="form-select w-auto me-2" onchange="this.form.submit()">
            <option disabled selected value="">Choose Program...</option>
            @foreach($programs as $program)
                <option value="{{ $program->program_id }}" {{ request('filter') == $program->program_id ? 'selected' : '' }}>
                    {{ $program->program_name }}
                </option>
            @endforeach
        </select>
    @elseif(request('filterBy') == 'section')
        <label for="filter" class="me-2">Section:</label>
        <select name="filter" id="filter" class="form-select w-auto me-2" onchange="this.form.submit()">
            <option disabled selected value="">Choose Section...</option>
            @foreach($sections as $section)
                <option value="{{ $section->section_id }}" {{ request('filter') == $section->section_id ? 'selected' : '' }}>
                    {{ $section->section_name }} ({{ $section->program->program_name }})
                </option>
            @endforeach
        </select>
    @elseif(request('filterBy') == 'year_level')
        <label for="filter" class="me-2">Year Level:</label>
        <select name="filter" id="filter" class="form-select w-auto me-2" onchange="this.form.submit()">
            <option disabled selected value="">Choose Year Level...</option>
            @foreach($yearLevels as $yearLevel)
                <option value="{{ $yearLevel->year_level_id }}" {{ request('filter') == $yearLevel->year_level_id ? 'selected' : '' }}>
                    {{ $yearLevel->year_level_name }}
                </option>
            @endforeach
        </select>
    @endif

    <select name="order" class="form-select w-auto me-2" onchange="this.form.submit()">
        <option value="" disabled selected>Order By:</option>
        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
    </select>

    <select name="count" id="count" class="form-select w-auto" onchange="this.form.submit()">
        <option value="" disabled selected>Items per page</option>
        <option value="5" {{ request('count') == 5 ? 'selected' : '' }}>5</option>
        <option value="10" {{ request('count') == 10 ? 'selected' : '' }}>10</option>
        <option value="50" {{ request('count') == 50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ request('count') == 100 ? 'selected' : '' }}>100</option>
        <option value="250" {{ request('count') == 250 ? 'selected' : '' }}>250</option>
    </select>

    <label for="date" class="ms-2 me-2">Date:</label>
    <input type="date" name="date" id="date" class="form-control w-auto me-2" onchange="this.form.submit()" value="{{ request('date') }}">
</form>
<form action="{{ route('attendance') }}" method="GET" class="row g-3">
    <div class="col-md-12">
        <button type="submit" class="btn btn-secondary" id="reset-filters">Reset</button>
    </div>
</form>
<br>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Program</th>
            <th>Section</th>
            <th>Year Level</th>
            <th>Date</th>
            <th>Time</th>
            <th>Attendance Status</th>
            <th>Instructor</th>
            <th>Action</th>
            <th>Approve</th>
            <th>Image</th>
        </tr>
    </thead>
  <tbody>
    @foreach ($logs as $log)
        <form action="{{ route('attendance.approve') }}" method="POST">
            @csrf
            <tr>
                <!-- Student Name -->
                <td>
                    {{ $log->student 
                        ? $log->student->last_name . ', ' . $log->student->first_name .' '. $log->student->middle_name 
                        : 'No student found' }}
                </td>

                <!-- Program -->
                <td>{{ $log->student ? $log->student->program->program_name : '-' }}</td>

                <!-- Section -->
                <td>{{ $log->student ? $log->student->section->section_name : '-' }}</td>

                <!-- Year Level -->
                <td>{{ $log->student ? $log->student->yearLevel->year_level_name : '-' }}</td>

                <!-- Date -->
                <td>{{ $log->created_at->format('M d, Y')}}</td>

                <!-- Time -->
                <td>{{ $log->created_at->format('H:i:s') }}</td>

                <!-- Attendance Status -->
                <td>
                    <select name="attendance_status" required>
                        <option value="">-- Select Status --</option>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                    </select>
                </td>

                <!-- Instructor -->
            <td>
    {{ $instructor->first_name }} {{ $instructor->last_name }}
    <input type="hidden" name="instructor" value="{{ $instructor->first_name }} {{ $instructor->last_name }}">
</td>


                <!-- Action -->
                <td>
                    @if ($log->action === 'Log-in')
                        <span class="text-primary">In</span>
                    @else
                        <span class="text-danger">Out</span>
                    @endif
                </td>

                <!-- Approve Button -->
                <td>
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                </td>

                <!-- Image -->
                <td>
                    <img src="/rfid_images/{{ basename($log->image) }}" 
                         alt="Student Image" 
                         style="max-width: 100px;">
                </td>
            </tr>

            <!-- Hidden Inputs -->
            <input type="hidden" name="student_name" value="{{ $log->student ? $log->student->last_name . ', ' . $log->student->first_name .' '. $log->student->middle_name : '' }}">
            <input type="hidden" name="program" value="{{ $log->student ? $log->student->program->program_name : '' }}">
            <input type="hidden" name="section" value="{{ $log->student ? $log->student->section->section_name : '' }}">
            <input type="hidden" name="year_level" value="{{ $log->student ? $log->student->yearLevel->year_level_name : '' }}">
            <input type="hidden" name="date" value="{{ $log->created_at->format('Y-m-d') }}">
            <input type="hidden" name="time" value="{{ $log->created_at->format('H:i:s') }}">
            <input type="hidden" name="action" value="{{ $log->action }}">
            <input type="hidden" name="image" value="{{ basename($log->image) }}">
        </form>
    @endforeach
</tbody>

</table>


<div class="d-flex justify-content-center">
    {{ $logs->links('vendor.pagination.simple-bootstrap-5') }}
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('reset-filters').addEventListener('click', function () {
        document.getElementById('date').value = '';
        const selects = document.querySelectorAll('select.form-select');
        selects.forEach(select => { select.selectedIndex = 0; });
    });
});
</script>


@endsection
