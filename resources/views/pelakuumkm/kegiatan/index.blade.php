@extends('layouts.pelakuumkm.app')
@section('content')
    @include('layouts.pelakuumkm.sidebar')
    <main class="main-content">
        <!-- Header Section -->
        <div class="text-white py-3 px-4 shadow-sm" id="nav" style="background-color: #5281ab">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="fw-bold mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <span>Daftar Kegiatan</span>

                        </h4>
                        <p class="mb-0 fs-6">Pantau dan kelola kegiatan untuk UMKM Anda</p>
                    </div>
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
                                <input type="text" class="form-control border-start-0" id="searchKegiatan"
                                    placeholder="Cari Kegiatan...">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <select class="form-control" id="filterJenisKegiatan">
                                <option value="">Semua Jenis Kegiatan</option>
                                @php
                                    $jenisKegiatan = $kegiatans->pluck('jenis_kegiatan')->unique();
                                @endphp
                                @foreach ($jenisKegiatan as $jenis)
                                    <option value="{{ $jenis }}">{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <select class="form-control" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="Belum Dimulai">Belum Dimulai</option>
                                <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                                <option value="Selesai">Selesai</option>
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

            <!-- Kegiatan List Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-list me-2"></i>Daftar Kegiatan
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
                                    <th class="px-3 py-3">Nama Kegiatan</th>
                                    <th class="py-3">Jenis Kegiatan</th>
                                    <th class="py-3">Tanggal Mulai</th>
                                    <th class="py-3">Tanggal Selesai</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Kuota</th>
                                    <th class="py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kegiatans as $kegiatan)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="px-3 py-3">{{$kegiatan->nama_kegiatan}}
                                        </td>
                                        <td>{{ $kegiatan->jenis_kegiatan ?? 'Tidak Dikategorikan' }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge text-white
                                                @if($kegiatan->status_kegiatan == 'Belum Dimulai') bg-danger
                                                @elseif($kegiatan->status_kegiatan == 'Pendaftaran') bg-info
                                                @elseif($kegiatan->status_kegiatan == 'Sedang Berlangsung') bg-primary
                                                @elseif($kegiatan->status_kegiatan == 'Selesai') bg-success
                                                @else bg-warning @endif">
                                                {{ $kegiatan->status_kegiatan }}
                                            </span>
                                        </td>
                                        <td>{{$kegiatan->kuota_pendaftaran}}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href=""
                                                    class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                                    title="Detail Kegiatan">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                                <h5>Belum Ada Kegiatan</h5>
                                                <p class="text-muted mb-3">Anda belum menambahkan kegiatan untuk UMKM Anda</p>
                                                <a href="{{ route('pelakukegiatan.create') }}"
                                                    class="btn btn-primary px-4">
                                                    <i class="fas fa-plus me-2"></i> Tambah Kegiatan Pertama Anda
                                                </a>
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
                        @forelse($kegiatans as $kegiatan)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="card h-100 border-0 shadow-sm hover-card">
                                    <div class="card-body p-3">
                                        @if($kegiatan->poster)
                                        <div class="text-center"><img src="{{ Storage::url($kegiatan->poster) }}"
                                            alt="Poster" width="100" height="100"></div>
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center mb-3"
                                                style="height: 200px; background-color: {{ $kegiatan->id % 2 == 0 ? '#1c4970' : '#2F77B6' }}; color: white;">
                                                <span class="display-4">
                                                    {{ strtoupper(substr($kegiatan->nama_kegiatan, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif

                                        <div class="d-flex align-items-center mb-3">
                                            <div class="me-3">
                                                <h6 class="mb-0 fw-bold text-black">{{ $kegiatan->nama_kegiatan }}</h6>
                                                <small class="text-muted">
                                                    {{ $kegiatan->jenis_kegiatan ?? 'Tidak Dikategorikan' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">
                                                <i class="fas fa-calendar-alt me-2"></i>
                                                {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}
                                                -
                                                {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d M Y') }}
                                            </small>
                                            <small class="text-muted d-block mb-1">
                                                <i class="fas fa-clock me-2"></i>
                                                {{ $kegiatan->jam_mulai }} - {{ $kegiatan->jam_selesai }}
                                            </small>
                                            <small class="text-muted d-block">
                                                <span class="badge
                                                    @if($kegiatan->status_kegiatan == 'Belum Dimulai') bg-secondary
                                                    @elseif($kegiatan->status_kegiatan == 'Sedang Berlangsung') bg-primary
                                                    @elseif($kegiatan->status_kegiatan == 'Selesai') bg-success
                                                    @else bg-warning @endif">
                                                    {{ $kegiatan->status_kegiatan }}
                                                </span>
                                            </small>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <a href=""
                                                class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                                title="Detail Kegiatan">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                    <h5>Belum Ada Kegiatan</h5>
                                    <p class="text-muted mb-3">Anda belum menambahkan kegiatan untuk UMKM Anda</p>

                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if (count($kegiatans) > 0)
                    <div class="card-footer bg-white p-3">
                        <div class="row align-items-center">
                            <div class="col-md-6 small text-muted">
                                Menampilkan {{ count($kegiatans) }} dari {{ count($kegiatans) }} Kegiatan
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

    </main>

    <style>
        .btn-tambah-kegiatan {
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
            const searchInput = document.getElementById('searchKegiatan');
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

            // Filter by Jenis Kegiatan
            const filterJenisKegiatan = document.getElementById('filterJenisKegiatan');
            if (filterJenisKegiatan) {
                filterJenisKegiatan.addEventListener('change', function() {
                    const selectedJenis = this.value.toLowerCase();
                    const tableRows = document.querySelectorAll('#tableView tbody tr');
                    const gridCards = document.querySelectorAll('#gridView .col-xl-3');

                    // Filter table rows
                    tableRows.forEach(row => {
                        const jenisText = row.querySelector('span.badge').textContent.toLowerCase();
                        if (selectedJenis === '' || jenisText.includes(selectedJenis)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Filter grid cards
                    gridCards.forEach(card => {
                        const jenisText = card.querySelector('.text-muted').textContent.toLowerCase();
                        if (selectedJenis === '' || jenisText.includes(selectedJenis)) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }

            // Filter by Status
            const filterStatus = document.getElementById('filterStatus');
            if (filterStatus) {
                filterStatus.addEventListener('change', function() {
                    const selectedStatus = this.value.toLowerCase();
                    const tableRows = document.querySelectorAll('#tableView tbody tr');
                    const gridCards = document.querySelectorAll('#gridView .col-xl-3');

                    // Filter table rows
                    tableRows.forEach(row => {
                        const statusText = row.querySelector('span.badge:last-child').textContent.toLowerCase();
                        if (selectedStatus === '' || statusText.includes(selectedStatus)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Filter grid cards
                    gridCards.forEach(card => {
                        const statusText = card.querySelector('span.badge').textContent.toLowerCase();
                        if (selectedStatus === '' || statusText.includes(selectedStatus)) {
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
