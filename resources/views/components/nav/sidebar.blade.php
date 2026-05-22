@vite(['resources/css/sidebar.blade.css'])

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


@if ((request()->routeIs(['student', 'department', 'program', 'section', 'year-level', 'school-year'])) && Auth::user()->role === 'Admin')
<aside class="fixed-sidebar position-relative start-0 d-flex flex-column align-items-start p-3 h-100 rounded-end">
    <a href="{{ route('department') }}" {{ request()->routeIs('department') ? 'btn-primary' : 'btn-secondary' }}">
        <i class="bi bi-building me-2"></i> Manage Departments
    </a>
    <a href="{{ route('program') }}"  {{ request()->routeIs('program') ? 'btn-primary' : 'btn-secondary' }}">
        <i class="bi bi-mortarboard-fill me-2"></i> Manage Programs
    </a>
    <a href="{{ route('year-level') }}" {{ request()->routeIs('year-level') ? 'btn-primary' : 'btn-secondary' }}">
        <i class="bi bi-calendar-fill me-2"></i> Manage Year Levels
    </a>
    <a href="{{ route('school-year') }}"  {{ request()->routeIs('school-year') ? 'btn-primary' : 'btn-secondary' }}">
        <i class="bi bi-alarm-fill me-2"></i> Manage School Years
    </a>
    <a href="{{ route('section') }}"  {{ request()->routeIs('section') ? 'btn-primary' : 'btn-secondary' }}">
        <i class="bi bi-bookmark-fill me-2"></i> Manage Sections
    </a>
    <a href="{{ route('student') }}"  {{ request()->routeIs('student') ? 'btn-primary' : 'btn-secondary' }}">
        <i class="bi bi-person-fill me-2"></i> Manage Students
    </a>
</aside>
@elseif ((request()->routeIs(['edit-account','confirm-password'])))
<aside class="fixed-sidebar position-relative start-0 d-flex flex-column align-items-start p-3 h-100 rounded-end">
    <a href="{{ route('edit-account') }}" {{ request()->routeIs('edit-account') ? 'btn-primary' : 'btn-secondary' }}">
        <i class="bi bi-pencil-fill"></i>Edit Account</a>
    <a href="{{ route('confirm-password') }}" {{ request()->routeIs('confirm-password') ? 'btn-primary' : 'btn-secondary' }}">
        <i class="bi bi-person-lock"></i>Change Password</a>
</aside>
@endif
