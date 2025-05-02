<div class="row">
    <!-- Stats Card 1 -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            @if(Auth::user()->role == 'pelakuumkm')
                                Omset (Bulanan)
                            @else
                                Total UMKM
                            @endif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @if(Auth::user()->role == 'pelakuumkm')
                                Rp {{ number_format($omsetBulanan ?? 4500000) }}
                            @else
                                {{ $totalUmkm ?? 158 }}
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        @if(Auth::user()->role == 'pelakuumkm')
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        @else
                            <i class="fas fa-store fa-2x text-gray-300"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card 2 -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            @if(Auth::user()->role == 'pelakuumkm')
                                Omset (Tahunan)
                            @else
                                UMKM Aktif
                            @endif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @if(Auth::user()->role == 'pelakuumkm')
                                Rp {{ number_format($omsetTahunan ?? 42500000) }}
                            @else
                                {{ $umkmAktif ?? 142 }}
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        @if(Auth::user()->role == 'pelakuumkm')
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        @else
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card 3 -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            @if(Auth::user()->role == 'pelakuumkm')
                                Kelengkapan Legalitas
                            @else
                                Progress Program
                            @endif
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    @if(Auth::user()->role == 'pelakuumkm')
                                        {{ $persenLegalitas ?? 60 }}%
                                    @else
                                        {{ $persenProgram ?? 75 }}%
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: {{ Auth::user()->role == 'pelakuumkm' ? ($persenLegalitas ?? 60) : ($persenProgram ?? 75) }}%"
                                        aria-valuenow="{{ Auth::user()->role == 'pelakuumkm' ? ($persenLegalitas ?? 60) : ($persenProgram ?? 75) }}"
                                        aria-valuemin="0"
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

    <!-- Stats Card 4 -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            @if(Auth::user()->role == 'pelakuumkm')
                                Kegiatan Mendatang
                            @else
                                Pendaftaran Menunggu
                            @endif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @if(Auth::user()->role == 'pelakuumkm')
                                {{ $kegiatanMendatang ?? 3 }}
                            @else
                                {{ $pendaftaranMenunggu ?? 18 }}
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>