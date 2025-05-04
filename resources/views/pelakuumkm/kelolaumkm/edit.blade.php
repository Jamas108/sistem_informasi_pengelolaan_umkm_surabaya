@extends('layouts.pelakuumkm.app')
@push('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/pelaku-edit-umkm.js') }}"></script>
    <script src="{{ asset('js/umkm-form.js') }}"></script>
    <script src="{{ asset('js/umkm-legalitas.js') }}"></script>
    <script src="{{ asset('js/umkm-omset.js') }}"></script>
    <script>
        $(document).ready(function() {
            // This initializes umkmCounter for both inline script and the external script
            window.umkmCounter = $('.umkm-form-entry').length > 0 ? $('.umkm-form-entry').length : 0;

            // Your other event handlers and functions can stay here...
        });
        $(document).ready(function() {
            // This initializes umkmCounter for both inline script and the external script
            window.umkmCounter = $('.umkm-form-entry').length > 0 ? $('.umkm-form-entry').length : 0;

            // Manual test for Bootstrap and jQuery
            console.log("Document ready in main script");
            console.log("jQuery version:", $.fn.jquery);

            // Test modal functionality directly
            $('#test-modal-btn').on('click', function() {
                console.log("Test modal button clicked");
                $('#editOmsetModal').modal('show');
            });
        });

        $(document).ready(function() {
            // Init Bootstrap components
            if (typeof $.fn.modal === 'function') {
                // Initialize modal with options
                $('#editOmsetModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: false
                });

                console.log("Bootstrap modal initialized successfully");
            } else {
                console.error("Bootstrap modal function not available");
            }
        });
    </script>
@endpush

@section('content')
    @include('layouts.pelakuumkm.sidebar')

    <main class="main-content">
        <div class="bg-primary text-white py-3 px-4 shadow-sm mb-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="fw-bold mb-0">
                            <i class="fas fa-edit me-2"></i>Edit Data UMKM
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <!-- Alert Container -->
                    @if (session('status'))
                        <div class="alert alert-{{ session('status_type') }} alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pelaku-tab" data-bs-toggle="tab" data-bs-target="#pelaku"
                                type="button" role="tab" aria-controls="pelaku" aria-selected="true">
                                <i class="fas fa-user me-1"></i> Data Pelaku UMKM
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="umkm-tab" data-bs-toggle="tab" data-bs-target="#umkm"
                                type="button" role="tab" aria-controls="umkm" aria-selected="false">
                                <i class="fas fa-store me-1"></i> Data UMKM
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="omset-tab" data-bs-toggle="tab" data-bs-target="#omset"
                                type="button" role="tab" aria-controls="omset" aria-selected="false">
                                <i class="fas fa-coins me-1"></i> Omset
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <form action="{{ route('pelakukelolaumkm.update', $pelakuUmkm->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab 1: Data Pelaku UMKM -->
                            <!-- Tab 1: Data Pelaku UMKM (Revised) -->
                            <div class="tab-pane fade show active" id="pelaku" role="tabpanel"
                                aria-labelledby="pelaku-tab">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-gradient-primary text-white py-3">
                                        <h5 class="m-0 font-weight-bold">Data Pelaku UMKM</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row mb-3">
                                            <label for="nik_pemilik" class="col-sm-3 col-form-label font-weight-bold">NIK
                                                Pemilik</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="nik" name="nik"
                                                    value="{{ $pelakuUmkm->nik }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="nama_pemilik" class="col-sm-3 col-form-label font-weight-bold">Nama
                                                Pemilik</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="nama_lengkap"
                                                    name="nama_lengkap" value="{{ $pelakuUmkm->nama_lengkap }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="no_kk_pemilik" class="col-sm-3 col-form-label font-weight-bold">No
                                                KK Pemilik</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="no_kk" name="no_kk"
                                                    value="{{ $pelakuUmkm->no_kk }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="tempat_lahir"
                                                class="col-sm-3 col-form-label font-weight-bold">Tempat Lahir</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="tempat_lahir"
                                                    name="tempat_lahir" value="{{ $pelakuUmkm->tempat_lahir }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="tanggal_lahir"
                                                class="col-sm-3 col-form-label font-weight-bold">Tanggal Lahir</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="tgl_lahir"
                                                    name="tgl_lahir" value="{{ $pelakuUmkm->tgl_lahir }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="jenis_kelamin"
                                                class="col-sm-3 col-form-label font-weight-bold">Jenis Kelamin</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                                    <option value="Laki-laki"
                                                        {{ $pelakuUmkm->jenis_kelamin == 'LAKI - LAKI' ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="Perempuan"
                                                        {{ $pelakuUmkm->jenis_kelamin == 'PEREMPUAN' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="status_hub_keluarga"
                                                class="col-sm-3 col-form-label font-weight-bold">Status Hub.
                                                Keluarga</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="status_hubungan_keluarga"
                                                    name="status_hubungan_keluarga">
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="ANAK"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'ANAK' ? 'selected' : '' }}>
                                                        ANAK</option>
                                                    <option value="CUCU"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'CUCU' ? 'selected' : '' }}>
                                                        CUCU</option>
                                                    <option value="ISTRI"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'ISTRI' ? 'selected' : '' }}>
                                                        ISTRI</option>
                                                    <option value="SUAMI"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'SUAMI' ? 'selected' : '' }}>
                                                        SUAMI</option>
                                                    <option value="KEPALA KELUARGA"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'KEPALA KELUARGA' ? 'selected' : '' }}>
                                                        KEPALA KELUARGA</option>
                                                    <option value="MENANTU"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'MENANTU' ? 'selected' : '' }}>
                                                        MENANTU</option>
                                                    <option value="MERTUA"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'MERTUA' ? 'selected' : '' }}>
                                                        MERTUA</option>
                                                    <option value="ORANG TUA"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'ORANG TUA' ? 'selected' : '' }}>
                                                        ORANG TUA</option>
                                                    <option value="FAMILI LAIN"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'FAMILI LAIN' ? 'selected' : '' }}>
                                                        FAMILI LAIN</option>
                                                    <option value="LAINYA"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'LAINYA' ? 'selected' : '' }}>
                                                        LAINYA</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="status" class="col-sm-3 col-form-label font-weight-bold">Status
                                                Perkawinan</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="status_perkawinan"
                                                    name="status_perkawinan">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="KAWIN"
                                                        {{ $pelakuUmkm->status_perkawinan == 'KAWIN' ? 'selected' : '' }}>
                                                        KAWIN</option>
                                                    <option value="KAWIN BELUM TERCATAT"
                                                        {{ $pelakuUmkm->status_perkawinan == 'KAWIN BELUM TERCATAT' ? 'selected' : '' }}>
                                                        KAWIN BELUM TERCATAT</option>
                                                    <option value="KAWIN TERCATAT"
                                                        {{ $pelakuUmkm->status_perkawinan == 'KAWIN TERCATAT' ? 'selected' : '' }}>
                                                        KAWIN TERCATAT</option>
                                                    <option value="BELUM KAWIN"
                                                        {{ $pelakuUmkm->status_perkawinan == 'BELUM KAWIN' ? 'selected' : '' }}>
                                                        BELUM KAWIN</option>
                                                    <option value="CERAI BELUM TERCATAT"
                                                        {{ $pelakuUmkm->status_perkawinan == 'CERAI BELUM TERCATAT' ? 'selected' : '' }}>
                                                        CERAI BELUM TERCATAT</option>
                                                    <option value="CERAI HIDUP"
                                                        {{ $pelakuUmkm->status_perkawinan == 'CERAI HIDUP' ? 'selected' : '' }}>
                                                        CERAI HIDUP</option>
                                                    <option value="CERAI MATI"
                                                        {{ $pelakuUmkm->status_perkawinan == 'CERAI MATI' ? 'selected' : '' }}>
                                                        CERAI MATI</option>
                                                    <option value="CERAI TERCATAT"
                                                        {{ $pelakuUmkm->status_perkawinan == 'CERAI TERCATAT' ? 'selected' : '' }}>
                                                        CERAI TERCATAT</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="alamat_sesuai_ktp"
                                                class="col-sm-3 col-form-label font-weight-bold">Alamat Sesuai
                                                KTP</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="alamat_sesuai_ktp"
                                                    name="alamat_sesuai_ktp"
                                                    value="{{ $pelakuUmkm->alamat_sesuai_ktp }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="kelurahan_sesuai_ktp"
                                                class="col-sm-3 col-form-label font-weight-bold">Kelurahan Sesuai
                                                KTP</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="kelurahan"
                                                    name="kelurahan" value="{{ $pelakuUmkm->kelurahan }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="rw"
                                                class="col-sm-3 col-form-label font-weight-bold">RW</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="rw" name="rw"
                                                    value="{{ $pelakuUmkm->rw }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="rt"
                                                class="col-sm-3 col-form-label font-weight-bold">RT</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="rt" name="rt"
                                                    value="{{ $pelakuUmkm->rt }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="telp"
                                                class="col-sm-3 col-form-label font-weight-bold">Telp</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="no_telp" name="no_telp"
                                                    value="{{ $pelakuUmkm->no_telp }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="pendidikan_terakhir"
                                                class="col-sm-3 col-form-label font-weight-bold">Pendidikan
                                                Terakhir</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="pendidikan_terakhir"
                                                    name="pendidikan_terakhir">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="TIDAK/BELUM SEKOLAH"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'TIDAK/BELUM SEKOLAH' ? 'selected' : '' }}>
                                                        TIDAK/BELUM SEKOLAH</option>
                                                    <option value="BELUM TAMAT SD/SEDERAJAT"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'BELUM TAMAT SD/SEDERAJAT' ? 'selected' : '' }}>
                                                        BELUM TAMAT SD/SEDERAJAT</option>
                                                    <option value="TAMAT SD/SEDERAJAT"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'TAMAT SD/SEDERAJAT' ? 'selected' : '' }}>
                                                        TAMAT SD/SEDERAJAT</option>
                                                    <option value="SLTP/SEDERAJAT"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'SLTP/SEDERAJAT' ? 'selected' : '' }}>
                                                        SLTP/SEDERAJAT</option>
                                                    <option value="SLTA/SEDERAJAT"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'SLTA/SEDERAJAT' ? 'selected' : '' }}>
                                                        SLTA/SEDERAJAT</option>
                                                    <option value="DIPLOMA I/II"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'DIPLOMA I/II' ? 'selected' : '' }}>
                                                        DIPLOMA I/II</option>
                                                    <option value="AKADEMI/DIPLOMA III/S. MUDA"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'AKADEMI/DIPLOMA III/S. MUDA' ? 'selected' : '' }}>
                                                        AKADEMI/DIPLOMA III/S. MUDA</option>
                                                    <option value="DIPLOMA IV/STRATA I"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'DIPLOMA IV/STRATA I' ? 'selected' : '' }}>
                                                        DIPLOMA IV/STRATA I</option>
                                                    <option value="STRATA II"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'STRATA II' ? 'selected' : '' }}>
                                                        STRATA II</option>
                                                    <option value="STRATA III"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'STRATA III' ? 'selected' : '' }}>
                                                        STRATA III</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="status_keaktifan"
                                                class="col-sm-3 col-form-label font-weight-bold">Status Keaktifan
                                                Pelaku UMKM</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="status_keaktifan"
                                                    name="status_keaktifan">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="AKTIF"
                                                        {{ $pelakuUmkm->status_keaktifan == 'AKTIF' ? 'selected' : '' }}>
                                                        AKTIF</option>
                                                    <option value="TIDAK AKTIF"
                                                        {{ $pelakuUmkm->status_keaktifan == 'TIDAK AKTIF' ? 'selected' : '' }}>
                                                        TIDAK AKTIF</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center mt-4 pt-3 border-top">
                                            <button type="submit" class="btn btn-success btn-md mr-3">
                                                <i class="fas fa-save mr-2"></i> Simpan Data Pelaku
                                            </button>
                                        </div>
                                    </div>
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
                                                            {{ $loop->iteration }}</h5>
                                                        @if ($loop->count > 1)
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
                                                                <label for="pengelolaan_usaha_{{ $index }}"
                                                                    class="col-sm-4 col-form-label font-weight-bold">Pengelolaan
                                                                    Usaha</label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control"
                                                                        id="pengelolaan_usaha_{{ $index }}"
                                                                        name="umkm[{{ $index }}][pengelolaan_usaha]">
                                                                        <option value="">-- Pilih --</option>
                                                                        <option value="PERSEORANGAN / MANDIRI"
                                                                            {{ $umkm->pengelolaan_usaha == 'PERSEORANGAN / MANDIRI' ? 'selected' : '' }}>
                                                                            PERSEORANGAN / MANDIRI</option>
                                                                        <option value="KELOMPOK / SUBKON / KERJASAMA"
                                                                            {{ $umkm->pengelolaan_usaha == 'KELOMPOK / SUBKON / KERJASAMA' ? 'selected' : '' }}>
                                                                            KELOMPOK / SUBKON / KERJASAMA</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label for="klasifikasi_kinerja_usaha_{{ $index }}"
                                                                    class="col-sm-4 col-form-label font-weight-bold">Klasifikasi
                                                                    Kinerja</label>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control"
                                                                        id="klasifikasi_kinerja_usaha_{{ $index }}"
                                                                        name="umkm[{{ $index }}][klasifikasi_kinerja_usaha]">
                                                                        <option value="">-- Pilih --</option>
                                                                        <option value="PEMULA"
                                                                            {{ $umkm->klasifikasi_kinerja_usaha == 'PEMULA' ? 'selected' : '' }}>
                                                                            PEMULA</option>
                                                                        <option value="MADYA"
                                                                            {{ $umkm->klasifikasi_kinerja_usaha == 'MADYA' ? 'selected' : '' }}>
                                                                            MADYA</option>
                                                                        <option value="MANDIRI"
                                                                            {{ $umkm->klasifikasi_kinerja_usaha == 'MANDIRI' ? 'selected' : '' }}>
                                                                            MANDIRI</option>
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
                                                                        <option value="INDUSTRI"
                                                                            {{ $umkm->sektor_usaha == 'INDUSTRI' ? 'selected' : '' }}>
                                                                            INDUSTRI</option>
                                                                        <option value="DAGANG"
                                                                            {{ $umkm->sektor_usaha == 'DAGANG' ? 'selected' : '' }}>
                                                                            DAGANG</option>
                                                                        <option value="JASA"
                                                                            {{ $umkm->sektor_usaha == 'JASA' ? 'selected' : '' }}>
                                                                            JASA</option>
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
                                                                        <option value="AKTIF"
                                                                            {{ $umkm->status == 'AKTIF' ? 'selected' : '' }}>
                                                                            AKTIF</option>
                                                                        <option value="TIDAK AKTIF"
                                                                            {{ $umkm->status == 'TIDAK AKTIF' ? 'selected' : '' }}>
                                                                            TIDAK AKTIF</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="umkm[{{ $index }}][id]"
                                                        value="{{ $umkm->id }}">
                                                    <div class="products-section mt-4 pt-3 border-top">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-3">
                                                            <h5 class="m-0 font-weight-bold text-primary">Produk UMKM</h5>
                                                            <button type="button"
                                                                class="btn btn-sm btn-primary add-product-btn"
                                                                data-umkm-id="{{ $umkm->id }}">
                                                                <i class="fas fa-plus-circle"></i> Tambah Produk
                                                            </button>
                                                        </div>

                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped"
                                                                id="products-table-{{ $umkm->id }}">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th class="text-center" width="5%">No</th>
                                                                        <th width="35%">Jenis Produk</th>
                                                                        <th width="35%">Tipe Produk</th>
                                                                        <th width="10%">Status</th>
                                                                        <th class="text-center" width="15%">Aksi</th>
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
                                                                            <td class="text-center">
                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-warning edit-product-btn"
                                                                                    data-product-id="{{ $produk->id }}"
                                                                                    data-umkm-id="{{ $umkm->id }}"
                                                                                    data-jenis-produk="{{ $produk->jenis_produk }}"
                                                                                    data-tipe-produk="{{ $produk->tipe_produk }}"
                                                                                    data-status="{{ $produk->status }}">
                                                                                    <i class="fas fa-edit"></i>
                                                                                </button>
                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-danger delete-product-btn"
                                                                                    data-product-id="{{ $produk->id }}"
                                                                                    data-umkm-id="{{ $umkm->id }}">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="5" class="text-center">Belum
                                                                                ada produk untuk UMKM ini</td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="d-sm-flex align-items-center justify-content-center my-4">
                                            <button type="button" class="btn btn-primary btn-md" id="add-umkm-btn">
                                                <i class="fas fa-plus-circle mr-2"></i> Tambah UMKM Baru
                                            </button>
                                            <button type="submit" class="btn btn-success btn-md ml-2">
                                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-primary text-white">
                        <h5 class="modal-title" id="addProductModalLabel">Tambah Produk</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="add-product-form-edit">
                            @csrf
                            <input type="hidden" id="add_product_umkm_id" name="umkm_id">
                            <input type="hidden" id="product_id" name="id" value="">
                            <input type="hidden" id="editing_mode" value="add">
                            <input type="hidden" id="is_temp_product" value="0">

                            <div class="form-group">
                                <label for="add_product_jenis" class="font-weight-bold">Jenis Produk</label>
                                <input type="text" class="form-control" id="add_product_jenis" name="jenis_produk" required>
                                <div class="invalid-feedback">Jenis produk wajib diisi</div>
                            </div>

                            <div class="form-group">
                                <label for="add_product_tipe" class="font-weight-bold">Tipe Produk</label>
                                <select class="form-control" id="add_product_tipe" name="tipe_produk" required>
                                    <option value="Makanan dan Minuman">Makanan dan Minuman</option>
                                    <option value="Makanan">Makanan</option>
                                    <option value="Minuman">Minuman</option>
                                    <option value="Fashion">Fashion</option>
                                    <option value="Handycraft">Handycraft</option>
                                    <option value="Lainya">Lainya</option>
                                </select>
                                <div class="invalid-feedback">Tipe produk wajib diisi</div>
                            </div>

                            <div class="form-group">
                                <label for="add_product_status" class="font-weight-bold">Status</label>
                                <select class="form-control" id="add_product_status" name="status" required>
                                    <option value="AKTIF">AKTIF</option>
                                    <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                </select>
                                <div class="invalid-feedback">Status wajib dipilih</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" form="add-product-form-edit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
