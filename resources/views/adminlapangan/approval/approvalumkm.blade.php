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
    <script>
        $('#rejectionModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var umkmId = button.data('umkm-id');
            var url = button.data('url');

            var modal = $(this);
            modal.find('#rejectionForm').attr('action', url);
        });

        // Form validation before submission
        $('#rejectionForm').submit(function(e) {
            var reason = $('#alasan_penolakan').val().trim();

            if (!reason) {
                e.preventDefault();
                alert('Alasan penolakan harus diisi!');
                return false;
            }

            return confirm('Apakah Anda yakin ingin menolak pengajuan UMKM ini?');
        });
    </script>
@endpush

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            $('#dataumkmtable').DataTable({
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
                return confirm('Apakah Anda yakin ingin menghapus data UMKM ini?');
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
                        <i class="fas fa-store-alt mr-2 text-primary"></i>Persetujuan Pengajuan UMKM
                    </h1>

                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold">Daftar UMKM Terdaftar</h6>
                        <div class="dropdown no-arrow">
                            <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-white"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('dataumkm.create') }}">Tambah Data Baru</a>
                                <a class="dropdown-item" href="#">Cetak Laporan</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" id="dataumkmtable">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>NIK Pemilik</th>
                                        <th>Nama Pemilik</th>
                                        <th>Nama Usaha</th>
                                        <th>Jenis Produk</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataumkms as $dataumkm)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $dataumkm->pelakuUmkm->nik }}</td>
                                            <td>{{ $dataumkm->pelakuUmkm->nama_lengkap }}</td>
                                            <td>{{ $dataumkm->nama_usaha }}</td>
                                            <td>{{ $dataumkm->jenis_produk }}</td>
                                            <td>
                                                @switch($dataumkm->status)
                                                    @case('Menunggu Verifikasi')
                                                        <span class="badge badge-warning status-badge">Menunggu Verifikasi</span>
                                                    @break

                                                    @default
                                                        <span
                                                            class="badge badge-secondary status-badge">{{ $dataumkm->status }}</span>
                                                @endswitch
                                            </td>
                                            <!-- Replace the action buttons in your table with these updated ones -->

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm text-center" role="group">
                                                    <a href="{{ route('approvalumkm.show', $dataumkm->id) }}"
                                                        class="btn btn-sm btn-info btn-action mr-2" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('approval.approve', $dataumkm->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success btn-action"
                                                            title="Setujui Pengajuan"
                                                            onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan UMKM ini?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('approval.reject', $dataumkm->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="button" class="btn btn-sm btn-danger btn-action ml-2"
                                                            title="Tolak Pengajuan" data-toggle="modal"
                                                            data-target="#rejectionModal"
                                                            data-umkm-id="{{ $dataumkm->id }}"
                                                            data-url="{{ route('approval.reject', $dataumkm->id) }}">
                                                            <i class="fas fa-times"></i>
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

        <div class="modal fade" id="rejectionModal" tabindex="-1" role="dialog" aria-labelledby="rejectionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectionModalLabel">Alasan Penolakan UMKM</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="rejectionForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="alasan_penolakan">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="4" required></textarea>
                                <small class="form-text text-muted">Berikan alasan yang jelas mengapa pengajuan UMKM ini
                                    ditolak.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                        </div>
                    </form>
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
x
