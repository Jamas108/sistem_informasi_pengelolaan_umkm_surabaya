    @extends('layouts.app')
    @push('scripts')
        <script src="{{ asset('js/umkm-form.js') }}"></script>
        <script src="{{ asset('js/umkm-legalitas.js') }}"></script>
        <script src="{{ asset('js/umkm-omset.js') }}"></script>
        <script src="{{ asset('js/umkm-intervensi.js') }}"></script>
        <script>
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

            document.addEventListener('DOMContentLoaded', function() {
                // Bootstrap form validation
                (function() {
                    'use strict';
                    window.addEventListener('load', function() {
                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        var forms = document.getElementsByClassName('needs-validation');

                        // Loop over them and prevent submission
                        var validation = Array.prototype.filter.call(forms, function(form) {
                            form.addEventListener('submit', function(event) {
                                if (form.checkValidity() === false) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                }
                                form.classList.add('was-validated');
                            }, false);
                        });
                    }, false);
                })();

                // Dynamic Jenis Kegiatan Population
                const kegiatanSelect = document.getElementById('kegiatan_id');
                const jenisKegiatanInput = document.getElementById('jenis_kegiatan');
                const lokasiKegiatanInput = document.getElementById('lokasi_kegiatan');
                const tanggalMulaiKegiatanInput = document.getElementById('tanggal_mulai');
                const tanggalSelesaiKegiatanInput = document.getElementById('tanggal_selesai');
                const jamMulaiKegiatanInput = document.getElementById('jam_mulai');
                const jamSelesaiKegiatanInput = document.getElementById('jam_selesai');
                const kuotaInfoDiv = document.getElementById('kuota-info');
                const kuotaProgressBar = document.getElementById('kuota-progress');
                const kuotaText = document.getElementById('kuota-text');
                const statusInfoDiv = document.getElementById('status-info');

                // Handle initial page load if a kegiatan is pre-selected
                function updateJenisKegiatan() {
                    const selectedOption = kegiatanSelect.options[kegiatanSelect.selectedIndex];
                    const jenisKegiatan = selectedOption.getAttribute('data-jenis');
                    jenisKegiatanInput.value = jenisKegiatan || '';
                    jenisKegiatanInput.readOnly = true;
                }

                function updateLokasiKegiatan() {
                    const selectedOption = kegiatanSelect.options[kegiatanSelect.selectedIndex];
                    const lokasiKegiatan = selectedOption.getAttribute('data-lokasi');
                    lokasiKegiatanInput.value = lokasiKegiatan || '';
                    lokasiKegiatanInput.readOnly = true;
                }

                function updateTanggalMulaiKegiatan() {
                    const selectedOption = kegiatanSelect.options[kegiatanSelect.selectedIndex];
                    const tanggalMulaiKegiatan = selectedOption.getAttribute('data-tanggal-mulai');
                    tanggalMulaiKegiatanInput.value = tanggalMulaiKegiatan || '';
                    tanggalMulaiKegiatanInput.readOnly = true;
                }

                function updateTanggalSelesaiKegiatan() {
                    const selectedOption = kegiatanSelect.options[kegiatanSelect.selectedIndex];
                    const tanggalSelesaiKegiatan = selectedOption.getAttribute('data-tanggal-selesai');
                    tanggalSelesaiKegiatanInput.value = tanggalSelesaiKegiatan || '';
                    tanggalSelesaiKegiatanInput.readOnly = true;
                }

                function updateJamMulaiKegiatan() {
                    const selectedOption = kegiatanSelect.options[kegiatanSelect.selectedIndex];
                    const jamMulaiKegiatan = selectedOption.getAttribute('data-jam-mulai');
                    jamMulaiKegiatanInput.value = jamMulaiKegiatan || '';
                    jamMulaiKegiatanInput.readOnly = true;
                }

                function updateJamSelesaiKegiatan() {
                    const selectedOption = kegiatanSelect.options[kegiatanSelect.selectedIndex];
                    const jamSelesaiKegiatan = selectedOption.getAttribute('data-jam-selesai');
                    jamSelesaiKegiatanInput.value = jamSelesaiKegiatan || '';
                    jamSelesaiKegiatanInput.readOnly = true;
                }

                // Function to update kuota information
                function updateKuotaInfo() {
                    if (kegiatanSelect.selectedIndex > 0) {
                        const selectedOption = kegiatanSelect.options[kegiatanSelect.selectedIndex];
                        const kuota = parseInt(selectedOption.getAttribute('data-kuota') || 0);
                        const sisa = parseInt(selectedOption.getAttribute('data-sisa') || 0);
                        const percentageFilled = ((kuota - sisa) / kuota) * 100;

                        // Update progress bar
                        kuotaProgressBar.style.width = percentageFilled + '%';
                        kuotaText.textContent =
                            `Kuota terisi: ${kuota - sisa} dari ${kuota} (${Math.round(percentageFilled)}%)`;

                        // Show kuota info
                        kuotaInfoDiv.style.display = 'block';
                    } else {
                        kuotaInfoDiv.style.display = 'none';
                    }
                }

                // Run on initial page load if an option is pre-selected
                if (kegiatanSelect.selectedIndex > 0) {
                    updateJenisKegiatan();
                    updateLokasiKegiatan();
                    updateTanggalMulaiKegiatan();
                    updateTanggalSelesaiKegiatan();
                    updateJamMulaiKegiatan();
                    updateJamSelesaiKegiatan();
                    updateKuotaInfo();
                }

                // Add change event listener
                kegiatanSelect.addEventListener('change', function() {
                    updateJenisKegiatan();
                    updateLokasiKegiatan();
                    updateTanggalMulaiKegiatan();
                    updateTanggalSelesaiKegiatan();
                    updateJamMulaiKegiatan();
                    updateJamSelesaiKegiatan();
                    updateKuotaInfo();
                });
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
                                    <a class="nav-link active" id="pelaku-tab" data-toggle="tab" href="#pelaku"
                                        role="tab" aria-controls="pelaku" aria-selected="true">Data Pelaku UMKM</a>
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
                                    <a class="nav-link" id="legalitas-tab" data-toggle="tab" href="#legalitas"
                                        role="tab" aria-controls="legalitas" aria-selected="false">Legalitas</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="intervensi-tab" data-toggle="tab" href="#intervensi"
                                        role="tab" aria-controls="intervensi" aria-selected="false">Intervensi</a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <form action="{{ route('dataumkm.update', $pelakuUmkm->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Debug Form Values -->
                                <div style="display: none;" id="debug-form-values">
                                    <pre>{{ json_encode($pelakuUmkm->toArray(), JSON_PRETTY_PRINT) }}</pre>
                                </div>
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
                                                    <label for="nik_pemilik"
                                                        class="col-sm-3 col-form-label font-weight-bold">NIK Pemilik</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" class="form-control" id="nik"
                                                            name="nik" value="{{ $pelakuUmkm->nik }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="nama_pemilik"
                                                        class="col-sm-3 col-form-label font-weight-bold">Nama
                                                        Pemilik</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="nama_lengkap"
                                                            name="nama_lengkap" value="{{ $pelakuUmkm->nama_lengkap }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="no_kk_pemilik"
                                                        class="col-sm-3 col-form-label font-weight-bold">No KK
                                                        Pemilik</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" class="form-control" id="no_kk"
                                                            name="no_kk" value="{{ $pelakuUmkm->no_kk }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="tempat_lahir"
                                                        class="col-sm-3 col-form-label font-weight-bold">Tempat
                                                        Lahir</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="tempat_lahir"
                                                            name="tempat_lahir" value="{{ $pelakuUmkm->tempat_lahir }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="tanggal_lahir"
                                                        class="col-sm-3 col-form-label font-weight-bold">Tanggal
                                                        Lahir</label>
                                                    <div class="col-sm-9">
                                                        <input type="date" class="form-control" id="tgl_lahir"
                                                            name="tgl_lahir" value="{{ $pelakuUmkm->tgl_lahir }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="jenis_kelamin"
                                                        class="col-sm-3 col-form-label font-weight-bold">Jenis
                                                        Kelamin</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="jenis_kelamin"
                                                            name="jenis_kelamin">
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
                                                    <label for="status"
                                                        class="col-sm-3 col-form-label font-weight-bold">Status
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
                                                        <input type="number" class="form-control" id="rw"
                                                            name="rw" value="{{ $pelakuUmkm->rw }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="rt"
                                                        class="col-sm-3 col-form-label font-weight-bold">RT</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" class="form-control" id="rt"
                                                            name="rt" value="{{ $pelakuUmkm->rt }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="telp"
                                                        class="col-sm-3 col-form-label font-weight-bold">Telp</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" class="form-control" id="no_telp"
                                                            name="no_telp" value="{{ $pelakuUmkm->no_telp }}">
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
                                                    <button type="submit" class="btn btn-success btn-md main-submit-btn">
                                                        <i class="fas fa-save mr-2"></i> Simpan Data Pelaku
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab 2: Data UMKM (Modified for Multiple Entries) -->
                                    <!-- Tab 2: Data UMKM (Enhanced Professional Design) -->
                                    <div class="tab-pane fade" id="umkm" role="tabpanel"
                                        aria-labelledby="umkm-tab">
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
                                                                <h5 class="m-0 umkm-number font-weight-bold text-primary">
                                                                    UMKM
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
                                                                                <option value="PERSEORANGAN / MANDIRIx"
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
                                                                        <label
                                                                            for="klasifikasi_kinerja_usaha_{{ $index }}"
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
                                                                        <label
                                                                            for="jumlah_tenaga_kerja_{{ $index }}"
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
                                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                                        <h5 class="m-0 font-weight-bold text-primary">Produk UMKM</h5>
                                                                        <button type="button" class="btn btn-sm btn-primary add-product-btn" data-umkm-id="{{ $umkm->id }}">
                                                                            <i class="fas fa-plus-circle"></i> Tambah Produk
                                                                        </button>
                                                                    </div>

                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-striped" id="products-table-{{ $umkm->id }}">
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
                                                                                    <tr id="product-{{ $produk->id }}" data-product-id="{{ $produk->id }}">
                                                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                                                        <td>{{ $produk->jenis_produk }}</td>
                                                                                        <td>{{ $produk->tipe_produk }}</td>
                                                                                        <td>
                                                                                            @if($produk->status == 'AKTIF')
                                                                                                <span class="badge badge-success">AKTIF</span>
                                                                                            @else
                                                                                                <span class="badge badge-danger">TIDAK AKTIF</span>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <button type="button" class="btn btn-sm btn-warning edit-product-btn"
                                                                                                data-product-id="{{ $produk->id }}"
                                                                                                data-umkm-id="{{ $umkm->id }}"
                                                                                                data-jenis-produk="{{ $produk->jenis_produk }}"
                                                                                                data-tipe-produk="{{ $produk->tipe_produk }}"
                                                                                                data-status="{{ $produk->status }}">
                                                                                                <i class="fas fa-edit"></i>
                                                                                            </button>
                                                                                            <button type="button" class="btn btn-sm btn-danger delete-product-btn"
                                                                                                data-product-id="{{ $produk->id }}"
                                                                                                data-umkm-id="{{ $umkm->id }}">
                                                                                                <i class="fas fa-trash"></i>
                                                                                            </button>
                                                                                        </td>
                                                                                    </tr>
                                                                                @empty
                                                                                    <tr>
                                                                                        <td colspan="5" class="text-center">Belum ada produk untuk UMKM ini</td>
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
                                                    <button type="button" class="btn btn-primary btn-md"
                                                        id="add-umkm-btn">
                                                        <i class="fas fa-plus-circle mr-2"></i> Tambah UMKM Baru
                                                    </button>
                                                    <button type="submit" class="btn btn-success btn-md ml-2">
                                                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="omset" role="tabpanel"
                                        aria-labelledby="omset-tab">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-gradient-primary text-white py-3">
                                                <h5 class="m-0 font-weight-bold">Data Omset UMKM</h5>
                                            </div>
                                            <div class="card-body p-4">
                                                <!-- Form for adding omset data -->
                                                <div id="omset-form-container" class="mb-4">
                                                    <!-- UMKM Selection -->
                                                    <div class="row mb-3">
                                                        <label for="umkm_id"
                                                            class="col-sm-3 col-form-label font-weight-bold">Pilih
                                                            UMKM</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" id="umkm_id"
                                                                name="omset[umkm_id]">
                                                                <option value="">-- Pilih UMKM --</option>
                                                                @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                    <option value="{{ $umkm->id }}">
                                                                        {{ $umkm->nama_usaha }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Jangka Waktu -->
                                                    <div class="row mb-3">
                                                        <label for="jangka_waktu"
                                                            class="col-sm-3 col-form-label font-weight-bold">Jangka
                                                            Waktu</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" class="form-control" id="jangka_waktu"
                                                                name="omset[jangka_waktu]">
                                                        </div>
                                                    </div>

                                                    <!-- Nilai Omset -->
                                                    <div class="row mb-3">
                                                        <label for="omset"
                                                            class="col-sm-3 col-form-label font-weight-bold">Nilai
                                                            Omset</label>
                                                        <div class="col-sm-9">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input type="text" class="form-control currency-input"
                                                                    id="total_omset" name="omset[total_omset]"
                                                                    placeholder="0">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Keterangan -->
                                                    <div class="row mb-3">
                                                        <label for="keterangan"
                                                            class="col-sm-3 col-form-label font-weight-bold">Keterangan</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" id="keterangan"
                                                                name="omset[keterangan]">
                                                                <option value="">-- Pilih Status --</option>
                                                                <option value="AKTIF">AKTIF</option>
                                                                <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-12 text-center">
                                                            <button type="button" class="btn btn-md btn-success"
                                                                id="tambah-data-omset">
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
                                                                <table class="table table-bordered" id="table-omset"
                                                                    width="100%" cellspacing="0">
                                                                    <thead class="bg-light">
                                                                        <tr>
                                                                            <th class="text-center" width="5%">NO</th>
                                                                            <th width="15%">UMKM</th>
                                                                            <th width="15%">Jangka Waktu</th>
                                                                            <th width="20%">Nilai Omset</th>
                                                                            <th width="15%">Keterangan</th>

                                                                            <th class="text-center" width="10%">Aksi
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if (isset($omsetData) && count($omsetData) > 0)
                                                                            @foreach ($omsetData as $index => $item)
                                                                                <tr>
                                                                                    <td class="text-center">
                                                                                        {{ $index + 1 }}</td>
                                                                                    <td>{{ $item->dataUmkm->nama_usaha }}
                                                                                    </td>
                                                                                    <td>{{ date('d-m-Y', strtotime($item->jangka_waktu)) }}
                                                                                    </td>
                                                                                    <td>Rp.
                                                                                        {{ number_format($item->omset, 0, ',', '.') }}
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($item->keterangan == 'AKTIF')
                                                                                            <span
                                                                                                class="badge badge-success">AKTIF</span>
                                                                                            @elseif ($item->keterangan == 'TIDAK AKTIF')
                                                                                            <span
                                                                                                class="badge badge-danger">TIDAK
                                                                                                AKTIF</span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <button type="button"
                                                                                            class="btn btn-warning btn-sm edit-omset"
                                                                                            data-id="{{ $item->id }}">
                                                                                            <i class="fas fa-edit"></i>
                                                                                            Edit
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @else
                                                                            <tr>
                                                                                <td colspan="6" class="text-center">
                                                                                    Belum
                                                                                    ada data omset</td>
                                                                            </tr>
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Modal for editing omset -->
                                                <div class="modal fade" id="editOmsetModal" tabindex="-1"
                                                    role="dialog" aria-labelledby="editOmsetModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-gradient-primary text-white">
                                                                <h5 class="modal-title" id="editOmsetModalLabel">Edit Data
                                                                    Omset</h5>
                                                                <button type="button" class="close text-white"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="edit-omset-form">
                                                                    <input type="hidden" id="edit_omset_id"
                                                                        name="id">

                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_umkm_id"
                                                                            class="col-sm-3 col-form-label">UMKM</label>
                                                                        <div class="col-sm-9">
                                                                            <select class="form-control" id="edit_umkm_id"
                                                                                name="umkm_id">
                                                                                @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                                    <option value="{{ $umkm->id }}">
                                                                                        {{ $umkm->nama_usaha }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_jangka_waktu"
                                                                            class="col-sm-3 col-form-label">Jangka
                                                                            Waktu</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="date" class="form-control"
                                                                                id="edit_jangka_waktu"
                                                                                name="jangka_waktu">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_omset"
                                                                            class="col-sm-3 col-form-label">Nilai
                                                                            Omset</label>
                                                                        <div class="col-sm-9">
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span
                                                                                        class="input-group-text">Rp.</span>
                                                                                </div>
                                                                                <input type="text"
                                                                                    class="form-control currency-input"
                                                                                    id="edit_omset" name="omset">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_keterangan"
                                                                            class="col-sm-3 col-form-label">Keterangan</label>
                                                                        <div class="col-sm-9">
                                                                            <select class="form-control"
                                                                                id="edit_keterangan" name="keterangan">
                                                                                <option value="AKTIF">AKTIF</option>
                                                                                <option value="TIDAK AKTIF">TIDAK AKTIF
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Batal</button>
                                                                <button type="button" class="btn btn-primary"
                                                                    id="save-edit-omset">Simpan Perubahan</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab 5: Legalitas UMKM -->
                                    <div class="tab-pane fade" id="legalitas" role="tabpanel"
                                        aria-labelledby="legalitas-tab">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-gradient-primary text-white py-3">
                                                <h5 class="m-0 font-weight-bold">Data Legalitas UMKM</h5>
                                            </div>
                                            <div class="card-body p-4">
                                                <!-- UMKM Selection for Legalitas -->
                                                <div class="row mb-4">
                                                    <label for="legalitas_umkm_id"
                                                        class="col-sm-3 col-form-label font-weight-bold">Pilih UMKM</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" id="legalitas_umkm_id"
                                                            name="legalitas[umkm_id]">
                                                            <option value="">-- Pilih UMKM --</option>
                                                            @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                <option value="{{ $umkm->id }}">
                                                                    {{ $umkm->nama_usaha }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Container for legalitas form -->
                                                <div id="legalitas-container">
                                                    <div class="row mb-3">
                                                        <label for="no_sk_nib"
                                                            class="col-sm-3 col-form-label font-weight-bold">No SK
                                                            NIB</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="no_sk_nib"
                                                                name="legalitas[no_sk_nib]"
                                                                value="{{ $pelakuUmkm->legalitas->no_sk_nib ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="no_sk_siup"
                                                            class="col-sm-3 col-form-label font-weight-bold">No SK
                                                            SIUP</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="no_sk_siup"
                                                                name="legalitas[no_sk_siup]"
                                                                value="{{ $pelakuUmkm->legalitas->no_sk_siup ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="no_sk_tdp"
                                                            class="col-sm-3 col-form-label font-weight-bold">No SK
                                                            TDP</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="no_sk_tdp"
                                                                name="legalitas[no_sk_tdp]"
                                                                value="{{ $pelakuUmkm->legalitas->no_sk_tdp ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="no_sk_pirt"
                                                            class="col-sm-3 col-form-label font-weight-bold">No SK
                                                            PIRT</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="no_sk_pirt"
                                                                name="legalitas[no_sk_pirt]"
                                                                value="{{ $pelakuUmkm->legalitas->no_sk_pirt ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="no_sk_bpom"
                                                            class="col-sm-3 col-form-label font-weight-bold">No SK
                                                            BPOM</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="no_sk_bpom"
                                                                name="legalitas[no_sk_bpom]"
                                                                value="{{ $pelakuUmkm->legalitas->no_sk_bpom ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="no_sk_halal"
                                                            class="col-sm-3 col-form-label font-weight-bold">No SK
                                                            HALAL</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="no_sk_halal"
                                                                name="legalitas[no_sk_halal]"
                                                                value="{{ $pelakuUmkm->legalitas->no_sk_halal ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="no_sk_merek"
                                                            class="col-sm-3 col-form-label font-weight-bold">No SK
                                                            MEREK</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="no_sk_merek"
                                                                name="legalitas[no_sk_merek]"
                                                                value="{{ $pelakuUmkm->legalitas->no_sk_merek ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="no_sk_haki"
                                                            class="col-sm-3 col-form-label font-weight-bold">No SK
                                                            HAKI</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="no_sk_haki"
                                                                name="legalitas[no_sk_haki]"
                                                                value="{{ $pelakuUmkm->legalitas->no_sk_haki ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="no_surat_keterangan"
                                                            class="col-sm-3 col-form-label font-weight-bold">No Surat
                                                            Keterangan</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control"
                                                                id="no_surat_keterangan"
                                                                name="legalitas[no_surat_keterangan]"
                                                                value="{{ $pelakuUmkm->legalitas->no_surat_keterangan ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-12 text-center">
                                                            <button type="button" class="btn btn-success btn-md"
                                                                id="simpan-legalitas">
                                                                <i class="fas fa-save mr-2"></i> Simpan Legalitas
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="mt-5">
                                                        <div class="card border-left-primary shadow">
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered"
                                                                        id="table-legalitas" width="100%"
                                                                        cellspacing="0">
                                                                        <thead class="bg-light">
                                                                            <tr>
                                                                                <th class="text-center" width="5%">NO
                                                                                </th>
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
                                                                                <th>Aksi</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if (isset($legalitasData) && count($legalitasData) > 0)
                                                                                @foreach ($legalitasData as $index => $item)
                                                                                    <tr>
                                                                                        <td class="text-center">
                                                                                            {{ $index + 1 }}</td>
                                                                                        <td>{{ $item->dataUmkm->nama_usaha ?? 'Tidak ada' }}
                                                                                        </td>
                                                                                        <td>{{ $item->no_sk_nib ?: '-' }}
                                                                                        </td>
                                                                                        <td>{{ $item->no_sk_siup ?: '-' }}
                                                                                        </td>
                                                                                        <td>{{ $item->no_sk_tdp ?: '-' }}
                                                                                        </td>
                                                                                        <td>{{ $item->no_sk_pirt ?: '-' }}
                                                                                        </td>
                                                                                        <td>{{ $item->no_sk_bpom ?: '-' }}
                                                                                        </td>
                                                                                        <td>{{ $item->no_sk_halal ?: '-' }}
                                                                                        </td>
                                                                                        <td>{{ $item->no_sk_merk ?: '-' }}
                                                                                        </td>
                                                                                        <td>{{ $item->no_sk_haki ?: '-' }}
                                                                                        </td>
                                                                                        <td>{{ $item->no_surat_keterangan ?: '-' }}
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <button type="button"
                                                                                                class="btn btn-warning btn-sm edit-legalitas"
                                                                                                data-id="{{ $item->id }}">
                                                                                                <i class="fas fa-edit"></i>
                                                                                                Edit
                                                                                            </button>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            @else
                                                                                <tr>
                                                                                    <td colspan="7"
                                                                                        class="text-center">
                                                                                        Belum ada data legalitas</td>
                                                                                </tr>
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal for editing legalitas -->
                                                    <div class="modal fade" id="editLegalitasModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="editLegalitasModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-gradient-primary text-white">
                                                                    <h5 class="modal-title" id="editLegalitasModalLabel">
                                                                        Edit
                                                                        Data Legalitas</h5>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="edit-legalitas-form">
                                                                        <input type="hidden" id="edit_legalitas_id"
                                                                            name="id">

                                                                        <div class="form-group row">
                                                                            <label for="edit_legalitas_umkm_id"
                                                                                class="col-sm-3 col-form-label">UMKM</label>
                                                                            <div class="col-sm-9">
                                                                                <select class="form-control"
                                                                                    id="edit_legalitas_umkm_id"
                                                                                    name="umkm_id">
                                                                                    @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                                        <option
                                                                                            value="{{ $umkm->id }}">
                                                                                            {{ $umkm->nama_usaha }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="edit_no_sk_nib"
                                                                                class="col-sm-3 col-form-label">No SK
                                                                                NIB</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" class="form-control"
                                                                                    id="edit_no_sk_nib" name="no_sk_nib">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="edit_no_sk_siup"
                                                                                class="col-sm-3 col-form-label">No SK
                                                                                SIUP</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" class="form-control"
                                                                                    id="edit_no_sk_siup"
                                                                                    name="no_sk_siup">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="edit_no_sk_tdp"
                                                                                class="col-sm-3 col-form-label">No SK
                                                                                TDP</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" class="form-control"
                                                                                    id="edit_no_sk_tdp" name="no_sk_tdp">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="edit_no_sk_pirt"
                                                                                class="col-sm-3 col-form-label">No SK
                                                                                PIRT</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" class="form-control"
                                                                                    id="edit_no_sk_pirt"
                                                                                    name="no_sk_pirt">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="edit_no_sk_bpom"
                                                                                class="col-sm-3 col-form-label">No SK
                                                                                BPOM</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" class="form-control"
                                                                                    id="edit_no_sk_bpom"
                                                                                    name="no_sk_bpom">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="edit_no_sk_halal"
                                                                                class="col-sm-3 col-form-label">No SK
                                                                                HALAL</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" class="form-control"
                                                                                    id="edit_no_sk_halal"
                                                                                    name="no_sk_halal">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="edit_no_sk_merek"
                                                                                class="col-sm-3 col-form-label">No SK
                                                                                MEREK</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" class="form-control"
                                                                                    id="edit_no_sk_merek"
                                                                                    name="no_sk_merek">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="edit_no_sk_haki"
                                                                                class="col-sm-3 col-form-label">No SK
                                                                                HAKI</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" class="form-control"
                                                                                    id="edit_no_sk_haki"
                                                                                    name="no_sk_haki">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="edit_no_surat_keterangan"
                                                                                class="col-sm-3 col-form-label">No Surat
                                                                                Keterangan</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" class="form-control"
                                                                                    id="edit_no_surat_keterangan"
                                                                                    name="no_surat_keterangan">
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Batal</button>
                                                                    <button type="button" class="btn btn-primary"
                                                                        id="save-edit-legalitas">Simpan Perubahan</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab 4: Intervensi UMKM -->
                                    <div class="tab-pane fade" id="intervensi" role="tabpanel"
                                        aria-labelledby="intervensi-tab">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-gradient-primary text-white py-3">
                                                <h5 class="m-0 font-weight-bold">Data Intervensi UMKM</h5>
                                            </div>
                                            <div class="card-body p-4">
                                                <!-- Form for adding intervention data -->
                                                <div id="intervensi-form-container" class="mb-4">
                                                    <!-- UMKM Selection for Intervensi -->
                                                    <div class="row mb-3">
                                                        <label for="intervensi_umkm_id"
                                                            class="col-sm-3 col-form-label font-weight-bold">Pilih
                                                            UMKM</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" id="intervensi_umkm_id"
                                                                name="intervensi[umkm_id]">
                                                                <option value="">-- Pilih UMKM --</option>
                                                                @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                    <option value="{{ $umkm->id }}">
                                                                        {{ $umkm->nama_usaha }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="intervensi_umkm_id"
                                                            class="col-sm-3 col-form-label font-weight-bold">Pilih
                                                            UMKM</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" id="kegiatan_id"
                                                                name="kegiatan_id">
                                                                <option value="">Pilih Kegiatan Intervensi</option>
                                                                @foreach ($kegiatans as $kegiatan)
                                                                    @php
                                                                        $existingInterventions = \App\Models\Intervensi::where(
                                                                            'kegiatan_id',
                                                                            $kegiatan->id,
                                                                        )->count();
                                                                        $quotaPercentage =
                                                                            ($existingInterventions /
                                                                                $kegiatan->kuota_pendaftaran) *
                                                                            100;
                                                                        $isQuotaFull =
                                                                            $existingInterventions >=
                                                                            $kegiatan->kuota_pendaftaran;
                                                                        $status = $kegiatan->status_kegiatan;
                                                                        $isRegistrationAllowed =
                                                                            $status === 'Pendaftaran';

                                                                        // Prepare status label
                                                                        $statusLabel = '';
                                                                        if ($status === 'Belum Dimulai') {
                                                                            $statusLabel = '- Pendaftaran Belum Dibuka';
                                                                        } elseif ($status === 'Sedang Berlangsung') {
                                                                            $statusLabel =
                                                                                '- Kegiatan Sedang Berlangsung';
                                                                        } elseif ($status === 'Selesai') {
                                                                            $statusLabel = '- Kegiatan Telah Selesai';
                                                                        }
                                                                    @endphp
                                                                    <option value="{{ $kegiatan->id }}"
                                                                        data-jenis="{{ $kegiatan->jenis_kegiatan }}"
                                                                        data-lokasi="{{ $kegiatan->lokasi_kegiatan }}"
                                                                        data-tanggal-mulai="{{ $kegiatan->tanggal_mulai }}"
                                                                        data-tanggal-selesai="{{ $kegiatan->tanggal_mulai }}"
                                                                        data-jam-mulai="{{ $kegiatan->jam_mulai }}"
                                                                        data-jam-selesai="{{ $kegiatan->jam_selesai }}"
                                                                        data-kuota="{{ $kegiatan->kuota_pendaftaran }}"
                                                                        data-sisa="{{ $kegiatan->kuota_pendaftaran - $existingInterventions }}"
                                                                        data-status="{{ $status }}"
                                                                        {{ $isQuotaFull || !$isRegistrationAllowed ? 'disabled' : '' }}
                                                                        {{ old('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}>
                                                                        {{ $kegiatan->nama_kegiatan }}
                                                                        ({{ $existingInterventions }}/{{ $kegiatan->kuota_pendaftaran }}
                                                                        Slot)
                                                                        {{ $statusLabel }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="jenis_kegiatan"
                                                            class="col-sm-3 col-form-label font-weight-bold">Kegiatan
                                                            Intervensi</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control"
                                                                id="jenis_kegiatan" name="jenis_kegiatan"
                                                                value="{{ old('jenis_kegiatan') }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="lokasi_kegiatan"
                                                            class="col-sm-3 col-form-label font-weight-bold">Lokasi
                                                            Kegiatan
                                                            Intervensi
                                                        </label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control"
                                                                id="lokasi_kegiatan" name="lokasi_kegiatan"
                                                                value="{{ old('lokasi_kegiatan') }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="lokasi_kegiatan"
                                                            class="col-sm-3 col-form-label font-weight-bold">Tanggal Mulai
                                                        </label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="tanggal_mulai"
                                                                name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                                                readonly>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="tanggal_selesai"
                                                            class="col-sm-3 col-form-label font-weight-bold">Tanggal
                                                            Selesai
                                                        </label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control"
                                                                id="tanggal_selesai" name="tanggal_selesai"
                                                                value="{{ old('tanggal_selesai') }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="jam_mulai"
                                                            class="col-sm-3 col-form-label font-weight-bold">Jam Mulai
                                                        </label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="jam_mulai"
                                                                name="jam_mulai" value="{{ old('jam_mulai') }}"
                                                                readonly>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label for="jam_selesai"
                                                            class="col-sm-3 col-form-label font-weight-bold">Jam Selesai
                                                        </label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="jam_selesai"
                                                                name="jam_selesai" value="{{ old('jam_selesai') }}"
                                                                readonly>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col-12 text-center">
                                                            <button type="button" class="btn btn-md btn-success"
                                                                id="tambah-data-intervensi">
                                                                <i class="fas fa-plus-circle mr-2"></i> Tambah Data
                                                                Intervensi
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Table of existing interventions -->
                                                <div class="mt-5">
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

                                                                            <th class="text-center" width="10%">Aksi
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <!-- Tabel akan diisi dari JavaScript -->
                                                                        <tr>
                                                                            <td colspan="8" class="text-center">Belum
                                                                                ada
                                                                                data intervensi</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Edit Intervensi Modal (Updated) -->
                                                <div class="modal fade" id="editIntervensiModal" tabindex="-1"
                                                    role="dialog" aria-labelledby="editIntervensiModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-gradient-primary text-white">
                                                                <h5 class="modal-title" id="editIntervensiModalLabel">Edit
                                                                    Data Intervensi</h5>
                                                                <button type="button" class="close text-white"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="edit-intervensi-form">
                                                                    <input type="hidden" id="edit_intervensi_id"
                                                                        name="id">

                                                                    <!-- UMKM Selection -->
                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_umkm_id"
                                                                            class="col-sm-3 col-form-label font-weight-bold">UMKM</label>
                                                                        <div class="col-sm-9">
                                                                            <select class="form-control" id="edit_umkm_id"
                                                                                name="umkm_id">
                                                                                @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                                    <option value="{{ $umkm->id }}">
                                                                                        {{ $umkm->nama_usaha }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Kegiatan Selection -->
                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_kegiatan_id"
                                                                            class="col-sm-3 col-form-label font-weight-bold">Kegiatan
                                                                            Intervensi</label>
                                                                        <div class="col-sm-9">
                                                                            <select class="form-control"
                                                                                id="edit_kegiatan_id" name="kegiatan_id">
                                                                                <!-- Options will be populated dynamically via AJAX -->
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Auto-populated fields based on Kegiatan selection -->
                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_jenis_kegiatan"
                                                                            class="col-sm-3 col-form-label font-weight-bold">Kegiatan
                                                                            Intervensi</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_jenis_kegiatan"
                                                                                name="jenis_kegiatan" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_lokasi_kegiatan"
                                                                            class="col-sm-3 col-form-label font-weight-bold">Lokasi
                                                                            Kegiatan Intervensi</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_lokasi_kegiatan"
                                                                                name="lokasi_kegiatan" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_tanggal_mulai"
                                                                            class="col-sm-3 col-form-label font-weight-bold">Tanggal
                                                                            Mulai</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_tanggal_mulai"
                                                                                name="tanggal_mulai" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_tanggal_selesai"
                                                                            class="col-sm-3 col-form-label font-weight-bold">Tanggal
                                                                            Selesai</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_tanggal_selesai"
                                                                                name="tanggal_selesai" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_jam_mulai"
                                                                            class="col-sm-3 col-form-label font-weight-bold">Jam
                                                                            Mulai</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_jam_mulai" name="jam_mulai"
                                                                                readonly>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_jam_selesai"
                                                                            class="col-sm-3 col-form-label font-weight-bold">Jam
                                                                            Selesai</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_jam_selesai" name="jam_selesai"
                                                                                readonly>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Read-only generated information -->
                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_no_pendaftaran"
                                                                            class="col-sm-3 col-form-label font-weight-bold">Nomor
                                                                            Pendaftaran</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_no_pendaftaran"
                                                                                name="no_pendaftaran_kegiatan" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Optional fields for additional data -->
                                                                    <div class="form-group row mb-3">
                                                                        <label for="edit_omset"
                                                                            class="col-sm-3 col-form-label font-weight-bold">Omset</label>
                                                                        <div class="col-sm-9">
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span
                                                                                        class="input-group-text">Rp.</span>
                                                                                </div>
                                                                                <input type="text"
                                                                                    class="form-control currency-input"
                                                                                    id="edit_omset" name="omset"
                                                                                    placeholder="0">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Batal</button>
                                                                <button type="button" class="btn btn-primary"
                                                                    id="save-edit-intervensi">
                                                                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
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

        <!-- Modal Tambah/Edit Produk -->
<!-- Modal Tambah/Edit Produk -->
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
                <form id="add-product-form">
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
                        <input type="text" class="form-control" id="add_product_tipe" name="tipe_produk" required>
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
                <button type="submit" form="add-product-form" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

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
