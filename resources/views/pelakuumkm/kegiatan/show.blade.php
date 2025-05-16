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
                            <span>Detail Kegiatan</span>

                        </h4>
                        <p class="mb-0 fs-6">Pantau dan kelola kegiatan untuk UMKM Anda</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4 py-4">
            <div class="row">
                <!-- Poster Column -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold">Poster Kegiatan</h6>
                        </div>
                        <div class="card-body text-center">
                            @if ($kegiatan->poster)
                                <img src="{{ Storage::url($kegiatan->poster) }}" alt="Poster Kegiatan"
                                    class="detail-poster img-fluid mb-3">
                            @else
                                <div class="alert alert-secondary">
                                    Tidak ada poster tersedia
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Detail Column -->
                <div class="col-md-8 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold">Informasi Detail Kegiatan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <span class="detail-label">Nama Kegiatan</span>
                                    <p>{{ $kegiatan->nama_kegiatan }}</p>
                                </div>
                                <div class="col-md-6">
                                    <span class="detail-label">Jenis Kegiatan</span>
                                    <p>{{ $kegiatan->jenis_kegiatan ?? 'Tidak Ditentukan' }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <span class="detail-label">Tanggal Mulai</span>
                                    <p>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d F Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <span class="detail-label">Tanggal Selesai</span>
                                    <p>{{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d F Y') }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <span class="detail-label">Jam Mulai</span>
                                    <p>{{ \Carbon\Carbon::parse($kegiatan->jam_mulai)->format('H:i') }} WIB</p>
                                </div>
                                <div class="col-md-6">
                                    <span class="detail-label">Jam Selesai</span>
                                    <p>{{ \Carbon\Carbon::parse($kegiatan->jam_selesai)->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <span class="detail-label">Status Kegiatan</span>
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
                                    <p>
                                        <span class="badge badge-{{ $badgeClass }} status-badge">
                                            {{ $kegiatan->status_kegiatan ?? 'Tidak Diketahui' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        const jenisText = card.querySelector('.text-muted').textContent
                        .toLowerCase();
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
                        const statusText = row.querySelector('span.badge:last-child').textContent
                            .toLowerCase();
                        if (selectedStatus === '' || statusText.includes(selectedStatus)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Filter grid cards
                    gridCards.forEach(card => {
                        const statusText = card.querySelector('span.badge').textContent
                        .toLowerCase();
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
