@extends('layouts.pelakuumkm.app')
@section('content')
    @include('layouts.pelakuumkm.sidebar')
    <main class="main-content">
        <!-- Header Section -->
        <div class="text-white py-3 px-4 shadow-sm" id="nav" style="background-color: #5281ab">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        <span>Dashboard UMKM</span>
                    </h4>
                    <p class="mb-0 fs-6">Pantau perkembangan usaha dan informasi terbaru</p>
                </div>

            </div>
        </div>

        <div class="container-fluid px-4 py-4">
            <!-- Ringkasan Kartu Statistik -->
            <div class="row g-3">
                <!-- Omset Bulanan -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-primary text-uppercase fw-bold small mb-1">Omset (Bulanan)</h6>
                                    <h4 class="fw-bold mb-0">Rp {{ number_format($omsetBulanan, 0, ',', '.') }}</h4>
                                </div>
                                <div class="avatar rounded-circle text-center text-white p-3"
                                    style="background-color: rgba(78, 115, 223, 0.1); width: 48px; height: 48px; line-height: 24px;">
                                    <i class="fas fa-calendar fa-lg text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Omset Tahunan -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-success text-uppercase fw-bold small mb-1">Omset (Tahunan)</h6>
                                    <h4 class="fw-bold mb-0">Rp {{ number_format($omsetTahunan, 0, ',', '.') }}</h4>
                                </div>
                                <div class="avatar rounded-circle text-center text-white p-3"
                                    style="background-color: rgba(40, 167, 69, 0.1); width: 48px; height: 48px; line-height: 24px;">
                                    <i class="fas fa-dollar-sign fa-lg text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Kegiatan Diikuti -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-info text-uppercase fw-bold small mb-1">Kegiatan Diikuti</h6>
                                    <h4 class="fw-bold mb-0">{{ $kegiatanDiikuti }}</h4>
                                </div>
                                <div class="avatar rounded-circle text-center text-white p-3"
                                    style="background-color: rgba(23, 162, 184, 0.1); width: 48px; height: 48px; line-height: 24px;">
                                    <i class="fas fa-clipboard-list fa-lg text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Legalitas -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-warning text-uppercase fw-bold small mb-1">Status Legalitas</h6>
                                    <h4 class="fw-bold mb-0">{{ $jumlahLegalitas }}/8</h4>
                                </div>
                                <div class="avatar rounded-circle text-center text-white p-3"
                                    style="background-color: rgba(255, 193, 7, 0.1); width: 48px; height: 48px; line-height: 24px;">
                                    <i class="fas fa-certificate fa-lg text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Status -->
            <div class="row g-3 mt-3">
                <!-- Grafik Omset -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-chart-line me-2"></i>Perkembangan Omset
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart-area" style="height: 300px">
                                <canvas id="omsetChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Legalitas -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-certificate me-2"></i>Status Legalitas
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="progress-list">
                                @php
                                    $legalitasItems = [
                                        'no_sk_nib' => 'NIB',
                                        'no_sk_siup' => 'SIUP',
                                        'no_sk_tdp' => 'TDP',
                                        'no_sk_pirt' => 'PIRT',
                                        'no_sk_bpom' => 'BPOM',
                                        'no_sk_halal' => 'Halal',
                                        'no_sk_merek' => 'Merek',
                                        'no_sk_haki' => 'HAKI'
                                    ];
                                @endphp

                                @foreach($legalitasItems as $key => $label)
                                    @php
                                        $status = isset($legalitas->$key) && !empty($legalitas->$key);
                                    @endphp
                                    <h6 class="small fw-semibold d-flex justify-content-between mb-2">
                                        {{ $label }}
                                        <span class="{{ $status ? 'text-success' : 'text-danger' }}">
                                            {{ $status ? 'Lengkap' : 'Belum Lengkap' }}
                                        </span>
                                    </h6>
                                    <div class="progress mb-3" style="height: 6px;">
                                        <div class="progress-bar {{ $status ? 'bg-success' : 'bg-danger' }}" role="progressbar"
                                            style="width: {{ $status ? '100%' : '0%' }}"
                                            aria-valuenow="{{ $status ? '100' : '0' }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kegiatan Mendatang dan UMKM -->
            <div class="row g-3 mt-3">
                <!-- Kegiatan Mendatang -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-calendar-alt me-2"></i>Kegiatan Mendatang
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @if(count($kegiatanMendatang) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="px-3 py-3">Nama Kegiatan</th>
                                                <th class="px-3 py-3">Lokasi</th>
                                                <th class="px-3 py-3">Tanggal</th>
                                                <th class="px-3 py-3 text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kegiatanMendatang as $kegiatan)
                                            <tr>
                                                <td class="px-3">{{ $kegiatan->nama_kegiatan }}</td>
                                                <td>{{ $kegiatan->lokasi_kegiatan }}</td>
                                                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('pelakukegiatan.show', $kegiatan->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Lihat Detail">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                    <h5>Tidak ada kegiatan mendatang</h5>
                                    <p class="text-muted">Belum ada kegiatan terjadwal untuk saat ini</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Data UMKM -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-store me-2"></i>Data UMKM Saya
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @if(count($dataUmkm) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="px-3 py-3">Nama Usaha</th>
                                                <th class="px-3 py-3">Jenis Produk</th>
                                                <th class="px-3 py-3">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dataUmkm as $umkm)
                                            <tr>
                                                <td class="px-3">{{ $umkm->nama_usaha }}</td>
                                                <td>{{ $umkm->jenis_produk }}</td>
                                                <td>
                                                    <span class="badge rounded-pill text-white
                                                        {{ $umkm->status == 'aktif' ? 'bg-success' : 'bg-warning' }} px-3 py-2">
                                                        {{ ucfirst($umkm->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-store-slash fa-4x text-muted mb-3"></i>
                                    <h5>Belum ada data UMKM</h5>
                                    <p class="text-muted mb-3">Anda belum menambahkan UMKM ke dalam sistem</p>
                                    <a href="{{ route('pelakukelolaumkm.create') }}" class="btn btn-primary px-4">
                                        <i class="fas fa-plus me-2"></i> Tambah UMKM Pertama Anda
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

       
    </main>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Data omset untuk chart
            const omsetData = @json($chartOmset);

            // Setup chart untuk omset
            const ctx = document.getElementById('omsetChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: omsetData.labels,
                    datasets: [{
                        label: 'Omset Bulanan (Rp)',
                        data: omsetData.values,
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        },
                        y: {
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                callback: function(value) {
                                    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                            },
                            grid: {
                                color: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: "rgb(255, 255, 255)",
                            bodyColor: "#858796",
                            titleMarginBottom: 10,
                            titleColor: '#6e707e',
                            titleFontSize: 14,
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            padding: 15,
                            displayColors: false,
                            intersect: false,
                            mode: 'index',
                            caretPadding: 10,
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y;
                                    return label + ': Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush

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

        /* Chart container */
        .chart-area {
            position: relative;
            height: 100%;
            width: 100%;
        }
    </style>
@endsection