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
        <!-- Header Section -->
        <div class="text-white py-3 px-4 shadow-sm" id="nav" style="background: linear-gradient(145deg, #1c4970, #2F77B6);">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-store me-2"></i>
                        <span>Kelola Intervensi</span>
                    </h4>
                    <p class="mb-0 fs-6">Pantau dan kelola kegiatan intervensi untuk UMKM Anda</p>
                </div>
                <div>
                    <a class="btn btn-light rounded-pill px-4 shadow-sm" href="{{ route('pelakukelolaintervensi.create') }}">
                        <i class="fas fa-plus-circle me-2"></i> Pendaftaran Intervensi
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
            <!-- Search & Filter Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-4 col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i
                                        class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0" id="searchIntervensi"
                                    placeholder="Cari Intervensi...">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <select class="form-control" id="filterJenisIntervensi">
                                <option value="">Semua Jenis Intervensi</option>
                                <option value="PEMASARAN">PEMASARAN</option>
                                <option value="PELATIHAN">PELATIHAN</option>
                                <option value="LEGALITAS/SERTIFIKASI">LEGALITAS/SERTIFIKASI</option>
                                <option value="KEMITRAAN">KEMITRAAN</option>
                                <option value="PEMBINAAN">PEMBINAAN</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <select class="form-control" id="filterUMKM">
                                <option value="">Semua UMKM</option>
                                @php
                                    $umkms = $dataintervensi->pluck('dataUmkm.nama_usaha')->unique();
                                @endphp
                                @foreach ($umkms as $umkm)
                                    <option value="{{ $umkm }}">{{ $umkm }}</option>
                                @endforeach
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

            <!-- Intervensi List Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-list me-2"></i>
                        <span>Daftar Intervensi</span>
                    </h5>
                </div>

                <!-- Table View -->
                <div id="tableView" class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-3 py-3">No</th>
                                    <th class="px-3 py-3">Nama Kegiatan</th>
                                    <th class="py-3">UMKM</th>
                                    <th class="py-3">Jenis Intervensi</th>
                                    <th class="py-3">Tanggal</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataintervensi as $intervensi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="px-3 py-3">{{ $intervensi->kegiatan->nama_kegiatan }}</td>
                                        <td class="text-black">{{ $intervensi->dataUmkm->nama_usaha }}</td>
                                        <td>{{ $intervensi->kegiatan->jenis_kegiatan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($intervensi->kegiatan->tgl_intervensi)->format('d M Y') }}
                                        </td>
                                        <td>
                                            <span class="badge text-white
                                                @if($intervensi->kegiatan->status_kegiatan == 'Belum Dimulai') bg-danger
                                                @elseif($intervensi->kegiatan->status_kegiatan == 'Pendaftaran') bg-info
                                                 @elseif($intervensi->kegiatan->status_kegiatan == 'Pendaftaran') bg-secondary
                                                @elseif($intervensi->kegiatan->status_kegiatan == 'Sedang Berlangsung') bg-primary
                                                @elseif($intervensi->kegiatan->status_kegiatan == 'Selesai') bg-success
                                                @else bg-warning @endif">
                                                {{ $intervensi->kegiatan->status_kegiatan }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('pelakukelolaintervensi.show', $intervensi->id) }}"
                                                    class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                                    title="Edit Intervensi">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pelakukelolaintervensi.edit', $intervensi->id) }}"
                                                    class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip"
                                                    title="Edit Intervensi">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-briefcase-slash fa-4x text-muted mb-3"></i>
                                                <h5>Belum Ada Intervensi</h5>
                                                <p class="text-muted mb-3">Anda belum menambahkan intervensi untuk UMKM
                                                    Anda</p>
                                                <a href="{{ route('pelakukelolaintervensi.create') }}"
                                                    class="btn btn-primary px-4">
                                                    <i class="fas fa-plus me-2"></i> Tambah Intervensi Pertama Anda
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <a href="{{ route('pelakukelolaintervensi.create') }}" class="btn btn-primary btn-tambah-intervensi shadow">
            <i class="fas fa-plus"></i>
        </a>
    </main>

    <style>
        /* Inherit styles from UMKM page with minor modifications */
        .btn-tambah-intervensi {
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

        .main-content {
            margin-left: 220px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
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
            const searchInput = document.getElementById('searchIntervensi');
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
        });
    </script>
@endsection
