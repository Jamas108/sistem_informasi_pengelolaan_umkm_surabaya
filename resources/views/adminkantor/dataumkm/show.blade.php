@extends('layouts.app')

@push('scripts')
<script type="text/javascript" src="{{ URL::asset ('js/umkm-show.js') }}"></script>
@endpush

@section('content')
    @include('layouts.sidebar')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navbar')
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Detail Data UMKM</h1>
                    <a href="{{ route('dataumkm.edit', $pelakuUmkm->id) }}"
                        class="d-none d-sm-inline-block btn btn-primary shadow-sm">
                        <i class="fas fa-edit fa-sm text-white-50"></i> Edit Data
                    </a>
                </div>

                <div class="container-fluid pt-2 px-2">
                    <div class="bg-white justify-content-between rounded shadow p-4">
                        <!-- Alert Container -->
                        @if (session('status'))
                            <div class="alert alert-{{ session('status_type') }} alert-dismissible fade show"
                                role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pelaku-tab" data-toggle="tab" href="#pelaku" role="tab"
                                    aria-controls="pelaku" aria-selected="true">Data Pelaku UMKM</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="umkm-tab" data-toggle="tab" href="#umkm" role="tab"
                                    aria-controls="umkm" aria-selected="false">Data UMKM</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="legalitas-tab" data-toggle="tab" href="#legalitas" role="tab"
                                    aria-controls="legalitas" aria-selected="false">Legalitas</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="intervensi-tab" data-toggle="tab" href="#intervensi" role="tab"
                                    aria-controls="intervensi" aria-selected="false">Intervensi</a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab 1: Data Pelaku UMKM -->
                            <div class="tab-pane fade show active" id="pelaku" role="tabpanel"
                                aria-labelledby="pelaku-tab">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-gradient-primary text-white py-3">
                                        <h5 class="m-0 font-weight-bold">Data Pelaku UMKM</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">NIK Pemilik</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="{{ $pelakuUmkm->nik }}"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Nama Pemilik</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"
                                                    value="{{ $pelakuUmkm->nama_lengkap }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">No KK Pemilik</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="{{ $pelakuUmkm->no_kk }}"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Tempat Lahir</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"
                                                    value="{{ $pelakuUmkm->tempat_lahir }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Tanggal Lahir</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"
                                                    value="{{ $pelakuUmkm->tgl_lahir }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Jenis Kelamin</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"
                                                    value="{{ $pelakuUmkm->jenis_kelamin }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Status Hub.
                                                Keluarga</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"
                                                    value="{{ $pelakuUmkm->status_hubungan_keluarga }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Status
                                                Perkawinan</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"
                                                    value="{{ $pelakuUmkm->status_perkawinan }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Alamat Sesuai
                                                KTP</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"
                                                    value="{{ $pelakuUmkm->alamat_sesuai_ktp }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Kelurahan Sesuai
                                                KTP</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"
                                                    value="{{ $pelakuUmkm->kelurahan }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">RW</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="{{ $pelakuUmkm->rw }}"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">RT</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="{{ $pelakuUmkm->rt }}"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Telp</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"
                                                    value="{{ $pelakuUmkm->no_telp }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Pendidikan
                                                Terakhir</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"
                                                    value="{{ $pelakuUmkm->pendidikan_terakhir }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Status Keaktifan Pelaku
                                                UMKM</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"
                                                    value="{{ $pelakuUmkm->status_keaktifan }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Data UMKM -->
                            <div class="tab-pane fade" id="umkm" role="tabpanel" aria-labelledby="umkm-tab">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-gradient-primary text-white py-3">
                                        <h5 class="m-0 font-weight-bold">Data UMKM</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <!-- Container for UMKM entries -->
                                        <div id="umkm-entries-container">
                                            @foreach ($pelakuUmkm->dataUmkm as $index => $umkm)
                                            @if ($umkm->status !== 'DITOLAK')
                                                <div class="umkm-form-show border rounded p-4 mb-4 shadow-sm"
                                                    id="umkm-entry-show-{{ $index }}"
                                                    data-umkm-id="{{ $umkm->id }}">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                                        <h5 class="m-0 umkm-number font-weight-bold text-primary">
                                                            UMKM {{ $loop->iteration }}</h5>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-sm-4 col-form-label font-weight-bold">Nama
                                                                    Usaha</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $umkm->nama_usaha }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-sm-4 col-form-label font-weight-bold">Alamat
                                                                    Usaha</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $umkm->alamat }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-sm-4 col-form-label font-weight-bold">Pengelolaan
                                                                    Usaha</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $umkm->pengelolaan_usaha }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-sm-4 col-form-label font-weight-bold">Klasifikasi
                                                                    Kinerja</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $umkm->klasifikasi_kinerja_usaha }}"
                                                                        readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-sm-4 col-form-label font-weight-bold">Jumlah
                                                                    Tenaga Kerja</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $umkm->jumlah_tenaga_kerja }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-sm-4 col-form-label font-weight-bold">Sektor
                                                                    Usaha</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $umkm->sektor_usaha }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-sm-4 col-form-label font-weight-bold">Status
                                                                    Keaktifan</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $umkm->status }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Products Section -->
                                                    <div class="products-section mt-4 pt-3 border-top">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-3">
                                                            <h5 class="m-0 font-weight-bold text-primary">Produk UMKM</h5>
                                                        </div>

                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped"
                                                                id="products-table-{{ $umkm->id }}">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th class="text-center" width="5%">No</th>
                                                                        <th width="35%">Jenis Produk</th>
                                                                        <th width="35%">Tipe Produk</th>
                                                                        <th width="25%">Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse($umkm->produkUmkm as $index => $produk)
                                                                        <tr id="product-{{ $produk->id }}"
                                                                            data-product-id="{{ $produk->id }}">
                                                                            <td class="text-center">{{ $index + 1 }}
                                                                            </td>
                                                                            <td>{{ $produk->jenis_produk }}</td>
                                                                            <td>{{ $produk->tipe_produk }}</td>
                                                                            <td>
                                                                                @if ($produk->status == 'AKTIF')
                                                                                    <span
                                                                                        class="badge badge-success">AKTIF</span>
                                                                                @else
                                                                                    <span class="badge badge-danger">TIDAK
                                                                                        AKTIF</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="4" class="text-center">Belum
                                                                                ada produk untuk UMKM ini</td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Tab 3: Legalitas UMKM -->
                            <div class="tab-pane fade" id="legalitas" role="tabpanel" aria-labelledby="legalitas-tab">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-gradient-primary text-white py-3">
                                        <h5 class="m-0 font-weight-bold">Data Legalitas UMKM</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="table-legalitas" width="100%"
                                                cellspacing="0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="text-center" width="5%">NO</th>
                                                        <th>UMKM</th>
                                                        <th>No NIB</th>
                                                        <th>No SIUP</th>
                                                        <th>No TDP</th>
                                                        <th>No PIRT</th>
                                                        <th>No BPOM</th>
                                                        <th>No HALAL</th>
                                                        <th>No MERK</th>
                                                        <th>No HAKI</th>
                                                        <th>No SK</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (isset($legalitasData) && count($legalitasData) > 0)
                                                        @foreach ($legalitasData as $index => $item)
                                                            <tr>
                                                                <td class="text-center">{{ $index + 1 }}</td>
                                                                <td>{{ $item->dataUmkm->nama_usaha ?? 'Tidak ada' }}</td>
                                                                <td>{{ $item->no_sk_nib ?: '-' }}</td>
                                                                <td>{{ $item->no_sk_siup ?: '-' }}</td>
                                                                <td>{{ $item->no_sk_tdp ?: '-' }}</td>
                                                                <td>{{ $item->no_sk_pirt ?: '-' }}</td>
                                                                <td>{{ $item->no_sk_bpom ?: '-' }}</td>
                                                                <td>{{ $item->no_sk_halal ?: '-' }}</td>
                                                                <td>{{ $item->no_sk_merk ?: '-' }}</td>
                                                                <td>{{ $item->no_sk_haki ?: '-' }}</td>
                                                                <td>-</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="11" class="text-center">Belum ada data
                                                                legalitas</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 4: Intervensi UMKM -->
                            <div class="tab-pane fade" id="intervensi" role="tabpanel" aria-labelledby="intervensi-tab">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-gradient-primary text-white py-3">
                                        <h5 class="m-0 font-weight-bold">Data Intervensi UMKM</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <!-- Table of existing interventions -->
                                        <div class="mt-3">
                                            <div class="card border-left-primary shadow">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" id="table-intervensi"
                                                            width="100%" cellspacing="0">
                                                            <thead class="bg-light">
                                                                <tr>
                                                                    <th class="text-center" width="5%">NO</th>
                                                                    <th width="15%">UMKM</th>
                                                                    <th width="15%">No. Pendaftaran</th>
                                                                    <th width="15%">Nama Kegiatan</th>
                                                                    <th width="10%">Status Kegiatan</th>
                                                                    <th width="10%">Tanggal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if (isset($intervensiData) && count($intervensiData) > 0)
                                                                    @foreach ($intervensiData as $index => $intervensi)
                                                                        <tr>
                                                                            <td class="text-center">{{ $index + 1 }}
                                                                            </td>
                                                                            <td>{{ $intervensi->dataUmkm->nama_usaha ?? '-' }}
                                                                            </td>
                                                                            <td>{{ $intervensi->no_pendaftaran_kegiatan }}
                                                                            </td>
                                                                            <td>{{ $intervensi->kegiatan->nama_kegiatan ?? '-' }}
                                                                            </td>
                                                                            <td>{{ $intervensi->kegiatan->status_kegiatan ?? '-' }}
                                                                            </td>
                                                                            <td>{{ $intervensi->kegiatan ? date('d/m/Y', strtotime($intervensi->kegiatan->tanggal_mulai)) : '-' }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="6" class="text-center">Belum ada
                                                                            data intervensi</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white mt-4">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2021</span>
                </div>
            </div>
        </footer>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize tab functionality
            $('#myTab a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Get the tab from URL if present
            let url = document.location.toString();
            if (url.match('#')) {
                let activeTab = url.split('#')[1];
                $('#myTab a[href="#' + activeTab + '"]').tab('show');
            }

            // Change URL when tab changes
            $('#myTab a').on('shown.bs.tab', function(e) {
                if (history.pushState) {
                    history.pushState(null, null, e.target.hash);
                } else {
                    window.location.hash = e.target.hash;
                }
            });
        });
    </script>
@endsection
