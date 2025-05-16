@extends('layouts.app')

@push('styles')
    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #224abe;
            --light-bg: #f8f9fc;
            --border-color: #e3e6f0;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fc;
        }

        .page-header {
            background-color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.35rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            border-radius: 0.35rem;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: linear-gradient(to right, var(--primary), var(--primary-dark));
            color: white;
            font-weight: bold;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-header {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.75rem;
            margin-bottom: 1.25rem;
            font-weight: 600;
            color: var(--primary);
            display: flex;
            align-items: center;
        }

        .section-header i {
            margin-right: 0.5rem;
        }

        .section-content {
            background-color: var(--light-bg);
            border-radius: 0.35rem;
            border-left: 4px solid var(--primary);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .info-label {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .document-item {
            border: 1px solid var(--border-color);
            border-radius: 0.35rem;
            padding: 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .document-item:hover {
            box-shadow: 0 0.15rem 0.5rem rgba(78, 115, 223, 0.25);
        }

        .badge-status {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
            font-weight: 600;
        }

        .actions-container {
            background-color: var(--light-bg);
            border-radius: 0.35rem;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .btn-icon-split {
            display: flex;
            align-items: center;
        }

        .btn-icon-split .icon {
            padding: 0.75rem 1rem;
            border-right: 1px solid rgba(255, 255, 255, 0.15);
            background-color: rgba(0, 0, 0, 0.1);
        }

        .btn-icon-split .text {
            padding: 0.75rem 1rem;
            flex-grow: 1;
            text-align: center;
        }

        footer {
            padding: 1.5rem 0;
            background-color: white;
            border-top: 1px solid var(--border-color);
            margin-top: 2rem;
        }
    </style>
@endpush

@section('content')
    @include('layouts.sidebar')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navbar')
            <div class="container-fluid py-4">
                <!-- Page Header -->
                <div class="page-header d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-store-alt me-2 text-primary"></i>Detail Pengajuan UMKM
                    </h1>
                    <a href="{{ route('approvalumkm.index') }}" class="btn btn-secondary btn-icon-split">
                        <span class="icon">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                        <span class="text">Kembali</span>
                    </a>
                </div>

                <!-- Main Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Detail UMKM #{{ $dataumkm->id }}</h5>
                        <span class="badge bg-warning badge-status">{{ $dataumkm->status }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Information Pelaku UMKM Section -->
                            <div class="col-12 mb-4">
                                <h4 class="section-header">
                                    <i class="fas fa-user me-2"></i>Informasi Pelaku UMKM
                                </h4>
                                <div class="section-content">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="info-label">Nama Lengkap</div>
                                            <div class="info-value">{{ $dataumkm->pelakuUmkm->nama_lengkap }}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="info-label">NIK</div>
                                            <div class="info-value">{{ $dataumkm->pelakuUmkm->nik }}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="info-label">No. Telepon</div>
                                            <div class="info-value">{{ $dataumkm->pelakuUmkm->no_telp }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Business Information Section -->
                            <div class="col-12 mb-4">
                                <h4 class="section-header">
                                    <i class="fas fa-store me-2"></i>Informasi Usaha
                                </h4>
                                <div class="section-content">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="info-label">Nama Usaha</div>
                                            <div class="info-value">{{ $dataumkm->nama_usaha }}</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="info-label">Jenis Produk</div>
                                            <div class="info-value">{{ $dataumkm->jenis_produk }}</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="info-label">Tahun Berdiri</div>
                                            <div class="info-value">{{ $dataumkm->tahun_berdiri ?? 'Tidak ada data' }}</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="info-label">Skala Usaha</div>
                                            <div class="info-value">{{ $dataumkm->skala_usaha ?? 'Tidak ada data' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Section -->
                            <div class="col-12 mb-4">
                                <h4 class="section-header">
                                    <i class="fas fa-map-marker-alt me-2"></i>Lokasi Usaha
                                </h4>
                                <div class="section-content">
                                    <div class="info-label">Alamat Lengkap</div>
                                    <div class="info-value">{{ $dataumkm->alamat ?? 'Tidak ada data' }}</div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="info-label">Desa/Kelurahan</div>
                                            <div class="info-value">{{ $dataumkm->desa ?? 'Tidak ada data' }}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="info-label">Kecamatan</div>
                                            <div class="info-value">{{ $dataumkm->kecamatan ?? 'Tidak ada data' }}</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="info-label">Kode Pos</div>
                                            <div class="info-value">{{ $dataumkm->kode_pos ?? 'Tidak ada data' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div class="col-12 mb-4">
                                <h4 class="section-header">
                                    <i class="fas fa-file-alt me-2"></i>Deskripsi Usaha
                                </h4>
                                <div class="section-content">
                                    <p>{{ $dataumkm->deskripsi_usaha ?? 'Tidak ada deskripsi usaha yang diberikan.' }}</p>
                                </div>
                            </div>

                            <!-- Document Section -->
                            @if($dataumkm->dokumen)
                            <div class="col-12 mb-4">
                                <h4 class="section-header">
                                    <i class="fas fa-file-pdf me-2"></i>Dokumen Pendukung
                                </h4>
                                <div class="section-content">
                                    <div class="document-item">
                                        <div>
                                            <i class="fas fa-file-pdf me-2 text-danger"></i>
                                            <span>{{ basename($dataumkm->dokumen) }}</span>
                                        </div>
                                        <a href="{{ asset('storage/' . $dataumkm->dokumen) }}" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="actions-container">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <form action="{{ route('approval.approve', $dataumkm->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-icon-split w-100">
                                            <span class="icon">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Setujui Pengajuan</span>
                                        </button>
                                        <small class="text-muted d-block mt-2">Setujui pengajuan UMKM ini dan memberikan akses
                                            kepada pelaku UMKM</small>
                                    </form>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <button type="button" class="btn btn-danger btn-icon-split w-100"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <span class="icon">
                                            <i class="fas fa-times"></i>
                                        </span>
                                        <span class="text">Tolak Pengajuan</span>
                                    </button>
                                    <small class="text-muted d-block mt-2">Tolak pengajuan UMKM ini dengan memberikan alasan
                                        penolakan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <footer class="text-center">
                    <div class="container">
                        <span>© {{ date('Y') }} UMKM Management System</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('approval.reject', $dataumkm->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectModalLabel">Tolak Pengajuan UMKM</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="alasan_penolakan" class="form-label">Alasan Penolakan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="5" required></textarea>
                            <small class="text-muted">Berikan alasan mengapa pengajuan UMKM ini ditolak. Alasan ini
                                akan ditampilkan kepada pelaku UMKM.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection