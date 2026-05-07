<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="gr-admin {{ request()->routeIs('admin.webgis') ? 'gr-page-webgis' : '' }}">
        <div id="gr-loader" class="gr-loader">
            <div class="text-center text-white">
                <div class="gr-spinner mx-auto mb-3"></div>
                <div class="fw-semibold">Geo-Road Admin</div>
                <div class="small text-white-50">Memuat dashboard...</div>
            </div>
        </div>

        <div class="d-flex">
            <div class="offcanvas offcanvas-start offcanvas-lg gr-sidebar" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel" data-bs-scroll="true" data-bs-backdrop="false">
                <div class="offcanvas-header d-lg-none">
                    <div class="fw-bold" id="adminSidebarLabel">Geo-Road</div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body d-flex flex-column p-0">
                    <div class="gr-branding text-center">
                        <div class="gr-mapicon mx-auto mb-2">
                            <i class="bi bi-map-fill"></i>
                        </div>
                        <div class="title">Geo-Road</div>
                        <div class="subtitle">Admin Panel • SIG Kerusakan Jalan</div>
                    </div>

                    <nav class="gr-sidebar-nav flex-grow-1">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> <span class="gr-navtext">Dashboard</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.roads.*') ? 'active' : '' }}" href="{{ route('admin.roads.index') }}">
                            <i class="bi bi-signpost-2 me-2"></i> <span class="gr-navtext">Data Jalan</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.webgis') ? 'active' : '' }}" href="{{ route('admin.webgis') }}">
                            <i class="bi bi-map me-2"></i> <span class="gr-navtext">Peta Jaringan Jalan</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}" href="{{ route('admin.statistics') }}">
                            <i class="bi bi-graph-up me-2"></i> <span class="gr-navtext">Statistik</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                            <i class="bi bi-file-earmark-text me-2"></i> <span class="gr-navtext">Laporan</span>
                        </a>
                    </nav>

                    <div class="mt-auto px-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn w-100 gr-logout">
                                <i class="bi bi-box-arrow-right me-2"></i> <span class="gr-navtext">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="gr-content">
                <div class="gr-main">
                    <div class="gr-topbar px-3 px-lg-4 py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-outline-dark d-lg-none rounded-4" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                                <i class="bi bi-list"></i>
                            </button>
                            <button id="grSidebarToggle" class="btn btn-sm btn-outline-dark d-none d-lg-inline-flex rounded-4" type="button" aria-label="Toggle sidebar">
                                <i class="bi bi-layout-sidebar-inset"></i>
                            </button>
                            <div>
                                <div class="fw-bold" style="font-size:1.1rem;line-height:1.15;text-transform:uppercase">Dinas Bina Marga dan Bina Konstruksi Provinsi Lampung</div>
                                <div class="small text-dark-emphasis">SIG Manajemen Data Kerusakan Jalan</div>
                            </div>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                            @if (auth()->user()?->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="gr-avatar">
                            @else
                                <i class="bi bi-person-circle"></i>
                            @endif
                            <span class="small fw-semibold">{{ auth()->user()->name ?? '-' }}</span>
                        </a>
                    </div>


                    <main class="gr-main-content p-3 p-lg-4">
                    @if (session('success') || session('error'))
                        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
                            <div class="toast text-bg-{{ session('success') ? 'success' : 'danger' }} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-gr-toast="1">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        {{ session('success') ?? session('error') }}
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @isset($header)
                        <div class="mb-3 text-dark" data-aos="fade-down">
                            {{ $header }}
                        </div>
                    @endisset

                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                    </main>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        @stack('scripts')
    </body>
</html>
