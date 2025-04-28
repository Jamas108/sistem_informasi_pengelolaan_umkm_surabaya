@extends('layouts.pelakuumkm.app')
@section('content')
    @include('layouts.pelakuumkm.sidebar')
    <main class="main-content p-4">
        <div class="container-fluid">
            <!-- Dashboard Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h3 text-black">Dashboard UMKM</h1>
                    <p class="text-muted text-black">Selamat datang, Budi Santoso</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-cash-stack fs-2 me-3"></i>
                            <div>
                                <h5 class="card-title mb-0">Total Pendapatan</h5>
                                <p class="mb-0 h4">Rp 85.000.000</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card bg-success text-white">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-shop fs-2 me-3"></i>
                            <div>
                                <h5 class="card-title mb-0">Total UMKM</h5>
                                <p class="mb-0 h4">7 Usaha</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-cart-check fs-2 me-3"></i>
                            <div>
                                <h5 class="card-title mb-0">Pesanan Aktif</h5>
                                <p class="mb-0 h4">42 Pesanan</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card bg-info text-white">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-graph-up fs-2 me-3"></i>
                            <div>
                                <h5 class="card-title mb-0">Pertumbuhan</h5>
                                <p class="mb-0 h4">15.7%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar UMKM -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Daftar UMKM Anda</h5>
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#tambahUMKMModal">
                                <i class="bi bi-plus"></i> Tambah UMKM
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card card-umkm">
                                        <div class="card-body">
                                            <h5 class="card-title">Kerajinan Batik Sinar Baru</h5>
                                            <p class="card-text text-muted">Produksi Batik Tulis Berkualitas</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-success">Aktif</span>
                                                <div>
                                                    <a href="#" class="btn btn-sm btn-outline-primary me-2">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- More UMKM cards can be added here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <button class="btn btn-primary btn-tambah-umkm" data-bs-toggle="modal" data-bs-target="#tambahUMKMModal">
            <i class="bi bi-plus fs-4"></i>
        </button>
    </main>

    <!-- Modal Tambah UMKM -->
    <div class="modal fade modal-tambah-umkm" id="tambahUMKMModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i> Tambah UMKM Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="namausaha" placeholder="Nama Usaha" required>
                            <label for="namausaha">Nama Usaha</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="kategoriusaha" required>
                                <option value="">Pilih Kategori</option>
                                <option value="makanan">Makanan & Minuman</option>
                                <option value="fashion">Fashion</option>
                                <option value="kerajinan">Kerajinan</option>
                                <option value="jasa">Jasa</option>
                            </select>
                            <label for="kategoriusaha">Kategori Usaha</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="Deskripsi Usaha" id="deskripasiusaha" style="height: 100px"></textarea>
                            <label for="deskripasiusaha">Deskripsi Usaha</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="omzetusaha" placeholder="Omzet Perbulan"
                                required>
                            <label for="omzetusaha">Omzet Perbulan (Rp)</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan UMKM</button>
                </div>
            </div>
        </div>
    </div>
@endsection
