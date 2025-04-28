<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

    <style>
        body {
            background-image: url('{{ Vite::asset('../resources/images/bg-page.png')  }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        #content-wrapper {
            background-color: rgba(255, 255, 255, 0.9);
        }

        /* Optional: Add a subtle overlay to make content more readable if needed */
        #wrapper::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1); /* Adjust opacity as needed */
            z-index: -1;
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

<body id="page-top">
    <div id="wrapper">
        @yield('content')
        @vite('resources/js/app.js')
        {{-- @include('sweetalert::alert') --}}
        @stack('scripts')
    </div>
</body>

</html>