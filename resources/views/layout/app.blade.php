<!-- resources/views/layout.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', "I'm not named")</title>
    @vite(['resources/js/delete-modal.js', 'resources/js/delete-multiple-modal.js', 'resources/js/edit-modal.js', 'resources/js/edit-multiple-modal.js'])
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="{{ asset('images/rci1.jpg') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS & JS -->

    @stack('styles')

    <style>
        /* Ensure the footer stays at the bottom */
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header>
        @include('components.nav.navbar')
    </header>

    <!-- Main Content -->
    <main class="d-flex">
        @include('components.nav.sidebar')
        <section class="flex-grow-1 mt-4">
            @yield('content')
        </section>
        @include('page.school.departments.create')
        @include('page.school.departments.edit')
        @include('page.school.departments.delete')
        @include('page.school.programs.create')
        @include('page.school.programs.edit')
        @include('page.school.programs.delete')
        @include('page.school.year-levels.create')
        @include('page.school.year-levels.edit')
        @include('page.school.year-levels.delete')
        @include('page.school.sections.create')
        @include('page.school.sections.edit')
        @include('page.school.sections.delete')
        @include('page.school.instructors.delete')
        @include('page.school.students.create')
        @include('page.school.students.edit')
        @include('page.school.students.edit-multiple')
        @include('page.school.students.delete')
        @include('page.school.students.delete-multiple')
        @include('page.school.students.upload')
        @include('page.school.school-year.create')
        @include('page.school.school-year.edit')
        @include('page.school.school-year.delete')
    </main>


    <!-- SweetAlert Notifications -->
    @if(session('success'))
        <script>
            Swal.fire({
                toast: true,
                icon: 'success',
                title: "{{ session('success') }}",
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#d4edda',
                color: '#155724',

            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                toast: true,
                icon: 'error',
                title: "{{ $errors->first() }}",
                position: 'top-end',
                showConfirmButton: false,
                timer: 10000,
                timerProgressBar: true,
                background: '#f8d7da',
                color: '#842029'
            });
        </script>
    @elseif (session('error'))
        <script>
            Swal.fire({
                toast: true,
                icon: 'error',
                title: "{{ session('error') }}",
                position: 'top-end',
                showConfirmButton: false,
                timer: 10000,
                timerProgressBar: true,
                background: '#f8d7da',
                color: '#842029'
            });
        </script>

    @elseif (session('duplicates'))
    <script>
        Swal.fire({
            icon: 'warning',
            html: `{!! '<strong>Duplicate Entries:</strong><br>' . implode('<br>', session('duplicates')) !!}`,
            position: 'center',
            showConfirmButton: true,
            width: '40em',
            background: '#fff3cd',
            color: '#856404',
            customClass: {
                popup: 'swal-wide swal-large-text'
            }
        });
    </script>
    
    <style>
        .swal-wide {
            max-width: 600px;
        }
        .swal-large-text {
            font-size: 1.1rem;
            text-align: left;
        }
    </style>
    @endif



    @yield('scripts')

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>