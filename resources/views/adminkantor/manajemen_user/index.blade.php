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
        .role-badge {
            font-size: 0.8rem;
            padding: 0.35rem 0.5rem;
        }
        .account-type-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
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
                return confirm('Apakah Anda yakin ingin menghapus data pengguna ini?');
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
                        <i class="fas fa-users mr-2 text-primary"></i>Manajemen Pengguna
                    </h1>
                    <div class="btn-group" role="group" aria-label="Data Actions">
                        <button type="button" class="btn btn-primary btn-icon-split ml-2" data-toggle="modal" data-target="#tambahUserModal">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Tambah Pengguna</span>
                        </button>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold">Daftar Pengguna Sistem</h6>
                        <div class="dropdown no-arrow">
                            <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-white"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#tambahUserModal">Tambah Pengguna Baru</a>
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
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user as $manajemenuser)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>

                                            <td>{{ $manajemenuser->username }}</td>
                                            <td>
                                                @switch($manajemenuser->role)
                                                    @case('adminkantor')
                                                        <span class="badge badge-primary role-badge">Admin Kantor</span>
                                                        @break
                                                    @case('adminlapangan')
                                                        <span class="badge badge-success role-badge">Admin Lapangan</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary role-badge">{{ $manajemenuser->role }}</span>
                                                @endswitch
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('manajemenuser.show', $manajemenuser->id) }}"
                                                       class="btn btn-info btn-action mr-2"
                                                       title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('manajemenuser.edit', $manajemenuser->id) }}"
                                                       class="btn btn-warning btn-action"
                                                       title="Edit Data">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('manajemenuser.destroy', $manajemenuser->id) }}"
                                                          method="POST"
                                                          class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-danger btn-action ml-2"
                                                                title="Hapus Data">
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

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>© {{ date('Y') }} User Management System</span>
                </div>
            </div>
        </footer>
    </div>

    <!-- Tambah User Modal -->
    <div class="modal fade" id="tambahUserModal" tabindex="-1" role="dialog" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="tambahUserModalLabel">
                        <i class="fas fa-user-plus mr-2"></i>Pilih Jenis Akun
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('manajemenuser.create', ['role' => 'adminkantor']) }}"
                               class="btn btn-primary btn-block py-3 account-type-btn">
                                <i class="fas fa-user-tie mr-2"></i>Admin Kantor
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('manajemenuser.create', ['role' => 'adminlapangan']) }}"
                               class="btn btn-success btn-block py-3 account-type-btn">
                                <i class="fas fa-user-shield mr-2"></i>Admin Lapangan
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endsection