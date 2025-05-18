@extends('layouts.pelakuumkm.app')
@section('content')
    @include('layouts.pelakuumkm.sidebar')
    <main class="main-content">
        <!-- Header Section -->
        <div class="text-white py-3 px-4 shadow-sm" id="nav" style="background: linear-gradient(145deg, #1c4970, #2F77B6);">
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
            <form method="GET" action="{{ route('pelakukegiatan.index') }}">
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
                            <select class="form-control" name="jenis_kegiatan">
                                <option value="">Semua Jenis Kegiatan</option>
                                <option value="PEMASARAN" @if(request('jenis_kegiatan') == 'PEMASARAN') selected @endif>PEMASARAN</option>
                                <option value="PELATIHAN" @if(request('jenis_kegiatan') == 'PELATIHAN') selected @endif>PELATIHAN</option>
                                <option value="LEGALITAS/SERTIFIKASI" @if(request('jenis_kegiatan') == 'LEGALITAS/SERTIFIKASI') selected @endif>LEGALITAS/SERTIFIKASI</option>
                                <option value="KEMITRAAN" @if(request('jenis_kegiatan') == 'KEMITRAAN') selected @endif>KEMITRAAN</option>
                                <option value="PEMBINAAN" @if(request('jenis_kegiatan') == 'PEMBINAAN') selected @endif>PEMBINAAN</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <select class="form-control" name="status_kegiatan">
                                <option value="">Semua Status</option>
                                <option value="Belum Dimulai" @if(request('status_kegiatan') == 'Belum Dimulai') selected @endif>Belum Dimulai</option>
                                <option value="Pendaftaran" @if(request('status_kegiatan') == 'Pendaftaran') selected @endif>Pendaftaran</option>
                                <option value="Persiapan Acara" @if(request('status_kegiatan') == 'Persiapan Acara') selected @endif>Persiapan Acara</option>
                                <option value="Selesai" @if(request('status_kegiatan') == 'Selesai') selected @endif>Selesai</option>


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

            <!-- Kegiatan List Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-list me-2"></i>
                        <span>Daftar Kegiatan</span>
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
                                                 @elseif($kegiatan->status_kegiatan == 'Pendaftaran') bg-secondary
                                                @elseif($kegiatan->status_kegiatan == 'Sedang Berlangsung') bg-primary
                                                @elseif($kegiatan->status_kegiatan == 'Selesai') bg-success
                                                @else bg-warning @endif">
                                                {{ $kegiatan->status_kegiatan }}
                                            </span>
                                        </td>
                                        <td>{{$kegiatan->kuota_pendaftaran}}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('pelakukegiatan.show', $kegiatan->id) }}"
                                                    class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                                    title="Detail Kegiatan">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
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
