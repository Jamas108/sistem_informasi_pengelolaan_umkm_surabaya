@extends('layouts.app')

@push('styles')
    <style>
        .card-header {
            background: linear-gradient(to right, #4e73e0, #224abe);
            color: white;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.1);
            cursor: pointer;
        }

        .btn-action {
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 0.35rem 0.5rem;
        }

        /* Banner Kegiatan - Horizontal Layout */
        .kegiatan-banner {
            background: linear-gradient(135deg, rgba(78, 115, 223, 0.9), rgba(34, 74, 190, 0.9)),
                url('/img/banner-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 1.5rem;
            margin-bottom: 1.8rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            position: relative;
            overflow: hidden;
        }

        .kegiatan-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/img/pattern.png');
            opacity: 0.1;
            z-index: 1;
        }

        .kegiatan-banner h4 {
            font-weight: 700;
            position: relative;
            z-index: 2;
            margin-bottom: 0;
        }

        .kegiatan-banner .icon {
            margin-right: 0.5rem;
            opacity: 0.9;
        }

        .kegiatan-info-row {
            position: relative;
            z-index: 2;
        }

        .kegiatan-info-card {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            padding: 0.8rem;
            height: 100%;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .kegiatan-info-card:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .info-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        .info-content {
            flex: 1;
        }

        .info-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.8;
            margin-bottom: 0.2rem;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 600;
        }

        .badge-lg {
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 2rem;
            box-shadow: 0 0.15rem 0.5rem 0 rgba(0, 0, 0, 0.1);
            z-index: 2;
            position: relative;
        }

        /* Improved card styles */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.8rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-header {
            border-radius: 0.5rem 0.5rem 0 0 !important;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
        }

        .card-header .card-title {
            margin-bottom: 0;
            display: flex;
            align-items: center;
        }

        .card-header .card-title i {
            margin-right: 0.5rem;
        }

        /* Table improvements */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fc;
            color: #5a5c69;
            font-weight: 700;
            border-bottom: 2px solid #e3e6f0;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(78, 115, 223, 0.03);
        }

        .table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        /* Stats cards improvements */
        .stats-card {
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            margin-right: 1rem;
            color: white;
        }

        .stats-icon-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
        }

        .stats-icon-success {
            background: linear-gradient(45deg, #1cc88a, #169b6b);
        }

        .stats-icon-danger {
            background: linear-gradient(45deg, #e74a3b, #c53030);
        }

        .stats-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #5a5c69;
            margin-bottom: 0.5rem;
        }

        .stats-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #5a5c69;
            margin-bottom: 0;
        }

        /* Badge Status Styles */
        .badge-status {
            padding: 0.4rem 0.8rem;
            border-radius: 10rem;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-hadir {
            background-color: rgba(28, 200, 138, 0.1);
            color: #1cc88a;
            border: 1px solid rgba(28, 200, 138, 0.2);
        }

        .badge-tidak-hadir {
            background-color: rgba(231, 74, 59, 0.1);
            color: #e74a3b;
            border: 1px solid rgba(231, 74, 59, 0.2);
        }

        .badge-terdaftar {
            background-color: rgba(78, 115, 223, 0.1);
            color: #4e73df;
            border: 1px solid rgba(78, 115, 223, 0.2);
        }

        /* Action buttons improvement */
        .action-buttons {
            display: flex;
            justify-content: center;
        }

        .btn-circle {
            border-radius: 100%;
            height: 2.5rem;
            width: 2.5rem;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.2rem;
        }

        .btn-circle-sm {
            height: 2rem;
            width: 2rem;
            font-size: 0.75rem;
        }

        /* Responsive tweaks for mobile */
        @media (max-width: 767px) {
            .kegiatan-info-card {
                margin-bottom: 0.5rem;
            }

            .kegiatan-banner h4 {
                font-size: 1.1rem;
            }

            .badge-lg {
                font-size: 0.7rem;
                padding: 0.2rem 0.5rem !important;
            }

            .stats-card {
                margin-bottom: 1rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            $('#pendaftartable').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                    infoFiltered: "(disaring dari _MAX_ total entri)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                columnDefs: [{
                    targets: [-1],
                    orderable: false
                }]
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // WhatsApp notifications
            $('.btn-notify').on('click', function() {
                const phoneNumber = $(this).data('phone');
                const umkmName = $(this).data('umkm');
                const kegiatanName = $(this).data('kegiatan');

                const message =
                    `Halo *${umkmName}*,%0A%0AKami ingin menginformasikan bahwa pendaftaran Anda untuk kegiatan *${kegiatanName}* telah berhasil. Silakan hadir sesuai jadwal yang telah ditentukan.%0A%0ATerima kasih.`;

                window.open(`https://wa.me/${phoneNumber}?text=${message}`, '_blank');
            });

            // Notify all button
            $('#notifyAll').on('click', function() {
                if (confirm('Apakah Anda yakin ingin mengirim notifikasi ke semua peserta?')) {
                    $('.btn-notify').each(function() {
                        $(this).trigger('click');
                    });
                }
            });

            // Print attendance list
            $('#printAttendance').on('click', function() {
                window.open($(this).data('url'), '_blank');
            });

            // Animation on cards
            $('.kegiatan-info-card').hover(
                function() {
                    $(this).find('.info-icon').css('background-color', 'rgba(255, 255, 255, 0.3)');
                },
                function() {
                    $(this).find('.info-icon').css('background-color', 'rgba(255, 255, 255, 0.2)');
                }
            );
        });
    </script>
@endpush

@section('content')
    @include('layouts.sidebar')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navbar')
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-users mr-2 text-primary"></i>Daftar Peserta Kegiatan {{ $kegiatan->nama_kegiatan }}
                    </h1>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <strong><i class="fas fa-check-circle mr-2"></i>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Participant List Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-list mr-2"></i>Daftar Peserta Pendaftar</h6>
                        <div class="dropdown no-arrow">
                            <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-white"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#" id="notifyAll">
                                    <i class="fas fa-bell mr-2 text-warning"></i>Notifikasi Semua Pendaftar
                                </a>
                                <a class="dropdown-item" href="#" id="printAttendance" data-url="#">
                                    <i class="fas fa-print mr-2 text-primary"></i>Cetak Daftar Hadir
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('datakegiatan.index') }}">
                                    <i class="fas fa-arrow-left mr-2 text-gray-500"></i>Kembali ke Daftar Kegiatan
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" id="pendaftartable">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th width="15%">No Pendaftaran</th>
                                        <th width="15%">Pemilik UMKM</th>
                                        <th width="15%">Nama UMKM</th>
                                        <th width="10%">No. Telepon</th>
                                        <th width="15%">Alamat UMKM</th>
                                        <th width="10%">Tgl Pendaftaran</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($intervensis as $index => $intervensi)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $intervensi->no_pendaftaran_kegiatan }}</td>
                                            <td>
                                                <strong>{{ $intervensi->dataUmkm->pelakuUmkm->nama_lengkap }}</strong>
                                            </td>
                                            <td>{{ $intervensi->dataUmkm->nama_usaha }}</td>

                                            <td>{{ $intervensi->dataUmkm->pelakuUmkm->no_telp ?? '-' }}</td>
                                            <td>{{ $intervensi->dataUmkm->alamat ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($intervensi->created_at)->format('d M Y') }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer" style="background-color: #e0e0e0">
            <div class="container  my-auto">
                <div class="copyright text-center my-auto">
                    <span class="text-black">© {{ date('Y') }} UMKM Management System Dinas Koperasi Usaha Kecil dan Menangah dan Perdagangan Kota Surabaya </span> <br>
                </div>
            </div>
        </footer>
    </div>
@endsection
