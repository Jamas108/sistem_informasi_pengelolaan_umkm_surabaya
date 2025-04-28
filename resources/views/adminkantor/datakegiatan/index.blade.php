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
    </style>
@endpush

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            $('#datakegiatatantable').DataTable({
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

            // Konfirmasi penghapusan
            $(document).on('submit', 'form.delete-form', function(e) {
                return confirm('Apakah Anda yakin ingin menghapus data Kegiatan ini?');
            });

            // Poster preview modal
            $('.poster-thumbnail').on('click', function() {
                const imgSrc = $(this).data('full-image');
                $('#posterPreviewModal img').attr('src', imgSrc);
                $('#posterPreviewModal').modal('show');
            });
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
                        <i class="fas fa-store-alt mr-2 text-primary"></i>Data Kegiatan Intervensi
                    </h1>
                    <div class="btn-group" role="group" aria-label="Data Actions">
                        <a href="{{ route('datakegiatan.create') }}" class="btn btn-primary btn-icon-split ml-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Tambah Data</span>
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold">Daftar Kegiatan Intervensi</h6>
                        <div class="dropdown no-arrow">
                            <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-white"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('datakegiatan.create') }}">Tambah Data Baru</a>
                                <a class="dropdown-item" href="#">Cetak Laporan</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" id="datakegiatatantable">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Poster</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Jenis Kegiatan</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kegiatans as $index => $kegiatan)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">
                                                @if ($kegiatan->poster)
                                                    <img src="{{ Storage::url($kegiatan->poster) }}" alt="Poster"
                                                        width="100" height="100">
                                                @else
                                                    <span class="text-muted">Tidak ada poster</span>
                                                @endif
                                            </td>
                                            <td>{{ $kegiatan->nama_kegiatan }}</td>
                                            <td>{{ $kegiatan->jenis_kegiatan ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d M Y') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($kegiatan->jam_mulai)->format('H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($kegiatan->jam_selesai)->format('H:i') }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = 'secondary';
                                                    switch ($kegiatan->status_kegiatan) {
                                                        case 'Belum Dimulai':
                                                            $badgeClass = 'warning';
                                                            break;
                                                        case 'Sedang Berlangsung':
                                                            $badgeClass = 'primary';
                                                            break;
                                                        case 'Selesai':
                                                            $badgeClass = 'success';
                                                            break;
                                                    }
                                                @endphp
                                                <span class="badge badge-{{ $badgeClass }} status-badge">
                                                    {{ $kegiatan->status_kegiatan ?? 'Tidak Diketahui' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if ($kegiatan->status_kegiatan == 'Pendaftaran')
                                                        @php
                                                            $existingInterventions = App\Models\Intervensi::where(
                                                                'kegiatan_id',
                                                                $kegiatan->id,
                                                            )->count();
                                                        @endphp
                                                        @if ($existingInterventions > 0)
                                                            <form
                                                                action="{{ route('datakegiatan.generate-bukti', $kegiatan->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Generate bukti pendaftaran untuk {{ $existingInterventions }} UMKM?');">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm">
                                                                    <i class="fas fa-file-pdf"></i> Generate Bukti
                                                                    ({{ $existingInterventions }} UMKM)
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="text-muted">Belum ada UMKM terdaftar</span>
                                                        @endif
                                                    @endif
                                                    <a href="{{ route('datakegiatan.show', $kegiatan->id) }}"
                                                        class="btn btn-info btn-sm btn-action" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('datakegiatan.edit', $kegiatan->id) }}"
                                                        class="btn btn-warning btn-sm btn-action" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('datakegiatan.destroy', $kegiatan->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm btn-action"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Poster Preview Modal -->
        <div class="modal fade" id="posterPreviewModal" tabindex="-1" role="dialog"
            aria-labelledby="posterPreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="posterPreviewModalLabel">Pratinjau Poster</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="" class="img-fluid" alt="Poster Preview">
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>© {{ date('Y') }} UMKM Management System</span>
                </div>
            </div>
        </footer>
    </div>
@endsection
