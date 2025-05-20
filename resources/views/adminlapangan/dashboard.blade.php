@extends('layouts.app')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data untuk charts
        const intervensiMonthlyData = @json($intervensiMonthlyData);
        const jenisKegiatanData = @json($distribusiJenisKegiatan);
        const kegiatanColors = @json($kegiatanColors);
        const intervensiOmsetData = @json($intervensiOmsetData);

        // Chart Intervensi per Bulan
        const intervensiBulananCtx = document.getElementById('intervensiMonthlyChart').getContext('2d');
        new Chart(intervensiBulananCtx, {
            type: 'line',
            data: {
                labels: intervensiMonthlyData.labels,
                datasets: [{
                    label: 'Jumlah Intervensi',
                    data: intervensiMonthlyData.values,
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
                            precision: 0
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
                        caretPadding: 10
                    }
                }
            }
        });

        // Pie Chart Jenis Kegiatan
        const jenisKegiatanCtx = document.getElementById('jenisKegiatanChart').getContext('2d');
        new Chart(jenisKegiatanCtx, {
            type: 'pie',
            data: {
                labels: jenisKegiatanData.map(item => item.name),
                datasets: [{
                    data: jenisKegiatanData.map(item => item.value),
                    backgroundColor: kegiatanColors,
                    hoverBackgroundColor: kegiatanColors.map(color => color.replace('0.7', '0.9')),
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: "rgb(255, 255, 255)",
                        bodyColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        padding: 15,
                        displayColors: false
                    }
                },
                cutout: '60%'
            }
        });

        // Bar Chart Perbandingan Omset
        const intervensiOmsetCtx = document.getElementById('intervensiOmsetChart').getContext('2d');
        new Chart(intervensiOmsetCtx, {
            type: 'bar',
            data: {
                labels: intervensiOmsetData.labels,
                datasets: [{
                        label: 'Sebelum Intervensi',
                        data: intervensiOmsetData.before,
                        backgroundColor: 'rgba(78, 115, 223, 0.7)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Setelah Intervensi',
                        data: intervensiOmsetData.after,
                        backgroundColor: 'rgba(28, 200, 138, 0.7)',
                        borderColor: 'rgba(28, 200, 138, 1)',
                        borderWidth: 1
                    }
                ]
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
                        display: true,
                        position: 'top'
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
    </script>
@endpush

@section('content')
    @include('layouts.sidebar')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navbar')
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Dashboard Admin Lapangan</h1>
                </div>

                <!-- Statistik dan KPI Cards -->
                <div class="row">
                    <!-- UMKM yang Dimonitor -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            UMKM Dimonitor</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUmkmDimonitor }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kegiatan Terjadwal -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Kegiatan Terjadwal</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKegiatanTerjadwal }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progres Intervensi -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Progres Intervensi</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    {{ $progresIntervensi }}%</div>
                                            </div>
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        style="width: {{ $progresIntervensi }}%"
                                                        aria-valuenow="{{ $progresIntervensi }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumentasi Kegiatan -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Dokumentasi</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDokumentasi }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-camera fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Intervensi dan Kegiatan -->
                <div class="row">
                    <!-- Chart Intervensi per Bulan -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Data Intervensi per Bulan</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="intervensiMonthlyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Distribusi Jenis Kegiatan -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Distribusi Jenis Kegiatan</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="jenisKegiatanChart"></canvas>
                                </div>
                                <div class="mt-4 text-center small">
                                    @foreach ($distribusiJenisKegiatan as $index => $item)
                                        <span class="mr-2">
                                            <i class="fas fa-circle"
                                                style="color: {{ $kegiatanColors[$index % count($kegiatanColors)] }}"></i>
                                            {{ $item['name'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kegiatan dan UMKM -->
                <div class="row">
                    <!-- Jadwal Kegiatan -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Jadwal Kegiatan</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Kegiatan</th>
                                                <th>Lokasi</th>
                                                <th>Tanggal</th>
                                                <th>Peserta</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jadwalKegiatan as $kegiatan)
                                                <tr>
                                                    <td>{{ $kegiatan->nama_kegiatan }}</td>
                                                    <td>{{ $kegiatan->lokasi_kegiatan }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}
                                                    </td>
                                                    <td>{{ $kegiatan->intervensi->count() }} /
                                                        {{ $kegiatan->kuota_pendaftaran }}</td>
                                                    <td>
                                                        <a href="{{ route('datakegiatan.show', $kegiatan->id) }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar UMKM yang Dimonitor -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">UMKM Dimonitor</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Nama Usaha</th>
                                                <th>Pemilik</th>
                                                <th>Sektor</th>
                                                <th>Terakhir Update</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($umkmDimonitor as $umkm)
                                                <tr>
                                                    <td>{{ $umkm->nama_usaha }}</td>
                                                    <td>{{ $umkm->pelakuUmkm->nama_lengkap }}</td>
                                                    <td>{{ $umkm->sektor_usaha }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($umkm->updated_at)->diffForHumans() }}
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

                <!-- Perbandingan Omset dan Aktivitas -->
                <div class="row">


                    <!-- Aktivitas Terakhir -->
                    {{-- <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terakhir</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    @foreach ($aktivitasTerakhir as $aktivitas)
                                        <a href="{{ route('adminlapangan.aktivitas.detail', $aktivitas->id) }}"
                                            class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">{{ $aktivitas->judul }}</h5>
                                                <small>{{ \Carbon\Carbon::parse($aktivitas->created_at)->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">{{ Str::limit($aktivitas->deskripsi, 100) }}</p>
                                            <small>Oleh: {{ $aktivitas->user->name }}</small>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <footer class="sticky-footer" style="background-color: #e0e0e0">
            <div class="container  my-auto">
                <div class="copyright text-center my-auto">
                    <span class="text-black">© {{ date('Y') }} UMKM Management System Dinas Koperasi Usaha Kecil dan Menangah dan Perdagangan Kota Surabaya </span> <br>
                </div>
            </div>
        </footer>   
    </div>
@endsection
