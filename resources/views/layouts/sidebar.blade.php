@php
    $currentRouteName = Route::currentRouteName();
    // Get the current authenticated user
    $user = Auth::user();
    // Get the role of the current user
    $role = $user->role; // Assuming your user model has a 'role' attribute
@endphp

<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #1C486F">

    <!-- Sidebar - Brand -->
    <div class="sidebar-brand d-flex align-items-center justify-content-center">
        <div class="sidebar-brand-icon">
            <img height="30px" width="30px" src="{{ Vite::asset('../resources/images/dinas-logo.png') }}"
                alt="Logo Surabaya Hebat" class="img-fluid" />
        </div>
        <div class="sidebar-brand-text">Pemkot Surabaya</div>
    </div>

    <hr class="sidebar-divider mb-2">

    @if ($role == 'adminkantor')
        <li class="nav-item mb-2">
            <a class="nav-link py-2" href="{{ route('dashboard.admin_kantor') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <a class="nav-link py-2" href="{{ route('dataumkm.index') }}">
                <i class="fas fa-fw fa-store-alt"></i>
                <span>Data UMKM</span>
            </a>
            <a class="nav-link py-2" href="{{ route('datakegiatan.index') }}">
                <i class="fas fa-fw fa-calendar-week"></i>
                <span>Data Kegiatan</span>
            </a>
        </li>

        <hr class="sidebar-divider mb-2">
        <li class="nav-item mb-2">
            <a class="nav-link py-2" href="{{ route('approvalumkm.index') }}">
                <i class="fas fa-fw fa-check-circle"></i>
                <span>Approve UMKM</span>
            </a>
        </li>

        <hr class="sidebar-divider mb-2">
        <li class="nav-item mb-2">
            <a class="nav-link py-2" href="{{ route('exportintervensi.index') }}">
                <i class="fas fa-fw fa-file-export"></i>
                <span>Laporan Intervensi</span>
            </a>
        </li>


        <hr class="sidebar-divider d-none d-md-block mb-0">

        <li class="nav-item">
            <a class="nav-link" href="{{ route('manajemenuser.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Manajemen User</span></a>
        </li>
    @endif

    @if ($role == 'adminlapangan')
        <li class="nav-item mb-2">
            <a class="nav-link py-2" href="{{ route('dashboard.admin_lapangan') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <a class="nav-link py-2" href="{{ route('dataumkm.index') }}">
                <i class="fas fa-fw fa-store-alt"></i>
                <span>Data UMKM</span>
            </a>
            <a class="nav-link py-2" href="{{ route('datakegiatan.index') }}">
                <i class="fas fa-fw fa-calendar-week"></i>
                <span>Data Kegiatan</span>
            </a>
        </li>


        <hr class="sidebar-divider mb-2">
        <li class="nav-item mb-2">
            <a class="nav-link py-2" href="{{ route('approvalumkm.index') }}">
                <i class="fas fa-fw fa-check-circle"></i>
                <span>Approve UMKM</span>
            </a>
        </li>
    @endif

    <!-- Menu specific for pelakuumkm -->
    @if ($role == 'pelakuumkm')
        <!-- Add specific menu items for UMKM actors -->
        <li class="nav-item mt-0 mb-2">
            <a class="nav-link py-2" href="index.html">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Laporan Omset</span>
            </a>
        </li>

        <li class="nav-item mt-0 mb-2">
            <a class="nav-link py-2" href="index.html">
                <i class="fas fa-fw fa-hands-helping"></i>
                <span>Pengajuan Intervensi</span>
            </a>
        </li>

        <li class="nav-item mt-0 mb-2">
            <a class="nav-link py-2" href="index.html">
                <i class="fas fa-fw fa-user-cog"></i>
                <span>Profil UMKM</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider mb-2">
    @endif

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>

<style>
    /* Additional CSS to further reduce spacing if needed */
    #accordionSidebar .nav-item .nav-link.py-1 {
        margin: 0;
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
    }
</style>
