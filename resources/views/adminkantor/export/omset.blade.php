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
                columnDefs: [
                    {
                        targets: [-1],
                        orderable: false
                    }
                ]
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
                        <i class="fas fa-store-alt mr-2 text-primary"></i>Data Omset
                    </h1>
                    <div class="btn-group" role="group" aria-label="Data Actions">
                        <a href="#" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-export"></i>
                            </span>
                            <span class="text">Export Data</span>
                        </a>
                        <a href="" class="btn btn-primary btn-icon-split ml-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Tambah Data</span>
                        </a>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold">Daftar Omset UMKM</h6>
                        <div class="dropdown no-arrow">
                            <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-white"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
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
                                        <th>Nama UMKM</th>
                                        <th>Pemilik UMKM</th>
                                        <th>Jangka Waktu</th>
                                        <th>Total Omset</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataomsets as $omset)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $omset->dataUmkm->nama_usaha }}</td>
                                        <td>{{ $omset->dataUmkm->pelakuUmkm->nama_lengkap }}</td>
                                        <td>{{ $omset->jangka_waktu }}</td>
                                        <td>{{ $omset->total_omset }}</td>
                                        <td>{{ $omset->keterangan }}</td>
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
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>© {{ date('Y') }} UMKM Management System</span>
                </div>
            </div>
        </footer>
    </div>
@endsection