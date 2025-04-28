<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pengelolaan UMKM Kota Surabaya') }}</title>

    <!-- CDN -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    @vite('resources/css/sb-admin-2.min.css')
    @vite('resources/css/all.css')
    @vite('resources/css/sb-admin-2.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap JS and jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>

    @yield('styles')

    <script src="{{ asset('js/umkm-form.js') }}"></script>
    @vite('resources/css/sb-admin-2.min.css')
    <script src="{{ asset('js/jquery.min.js') }}"></script>


    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #1c4970;
            --secondary-color: #2F77B6FF;
            --light-background: #f8f9fa;
            --dark-text: #333;
        }

        body {
            background-color: var(--light-background);
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            transition: transform 0.3s ease;
            z-index: 1050;
        }

        .sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1060;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
        }

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

        .sidebar-logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-pills .nav-link {
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background-color: var(--secondary-color);
            color: white;
        }

        .nav-pills .nav-link:hover {
            color: white;
            background-color: rgba(0, 210, 255, 0.2);
        }

        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        .card-umkm {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-umkm:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-tambah-umkm {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1040;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-tambah-umkm .modal-header {
            background-color: var(--primary-color);
            color: white;
        }

        .form-floating>label {
            color: var(--dark-text);
        }
    </style>
 {{-- Togler Sidebar Script --}}
 <script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('accordionSidebar');

        sidebarToggle.addEventListener('click', function() {
            if (sidebar.classList.contains('toggled')) {
                sidebar.classList.remove('toggled');
            } else {
                sidebar.classList.add('toggled');
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.dropdown-toggle').dropdown();
    });
</script>


</head>

<body>
    @yield('content')
    @vite('resources/js/app.js')
    {{-- @include('sweetalert::alert') --}}
    @stack('scripts')
    <script src="{{ asset('js/umkm-form.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle for mobile
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 992) {
                    if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
    </script>
</body>

</html>
