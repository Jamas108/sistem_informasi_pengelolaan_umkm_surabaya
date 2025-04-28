@extends('layouts.app')

@push('styles')
    <style>
        .card-header {
            background: linear-gradient(to right, #4e73e0, #224abe);
            color: white;
        }

        .preview-image {
            max-width: 300px;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
@endpush

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            // Image preview
            $('#poster').on('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(file);
                }
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
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-plus mr-2 text-primary"></i>Tambah Data Kegiatan
                    </h1>
                    <a href="{{ route('datakegiatan.index') }}" class="btn btn-secondary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                        <span class="text">Kembali</span>
                    </a>
                </div>

                <!-- Form Tambah Kegiatan -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-white">Form Tambah Kegiatan Intervensi</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('datakegiatan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nama_kegiatan">Nama Kegiatan <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('nama_kegiatan') is-invalid @enderror"
                                            id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}"
                                            required>
                                        @error('nama_kegiatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="jenis_kegiatan">Jenis Kegiatan</label>
                                        <select class="form-control" id="jenis_kegiatan" name="jenis_kegiatan">
                                            <option value="">-- Pilih Jenis Intervensi --</option>
                                            <option value="PEMASARAN">PEMASARAN</option>
                                            <option value="PELATIHAN">PELATIHAN</option>
                                            <option value="LEGALITAS/SERTIFIKASI">LEGALITAS/SERTIFIKASI</option>
                                            <option value="KEMITRAAN">KEMITRAAN</option>
                                            <option value="PEMBINAAN">PEMBINAAN</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="jenis_kegiatan">Lokasi Kegiatan</label>
                                        <input type="text"
                                            class="form-control @error('lokasi_kegiatan') is-invalid @enderror"
                                            id="lokasi_kegiatan" name="lokasi_kegiatan"
                                            value="{{ old('lokasi_kegiatan') }}">
                                        @error('lokasi_kegiatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                            id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                            required>
                                        @error('tanggal_mulai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_selesai">Tanggal Selesai <span
                                                class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                            id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                            required>
                                        @error('tanggal_selesai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam_mulai">Jam Mulai <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror"
                                            id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                                        @error('jam_mulai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam_selesai">Jam Selesai <span class="text-danger">*</span></label>
                                        <input type="time"
                                            class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai"
                                            name="jam_selesai" value="{{ old('jam_selesai') }}" required>
                                        @error('jam_selesai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="kuota_pendaftaran">Kuota Kegiatan</label>
                                        <input type="number"
                                            class="form-control @error('kuota_pendaftaran') is-invalid @enderror"
                                            id="kuota_pendaftaran" name="kuota_pendaftaran"
                                            value="{{ old('kuota_pendaftaran') }}" placeholder="Masukan Kuota Pendaftaran Kegiatan">
                                        @error('kuota_pendaftaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status_kegiatan">Status Kegiatan</label>
                                <select class="form-control @error('status_kegiatan') is-invalid @enderror"
                                    id="status_kegiatan" name="status_kegiatan">
                                    <option value="">Pilih Status</option>
                                    <option value="Belum Dimulai"
                                        {{ old('status_kegiatan') == 'BELUM DIMULAI' ? 'selected' : '' }}>BELUM DIMULAI
                                    </option>
                                    <option value="Sedang"
                                        {{ old('status_kegiatan') == 'PENDAFTARAN' ? 'selected' : '' }}>PENDAFTARAN
                                    </option>
                                    <option value="Sedang Berlangsung"
                                        {{ old('status_kegiatan') == 'SEDANG BERLANGSUNG' ? 'selected' : '' }}>SEDANG
                                        BERLANGSUNG</option>
                                    <option value="Selesai" {{ old('status_kegiatan') == 'SELESAI' ? 'selected' : '' }}>
                                        SELESAI</option>
                                </select>
                                @error('status_kegiatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="poster">Poster Kegiatan</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('poster') is-invalid @enderror"
                                        id="poster" name="poster" accept="image/*">
                                    <label class="custom-file-label" for="poster">Pilih Poster</label>
                                    @error('poster')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mt-3 text-center">
                                    <img id="image-preview" width="150" height="150" class="preview-image"
                                        src="#" alt="Preview">
                                </div>

                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Simpan Data
                                </button>
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
                    <span>© {{ date('Y') }} UMKM Management System</span>
                </div>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
    <script>
        // Custom file input label
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val.split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
@endpush
