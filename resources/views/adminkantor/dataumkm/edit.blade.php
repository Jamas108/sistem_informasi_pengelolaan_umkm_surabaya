@extends('layouts.app')
@push('scripts')
<script src="{{ asset('js/umkm-form.js') }}"></script>
<script>
    $(document).ready(function() {
        // This initializes umkmCounter for both inline script and the external script
        window.umkmCounter = $('.umkm-form-entry').length > 0 ? $('.umkm-form-entry').length : 0;

        // Your other event handlers and functions can stay here...
    });
</script>
@endpush
@section('content')
    @include('layouts.sidebar')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navbar')
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Edit Data UMKM</h1>
                </div>

                <div class="container-fluid pt-2 px-2">
                    <div class="bg-white justify-content-between rounded shadow p-4">
                        <!-- Alert Container -->
                        @if (session('status'))
                            <div class="alert alert-{{ session('status_type') }} alert-dismissible fade show" role="alert">
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
                                <a class="nav-link" id="omset-tab" data-toggle="tab" href="#omset" role="tab"
                                    aria-controls="omset" aria-selected="false">Omset</a>
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
                        <form action="{{ route('dataumkm.update', $pelakuUmkm->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="tab-content" id="myTabContent">
                                <!-- Tab 1: Data Pelaku UMKM -->
                                <div class="tab-pane fade show active" id="pelaku" role="tabpanel"
                                    aria-labelledby="pelaku-tab">
                                    <div class="row mb-3">
                                        <label for="nik_pemilik" class="col-sm-2 col-form-label">NIK Pemilik</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nik" name="nik"
                                                value="{{ $pelakuUmkm->nik }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="nama_pemilik" class="col-sm-2 col-form-label">Nama Pemilik</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                                value="{{ $pelakuUmkm->nama_lengkap }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="no_kk_pemilik" class="col-sm-2 col-form-label">No KK Pemilik</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="no_kk" name="no_kk"
                                                value="{{ $pelakuUmkm->no_kk }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="tempat_lahir"
                                                name="tempat_lahir" value="{{ $pelakuUmkm->tempat_lahir }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir"
                                                value="{{ $pelakuUmkm->tgl_lahir }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="jenis_kelamin"
                                                name="jenis_kelamin" value="{{ $pelakuUmkm->jenis_kelamin }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="status_hub_keluarga" class="col-sm-2 col-form-label">Status Hub.
                                            Keluarga</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="status_hubungan_keluarga"
                                                name="status_hubungan_keluarga"
                                                value="{{ $pelakuUmkm->status_hubungan_keluarga }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="status" class="col-sm-2 col-form-label">Status Perkawinan</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="status_perkawinan"
                                                name="status_perkawinan" value="{{ $pelakuUmkm->status_perkawinan }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="alamat_sesuai_ktp" class="col-sm-2 col-form-label">Alamat Sesuai
                                            KTP</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="alamat_sesuai_ktp"
                                                name="alamat_sesuai_ktp" value="{{ $pelakuUmkm->alamat_sesuai_ktp }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="kelurahan_sesuai_ktp" class="col-sm-2 col-form-label">Kelurahan Sesuai
                                            KTP</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="kelurahan" name="kelurahan"
                                                value="{{ $pelakuUmkm->kelurahan }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="rw" class="col-sm-2 col-form-label">RW</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="rw" name="rw"
                                                value="{{ $pelakuUmkm->rw }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="rt" class="col-sm-2 col-form-label">RT</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="rt" name="rt"
                                                value="{{ $pelakuUmkm->rt }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="telp" class="col-sm-2 col-form-label">Telp</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="no_telp" name="no_telp"
                                                value="{{ $pelakuUmkm->no_telp }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="pendidikan_terakhir" class="col-sm-2 col-form-label">Pendidikan
                                            Terakhir</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="pendidikan_terakhir"
                                                name="pendidikan_terakhir"
                                                value="{{ $pelakuUmkm->pendidikan_terakhir }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="pendidikan_terakhir" class="col-sm-2 col-form-label">Status Keaktifan
                                            Pelaku UMKM</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="status_keaktifan"
                                                name="status_keaktifan" value="{{ $pelakuUmkm->status_keaktifan }}">
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary"
                                            onclick="$('#umkm-tab').tab('show')">
                                            Selanjutnya <i class="fas fa-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Tab 2: Data UMKM (Modified for Multiple Entries) -->
                                <!-- Tab 2: Data UMKM (Enhanced Professional Design) -->
                                <div class="tab-pane fade" id="umkm" role="tabpanel" aria-labelledby="umkm-tab">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-gradient-primary text-white py-3">
                                            <h5 class="m-0 font-weight-bold">Data UMKM</h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <!-- Container for UMKM entries -->
                                            <div id="umkm-entries-container">
                                                @foreach ($pelakuUmkm->dataUmkm as $index => $umkm)
                                                    <div class="umkm-form-entry border rounded p-4 mb-4 shadow-sm"
                                                        id="umkm-entry-{{ $index }}"
                                                        data-umkm-id="{{ $index }}">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                                            <h5 class="m-0 umkm-number font-weight-bold text-primary">UMKM
                                                                #{{ $loop->iteration }}</h5>
                                                            @if ($loop->count > 1)
                                                                <button type="button"
                                                                    class="btn btn-outline-danger btn-sm remove-umkm"
                                                                    data-umkm-id="{{ $index }}">
                                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                                </button>
                                                            @endif
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="nama_usaha_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Nama
                                                                        Usaha</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="nama_usaha_{{ $index }}"
                                                                            name="umkm[{{ $index }}][nama_usaha]"
                                                                            value="{{ $umkm->nama_usaha }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="alamat_usaha_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Alamat
                                                                        Usaha</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="alamat_usaha_{{ $index }}"
                                                                            name="umkm[{{ $index }}][alamat]"
                                                                            value="{{ $umkm->alamat }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="jenis_produk_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Jenis
                                                                        Produk</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="jenis_produk_{{ $index }}"
                                                                            name="umkm[{{ $index }}][jenis_produk]"
                                                                            value="{{ $umkm->jenis_produk }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="tipe_produk_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Tipe
                                                                        Produk</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="tipe_produk_{{ $index }}"
                                                                            name="umkm[{{ $index }}][tipe_produk]"
                                                                            value="{{ $umkm->tipe_produk }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="pengelolaan_usaha_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Pengelolaan
                                                                        Usaha</label>
                                                                    <div class="col-sm-8">
                                                                        <select class="form-control"
                                                                            id="pengelolaan_usaha_{{ $index }}"
                                                                            name="umkm[{{ $index }}][pengelolaan_usaha]">
                                                                            <option value="">-- Pilih --</option>
                                                                            <option value="Perseorangan"
                                                                                {{ $umkm->pengelolaan_usaha == 'Perseorangan' ? 'selected' : '' }}>
                                                                                Perseorangan</option>
                                                                            <option value="Kelompok"
                                                                                {{ $umkm->pengelolaan_usaha == 'Kelompok' ? 'selected' : '' }}>
                                                                                Kelompok</option>
                                                                            <option value="Badan Usaha"
                                                                                {{ $umkm->pengelolaan_usaha == 'Badan Usaha' ? 'selected' : '' }}>
                                                                                Badan Usaha</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label
                                                                        for="klasifikasi_kinerja_usaha_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Klasifikasi
                                                                        Kinerja</label>
                                                                    <div class="col-sm-8">
                                                                        <select class="form-control"
                                                                            id="klasifikasi_kinerja_usaha_{{ $index }}"
                                                                            name="umkm[{{ $index }}][klasifikasi_kinerja_usaha]">
                                                                            <option value="">-- Pilih --</option>
                                                                            <option value="Sangat Baik"
                                                                                {{ $umkm->klasifikasi_kinerja_usaha == 'Sangat Baik' ? 'selected' : '' }}>
                                                                                Sangat Baik</option>
                                                                            <option value="Baik"
                                                                                {{ $umkm->klasifikasi_kinerja_usaha == 'Baik' ? 'selected' : '' }}>
                                                                                Baik</option>
                                                                            <option value="Cukup"
                                                                                {{ $umkm->klasifikasi_kinerja_usaha == 'Cukup' ? 'selected' : '' }}>
                                                                                Cukup</option>
                                                                            <option value="Kurang"
                                                                                {{ $umkm->klasifikasi_kinerja_usaha == 'Kurang' ? 'selected' : '' }}>
                                                                                Kurang</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="jumlah_tenaga_kerja_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Jumlah
                                                                        Tenaga Kerja</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="number" class="form-control"
                                                                            id="jumlah_tenaga_kerja_{{ $index }}"
                                                                            name="umkm[{{ $index }}][jumlah_tenaga_kerja]"
                                                                            value="{{ $umkm->jumlah_tenaga_kerja }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="sektor_usaha_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Sektor
                                                                        Usaha</label>
                                                                    <div class="col-sm-8">
                                                                        <select class="form-control"
                                                                            id="sektor_usaha_{{ $index }}"
                                                                            name="umkm[{{ $index }}][sektor_usaha]">
                                                                            <option value="">-- Pilih --</option>
                                                                            <option value="Manufaktur"
                                                                                {{ $umkm->sektor_usaha == 'Manufaktur' ? 'selected' : '' }}>
                                                                                Manufaktur</option>
                                                                            <option value="Jasa"
                                                                                {{ $umkm->sektor_usaha == 'Jasa' ? 'selected' : '' }}>
                                                                                Jasa</option>
                                                                            <option value="Perdagangan"
                                                                                {{ $umkm->sektor_usaha == 'Perdagangan' ? 'selected' : '' }}>
                                                                                Perdagangan</option>
                                                                            <option value="Pertanian"
                                                                                {{ $umkm->sektor_usaha == 'Pertanian' ? 'selected' : '' }}>
                                                                                Pertanian</option>
                                                                            <option value="Peternakan"
                                                                                {{ $umkm->sektor_usaha == 'Peternakan' ? 'selected' : '' }}>
                                                                                Peternakan</option>
                                                                            <option value="Perikanan"
                                                                                {{ $umkm->sektor_usaha == 'Perikanan' ? 'selected' : '' }}>
                                                                                Perikanan</option>
                                                                            <option value="Lainnya"
                                                                                {{ $umkm->sektor_usaha == 'Lainnya' ? 'selected' : '' }}>
                                                                                Lainnya</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="status_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Status
                                                                        Keaktifan</label>
                                                                    <div class="col-sm-8">
                                                                        <select class="form-control"
                                                                            id="status_{{ $index }}"
                                                                            name="umkm[{{ $index }}][status]">
                                                                            <option value="">-- Pilih --</option>
                                                                            <option value="Aktif"
                                                                                {{ $umkm->status == 'Aktif' ? 'selected' : '' }}>
                                                                                Aktif</option>
                                                                            <option value="Tidak Aktif"
                                                                                {{ $umkm->status == 'Tidak Aktif' ? 'selected' : '' }}>
                                                                                Tidak Aktif</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <input type="hidden" name="umkm[{{ $index }}][id]"
                                                            value="{{ $umkm->id }}">
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="text-center my-4">
                                                <button type="button" class="btn btn-primary btn-lg" id="add-umkm-btn">
                                                    <i class="fas fa-plus-circle mr-2"></i> Tambah UMKM Baru
                                                </button>
                                            </div>

                                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                                <button type="button" class="btn btn-outline-secondary btn-lg"
                                                    onclick="$('#pelaku-tab').tab('show')">
                                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                                </button>
                                                <button type="submit" class="btn btn-success btn-lg">
                                                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="omset" role="tabpanel" aria-labelledby="omset-tab">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-gradient-primary text-white py-3">
                                            <h5 class="m-0 font-weight-bold">Data Omset UMKM</h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <!-- Form for adding omset data -->
                                            <div id="omset-form-container" class="mb-4">
                                                <!-- UMKM Selection -->
                                                <div class="row mb-3">
                                                    <label for="umkm_id" class="col-sm-3 col-form-label font-weight-bold">Pilih UMKM</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="umkm_id" name="omset[umkm_id]">
                                                            <option value="">-- Pilih UMKM --</option>
                                                            @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                <option value="{{ $umkm->id }}">{{ $umkm->nama_usaha }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Jangka Waktu -->
                                                <div class="row mb-3">
                                                    <label for="jangka_waktu" class="col-sm-3 col-form-label font-weight-bold">Jangka Waktu</label>
                                                    <div class="col-sm-9">
                                                        <input type="date" class="form-control" id="jangka_waktu" name="omset[jangka_waktu]">
                                                    </div>
                                                </div>

                                                <!-- Nilai Omset -->
                                                <div class="row mb-3">
                                                    <label for="omset" class="col-sm-3 col-form-label font-weight-bold">Nilai Omset</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp.</span>
                                                            </div>
                                                            <input type="text" class="form-control currency-input" id="total_omset" name="omset[total_omset]" placeholder="0">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Keterangan -->
                                                <div class="row mb-3">
                                                    <label for="keterangan" class="col-sm-3 col-form-label font-weight-bold">Keterangan</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="keterangan" name="omset[keterangan]">
                                                            <option value="">-- Pilih Status --</option>
                                                            <option value="aktif">Aktif</option>
                                                            <option value="tidak_aktif">Tidak Aktif</option>
                                                            <option value="meningkat">Meningkat</option>
                                                            <option value="menurun">Menurun</option>
                                                            <option value="stabil">Stabil</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <button type="button" class="btn btn-success" id="tambah-data-omset">
                                                            <i class="fas fa-plus-circle mr-2"></i> Tambah Data Omset
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Table of existing omset data -->
                                            <div class="mt-5">
                                                <div class="card border-left-primary shadow">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered" id="table-omset" width="100%" cellspacing="0">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th class="text-center" width="5%">NO</th>
                                                                        <th width="15%">UMKM</th>
                                                                        <th width="15%">Jangka Waktu</th>
                                                                        <th width="20%">Nilai Omset</th>
                                                                        <th width="15%">Keterangan</th>

                                                                        <th class="text-center" width="10%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if (isset($omsetData) && count($omsetData) > 0)
                                                                        @foreach ($omsetData as $index => $item)
                                                                            <tr>
                                                                                <td class="text-center">{{ $index + 1 }}</td>
                                                                                <td>{{ $item->dataUmkm->nama_usaha }}</td>
                                                                                <td>{{ date('d-m-Y', strtotime($item->jangka_waktu)) }}</td>
                                                                                <td>Rp. {{ number_format($item->omset, 0, ',', '.') }}</td>
                                                                                <td>
                                                                                    @if ($item->keterangan == 'aktif')
                                                                                        <span class="badge badge-success">Aktif</span>
                                                                                    @elseif ($item->keterangan == 'tidak_aktif')
                                                                                        <span class="badge badge-danger">Tidak Aktif</span>
                                                                                    @elseif ($item->keterangan == 'meningkat')
                                                                                        <span class="badge badge-info">Meningkat</span>
                                                                                    @elseif ($item->keterangan == 'menurun')
                                                                                        <span class="badge badge-warning">Menurun</span>
                                                                                    @elseif ($item->keterangan == 'stabil')
                                                                                        <span class="badge badge-secondary">Stabil</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button" class="btn btn-warning btn-sm edit-omset" data-id="{{ $item->id }}">
                                                                                        <i class="fas fa-edit"></i> Edit
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td colspan="6" class="text-center">Belum ada data omset</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="$('#umkm-tab').tab('show')">
                                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                                </button>
                                                <button type="button" class="btn btn-primary btn-lg" onclick="$('#legalitas-tab').tab('show')">
                                                    Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                                                </button>
                                            </div>

                                            <!-- Modal for editing omset -->
                                            <div class="modal fade" id="editOmsetModal" tabindex="-1" role="dialog" aria-labelledby="editOmsetModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-gradient-primary text-white">
                                                            <h5 class="modal-title" id="editOmsetModalLabel">Edit Data Omset</h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="edit-omset-form">
                                                                <input type="hidden" id="edit_omset_id" name="id">

                                                                <div class="form-group row">
                                                                    <label for="edit_umkm_id" class="col-sm-3 col-form-label">UMKM</label>
                                                                    <div class="col-sm-9">
                                                                        <select class="form-control" id="edit_umkm_id" name="umkm_id">
                                                                            @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                                <option value="{{ $umkm->id }}">{{ $umkm->nama_usaha }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="edit_jangka_waktu" class="col-sm-3 col-form-label">Jangka Waktu</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="date" class="form-control" id="edit_jangka_waktu" name="jangka_waktu">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="edit_omset" class="col-sm-3 col-form-label">Nilai Omset</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">Rp.</span>
                                                                            </div>
                                                                            <input type="text" class="form-control currency-input" id="edit_omset" name="omset">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="edit_keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                                                                    <div class="col-sm-9">
                                                                        <select class="form-control" id="edit_keterangan" name="keterangan">
                                                                            <option value="aktif">Aktif</option>
                                                                            <option value="tidak_aktif">Tidak Aktif</option>
                                                                            <option value="meningkat">Meningkat</option>
                                                                            <option value="menurun">Menurun</option>
                                                                            <option value="stabil">Stabil</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="button" class="btn btn-primary" id="save-edit-omset">Simpan Perubahan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tab 5: Legalitas UMKM -->
                                <div class="tab-pane fade" id="legalitas" role="tabpanel" aria-labelledby="legalitas-tab">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-gradient-primary text-white py-3">
                                            <h5 class="m-0 font-weight-bold">Data Legalitas UMKM</h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <!-- UMKM Selection for Legalitas -->
                                            <div class="row mb-4">
                                                <label for="legalitas_umkm_id" class="col-sm-3 col-form-label font-weight-bold">Pilih UMKM</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" id="legalitas_umkm_id" name="legalitas[umkm_id]">
                                                        <option value="">-- Pilih UMKM --</option>
                                                        @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                            <option value="{{ $umkm->id }}">{{ $umkm->nama_usaha }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Container for legalitas form -->
                                            <div id="legalitas-container">
                                                <div class="row mb-3">
                                                    <label for="no_sk_nib" class="col-sm-3 col-form-label font-weight-bold">No SK NIB</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="no_sk_nib"
                                                            name="legalitas[no_sk_nib]"
                                                            value="{{ $pelakuUmkm->legalitas->no_sk_nib ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="no_sk_siup" class="col-sm-3 col-form-label font-weight-bold">No SK SIUP</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="no_sk_siup"
                                                            name="legalitas[no_sk_siup]"
                                                            value="{{ $pelakuUmkm->legalitas->no_sk_siup ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="no_sk_tdp" class="col-sm-3 col-form-label font-weight-bold">No SK TDP</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="no_sk_tdp"
                                                            name="legalitas[no_sk_tdp]"
                                                            value="{{ $pelakuUmkm->legalitas->no_sk_tdp ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="no_sk_pirt" class="col-sm-3 col-form-label font-weight-bold">No SK PIRT</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="no_sk_pirt"
                                                            name="legalitas[no_sk_pirt]"
                                                            value="{{ $pelakuUmkm->legalitas->no_sk_pirt ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="no_sk_bpom" class="col-sm-3 col-form-label font-weight-bold">No SK BPOM</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="no_sk_bpom"
                                                            name="legalitas[no_sk_bpom]"
                                                            value="{{ $pelakuUmkm->legalitas->no_sk_bpom ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="no_sk_halal" class="col-sm-3 col-form-label font-weight-bold">No SK HALAL</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="no_sk_halal"
                                                            name="legalitas[no_sk_halal]"
                                                            value="{{ $pelakuUmkm->legalitas->no_sk_halal ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="no_sk_merek" class="col-sm-3 col-form-label font-weight-bold">No SK MEREK</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="no_sk_merek"
                                                            name="legalitas[no_sk_merek]"
                                                            value="{{ $pelakuUmkm->legalitas->no_sk_merek ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="no_sk_haki" class="col-sm-3 col-form-label font-weight-bold">No SK HAKI</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="no_sk_haki"
                                                            name="legalitas[no_sk_haki]"
                                                            value="{{ $pelakuUmkm->legalitas->no_sk_haki ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="no_surat_keterangan" class="col-sm-3 col-form-label font-weight-bold">No Surat Keterangan</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="no_surat_keterangan"
                                                            name="legalitas[no_surat_keterangan]"
                                                            value="{{ $pelakuUmkm->legalitas->no_surat_keterangan ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <button type="button" class="btn btn-success btn-lg btn-block" id="simpan-legalitas">
                                                            <i class="fas fa-save mr-2"></i> Simpan Legalitas
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="mt-5">
                                                    <div class="card border-left-primary shadow">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" id="table-legalitas" width="100%" cellspacing="0">
                                                                    <thead class="bg-light">
                                                                        <tr>
                                                                            <th class="text-center" width="5%">NO</th>
                                                                            <th width="20%">UMKM</th>
                                                                            <th width="15%">No NIB</th>
                                                                            <th width="15%">No SIUP</th>
                                                                            <th width="15%">No PIRT</th>
                                                                            <th width="15%">No HALAL</th>
                                                                            <th class="text-center" width="10%">Aksi</th>
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
                                                                                    <td>{{ $item->no_sk_pirt ?: '-' }}</td>
                                                                                    <td>{{ $item->no_sk_halal ?: '-' }}</td>
                                                                                    <td class="text-center">
                                                                                        <button type="button" class="btn btn-warning btn-sm edit-legalitas" data-id="{{ $item->id }}">
                                                                                            <i class="fas fa-edit"></i> Edit
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @else
                                                                            <tr>
                                                                                <td colspan="7" class="text-center">Belum ada data legalitas</td>
                                                                            </tr>
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal for editing legalitas -->
                                                <div class="modal fade" id="editLegalitasModal" tabindex="-1" role="dialog" aria-labelledby="editLegalitasModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-gradient-primary text-white">
                                                                <h5 class="modal-title" id="editLegalitasModalLabel">Edit Data Legalitas</h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="edit-legalitas-form">
                                                                    <input type="hidden" id="edit_legalitas_id" name="id">

                                                                    <div class="form-group row">
                                                                        <label for="edit_legalitas_umkm_id" class="col-sm-3 col-form-label">UMKM</label>
                                                                        <div class="col-sm-9">
                                                                            <select class="form-control" id="edit_legalitas_umkm_id" name="umkm_id">
                                                                                @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                                    <option value="{{ $umkm->id }}">{{ $umkm->nama_usaha }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_nib" class="col-sm-3 col-form-label">No SK NIB</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" id="edit_no_sk_nib" name="no_sk_nib">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_siup" class="col-sm-3 col-form-label">No SK SIUP</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" id="edit_no_sk_siup" name="no_sk_siup">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_tdp" class="col-sm-3 col-form-label">No SK TDP</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" id="edit_no_sk_tdp" name="no_sk_tdp">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_pirt" class="col-sm-3 col-form-label">No SK PIRT</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" id="edit_no_sk_pirt" name="no_sk_pirt">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_bpom" class="col-sm-3 col-form-label">No SK BPOM</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" id="edit_no_sk_bpom" name="no_sk_bpom">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_halal" class="col-sm-3 col-form-label">No SK HALAL</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" id="edit_no_sk_halal" name="no_sk_halal">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_merek" class="col-sm-3 col-form-label">No SK MEREK</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" id="edit_no_sk_merek" name="no_sk_merek">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_haki" class="col-sm-3 col-form-label">No SK HAKI</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" id="edit_no_sk_haki" name="no_sk_haki">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_surat_keterangan" class="col-sm-3 col-form-label">No Surat Keterangan</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control" id="edit_no_surat_keterangan" name="no_surat_keterangan">
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <button type="button" class="btn btn-primary" id="save-edit-legalitas">Simpan Perubahan</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                                    <button type="button" class="btn btn-outline-secondary btn-lg" onclick="$('#intervensi-tab').tab('show')">
                                                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                                                    </button>
                                                    <button type="button" class="btn btn-primary btn-lg" onclick="$('#omset-tab').tab('show')">
                                                        Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                                                    </button>
                                                </div>

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
                                            <!-- Form for adding intervention data -->
                                            <div id="intervensi-form-container" class="mb-4">
                                                <!-- UMKM Selection for Intervensi -->
                                                <div class="row mb-3">
                                                    <label for="intervensi_umkm_id" class="col-sm-3 col-form-label font-weight-bold">Pilih UMKM</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="intervensi_umkm_id" name="intervensi[umkm_id]">
                                                            <option value="">-- Pilih UMKM --</option>
                                                            @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                <option value="{{ $umkm->id }}">{{ $umkm->nama_usaha }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="tanggal_intervensi" class="col-sm-3 col-form-label font-weight-bold">Tanggal Intervensi</label>
                                                    <div class="col-sm-9">
                                                        <input type="date" class="form-control" id="tanggal_intervensi" name="intervensi[tanggal_intervensi]">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="jenis_intervensi" class="col-sm-3 col-form-label font-weight-bold">Jenis Intervensi</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="jenis_intervensi" name="intervensi[jenis_intervensi]">
                                                            <option value="">-- Pilih Jenis Intervensi --</option>
                                                            <option value="Pemasaran">Pemasaran</option>
                                                            <option value="Pendampingan">Pendampingan</option>
                                                            <option value="Pelatihan">Pelatihan</option>
                                                            <option value="Pameran">Pameran</option>
                                                            <option value="Bantuan Modal">Bantuan Modal</option>
                                                            <option value="Bantuan Alat">Bantuan Alat</option>
                                                            <option value="Lainnya">Lainnya</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="nama_kegiatan" class="col-sm-3 col-form-label font-weight-bold">Nama Kegiatan</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="nama_kegiatan" name="intervensi[nama_kegiatan]"
                                                            placeholder="Masukkan nama kegiatan">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="omset_intervensi" class="col-sm-3 col-form-label font-weight-bold">Omset</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp.</span>
                                                            </div>
                                                            <input type="text" class="form-control currency-input" id="omset_intervensi"
                                                                name="intervensi[omset]" placeholder="0">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <button type="button" class="btn btn-success" id="tambah-data-intervensi">
                                                            <i class="fas fa-plus-circle mr-2"></i> Tambah Data Intervensi
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Table of existing interventions -->
                                            <div class="mt-5">
                                                <div class="card border-left-primary shadow">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered" id="table-intervensi" width="100%" cellspacing="0">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th class="text-center" width="5%">NO</th>
                                                                        <th width="15%">UMKM</th>
                                                                        <th width="15%">Tanggal</th>
                                                                        <th width="15%">Jenis</th>
                                                                        <th width="20%">Nama Kegiatan</th>
                                                                        <th width="15%">Omset</th>
                                                                        <th class="text-center" width="10%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if (isset($pelakuUmkm->intervensi) && $pelakuUmkm->intervensi->count() > 0)
                                                                        @foreach ($pelakuUmkm->intervensi as $index => $item)
                                                                            <tr>
                                                                                <td class="text-center">{{ $index + 1 }}</td>
                                                                                <td>{{ $item->dataUmkm->nama_usaha ?? 'Tidak ada' }}</td>
                                                                                <td>{{ \Carbon\Carbon::parse($item->tanggal_intervensi)->format('d-m-Y') }}</td>
                                                                                <td>{{ $item->jenis_intervensi }}</td>
                                                                                <td>{{ $item->nama_kegiatan }}</td>
                                                                                <td>Rp. {{ number_format($item->omset, 0, ',', '.') }}</td>
                                                                                <td class="text-center">
                                                                                    <button type="button" class="btn btn-warning btn-sm edit-intervensi"
                                                                                        data-id="{{ $item->id }}">
                                                                                        <i class="fas fa-edit"></i> Edit
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td colspan="7" class="text-center">Belum ada data intervensi</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="$('#umkm-tab').tab('show')">
                                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                                </button>
                                                <button type="button" class="btn btn-primary btn-lg" onclick="$('#legalitas-tab').tab('show')">
                                                    Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for editing intervention -->
                                <div class="modal fade" id="editIntervensiModal" tabindex="-1" role="dialog"
                                    aria-labelledby="editIntervensiModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-gradient-primary text-white">
                                                <h5 class="modal-title" id="editIntervensiModalLabel">Edit Data Intervensi
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="edit-intervensi-form">
                                                    <input type="hidden" id="edit_intervensi_id" name="id">

                                                    <div class="form-group row">
                                                        <label for="edit_tanggal_intervensi"
                                                            class="col-sm-3 col-form-label">Tanggal Intervensi</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" class="form-control"
                                                                id="edit_tanggal_intervensi" name="tanggal_intervensi">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="edit_jenis_intervensi"
                                                            class="col-sm-3 col-form-label">Jenis Intervensi</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" id="edit_jenis_intervensi"
                                                                name="jenis_intervensi">
                                                                <option value="">-- Pilih Jenis Intervensi --
                                                                </option>
                                                                <option value="Pemasaran">Pemasaran</option>
                                                                <option value="Pendampingan">Pendampingan</option>
                                                                <option value="Pelatihan">Pelatihan</option>
                                                                <option value="Pameran">Pameran</option>
                                                                <option value="Bantuan Modal">Bantuan Modal</option>
                                                                <option value="Bantuan Alat">Bantuan Alat</option>
                                                                <option value="Lainnya">Lainnya</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="edit_nama_kegiatan"
                                                            class="col-sm-3 col-form-label">Nama Kegiatan</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control"
                                                                id="edit_nama_kegiatan" name="nama_kegiatan">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="edit_omset"
                                                            class="col-sm-3 col-form-label">Omset</label>
                                                        <div class="col-sm-9">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input type="text" class="form-control currency-input"
                                                                    id="edit_omset" name="omset">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-primary"
                                                    id="save-edit-intervensi">Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
@endsection
