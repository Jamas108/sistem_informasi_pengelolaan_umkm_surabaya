@php
    $currentRouteName = Route::currentRouteName();
@endphp

<!-- Sidebar Toggle Button (Mobile) -->
<button class="sidebar-toggle btn btn-primary d-lg-none" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar -->
<div class="sidebar shadow-lg" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <div class="sidebar-brand d-flex align-items-center justify-content-center py-4 border-bottom">
        <div class="sidebar-brand-icon me-2">
            <img height="40px" width="40px" src="{{ Vite::asset('../resources/images/dinas-logo.png') }}"
                alt="Logo Surabaya Hebat" class="img-fluid" />
        </div>
        <div class="sidebar-brand-text fw-bold">UMKM Surabaya</div>
    </div>

    <!-- User Profile Summary -->
    <div class="user-profile text-center py-3 border-bottom mb-3">
        <div class="avatar mb-2">
            <i class="rounded-circle fas fa-user fa-2x" width="70" height="70" alt="User Avatar"></i>
        </div>
        <div class="user-info">
            <h6 class="fw-bold mb-0">{{ Auth::user()->pelakuUmkm->nama_lengkap ?? 'Pelaku UMKM' }}</h6>
            <small class="text-white-50">Pelaku UMKM</small>
        </div>
    </div>

    <!-- Nav Items -->
    <div class="nav-items px-3">
        <div class="menu-section mb-2">
            <small class="text-uppercase fw-bold text-white-50 ps-3">Menu Utama</small>
        </div>

        <a class="nav-link {{ $currentRouteName == 'dashboard.pelaku_umkm' ? 'active' : '' }}"
            href="{{ route('dashboard.pelaku_umkm') }}">
            <div class="d-flex align-items-center">
                <div class="icon-circle me-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <span class="ml-2">Dashboard</span>
            </div>
        </a>

        <a class="nav-link {{ $currentRouteName == 'pelakukelolaumkm.index' ? 'active' : '' }}"
            href="{{ route('pelakukelolaumkm.index') }}">
            <div class="d-flex align-items-center">
                <div class="icon-circle me-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-store"></i>
                </div>
                <span class="ml-2">Kelola UMKM</span>
            </div>
        </a>

        <a class="nav-link" href="{{ route('pelakukelolaintervensi.index') }}">
            <div class="d-flex align-items-center">
                <div class="icon-circle me-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-tasks"></i>
                </div>
                <span class="ml-2">Intervensi</span>
            </div>
        </a>

        <a class="nav-link" href="{{ route('pelakukegiatan.index') }}">
            <div class="d-flex align-items-center">
                <div class="icon-circle me-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <span class="ml-2">Kegiatan</span>
            </div>
        </a>

        <div class="menu-section mt-4 mb-2">
            <small class="text-uppercase fw-bold text-white-50 ps-3">Akun</small>
        </div>

        <a class="nav-link {{ $currentRouteName == 'profil' ? 'active' : '' }}" href="{{ route('profil.index') }}">
            <div class="d-flex align-items-center">
                <div class="icon-circle me-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-user"></i>
                </div>
                <span class="ml-2">Profil Saya</span>
            </div>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <a class="nav-link" href="#"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <div class="d-flex align-items-center">
                <div class="icon-circle me-3 d-flex align-items-center justify-content-center bg-danger">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <span class="ml-2">Keluar</span>
            </div>
        </a>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer mt-auto border-top p-3">
        <div class="text-center mt-2">
            <small class="text-white-50">&copy; 2025 Pemkot Surabaya</small>
        </div>
    </div>
</div>

<style>
    /* Custom Sidebar Styling */
    .sidebar {
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        width: 280px;
        background: linear-gradient(145deg, #1c4970, #2F77B6);
        color: white;
        transition: all 0.3s ease;
        z-index: 1050;
        overflow-y: auto;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .sidebar.toggled {
        width: 80px;
    }

    .sidebar.toggled .sidebar-brand-text,
    .sidebar.toggled span,
    .sidebar.toggled .user-info,
    .sidebar.toggled .menu-section,
    .sidebar.toggled .sidebar-footer {
        display: none;
    }

    .sidebar.toggled .nav-link {
        text-align: center;
        padding: 1rem;
    }

    .sidebar.toggled .icon-circle {
        margin-right: 0 !important;
    }

    .sidebar-toggle {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1060;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.2);
    }

    /* Style for navigation items */
    .nav-link {
        color: rgba(255, 255, 255, 0.8);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
        transform: translateX(5px);
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        font-weight: 600;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    /* Icon circle styling */
    .icon-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.1);
        transition: all 0.2s ease;
    }

    .nav-link:hover .icon-circle {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .nav-link.active .icon-circle {
        background-color: white;
        color: #1c4970;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0 !important;
        }
    }

    /* Adjust main content */
    .main-content {
        margin-left: 280px;
        transition: margin-left 0.3s ease;
    }

    /* For toggled sidebar */
    body.sidebar-toggled .main-content {
        margin-left: 80px;
    }

    @media (max-width: 992px) {
        body.sidebar-toggled .main-content {
            margin-left: 0;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('accordionSidebar');
        const body = document.body;

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');

                // On larger screens, toggle the sidebar width
                if (window.innerWidth >= 992) {
                    sidebar.classList.toggle('toggled');
                    body.classList.toggle('sidebar-toggled');
                }
            });
        }

        // Handle responsive behavior - close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 992 &&
                sidebar &&
                sidebarToggle &&
                !sidebar.contains(event.target) &&
                !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Add active class to current menu item
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href) && href !== '#') {
                link.classList.add('active');
            }
        });
    });
</script>
