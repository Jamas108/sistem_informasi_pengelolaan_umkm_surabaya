@extends('layouts.app')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data charts
        const umkmGrowthData = @json($chartUmkmGrowth);
        const sektorUmkmData = @json($distribusiSektor);
        const tenagaKerjaData = @json($distribusiTenagaKerja);
        const sektorColors = @json($sektorColors);

        // Setup chart pertumbuhan UMKM
        const umkmCtx = document.getElementById('umkmGrowthChart').getContext('2d');
        new Chart(umkmCtx, {
            type: 'line',
            data: {
                labels: umkmGrowthData.labels,
                datasets: [{
                    label: 'Jumlah UMKM',
                    data: umkmGrowthData.values,
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
                            padding: 10
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

        // Setup pie chart distribusi sektor UMKM
        const sektorCtx = document.getElementById('sektorUmkmChart').getContext('2d');
        new Chart(sektorCtx, {
            type: 'pie',
            data: {
                labels: sektorUmkmData.map(item => item.name),
                datasets: [{
                    data: sektorUmkmData.map(item => item.value),
                    backgroundColor: sektorColors,
                    hoverBackgroundColor: sektorColors.map(color => color.replace('0.7', '0.9')),
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

        // Setup chart distribusi tenaga kerja
        const tenagaKerjaCtx = document.getElementById('tenagaKerjaChart').getContext('2d');
        new Chart(tenagaKerjaCtx, {
            type: 'bar',
            data: {
                labels: tenagaKerjaData.labels,
                datasets: [{
                    label: 'Jumlah UMKM',
                    data: tenagaKerjaData.values,
                    backgroundColor: 'rgba(28, 200, 138, 0.7)',
                    hoverBackgroundColor: 'rgba(28, 200, 138, 0.9)',
                    borderColor: '#1cc88a',
                    borderWidth: 1
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
                            maxTicksLimit: 6
                        }
                    },
                    y: {
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5,
                            padding: 10
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
                        displayColors: false
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
                    <h1 class="h3 mb-0 text-gray-800">Dashboard Admin Kantor</h1>
                    <a href=""
                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-download fa-sm text-white-50"></i> Ekspor Laporan
                    </a>
                </div>

                <!-- Statistik dan KPI Cards -->
                <div class="row">
                    <!-- Total UMKM -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total UMKM</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUmkm }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-store fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Kegiatan -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Kegiatan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKegiatan }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rata-rata Omset -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Rata-Rata Omset</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                            {{ number_format($rataRataOmset, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- UMKM dengan Legalitas Lengkap -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Legalitas Lengkap</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $umkmLegalitasLengkap }} UMKM
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-certificate fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik dan Statistik -->
                <div class="row">
                    <!-- Grafik Pertumbuhan UMKM -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Pertumbuhan Jumlah UMKM</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="umkmGrowthChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pie Chart - Distribusi UMKM berdasarkan Sektor -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Distribusi Sektor UMKM</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="sektorUmkmChart"></canvas>
                                </div>
                                <div class="mt-4 text-center small">
                                    @foreach ($distribusiSektor as $index => $sektor)
                                        <span class="mr-2">
                                            <i class="fas fa-circle"
                                                style="color: {{ $sektorColors[$index % count($sektorColors)] }}"></i>
                                            {{ $sektor['name'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UMKM dan Kegiatan Terbaru -->
                <div class="row">
                    <!-- UMKM Terbaru -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">UMKM Terbaru</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Nama Usaha</th>
                                                <th>Pemilik</th>
                                                <th>Jenis Produk</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($umkmTerbaru as $umkm)
                                                <tr>
                                                    <td>{{ $umkm->nama_usaha }}</td>
                                                    <td>{{ $umkm->pelakuUmkm->nama_lengkap }}</td>
                                                    <td>{{ $umkm->jenis_produk }}</td>
                                                    <td>
                                                        <span
                                                            class="badge badge-{{ $umkm->status == 'aktif' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($umkm->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('dataumkm.show', $umkm->pelakuUmkm->id) }}"
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

                    <!-- Kegiatan Mendatang -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Kegiatan Mendatang</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Nama Kegiatan</th>
                                                <th>Lokasi</th>
                                                <th>Tanggal</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kegiatanMendatang as $kegiatan)
                                                <tr>
                                                    <td>{{ $kegiatan->nama_kegiatan }}</td>
                                                    <td>{{ $kegiatan->lokasi_kegiatan }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-{{ $kegiatan->status_kegiatan == 'aktif' ? 'success' : 'secondary' }}">
                                                            {{ ucfirst($kegiatan->status_kegiatan) }}
                                                        </span>
                                                    </td>
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
                </div>

                <!-- Progres Legalitas dan Distribusi UMKM -->
                <div class="row">
                    <!-- Progres Legalitas -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Status Legalitas UMKM</h6>
                            </div>
                            <div class="card-body">
                                <h4 class="small font-weight-bold">NIB <span
                                        class="float-right">{{ $persentaseLegalitas['nib'] }}%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ $persentaseLegalitas['nib'] }}%"
                                        aria-valuenow="{{ $persentaseLegalitas['nib'] }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>

                                <h4 class="small font-weight-bold">SIUP <span
                                        class="float-right">{{ $persentaseLegalitas['siup'] }}%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $persentaseLegalitas['siup'] }}%"
                                        aria-valuenow="{{ $persentaseLegalitas['siup'] }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>

                                <h4 class="small font-weight-bold">PIRT <span
                                        class="float-right">{{ $persentaseLegalitas['pirt'] }}%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: {{ $persentaseLegalitas['pirt'] }}%"
                                        aria-valuenow="{{ $persentaseLegalitas['pirt'] }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>

                                <h4 class="small font-weight-bold">Sertifikat Halal <span
                                        class="float-right">{{ $persentaseLegalitas['halal'] }}%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ $persentaseLegalitas['halal'] }}%"
                                        aria-valuenow="{{ $persentaseLegalitas['halal'] }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>

                                <h4 class="small font-weight-bold">Sertifikat Merek <span
                                        class="float-right">{{ $persentaseLegalitas['merek'] }}%</span></h4>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                        style="width: {{ $persentaseLegalitas['merek'] }}%"
                                        aria-valuenow="{{ $persentaseLegalitas['merek'] }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Distribusi Jumlah Tenaga Kerja -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Distribusi Tenaga Kerja</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-bar">
                                    <canvas id="tenagaKerjaChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

