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
                        <i class="fas fa-store-alt mr-2 text-primary"></i>Data UMKM
                    </h1>
                    <div class="btn-group" role="group" aria-label="Data Actions">
                        <a href="{{ route('dataumkm.create') }}" class="btn btn-primary btn-icon-split ml-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Tambah Data</span>
                        </a>
                    </div>
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

                <form method="GET" action="{{ route('dataumkm.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="kelurahan">Kelurahan</label>
                            <select name="kelurahan" class="form-control">
                                <option value="">-- Semua Kelurahan --</option>
                                @foreach ($kelurahanList as $id => $nama)
                                    <option value="{{ $nama }}" {{ request('kelurahan') == $nama ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="status_keaktifan">Status</label>
                            <select name="status_keaktifan" class="form-control">
                                <option value="">-- Semua Status --</option>
                                <option value="AKTIF" {{ request('status_keaktifan') == 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                                <option value="TIDAK AKTIF" {{ request('status_keaktifan') == 'TIDAK AKTIF' ? 'selected' : '' }}>TIDAK AKTIF</option>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                        </div>
                        {{-- <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('dataumkm.index') }}" class="btn btn-secondary w-100">Reset</a>
                        </div> --}}
                    </div>
                </form>

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
                                        <th>NIK</th>
                                        <th>Nama Lengkap</th>
                                        <th>Kelurahan</th>
                                        <th>Alamat</th>
                                        {{-- <th></th>
                                        <th>Alamat</th> --}}
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datapelakuumkms as $pelakuumkm)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $pelakuumkm->nik }}</td>
                                            <td>{{ $pelakuumkm->nama_lengkap }}</td>
                                            <td>{{ $pelakuumkm->kelurahan }}</td>
                                            <td>{{ $pelakuumkm->alamat_sesuai_ktp }}</td>
                                            {{-- <td>{{ $pelakuumkm-> }}</td>
                                        <td>{{ $pelakuumkm->alamat }}</td> --}}
                                            <td>
                                                @switch($pelakuumkm->status_keaktifan)
                                                    @case('AKTIF')
                                                        <span class="badge badge-success status-badge">AKTIF</span>
                                                    @break

                                                    @case('TIDAK AKTIF')
                                                        <span class="badge badge-danger status-badge">TIDAK AKTIF</span>
                                                    @break

                                                    @default
                                                        <span
                                                            class="badge badge-secondary status-badge">{{ $pelakuumkm->status_keaktifan }}</span>
                                                @endswitch
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm text-center" role="group">
                                                    <a href="{{ route('dataumkm.show', $pelakuumkm->id) }}"
                                                        class="btn btn-sm btn-info btn-action mr-2"
                                                        title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('dataumkm.edit', $pelakuumkm->id) }}"
                                                        class="btn btn-sm btn-warning btn-action" title="Edit Data">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
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
