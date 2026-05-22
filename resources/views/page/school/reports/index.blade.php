@extends('layout.app')
@section('title', 'Attendance Report | RCI AMS')

@section('content')
    <div class="container-fluid">
        <form method="GET" class="mb-4" id="filterForm">
            <div class="row g-1">
                <!-- Date Filter -->
                <div class="col-md-2">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" value="{{ $date }}" class="form-control" id="date">
                </div>
                <!-- Department Filter -->
                <div class="col-md-2">
                    <label for="department" class="form-label">Department</label>
                    <select name="department_id" class="form-select" id="department" onchange="filterPrograms()">
                        <option value="">-- All --</option>
                        @foreach ($departments as $d)
                            <option value="{{ $d->department_id }}" {{ $filters['department_id'] == $d->department_id ? 'selected' : '' }}>
                                {{ $d->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Program Filter -->
                <div class="col-md-2">
                    <label for="program" class="form-label">Program</label>
                    <select name="program_id" class="form-select" id="program">
                        <option value="">-- All --</option>
                        @foreach ($programs as $p)
                            <option value="{{ $p->program_id }}" data-department="{{ $p->department_id }}" {{ $filters['program_id'] == $p->program_id ? 'selected' : '' }}>
                                {{ $p->program_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Year Level Filter -->
                <div class="col-md-2">
                    <label for="year_level" class="form-label">Year Level</label>
                    <select name="year_level_id" class="form-select" id="year_level" onchange="filterYearLevel()">
                        <option value="">-- All --</option>
                        @foreach ($yearlevels as $y)
                            <option value="{{ $y->year_level_id }}" data-department="{{ $y->department_id ?? '' }}" {{ $filters['year_level_id'] == $y->year_level_id ? 'selected' : '' }}>
                                {{ $y->year_level_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Section Filter -->
                <div class="col-md-2">
                    <label for="section" class="form-label">Section</label>
                    <select name="section_id" class="form-select" id="section">
                        <option value="">-- All --</option>
                        @foreach ($sections as $s)
                            <option value="{{ $s->section_id }}" data-program="{{ $s->program_id }}" data-year="{{ $s->year_level_id }}" {{ $filters['section_id'] == $s->section_id ? 'selected' : '' }}>
                                {{ $s->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- School Year Filter -->
                <div class="col-md-2">
                    <label for="school_year" class="form-label">School Year</label>
                    <select name="school_year_id" class="form-select" id="school_year">
                        <option value="">-- All --</option>
                        @foreach ($schoolYears as $sy)
                            <option value="{{ $sy->school_year_id }}" {{ $filters['school_year_id'] == $sy->school_year_id ? 'selected' : '' }}>
                                {{ $sy->school_year_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary float-end me-2">Apply Filters</button>
                    <button type="button" class="btn btn-secondary float-end" id="reset-filters">Reset</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Department</th>
                        <th>Program</th>
                        <th>Year Level</th>
                        <th>Section</th>
                        <th>School Year</th>
                        <th>Present</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendance as $key => $data)
                        <tr>
                            <td>{{ $data['department'] }}</td>
                            <td>{{ $data['program'] }}</td>
                            <td>{{ $data['year_level'] }}</td>
                            <td>{{ $data['section'] }}</td>
                            <td>{{ $data['school_year'] }}</td>
                            <td>{{ $data['present'] }}</td>
                            <td>{{ $data['total'] }}</td>
                            <td>{{ implode(', ', $data['dates']->toArray()) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('reset-filters').addEventListener('click', function () {
                document.getElementById('date').value = '';
                const selects = document.querySelectorAll('select.form-select');
                selects.forEach(select => select.selectedIndex = 0);
                document.getElementById('filterForm').submit();
            });

            // Initialize filters on load
            filterPrograms();
            filterYearLevel();
            filterSections();
        });

        function filterPrograms() {
            const selectedDepartment = document.getElementById('department').value;
            const programSelect = document.getElementById('program');
            const options = programSelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === "") { option.hidden = false; return; }
                const deptId = option.getAttribute('data-department');
                option.hidden = selectedDepartment && deptId !== selectedDepartment;
            });

            if (programSelect.selectedOptions.length && programSelect.selectedOptions[0].hidden) {
                programSelect.value = "";
            }

            document.getElementById('year_level').value = '';
            filterYearLevel();
            filterSections();
        }

        function filterYearLevel() {
            const selectedDepartment = document.getElementById('department').value;
            const yearLevelSelect = document.getElementById('year_level');
            const options = yearLevelSelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === "") { option.hidden = false; return; }
                const deptId = option.getAttribute('data-department');
                option.hidden = selectedDepartment && deptId !== selectedDepartment;
            });

            if (yearLevelSelect.selectedOptions.length && yearLevelSelect.selectedOptions[0].hidden) {
                yearLevelSelect.value = "";
            }

            filterSections();
        }

        function filterSections() {
            const selectedProgram = document.getElementById('program').value;
            const selectedYear = document.getElementById('year_level').value;
            const sectionSelect = document.getElementById('section');
            const options = sectionSelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === "") { option.hidden = false; return; }
                const progId = option.getAttribute('data-program');
                const yearId = option.getAttribute('data-year');

                let visible = true;
                if (selectedProgram && progId !== selectedProgram) visible = false;
                if (selectedYear && yearId !== selectedYear) visible = false;

                option.hidden = !visible;
            });

            if (sectionSelect.selectedOptions.length && sectionSelect.selectedOptions[0].hidden) {
                sectionSelect.value = "";
            }
        }

        // Re-filter sections when program/year changes
        document.getElementById('program').addEventListener('change', filterSections);
        document.getElementById('year_level').addEventListener('change', filterSections);
    </script>
@endsection
