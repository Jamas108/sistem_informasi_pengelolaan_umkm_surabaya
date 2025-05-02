@extends('layouts.pelakuumkm.app')
@push('scripts')
    <script>
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
                    kuotaText.textContent = `Kuota terisi: ${kuota - sisa} dari ${kuota} (${Math.round(percentageFilled)}%)`;

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
    @include('layouts.pelakuumkm.sidebar')
    <main class="main-content">
        <!-- Gradient Header -->
        <div class="bg-primary text-white py-4 shadow-sm">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="bg-opacity-20 rounded-circle p-3 me-3">
                                <i class="fas fa-plus-circle fa-2x text-white"></i>
                            </div>
                            <div>
                                <h2 class="mb-1 fw-bold">Tambah Intervensi Baru</h2>
                                <p class="mb-0 text-white-75">Catat dan lacak kegiatan pengembangan UMKM Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('pelakukelolaintervensi.index') }}"
                            class="btn btn-outline-light d-inline-flex align-items-center">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4 py-4">
            <div class="row">
                <div class="col-xl-8 col-lg-10 mx-auto">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Kesalahan Validasi!</strong> Silakan periksa kembali input Anda.
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-white py-4 border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-soft-primary rounded-circle p-3 me-3">
                                    <i class="fas fa-briefcase text-primary fa-2x"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-bold text-primary">Formulir Intervensi</h4>
                                    <p class="text-muted mb-0">Lengkapi informasi detail kegiatan intervensi UMKM</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('pelakukelolaintervensi.store') }}" method="POST"
                                class="needs-validation" novalidate>
                                @csrf

                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="umkm_id" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-store me-2 text-primary"></i>
                                                <span>Pilih UMKM</span>
                                            </label>
                                            <div class="position-relative">
                                                <select class="form-control" id="umkm_id" name="umkm_id" required>
                                                    <option value="">Pilih UMKM yang akan diintervensi</option>
                                                    @foreach ($umkms as $umkm)
                                                        <option value="{{ $umkm->id }}"
                                                            data-sector="{{ $umkm->sektor_usaha }}"
                                                            {{ old('umkm_id') == $umkm->id ? 'selected' : '' }}>
                                                            {{ $umkm->nama_usaha }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Harap pilih UMKM untuk intervensi
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="kegiatan_id" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-clipboard-list me-2 text-primary"></i>
                                                <span>Pilih Kegiatan</span>
                                            </label>
                                            <div class="position-relative">
                                                <select class="form-control" id="kegiatan_id" name="kegiatan_id" required>
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
                                                                $existingInterventions >= $kegiatan->kuota_pendaftaran;
                                                            $status = $kegiatan->status_kegiatan;
                                                            $isRegistrationAllowed = $status === 'Pendaftaran';

                                                            // Prepare status label
                                                            $statusLabel = '';
                                                            if ($status === 'Belum Dimulai') {
                                                                $statusLabel = '- Pendaftaran Belum Dibuka';
                                                            } elseif ($status === 'Sedang Berlangsung') {
                                                                $statusLabel = '- Kegiatan Sedang Berlangsung';
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
                                                <div class="invalid-tooltip">
                                                    Harap pilih kegiatan intervensi
                                                </div>
                                            </div>
                                            <div id="kuota-info" class="mt-2" style="display: none;">
                                                <div class="progress" style="height: 20px;">
                                                    <div id="kuota-progress" class="progress-bar" role="progressbar"
                                                        style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <small id="kuota-text" class="text-muted"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="jenis_kegiatan" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                <span>Jenis Kegiatan Intervensi</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="jenis_kegiatan"
                                                    name="jenis_kegiatan" value="{{ old('jenis_kegiatan') }}" readonly>
                                                <div class="invalid-tooltip">
                                                    Harap pilih jenis kegiatan intervensi
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="lokasi_kegiatan" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                <span>Lokasi Kegiatan Intervensi</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="lokasi_kegiatan"
                                                    name="lokasi_kegiatan" value="{{ old('lokasi_kegiatan') }}" readonly>
                                                <div class="invalid-tooltip">
                                                    Harap pilih jenis kegiatan intervensi
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_mulai" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                <span>Tanggal Mulai</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="tanggal_mulai"
                                                    name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" readonly>
                                                <div class="invalid-tooltip">
                                                    Harap pilih jenis kegiatan intervensi
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_selesai" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                <span>Tanggal Selesai</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="tanggal_selesai"
                                                    name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" readonly>
                                                <div class="invalid-tooltip">
                                                    Harap pilih jenis kegiatan intervensi
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jam_mulai" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                <span>Waktu Mulai</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="jam_mulai"
                                                    name="jam_mulai" value="{{ old('jam_mulai') }}" readonly>
                                                <div class="invalid-tooltip">
                                                    Harap pilih jenis kegiatan intervensi
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jam_selesai" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                <span>Waktu Selesai</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="jam_selesai"
                                                    name="jam_selesai" value="{{ old('jam_selesai') }}" readonly>
                                                <div class="invalid-tooltip">
                                                    Harap pilih jenis kegiatan intervensi
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="card-footer bg-light p-4">
                            <div class="alert alert-info mb-4">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle fa-2x me-3"></i>
                                    </div>
                                    <div class="ml-2">
                                        <h5 class="alert-heading">Informasi Pendaftaran</h5>
                                        <p class="mb-0">Pendaftaran kegiatan hanya dapat dilakukan ketika status kegiatan <strong>"Pendaftaran Dibuka"</strong>. Kegiatan dengan status <strong>"Belum Dimulai"</strong> tidak dapat dipilih.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('pelakukelolaintervensi.index') }}"
                                    class="btn btn-outline-secondary btn-md">
                                    <i class="fas fa-times me-2"></i>
                                    <span>Batal</span>
                                </a>
                                <button type="submit" class="btn btn-primary btn-md">
                                    <i class="fas fa-save me-2"></i>
                                    <span>Simpan Intervensi</span>
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

    <style>
        /* Custom background for soft colors */
        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .main-content {
            margin-left: 220px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        /* Enhanced select styling */
        .form-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Tooltip positioning for validation */
        .was-validated .form-select:invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
        }

        .was-validated .form-select:invalid~.invalid-tooltip,
        .form-select.is-invalid~.invalid-tooltip {
            display: block;
        }

        /* Progress bar styling */
        .progress {
            background-color: #e9ecef;
            border-radius: 0.25rem;
            overflow: hidden;
        }

        .progress-bar {
            background-color: #0d6efd;
            color: #fff;
            text-align: center;
            transition: width 0.6s ease;
        }

        /* Disabled option styling */
        select option:disabled {
            color: #6c757d;
            font-style: italic;
        }
    </style>
@endsection