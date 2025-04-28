@extends('layouts.pelakuumkm.app')
@section('content')
    @include('layouts.pelakuumkm.sidebar')
    <main class="main-content">
        <!-- Header Section -->
        <div class="text-white py-3 px-4 shadow-sm" id="nav" style="background-color: #5281ab">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-store me-2"></i>
                        <span>Kelola UMKM</span>
                    </h4>
                    <p class="mb-0 fs-6">Atur dan kembangkan usaha Anda untuk pertumbuhan bisnis yang optimal</p>
                </div>
                <div>
                    <button class="btn btn-light rounded-pill px-4 shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#tambahUMKMModal">
                        <i class="fas fa-plus-circle me-2"></i> Tambah UMKM Baru
                    </button>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4 py-4">
            <!-- Stats Cards -->


            <!-- Search & Filter Section -->
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
                            <select class="form-control" id="filterSector">
                                <option value="">Semua Sektor</option>
                                <option value="makanan">Makanan & Minuman</option>
                                <option value="fashion">Fashion</option>
                                <option value="kerajinan">Kerajinan</option>
                                <option value="jasa">Jasa</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <select class="form-control" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Menunggu">Menunggu Verifikasi</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <button class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- UMKM List Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-list me-2"></i>Daftar UMKM
                    </h5>
                    <div class="btn-group">
                        <button id="tableViewBtn" class="btn btn-sm btn-primary">
                            <i class="fas fa-table me-1"></i> Tabel
                        </button>
                        <button id="gridViewBtn" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-th-large me-1"></i> Grid
                        </button>
                    </div>
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
                                        <td>
                                            <span
                                                class="badge rounded-pill text-white 
                                                @if ($umkm->sektor_usaha == 'Makanan & Minuman') bg-danger
                                                @elseif($umkm->sektor_usaha == 'Fashion') bg-info
                                                @elseif($umkm->sektor_usaha == 'Kerajinan') bg-warning
                                                @elseif($umkm->sektor_usaha == 'Jasa') bg-primary
                                                @else bg-secondary @endif px-3 py-2">
                                                {{ $umkm->sektor_usaha ?? 'Tidak Dikategorikan' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="status-dot {{ $umkm->status == 'AKTIF' ? 'bg-success' : 'bg-warning' }} me-2">
                                                </div>
                                                <span
                                                    class="{{ $umkm->status == 'AKTIF' ? 'text-success' : 'text-warning' }} fw-medium ml-2">
                                                    {{ $umkm->status }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="#" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="tooltip" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pelakukelolaumkm.edit', $umkm->pelakuUmkm->id) }}"
                                                    class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip"
                                                    title="Edit UMKM">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="tooltip" title="Hapus UMKM"
                                                        onclick="return confirm('Anda yakin ingin menghapus UMKM ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
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
                <div id="gridView" class="card-body p-3" style="display: none;">
                    <div class="row g-3">
                        @forelse($dataumkms as $umkm)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="card h-100 border-0 shadow-sm hover-card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar rounded-circle text-center text-white me-3"
                                                style="background-color: {{ $umkm->id % 2 == 0 ? '#1c4970' : '#2F77B6' }}; width: 48px; height: 48px; line-height: 48px;">
                                                {{ strtoupper(substr($umkm->nama_usaha, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold ml-2">{{ $umkm->nama_usaha }}</h6>
                                                <div class="d-flex align-items-center mt-1 ml-2">
                                                    <div
                                                        class="status-dot {{ $umkm->status == 'AKTIF' ? 'bg-success' : 'bg-warning' }} me-2">
                                                    </div>
                                                    <small
                                                        class="{{ $umkm->status == 'AKTIF' ? 'text-success' : 'text-warning' }} ">{{ $umkm->status }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1"><i
                                                    class="fas fa-map-marker-alt me-2"></i>{{ $umkm->alamat }}</small>
                                            <small class="text-muted d-block"><i
                                                    class="fas fa-tag me-2"></i>{{ $umkm->sektor_usaha ?? 'Tidak Dikategorikan' }}</small>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <a href="#" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="tooltip" title="Lihat Detail">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                            <div class="btn-group">
                                                <a href="{{ route('pelakukelolaumkm.edit', $umkm->pelakuUmkm->id) }}"
                                                    class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip"
                                                    title="Edit UMKM">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="tooltip" title="Hapus UMKM"
                                                        onclick="return confirm('Anda yakin ingin menghapus UMKM ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-store-slash fa-4x text-muted mb-3"></i>
                                    <h5>Belum Ada UMKM</h5>
                                    <p class="text-muted mb-3">Anda belum menambahkan UMKM ke dalam sistem</p>
                                    <button class="btn btn-primary px-4" data-bs-toggle="modal"
                                        data-bs-target="#tambahUMKMModal">
                                        <i class="fas fa-plus me-2"></i> Tambah UMKM Pertama Anda
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if (count($dataumkms) > 0)
                    <div class="card-footer bg-white p-3">
                        <div class="row align-items-center">
                            <div class="col-md-6 small text-muted">
                                Menampilkan {{ count($dataumkms) }} dari {{ count($dataumkms) }} UMKM
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="Page navigation" class="float-md-end">
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" aria-label="Previous">
                                                <span aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
                                            </a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" aria-label="Next">
                                                <span aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Floating Action Button -->
        <button class="btn btn-primary btn-tambah-umkm shadow" data-bs-toggle="modal" data-bs-target="#tambahUMKMModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>

    <!-- Modal Tambah UMKM -->
    <div class="modal fade" id="tambahUMKMModal" tabindex="-1" aria-labelledby="tambahUMKMModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tambahUMKMModalLabel">
                        <i class="fas fa-plus-circle me-2"></i> Tambah UMKM Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="tambahUMKMForm">
                        <div class="mb-3">
                            <label for="namausaha" class="form-label fw-medium">Nama Usaha <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-store"></i></span>
                                <input type="text" class="form-control border-start-0" id="namausaha"
                                    placeholder="Masukkan Nama UMKM" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="kategoriusaha" class="form-label fw-medium">Kategori Usaha <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-tag"></i></span>
                                <select class="form-select border-start-0" id="kategoriusaha" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="makanan">Makanan & Minuman</option>
                                    <option value="fashion">Fashion</option>
                                    <option value="kerajinan">Kerajinan</option>
                                    <option value="jasa">Jasa</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamatusaha" class="form-label fw-medium">Alamat Usaha <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fas fa-map-marker-alt"></i></span>
                                <input type="text" class="form-control border-start-0" id="alamatusaha"
                                    placeholder="Masukkan Alamat Lengkap" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripasiusaha" class="form-label fw-medium">Deskripsi Usaha</label>
                            <textarea class="form-control" id="deskripasiusaha" rows="3" placeholder="Jelaskan tentang usaha Anda"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="omzetusaha" class="form-label fw-medium">Omzet Perbulan (Rp) <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-coins"></i></span>
                                <input type="text" class="form-control border-start-0" id="omzetusaha"
                                    placeholder="Masukkan omzet rata-rata per bulan" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="submitUMKM">
                        <i class="fas fa-save me-1"></i> Simpan UMKM
                    </button>
                </div>
            </div>
        </div>
    </div>

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
