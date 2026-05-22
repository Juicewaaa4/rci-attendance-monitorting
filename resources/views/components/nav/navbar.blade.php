@if (!request()->routeIs(['scan', 'login']))
    <nav class="navbar navbar-expand-lg navbar-light bg-light z-2">
        <div class="container">
            <a class="navbar-brand" href="{{ Auth::check() ? route('dashboard') : route('login') }}">RCI AMS</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse d-flex justify-content-between align-items-center" id="navbarNav">
                <!-- Left nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-primary fw-bold"
                            href="{{ Auth::check() ? route('dashboard') : route('login') }}">
                            {{ Auth::check() ? 'Dashboard' : null }}
                        </a>
                    </li>
                </ul>

                <!-- Center text -->
                {{-- <div class="text-center mx-auto">
                    <p class="mb-0">Brought to you by <a class="text-decoration-none text-primary"
                            href="https://www.facebook.com/EbiEbiEcho" target="_blank" rel="noopener noreferrer">Echo</a>
                    </p>
                </div> --}}

                <!-- Right actions -->
                @if (Auth::check())
                    <div class="d-flex align-items-center">
                        <form action="{{ route('process-logout') }}" method="POST" class="d-flex">
                            @csrf
                            <button type="submit" class="btn btn-danger text-white px-3">
                                <i class="bi bi-box-arrow-right"></i> Log-out
                            </button>
                        </form>
                        <a class="btn btn-secondary ms-2" href="{{ route('edit-account') }}">
                        <i class="bi bi-gear" style="margin-right: 6px;"></i>Settings
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </nav>
@endif
 