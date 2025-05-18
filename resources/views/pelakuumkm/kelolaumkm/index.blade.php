@extends('layouts.pelakuumkm.app')
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find all alert elements
            const alerts = document.querySelectorAll('.alert');

            alerts.forEach(function(alert) {
                // Set a timeout to hide the alert after 3 seconds
                setTimeout(function() {
                    alert.classList.remove('show'); // Remove the 'show' class to hide the alert
                    alert.classList.add(
                        'fade'); // Add the 'fade' class to trigger the fade-out effect
                }, 3000); // 3000 milliseconds = 3 seconds
            });
        });
    </script>
@endpush
@section('content')
    @include('layouts.pelakuumkm.sidebar')
    <main class="main-content">
        <!-- Header Section -->
        <div class="text-white py-3 px-4 shadow-sm" id="nav" style="background: linear-gradient(145deg, #1c4970, #2F77B6);">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-store me-2"></i>
                        <span>Kelola UMKM</span>
                    </h4>
                    <p class="mb-0 fs-6">Atur dan kembangkan usaha Anda untuk pertumbuhan bisnis yang optimal</p>
                </div>
                <div>
                    <a class="btn btn-light rounded-pill px-4 shadow-sm" href="{{ route('pelakukelolaumkm.create') }}">
                        <i class="fas fa-plus-circle me-2"></i> Pendaftaran UMKM
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid px-4 py-4">

            @if (session('success'))
                <div class="alert alert-success fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger fade show" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Stats Cards -->


            <!-- Search & Filter Section -->
            <form method="GET" action="{{ route('pelakukelolaumkm.index') }}">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-search text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0" id="searchUMKM"
                                        placeholder="Cari UMKM...">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <select class="form-control" name="sector" id="filterSector">
                                    <option value="">Semua Sektor</option>
                                    <option value="INDUSTRI" @if (request('sector') == 'INDUSTRI') selected @endif>INDUSTRI
                                    </option>
                                    <option value="DAGANG" @if (request('sector') == 'DAGANG') selected @endif>DAGANG</option>
                                    <option value="JASA" @if (request('sector') == 'JASA') selected @endif>JASA</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <select class="form-control" name="status" id="filterStatus">
                                    <option value="">Semua Status</option>
                                    <option value="AKTIF" @if (request('status') == 'AKTIF') selected @endif>AKTIF</option>
                                    <option value="TIDAK AKTIF" @if (request('status') == 'TIDAK AKTIF') selected @endif>TIDAK
                                        AKTIF</option>
                                    <option value="Menunggu Verifikasi" @if (request('status') == 'Menunggu Verifikasi') selected @endif>
                                        Menunggu Verifikasi</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- UMKM List Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-list me-2"></i>
                        <span>Daftar UMKM</span>
                    </h5>
                </div>

                <!-- Table View -->
                <div id="tableView" class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-3 py-3">No</th>
                                    <th class="px-3 py-3">Nama UMKM</th>
                                    <th class="py-3">Alamat</th>
                                    <th class="py-3">Sektor Usaha</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataumkms as $umkm)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $umkm->nama_usaha }}</td>
                                        <td>{{ $umkm->alamat }}</td>
                                        <td>{{ $umkm->sektor_usaha }}</td>

                                        <td>
                                            <span
                                                class="badge rounded-pill text-white
                                                @if ($umkm->status == 'AKTIF') bg-success
                                                @elseif($umkm->status == 'DITOLAK') bg-danger
                                                @elseif($umkm->status == 'MENUNGGU VERIFIKASI') bg-info
                                                @elseif($umkm->status == 'TIDAK AKTIF') bg-warning
                                                @else bg-secondary @endif px-3 py-2">
                                                {{ $umkm->status ?? 'Tidak Dikategorikan' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('pelakukelolaumkm.show', $umkm->id) }}"
                                                    class="btn btn-sm btn-outline-primary mr-2" data-bs-toggle="tooltip"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if ($umkm->status != 'DITOLAK')
                                                    <a href="{{ route('pelakukelolaumkm.edit', $umkm->id) }}"
                                                        class="btn btn-sm btn-outline-warning mr-2" data-bs-toggle="tooltip"
                                                        title="Edit UMKM">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif

                                                @if ($umkm->status == 'DITOLAK')
                                                    <form action="{{ route('pelakukelolaumkm.destroy', $umkm->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="tooltip" title="Hapus UMKM"
                                                            onclick="return confirm('Anda yakin ingin menghapus UMKM ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-store-slash fa-4x text-muted mb-3"></i>
                                                <h5>Belum Ada UMKM</h5>
                                                <p class="text-muted mb-3">Anda belum menambahkan UMKM ke dalam sistem</p>
                                                <button class="btn btn-primary px-4" data-bs-toggle="modal"
                                                    data-bs-target="#tambahUMKMModal">
                                                    <i class="fas fa-plus me-2"></i> Tambah UMKM Pertama Anda
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Grid View (initially hidden) -->

            </div>
        </div>

        <!-- Floating Action Button -->
        <button class="btn btn-primary btn-tambah-umkm shadow" data-bs-toggle="modal" data-bs-target="#tambahUMKMModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>


    <style>
        /* Utility classes */
        .avatar-lg {
            width: 64px;
            height: 64px;
            font-size: 1.75rem;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .hover-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        /* Floating action button */
        .btn-tambah-umkm {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            z-index: 1040;
        }

        /* Make main content responsive with sidebar */
        .main-content {
            margin-left: 220px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }


        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
            }
        }

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
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Toggle between table and grid view
            const tableViewBtn = document.getElementById('tableViewBtn');
            const gridViewBtn = document.getElementById('gridViewBtn');
            const tableView = document.getElementById('tableView');
            const gridView = document.getElementById('gridView');

            tableViewBtn.addEventListener('click', function() {
                tableView.style.display = 'block';
                gridView.style.display = 'none';
                tableViewBtn.classList.add('btn-primary');
                tableViewBtn.classList.remove('btn-outline-primary');
                gridViewBtn.classList.add('btn-outline-primary');
                gridViewBtn.classList.remove('btn-primary');
            });

            gridViewBtn.addEventListener('click', function() {
                tableView.style.display = 'none';
                gridView.style.display = 'block';
                gridViewBtn.classList.add('btn-primary');
                gridViewBtn.classList.remove('btn-outline-primary');
                tableViewBtn.classList.add('btn-outline-primary');
                tableViewBtn.classList.remove('btn-primary');
            });

            // Search functionality
            const searchInput = document.getElementById('searchUMKM');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const tableRows = document.querySelectorAll('#tableView tbody tr');
                    const gridCards = document.querySelectorAll('#gridView .col-xl-3');

                    // Filter table rows
                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Filter grid cards
                    gridCards.forEach(card => {
                        const text = card.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }

            // Format currency input
            const omzetInput = document.getElementById('omzetusaha');
            if (omzetInput) {
                omzetInput.addEventListener('input', function(e) {
                    // Remove non-numeric characters
                    let value = this.value.replace(/\D/g, '');

                    // Format with thousands separator
                    if (value) {
                        value = parseInt(value).toLocaleString('id-ID');
                    }

                    this.value = value;
                });
            }

            // Form submission handler
            const submitBtn = document.getElementById('submitUMKM');
            const umkmForm = document.getElementById('tambahUMKMForm');

            if (submitBtn && umkmForm) {
                submitBtn.addEventListener('click', function() {
                    if (umkmForm.checkValidity()) {
                        // Handle form submission (can be replaced with actual AJAX call)
                        alert('UMKM berhasil ditambahkan!');
                        $('#tambahUMKMModal').modal('hide');
                    } else {
                        umkmForm.reportValidity();
                    }
                });
            }
        });
    </script>
@endsection
