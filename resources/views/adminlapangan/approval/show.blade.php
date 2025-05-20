@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navbar')
            <div class="container-fluid py-4">
                <!-- Page Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0">
                            <i class="fas fa-store-alt me-2 text-primary"></i>Detail Pengajuan UMKM
                        </h1>

                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning px-3 py-2 mr-3 text-white fs-6">{{ $dataumkm->status }}</span>
                            <a href="{{ route('approvalumkm.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->

                <!-- Main Content -->
                <div class="row">
                    <!-- Profil UMKM Section -->
                    <div class="col-md-12">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="text-primary mb-0">
                                    <i class="fas fa-user-circle me-2"></i> Informasi Pelaku UMKM
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted small">Nama Lengkap</label>
                                        <input type="text" class="form-control bg-light" value="{{ $dataumkm->pelakuUmkm->nama_lengkap }}" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">NIK</label>
                                        <input type="text" class="form-control bg-light" value="{{ $dataumkm->pelakuUmkm->nik }}" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">No. Telepon</label>
                                        <input type="text" class="form-control bg-light" value="{{ $dataumkm->pelakuUmkm->no_telp }}" disabled>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted small">Tanggal Pendaftaran</label>
                                        <input type="text" class="form-control bg-light" value="{{ \Carbon\Carbon::parse($dataumkm->pelakuUmkm->created_at)->format('d M Y') }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Usaha Section -->
                    <div class="col-md-12">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="text-primary mb-0">
                                    <i class="fas fa-store me-2"></i> Informasi Usaha
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted small">Nama Usaha</label>
                                        <input type="text" class="form-control bg-light" value="{{ $dataumkm->nama_usaha }}" disabled>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Sektor Usaha</label>
                                        <input type="text" class="form-control bg-light" value="{{ $dataumkm->sektor_usaha ?? 'Tidak ada data' }}" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Jumlah Tenaga Kerja</label>
                                        <input type="text" class="form-control bg-light" value="{{ $dataumkm->jumlah_tenaga_kerja ?? 'Tidak ada data' }}" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Pengelolaan Usaha</label>
                                        <input type="text" class="form-control bg-light" value="{{ $dataumkm->pengelolaan_usaha ?? 'Tidak ada data' }}" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Klasifikasi Kinerja Usaha</label>
                                        <input type="text" class="form-control bg-light" value="{{ $dataumkm->klasifikasi_kinerja_usaha ?? 'Tidak ada data' }}" disabled>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-muted small">Alamat Lengkap</label>
                                        <textarea class="form-control bg-light" rows="2" disabled>{{ $dataumkm->alamat ?? 'Tidak ada data' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Produk UMKM Section -->
                    <div class="col-12">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="text-primary mb-0">
                                    <i class="fas fa-box me-2"></i> Produk UMKM
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($dataumkm->produkUmkm->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" width="5%">No</th>
                                                    <th>Tipe Produk</th>
                                                    <th>Jenis Produk</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dataumkm->produkUmkm as $index => $produk)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $produk->tipe_produk }}</td>
                                                    <td>{{ $produk->jenis_produk }}</td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> Tidak ada data produk untuk UMKM ini.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>



                    <!-- Action Buttons Section -->
                    <div class="col-12">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="text-primary mb-0">
                                    <i class="fas fa-cogs me-2"></i> Tindakan
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <form action="{{ route('approval.approve', $dataumkm->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-lg w-100">
                                                <i class="fas fa-check-circle me-2"></i> Setujui Pengajuan
                                            </button>
                                            <small class="text-muted d-block mt-2 text-center">
                                                Setujui pengajuan UMKM ini dan berikan akses kepada pelaku UMKM
                                            </small>
                                        </form>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button type="button" class="btn btn-danger btn-lg w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                            <i class="fas fa-times-circle me-2"></i> Tolak Pengajuan
                                        </button>
                                        <small class="text-muted d-block mt-2 text-center">
                                            Tolak pengajuan UMKM ini dengan memberikan alasan penolakan
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="sticky-footer" style="background-color: #e0e0e0">
                    <div class="container  my-auto">
                        <div class="copyright text-center my-auto">
                            <span class="text-black">© {{ date('Y') }} UMKM Management System Dinas Koperasi Usaha Kecil dan Menangah dan Perdagangan Kota Surabaya </span> <br>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('approval.reject', $dataumkm->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i> Tolak Pengajuan UMKM
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="alasan_penolakan" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="5" required></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i> Berikan alasan mengapa pengajuan UMKM ini ditolak. Alasan ini akan ditampilkan kepada pelaku UMKM.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times-circle me-1"></i> Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection