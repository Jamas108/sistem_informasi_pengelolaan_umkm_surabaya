@extends('layouts.app')

@section('content')
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Cek apakah jQuery berfungsi
            console.log("jQuery is working!");

            // Panggil file JSON dari direktori public
            $.getJSON("{{ asset('data/kelurahan_sby.json') }}")
                .done(function(data) {
                    var kelurahanSelect = $("#kelurahan");
                    kelurahanSelect.empty(); // Kosongkan opsi terlebih dahulu
                    kelurahanSelect.append('<option value="">Pilih Kelurahan...</option>');

                    // Loop untuk menambahkan data ke dropdown
                    $.each(data, function(key, entry) {
                        kelurahanSelect.append($('<option></option>').attr('value', entry.nama).text(
                            entry.nama));
                    });

                    console.log("Data kelurahan berhasil dimuat:", data);
                })
                .fail(function(jqxhr, textStatus, error) {
                    console.error("Gagal mengambil data JSON:", textStatus, error);
                });
        });
    </script>
@endpush
<div class="login-container d-flex justify-content-center align-items-center"
    style="background-image: url('{{ Vite::asset('resources/images/bglogin.png') }}'); background-size: cover; background-position: center; min-height: 100vh; width: 100%;">

    <!-- Use a flexbox container for side-by-side layout -->
    <div class="d-flex w-100">
        <!-- Logo Section -->
        <div class="col-md-6 d-flex justify-content-center align-items-center p-0">
            <img src="{{ Vite::asset('../resources/images/logo_register.png') }}" alt="Logo Surabaya Hebat"
                class="img-fluid" />
        </div>

        <!-- Form Section -->
        <div class="col-md-5 d-flex justify-content-center align-items-center p-0">
            <div class="card w-75 my-5">
                <div class="text-center">
                    <h4 style="color: black;" class="mt-3 mx-5">Sistem Informasi UMKM Kota Surabaya</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <!-- Nama Lengkap Field -->
                        <div class="mb-3">
                            <input id="nama_lengkap" type="text"
                                class="form-control @error('nama_lengkap') is-invalid @enderror" name="nama_lengkap"
                                value="{{ old('nama_lengkap') }}"  placeholder="Nama Lengkap">
                            @error('nama_lengkap')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <!-- NIK Field -->
                        <div class="mb-3">
                            <input id="nik" type="number" class="form-control @error('nik') is-invalid @enderror"
                                name="nik" value="{{ old('nik') }}" placeholder="NIK">
                            @error('nik')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Nomor KK Field -->
                        <div class="mb-3">
                            <input id="no_kk" type="number"
                                class="form-control @error('no_kk') is-invalid @enderror" name="no_kk"
                                value="{{ old('no_kk') }}" placeholder="Nomor KK">
                            @error('no_kk')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- No. Telepon Field -->
                        <div class="mb-3">
                            <input id="no_telp" type="number"
                                class="form-control @error('no_telp') is-invalid @enderror" name="no_telp"
                                value="{{ old('no_telp') }}" placeholder="No. Telepon">
                            @error('no_telp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Tempat Lahir Field -->
                        <div class="mb-3">
                            <input id="tempat_lahir" type="text"
                                class="form-control @error('tempat_lahir') is-invalid @enderror" name="tempat_lahir"
                                value="{{ old('tempat_lahir') }}"  placeholder="Tempat Lahir">
                            @error('tempat_lahir')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir Field -->
                        <div class="mb-3">
                            <label for="tgl_lahir" class="form-label small">Tanggal Lahir</label>
                            <input id="tgl_lahir" type="date"
                                class="form-control @error('tgl_lahir') is-invalid @enderror" name="tgl_lahir"
                                value="{{ old('tgl_lahir') }}" >
                            @error('tgl_lahir')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin Field -->
                        <div class="mb-3">
                            <select class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                name="jenis_kelamin">
                                <option value="">Pilih Jenis Kelamin...</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Alamat Field -->
                        <div class="mb-3">
                            <textarea id="alamat_sesuai_ktp" class="form-control @error('alamat_sesuai_ktp') is-invalid @enderror" name="alamat_sesuai_ktp"
                                placeholder="Masukan Alamat sesuai KTP">{{ old('alamat_sesuai_ktp') }}</textarea>
                            @error('alamat_sesuai_ktp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Kelurahan Field -->
                        <div class="mt-3">
                            <div class="mt-3">
                                <select id="kelurahan" class="form-control @error('kelurahan') is-invalid @enderror"
                                    name="kelurahan">
                                    <option value="">Pilih Kelurahan...</option>
                                </select>
                                @error('kelurahan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- RT/RW Fields -->
                        <div class="row mt-3">
                            <div class="col-6">
                                <input id="rt" type="number"
                                    class="form-control @error('rt') is-invalid @enderror" name="rt"
                                    value="{{ old('rt') }}" placeholder="RT">
                                @error('rt')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <input id="rw" type="number"
                                    class="form-control @error('rw') is-invalid @enderror" name="rw"
                                    value="{{ old('rw') }}" placeholder="RW">
                                @error('rw')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <!-- Status Hubungan Keluarga Field -->
                        <div class="mt-3">
                            <select class="form-control @error('status_hubungan_keluarga') is-invalid @enderror"
                                name="status_hubungan_keluarga">
                                <option value="">Pilih Status Hubungan Keluarga...</option>
                                <option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
                                <option value="SUAMI">SUAMI</option>
                                <option value="ISTRI">ISTRI</option>
                                <option value="ANAK">ANAK</option>
                                <option value="CUCU">CUCU</option>
                                <option value="MENANTU">MENANTU</option>
                                <option value="MERTUA">MERTUA</option>
                                <option value="ORANG TUA">ORANG TUA</option>
                                <option value="KELUARGA LAIN">KELUARGA LAIN</option>
                                <option value="LAINYA">LAINYA</option>
                            </select>
                            @error('status_hubungan_keluarga')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Status Perkawinan Field -->
                        <div class="mt-3">
                            <select class="form-control @error('status_perkawinan') is-invalid @enderror"
                                name="status_perkawinan">
                                <option value="">Pilih Status Perkawinan...</option>
                                <option value="BELUM KAWIN">BELUM KAWIN</option>
                                <option value="KAWIN">KAWIN</option>
                                <option value="KAWIN TERCATAT">KAWIN TERCATAT</option>
                                <option value="KAWIN BELUM TERCATAT">KAWIN BELUM TERCATAT</option>
                                <option value="CERAI HIDUP">CERAI HIDUP</option>
                                <option value="CERAI MATI">CERAI MATI</option>
                                <option value="CERAI TERCATAT">CERAI TERCATAT</option>
                                <option value="CERAI BELUM TERCATAT">CERAI BELUM TERCATAT</option>
                            </select>
                            @error('status_perkawinan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Pendidikan Terakhir Field -->
                        <div class="mt-3">
                            <select class="form-control @error('pendidikan_terakhir') is-invalid @enderror"
                                name="pendidikan_terakhir">
                                <option value="">Pilih Pendidikan Terakhir...</option>
                                <option value="TIDAK/BELUM SEKOLAH">TIDAK/BELUM SEKOLAH</option>
                                <option value="BELUM TAMAT SD/SEDERAJAT">BELUM TAMAT SD/SEDERAJAT</option>
                                <option value="TAMAT SD/Sederajat">TAMAT SD/SEDERAJAT</option>
                                <option value="SLTP/Sederajat">SLTP/SEDERAJAT</option>
                                <option value="SLTA/Sederajat">SLTA/SEDERAJAT</option>
                                <option value="DIPLOMA I/II">DIPLOMA I/II</option>
                                <option value="AKADEMI/DIPLOMA III/S. MUDA">AKADEMI/DIPLOMA III/S. MUDA</option>
                                <option value="DIPLOMA IV/STRATA I">DIPLOMA IV/STRATA I</option>
                                <option value="STRATA II">STRATA II</option>
                                <option value="STRATA III">STRATA III</option>
                            </select>
                            @error('pendidikan_terakhir')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="mt-3">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                autocomplete="new-password" placeholder="Password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="mt-3">
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" autocomplete="new-password"
                                placeholder="Konfirmasi Password">
                        </div>

                        <!-- Register Button -->
                        <div class="d-flex justify-content-between mb-3">
                            <button type="submit" class="btn btn-danger mb-2">
                                <i class="fas fa-user-plus"></i> {{ __('Daftar') }}
                            </button>
                            <a class="btn btn-link mb-2" href="{{ route('login') }}">
                                {{ __('Sudah punya akun?') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Full-page container with no space between logo and form */
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        width: 100%;
        background-size: cover;
        background-position: center;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        max-height: 90vh;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-link {
        color: #007bff;
    }

    /* Remove padding/margins from columns to ensure they fill the space */
    .col-md-6 {
        padding: 0;
    }

    /* Ensure proper spacing in form fields */
    .form-control {
        margin-bottom: 0.5rem;
    }

    /* Text styles */
    h6 {
        color: #495057;
        font-weight: 600;
    }

    /* Logo sizing */
    .img-fluid {
        max-width: 70%;
        height: auto;
    }

    /* Make sure select dropdowns have same height as inputs */
    select.form-control {
        height: calc(1.5em + 0.75rem + 2px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .d-flex.w-100 {
            flex-direction: column;
        }

        .col-md-6,
        .col-md-5 {
            width: 100%;
        }

        .card {
            width: 90%;
            margin: 20px auto;
        }
    }
</style>
@endsection
