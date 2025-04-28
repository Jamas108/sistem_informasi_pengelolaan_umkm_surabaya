@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navbar')
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{{ $pageTitle }}</h1>
                </div>

                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="container-fluid pt-2 px-2">
                    <div class="bg-white rounded shadow p-4">
                        <form action="{{ route('manajemenuser.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="{{ $role }}">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Data Akun</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="username">Username <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0">Data Pribadi</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="nik">NIK <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik') }}" required maxlength="16">
                                            </div>
                                            <div class="form-group">
                                                <label for="no_kk">Nomor KK <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="no_kk" name="no_kk" value="{{ old('no_kk') }}" required maxlength="16">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0">Data Kelahiran</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="tgl_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                                <div class="d-flex">
                                                    <div class="custom-control custom-radio mr-4">
                                                        <input type="radio" id="jk_laki" name="jenis_kelamin" value="Laki-laki" class="custom-control-input" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }} required>
                                                        <label class="custom-control-label" for="jk_laki">Laki-laki</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="jk_perempuan" name="jenis_kelamin" value="Perempuan" class="custom-control-input" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="jk_perempuan">Perempuan</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-warning text-dark">
                                            <h5 class="mb-0">Status</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="status_hubungan_keluarga">Status Hubungan Keluarga <span class="text-danger">*</span></label>
                                                <select class="form-control" id="status_hubungan_keluarga" name="status_hubungan_keluarga" required>
                                                    <option value="" selected disabled>-- Pilih Status --</option>
                                                    <option value="Kepala Keluarga" {{ old('status_hubungan_keluarga') == 'Kepala Keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                                    <option value="Istri" {{ old('status_hubungan_keluarga') == 'Istri' ? 'selected' : '' }}>Istri</option>
                                                    <option value="Anak" {{ old('status_hubungan_keluarga') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                                    <option value="Lainnya" {{ old('status_hubungan_keluarga') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="status_perkawinan">Status Perkawinan <span class="text-danger">*</span></label>
                                                <select class="form-control" id="status_perkawinan" name="status_perkawinan" required>
                                                    <option value="" selected disabled>-- Pilih Status --</option>
                                                    <option value="Belum Kawin" {{ old('status_perkawinan') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                                    <option value="Kawin" {{ old('status_perkawinan') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                                    <option value="Cerai Hidup" {{ old('status_perkawinan') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                                    <option value="Cerai Mati" {{ old('status_perkawinan') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="pendidikan_terakhir">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                                <select class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir" required>
                                                    <option value="" selected disabled>-- Pilih Pendidikan --</option>
                                                    <option value="SD" {{ old('pendidikan_terakhir') == 'SD' ? 'selected' : '' }}>SD</option>
                                                    <option value="SMP" {{ old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                    <option value="SMA/SMK" {{ old('pendidikan_terakhir') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                                    <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                                                    <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                                                    <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                                                    <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-secondary text-white">
                                            <h5 class="mb-0">Data Alamat</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                                <textarea class="form-control" id="alamat_sesuai_ktp" name="alamat_sesuai_ktp" rows="3" required>{{ old('alamat_sesuai_ktp') }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="kelurahan">Kelurahan <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="kelurahan" name="kelurahan" value="{{ old('kelurahan') }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="rt">RT <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" id="rt" name="rt" value="{{ old('rt') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="rw">RW <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" id="rw" name="rw" value="{{ old('rw') }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-danger text-white">
                                            <h5 class="mb-0">Kontak</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="no_telp">Nomor Telepon <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{ route('manajemenuser.index') }}" class="btn btn-secondary mr-2">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
@endsection