@extends('layout.app')

@section('title', 'Instructor Records | RCI AMS')

@section('content')
@php
    // Get hidden log IDs for this instructor from session
    $hiddenLogs = session('hidden_instructor_logs', []);
@endphp

<div class="container mt-4">
    <h2 class="mb-3">Instructor Attendance Records</h2>

    {{-- FILTERS --}}
    <form method="GET" action="{{ url()->current() }}" class="row g-2 mb-4">

        {{-- Search by Name (firstname, middlename, lastname) --}}
        <div class="col-md-3">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Search name..." 
                value="{{ request('search') }}">
        </div>

        {{-- Status (Present / Absent / Late) --}}
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Status --</option>
                <option value="present" {{ request('status')=='present' ? 'selected' : '' }}>Present</option>
                <option value="absent" {{ request('status')=='absent' ? 'selected' : '' }}>Absent</option>
                <option value="Late" {{ request('status')=='Late' ? 'selected' : '' }}>Late</option>
            </select>
        </div>

        {{-- Section --}}
        <div class="col-md-2">
            <select name="section" class="form-select">
    <option value="">-- Section --</option>
    @foreach($sections as $section)
        <option value="{{ $section }}" 
            {{ request('section') == $section ? 'selected' : '' }}>
            {{ $section }}
        </option>
    @endforeach
</select>

        </div>

        {{-- Year Level --}}
        <div class="col-md-2">
            <select name="year_level" class="form-select">
    <option value="">-- Year Level --</option>
    @foreach($yearLevels as $level)
        <option value="{{ $level }}" 
            {{ request('year_level') == $level ? 'selected' : '' }}>
            {{ $level }}
        </option>
    @endforeach
</select>

        </div>

        {{-- Date Picker --}}
        <div class="col-md-2">
            <input 
                type="date" 
                name="date" 
                class="form-control"
                value="{{ request('date') }}">
        </div>

        {{-- Filter Button --}}
        <div class="col-md-1">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

        <a href="{{ route('instructor.attendance.export', request()->all()) }}" class="btn btn-success mb-3">
    Export CSV
</a>


    </form>

    <table class="table table-bordered table-striped" id="attendanceTable">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Program</th>
                <th>Section</th>
                <th>Year Level</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Instructor</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                @if(!in_array($log->id, $hiddenLogs))
                <tr data-log-id="{{ $log->id }}">
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->student_name }}</td>
                    <td>{{ $log->program }}</td>
                    <td>{{ $log->section }}</td>
                    <td>{{ $log->year_level }}</td>
                    <td>{{ $log->date }}</td>
                    <td>{{ $log->time }}</td>
                    <td class="status">{{ $log->attendance_status }}</td>
                    <td>{{ $log->instructor }}</td>
                    <td>{{ $log->created_at }}</td>
                    <td>{{ $log->updated_at }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary edit-btn" style="width:70px;">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" style="width:70px;">Delete</button>
                    </td>
                </tr>
                @endif
            @empty
                <tr>
                    <td colspan="12" class="text-center">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- JavaScript for Edit/Delete --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('attendanceTable');

    table.addEventListener('click', function (e) {
        const target = e.target;
        const row = target.closest('tr');

        // Delete button
        if (target.classList.contains('delete-btn')) {
            const logId = row.getAttribute('data-log-id');

             // Ask for confirmation
        const confirmed = confirm("Are you sure you want to delete this attendance record?");
         if (!confirmed) return; // If user clicks Cancel, do nothing


            // AJAX request to hide row in session
            fetch('{{ route("instructor.attendance.hide") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ log_id: logId })
            }).then(res => res.json())
              .then(data => {
                  if (data.success) {
                      row.remove(); // hide row visually
                  }
              });
        }

        // Edit/Save button
        if (target.classList.contains('edit-btn')) {
            const statusCell = row.querySelector('.status');

            if (target.textContent === 'Edit') {
                const currentStatus = statusCell.textContent.trim();
                const select = document.createElement('select');
                select.innerHTML = `
                    <option value="Present" ${currentStatus === 'Present' ? 'selected' : ''}>Present</option>
                    <option value="Absent" ${currentStatus === 'Absent' ? 'selected' : ''}>Absent</option>
                    <option value="Late" ${currentStatus === 'Late' ? 'selected' : ''}>Late</option>
                `;
                statusCell.textContent = '';
                statusCell.appendChild(select);

                target.textContent = 'Save';
                target.classList.remove('btn-primary');
                target.classList.add('btn-success');
            } else if (target.textContent === 'Save') {
                const select = statusCell.querySelector('select');
                if (select) {
                    statusCell.textContent = select.value;
                }
                target.textContent = 'Edit';
                target.classList.remove('btn-success');
                target.classList.add('btn-primary');
            }
        }
    });
});
</script>

@endsection
