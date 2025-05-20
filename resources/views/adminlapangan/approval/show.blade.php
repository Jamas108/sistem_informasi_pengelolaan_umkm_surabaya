@extends('layouts.app')

@push('styles')
    <style>
        .card-header {
            background: linear-gradient(to right, #4e73e0, #224abe);
            color: white;
        }

        .detail-section {
            margin-bottom: 1.5rem;
        }

        .detail-title {
            font-weight: 600;
            color: #4e73df;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .detail-content {
            background-color: #f8f9fc;
            padding: 1rem;
            border-radius: 0.35rem;
            border-left: 4px solid #4e73df;
        }

        .document-preview {
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            padding: 0.5rem;
            margin-bottom: 1rem;
            transition: all 0.2s;
        }

        .document-preview:hover {
            box-shadow: 0 0.15rem 0.5rem rgba(78, 115, 223, 0.25);
        }

        .btn-action {
            margin-right: 0.5rem;
        }

        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #4e73df;
        }

        .umkm-image {
            max-height: 200px;
            object-fit: cover;
            border-radius: 0.35rem;
        }

        .approval-buttons {
            padding: 1.5rem;
            background-color: #f8f9fc;
            border-radius: 0.35rem;
            border-top: 1px solid #e3e6f0;
        }
    </style>
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
                        <i class="fas fa-store-alt mr-2 text-primary"></i>Detail Pengajuan UMKM
                    </h1>
                    <a href="{{ route('approvalumkm.index') }}" class="btn btn-secondary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                        <span class="text">Kembali</span>
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold">Detail UMKM #{{ $dataumkm->id }}</h6>
                        <span class="badge badge-warning status-badge">{{ $dataumkm->status }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 mb-4 text-center">
                                @if ($dataumkm->foto_usaha)
                                    <img src="{{ asset('storage/' . $dataumkm->foto_usaha) }}" alt="Foto Usaha"
                                        class="img-fluid umkm-image mb-3">
                                @else
                                    <div class="text-center p-4 bg-light mb-3">
                                        <i class="fas fa-store fa-4x text-secondary"></i>
                                        <p class="mt-2 mb-0 text-muted">Belum ada foto usaha</p>
                                    </div>
                                @endif

                                <div class="text-center mb-3">
                                    @if ($dataumkm->pelakuUmkm->foto)
                                        <img src="{{ asset('storage/' . $dataumkm->pelakuUmkm->foto) }}" alt="Profil"
                                            class="profile-image mb-3">
                                    @else
                                        <div
                                            class="mx-auto profile-image d-flex align-items-center justify-content-center bg-primary text-white">
                                            <i class="fas fa-user fa-3x"></i>
                                        </div>
                                    @endif
                                    <h5 class="font-weight-bold mt-3">{{ $dataumkm->pelakuUmkm->nama_lengkap }}</h5>
                                    <p class="text-muted mb-1">NIK: {{ $dataumkm->pelakuUmkm->nik }}</p>
                                    <p class="text-muted mb-1"><i class="fas fa-phone-alt mr-1"></i>
                                        {{ $dataumkm->pelakuUmkm->no_telp }}</p>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div class="detail-section">
                                    <div class="detail-title">
                                        <i class="fas fa-store mr-2"></i>Informasi Usaha
                                    </div>
                                    <div class="detail-content">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <p class="mb-1 text-muted">Nama Usaha</p>
                                                <h6>{{ $dataumkm->nama_usaha }}</h6>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="mb-1 text-muted">Jenis Produk</p>
                                                <h6>{{ $dataumkm->jenis_produk }}</h6>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="mb-1 text-muted">Tahun Berdiri</p>
                                                <h6>{{ $dataumkm->tahun_berdiri ?? 'Tidak ada data' }}</h6>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <p class="mb-1 text-muted">Skala Usaha</p>
                                                <h6>{{ $dataumkm->skala_usaha ?? 'Tidak ada data' }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <div class="detail-title">
                                        <i class="fas fa-map-marker-alt mr-2"></i>Lokasi Usaha
                                    </div>
                                    <div class="detail-content">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <p class="mb-1 text-muted">Alamat Lengkap</p>
                                                <h6>{{ $dataumkm->alamat ?? 'Tidak ada data' }}</h6>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <p class="mb-1 text-muted">Desa/Kelurahan</p>
                                                <h6>{{ $dataumkm->desa ?? 'Tidak ada data' }}</h6>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <p class="mb-1 text-muted">Kecamatan</p>
                                                <h6>{{ $dataumkm->kecamatan ?? 'Tidak ada data' }}</h6>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <p class="mb-1 text-muted">Kode Pos</p>
                                                <h6>{{ $dataumkm->kode_pos ?? 'Tidak ada data' }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <div class="detail-title">
                                        <i class="fas fa-file-alt mr-2"></i>Deskripsi Usaha
                                    </div>
                                    <div class="detail-content">
                                        <p>{{ $dataumkm->deskripsi_usaha ?? 'Tidak ada deskripsi usaha yang diberikan.' }}
                                        </p>
                                    </div>
                                </div>

                                @if ($dataumkm->dokumen)
                                    <div class="detail-section">
                                        <div class="detail-title">
                                            <i class="fas fa-file-pdf mr-2"></i>Dokumen Pendukung
                                        </div>
                                        <div class="detail-content">
                                            <div class="document-preview d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-file-pdf mr-2 text-danger"></i>
                                                    <span>{{ basename($dataumkm->dokumen) }}</span>
                                                </div>
                                                <a href="{{ asset('storage/' . $dataumkm->dokumen) }}"
                                                    class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fas fa-eye mr-1"></i> Lihat
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="approval-buttons text-center">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <form action="{{ route('approval.approve', $dataumkm->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-icon-split btn-lg w-100 mb-2">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="text">Setujui Pengajuan</span>
                                    </button>
                                    <small class="form-text text-muted">Setujui pengajuan UMKM ini dan memberikan akses
                                        kepada pelaku UMKM</small>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-danger btn-icon-split btn-lg w-100 mb-2"
                                    data-toggle="modal" data-target="#rejectModal">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-times"></i>
                                    </span>
                                    <span class="text">Tolak Pengajuan</span>
                                </button>
                                <small class="form-text text-muted">Tolak pengajuan UMKM ini dengan memberikan alasan
                                    penolakan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer" style="background-color: #e0e0e0">
            <div class="container  my-auto">
                <div class="copyright text-center my-auto">
                    <span class="text-black">© {{ date('Y') }} UMKM Management System Dinas Koperasi Usaha Kecil dan Menangah dan Perdagangan Kota Surabaya </span> <br>
                </div>
            </div>
        </footer>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('approval.reject', $dataumkm->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectModalLabel">Tolak Pengajuan UMKM</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="alasan_penolakan">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="5" required></textarea>
                            <small class="form-text text-muted">Berikan alasan mengapa pengajuan UMKM ini ditolak. Alasan
                                ini akan ditampilkan kepada pelaku UMKM.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
