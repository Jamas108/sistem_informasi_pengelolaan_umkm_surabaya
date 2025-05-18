@extends('layouts.pelakuumkm.app')
@section('content')
    @include('layouts.pelakuumkm.sidebar')
    <main class="main-content">
        <!-- Header Section with Gradient Background -->
        <div class="text-white py-3 px-4 shadow-sm" id="nav" style="background: linear-gradient(145deg, #1c4970, #2F77B6);">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-store me-2"></i>
                        <span>Profil Anda</span>
                    </h4>
                    <p class="mb-0 fs-6">Kelola Informasi Pribadi Anda</p>
                </div>
                <div>
                    <button type="button" id="toggleEditMode" class="btn btn-light btn-sm px-3 shadow-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </button>
                </div>
            </div>
        </div>
        

        <!-- Content Section -->
        <div class="container-fluid py-4">
            <!-- Alerts -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    @if (session('success'))
                        <div class="alert custom-alert-success alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Berhasil!</strong> {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert custom-alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Gagal!</strong> {{ session('error') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- Profile Information -->
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm rounded-3 mb-4 profile-card">
                        <div class="card-body p-4">
                            <!-- Profile Summary -->
                            <div class="profile-summary mb-4">
                                <div class="row">
                                    <div class="col-md-12 text-center mb-4">
                                        <div class="profile-icon-wrapper mx-auto mb-3">
                                            <i class="fas fa-user fa-3x text-white"></i>
                                        </div>
                                        <h5 class="mb-1">{{ $pelakuUmkm->nama_lengkap }}</h5>
                                        <p class="text-muted">Pelaku UMKM</p>
                                    </div>
                                </div>
                                <hr class="styled-hr">
                            </div>

                            <form id="profileForm" action="{{ route('profil.update', $pelakuUmkm->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Information Categories -->
                                    <div class="col-md-12 mb-4">
                                        <div class="section-title">
                                            <i class="fas fa-id-card text-primary me-2"></i>
                                            Informasi Identitas
                                        </div>
                                    </div>

                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label text-muted small">Nama Lengkap</label>
                                            <div class="profile-field">
                                                <span class="profile-value">{{ $pelakuUmkm->nama_lengkap }}</span>
                                                <input type="text" name="nama_lengkap"
                                                    class="form-control profile-input d-none"
                                                    value="{{ $pelakuUmkm->nama_lengkap }}">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-muted small">NIK</label>
                                            <div class="profile-field">
                                                <span class="profile-value">{{ $pelakuUmkm->nik }}</span>
                                                <input type="text" name="nik"
                                                    class="form-control profile-input d-none"
                                                    value="{{ $pelakuUmkm->nik }}">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-muted small">No. KK</label>
                                            <div class="profile-field">
                                                <span class="profile-value">{{ $pelakuUmkm->no_kk }}</span>
                                                <input type="text" name="no_kk"
                                                    class="form-control profile-input d-none"
                                                    value="{{ $pelakuUmkm->no_kk }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label text-muted small">Tempat Lahir</label>
                                            <div class="profile-field">
                                                <span class="profile-value">{{ $pelakuUmkm->tempat_lahir }}</span>
                                                <input type="text" name="tempat_lahir"
                                                    class="form-control profile-input d-none"
                                                    value="{{ $pelakuUmkm->tempat_lahir }}">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-muted small">Tanggal Lahir</label>
                                            <div class="profile-field">
                                                <span class="profile-value">{{ $pelakuUmkm->tgl_lahir }}</span>
                                                <input type="date" name="tgl_lahir"
                                                    class="form-control profile-input d-none"
                                                    value="{{ $pelakuUmkm->tgl_lahir }}">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label text-muted small">Jenis Kelamin</label>
                                            <div class="profile-field">
                                                <span class="profile-value">{{ $pelakuUmkm->jenis_kelamin }}</span>
                                                <select name="jenis_kelamin" class="form-select profile-input d-none">
                                                    <option value="Laki-laki"
                                                        {{ $pelakuUmkm->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="Perempuan"
                                                        {{ $pelakuUmkm->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Information Categories -->
                                    <div class="col-md-12 mb-4 mt-2">
                                        <div class="section-title">
                                            <i class="fas fa-users text-primary me-2"></i>
                                            Informasi Keluarga
                                        </div>
                                    </div>

                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label text-muted small">Status Hubungan Keluarga</label>
                                            <div class="profile-field">
                                                <span
                                                    class="profile-value">{{ $pelakuUmkm->status_hubungan_keluarga }}</span>
                                                <select name="status_hubungan_keluarga"
                                                    class="form-select profile-input d-none">
                                                    <option value="Kepala Keluarga"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'Kepala Keluarga' ? 'selected' : '' }}>
                                                        Kepala Keluarga</option>
                                                    <option value="Istri"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'Istri' ? 'selected' : '' }}>
                                                        Istri</option>
                                                    <option value="Anak"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'Anak' ? 'selected' : '' }}>
                                                        Anak</option>
                                                    <option value="Lainnya"
                                                        {{ $pelakuUmkm->status_hubungan_keluarga == 'Lainnya' ? 'selected' : '' }}>
                                                        Lainnya</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label text-muted small">Status Perkawinan</label>
                                            <div class="profile-field">
                                                <span class="profile-value">{{ $pelakuUmkm->status_perkawinan }}</span>
                                                <select name="status_perkawinan" class="form-select profile-input d-none">
                                                    <option value="Belum Kawin"
                                                        {{ $pelakuUmkm->status_perkawinan == 'Belum Kawin' ? 'selected' : '' }}>
                                                        Belum Kawin</option>
                                                    <option value="Kawin"
                                                        {{ $pelakuUmkm->status_perkawinan == 'Kawin' ? 'selected' : '' }}>
                                                        Kawin</option>
                                                    <option value="Cerai Hidup"
                                                        {{ $pelakuUmkm->status_perkawinan == 'Cerai Hidup' ? 'selected' : '' }}>
                                                        Cerai Hidup</option>
                                                    <option value="Cerai Mati"
                                                        {{ $pelakuUmkm->status_perkawinan == 'Cerai Mati' ? 'selected' : '' }}>
                                                        Cerai Mati</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Information Categories -->
                                    <div class="col-md-12 mb-4 mt-2">
                                        <div class="section-title">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            Informasi Alamat
                                        </div>
                                    </div>

                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label text-muted small">Kelurahan</label>
                                            <div class="profile-field">
                                                <span class="profile-value">{{ $pelakuUmkm->kelurahan }}</span>
                                                <input type="text" name="kelurahan"
                                                    class="form-control profile-input d-none"
                                                    value="{{ $pelakuUmkm->kelurahan }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label text-muted small">RT</label>
                                                    <div class="profile-field">
                                                        <span class="profile-value">{{ $pelakuUmkm->rt }}</span>
                                                        <input type="text" name="rt"
                                                            class="form-control profile-input d-none"
                                                            value="{{ $pelakuUmkm->rt }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label text-muted small">RW</label>
                                                    <div class="profile-field">
                                                        <span class="profile-value">{{ $pelakuUmkm->rw }}</span>
                                                        <input type="text" name="rw"
                                                            class="form-control profile-input d-none"
                                                            value="{{ $pelakuUmkm->rw }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label text-muted small">Alamat Sesuai KTP</label>
                                            <div class="profile-field">
                                                <span class="profile-value">{{ $pelakuUmkm->alamat_sesuai_ktp }}</span>
                                                <textarea name="alamat_sesuai_ktp" class="form-control profile-input d-none" rows="4">{{ $pelakuUmkm->alamat_sesuai_ktp }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Information Categories -->
                                    <div class="col-md-12 mb-4 mt-2">
                                        <div class="section-title">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Informasi Lainnya
                                        </div>
                                    </div>

                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label text-muted small">No. Telepon</label>
                                            <div class="profile-field">
                                                <span class="profile-value">{{ $pelakuUmkm->no_telp }}</span>
                                                <input type="text" name="no_telp"
                                                    class="form-control profile-input d-none"
                                                    value="{{ $pelakuUmkm->no_telp }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label text-muted small">Pendidikan Terakhir</label>
                                            <div class="profile-field">
                                                <span class="profile-value">{{ $pelakuUmkm->pendidikan_terakhir }}</span>
                                                <select name="pendidikan_terakhir"
                                                    class="form-select profile-input d-none">
                                                    <option value="SD"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'SD' ? 'selected' : '' }}>SD
                                                    </option>
                                                    <option value="SMP"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'SMP' ? 'selected' : '' }}>
                                                        SMP</option>
                                                    <option value="SMA/SMK"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'SMA/SMK' ? 'selected' : '' }}>
                                                        SMA/SMK</option>
                                                    <option value="D3"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'D3' ? 'selected' : '' }}>D3
                                                    </option>
                                                    <option value="S1"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'S1' ? 'selected' : '' }}>S1
                                                    </option>
                                                    <option value="S2"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'S2' ? 'selected' : '' }}>S2
                                                    </option>
                                                    <option value="S3"
                                                        {{ $pelakuUmkm->pendidikan_terakhir == 'S3' ? 'selected' : '' }}>S3
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="action-buttons d-none mt-4" id="actionButtons">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary px-4 me-2">
                                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                                            </button>
                                            <button type="button" id="cancelEdit"
                                                class="btn btn-outline-secondary px-4">
                                                <i class="fas fa-times me-1"></i> Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- Password Change Section -->
                            <div class="col-md-12 mb-4 mt-2">
                                <div class="section-title">
                                    <i class="fas fa-key text-primary me-2"></i>
                                    Ubah Password
                                </div>
                            </div>

                            <div class="col-md-12">
                                <form action="{{ route('profil.updatePassword') }}" method="POST" class="mb-4">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="current_password" class="form-label text-muted small">Password
                                                    Saat Ini</label>
                                                <input type="password" id="current_password" name="current_password"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="password" class="form-label text-muted small">Password
                                                    Baru</label>
                                                <input type="password" id="password" name="password"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="password_confirmation"
                                                    class="form-label text-muted small">Konfirmasi Password Baru</label>
                                                <input type="password" id="password_confirmation"
                                                    name="password_confirmation" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fas fa-save me-1"></i> Perbarui Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        /* Profile Header */
        .profile-header {
            background: linear-gradient(135deg, #5281ab 0%, #3c6591 100%);
            border-bottom: 3px solid rgba(255, 255, 255, 0.1);
        }

        /* Profile Card */
        .profile-card {
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

        /* Profile Summary */
        .profile-icon-wrapper {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #5281ab 0%, #3c6591 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Styled horizontal rule */
        .styled-hr {
            border: 0;
            height: 1px;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(82, 129, 171, 0.5), rgba(0, 0, 0, 0));
            margin: 1.5rem 0;
        }

        /* Section title */
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #345676;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #eaeaea;
            margin-bottom: 1.5rem;
        }

        /* Profile fields */
        .profile-field {
            position: relative;
            padding: 10px 15px;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin-bottom: 5px;
            border-left: 3px solid #5281ab;
            transition: all 0.2s ease;
        }

        .profile-field:hover {
            background-color: #f0f0f0;
            border-left-color: #345676;
        }

        .profile-value {
            display: block;
            min-height: 24px;
            font-size: 0.95rem;
        }

        /* Form controls */
        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 0.5rem 0.75rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #5281ab;
            box-shadow: 0 0 0 0.25rem rgba(82, 129, 171, 0.25);
        }

        /* Custom alerts */
        .custom-alert-success {
            background-color: #e6f4ea;
            border-color: #b7dfb9;
            color: #24783e;
            border-radius: 8px;
            padding: 0.75rem 1.25rem;
        }

        .custom-alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #842029;
            border-radius: 8px;
            padding: 0.75rem 1.25rem;
        }

        /* Button styling */
        .action-buttons {
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
        }

        .btn-primary {
            background-color: #5281ab;
            border-color: #5281ab;
        }

        .btn-primary:hover {
            background-color: #3c6591;
            border-color: #3c6591;
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle edit mode
            const toggleEditButton = document.getElementById('toggleEditMode');
            const cancelEditButton = document.getElementById('cancelEdit');
            const actionButtons = document.getElementById('actionButtons');
            const profileForm = document.getElementById('profileForm');
            const profileValues = document.querySelectorAll('.profile-value');
            const profileInputs = document.querySelectorAll('.profile-input');

            // Function to enter edit mode with animation
            function enterEditMode() {
                // Add animation class to profile card
                document.querySelector('.profile-card').classList.add('animate__animated', 'animate__pulse');

                // Hide values and show inputs
                profileValues.forEach(value => {
                    value.classList.add('d-none');
                });

                profileInputs.forEach(input => {
                    input.classList.remove('d-none');
                    // Add animation to inputs
                    input.classList.add('animate__animated', 'animate__fadeIn');
                });

                // Show action buttons and hide edit button
                actionButtons.classList.remove('d-none');
                actionButtons.classList.add('animate__animated', 'animate__fadeInUp');
                toggleEditButton.classList.add('d-none');
            }

            // Function to exit edit mode
            function exitEditMode() {
                // Show values and hide inputs
                profileValues.forEach(value => {
                    value.classList.remove('d-none');
                });

                profileInputs.forEach(input => {
                    input.classList.add('d-none');
                });

                // Hide action buttons and show edit button
                actionButtons.classList.add('d-none');
                toggleEditButton.classList.remove('d-none');

                // Remove animation classes
                document.querySelector('.profile-card').classList.remove('animate__animated', 'animate__pulse');
            }

            // Toggle edit mode event
            toggleEditButton.addEventListener('click', enterEditMode);

            // Cancel edit event
            cancelEditButton.addEventListener('click', exitEditMode);

            // Initialize tooltips if present
            if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip !== 'undefined') {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Add animation to alert messages if present
            const alerts = document.querySelectorAll('.alert');
            if (alerts.length > 0) {
                alerts.forEach(alert => {
                    alert.classList.add('animate__animated', 'animate__fadeIn');
                });
            }
        });
    </script>
@endsection
