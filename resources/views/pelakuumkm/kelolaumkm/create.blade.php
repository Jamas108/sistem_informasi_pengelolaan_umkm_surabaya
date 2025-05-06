@extends('layouts.pelakuumkm.app')
@push('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/pelaku-create-umkm.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/produk-umkm.js') }}"></script>
@endpush
@section('content')
    @include('layouts.pelakuumkm.sidebar')
    <main class="main-content">
        <!-- Header Section -->
        <div class="text-white py-3 px-4 shadow-sm" id="nav" style="background: linear-gradient(145deg, #1c4970, #2F77B6);">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        <span>Tambah UMKM Baru</span>
                    </h4>
                    <p class="mb-0 fs-6">Tambahkan usaha baru untuk mengembangkan bisnis Anda</p>
                </div>
                <div>
                    <a href="{{ route('pelakukelolaumkm.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4 py-4">
            <!-- Progress Indicator -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="p-4 pb-2">
                        <h5 class="fw-semibold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Informasi Tambah
                            UMKM</h5>
                        <p class="text-muted mb-0">Lengkapi formulir di bawah ini dengan data usaha yang valid. Data Anda
                            akan diverifikasi oleh admin sebelum disetujui.</p>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar" id="formProgress" style="width: 0%;"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer">
                @if (session('status'))
                    <div class="alert alert-{{ session('status_type') }} alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <!-- Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4 border-0">
                    <h5 class="card-title mb-0 fw-bold text-primary">
                        <i class="fas fa-store-alt me-2"></i>Form Tambah UMKM
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('pelakukelolaumkm.store') }}" method="POST">
                        @csrf

                        <!-- Container for UMKM entries -->
                        <div id="umkm-entries-container">
                            <!-- UMKM entries will be added here dynamically -->
                        </div>

                        <div class="text-center my-3">
                            <button type="button" class="btn btn-primary" id="add-umkm-btn">
                                <i class="fas fa-plus"></i> Tambah UMKM
                            </button>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Floating Action Button (Mobile) -->
        <button class="btn btn-primary btn-tambah-umkm shadow" id="mobile-add-umkm">
            <i class="fas fa-plus"></i>
        </button>
    </main>

    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="productModalLabel">Produk UMKM</h5>
                    <!-- Bootstrap 5 close button - akan otomatis disembunyikan jika menggunakan Bootstrap 4 -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Hidden field for UMKM ID reference -->
                    <input type="hidden" id="current-umkm-id" value="">

                    <!-- Product list table -->
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered" id="productTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Jenis Produk</th>
                                    <th>Tipe Produk</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="product-list-body">
                                <!-- Product rows will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Add/Edit Product Form -->
                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="m-0 fw-bold" id="product-form-title">Tambah Produk Baru</h6>
                        </div>
                        <div class="card-body">
                            <form id="product-form">
                                <input type="hidden" id="product-id" value="">
                                <input type="hidden" id="editing-mode" value="add">

                                <div class="row mb-3">
                                    <label for="product-jenis" class="col-sm-3 col-form-label">Jenis Produk</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="product-jenis" placeholder="Masukan Jenis Produk" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="product-tipe" class="col-sm-3 col-form-label">Tipe Produk</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="product-tipe" required>
                                            <option value="">Pilih Jenis Produk</option>
                                            <option value="Makanan dan Minuman">Makanan dan Minuman</option>
                                            <option value="Makanan">Makanan</option>
                                            <option value="Minuman">Minuman</option>
                                            <option value="Fashion">Fashion</option>
                                            <option value="Handycraft">Handycraft</option>
                                            <option value="Lainya">Lainya</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="product-status" class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="product-status" required>
                                            <option value="AKTIF">AKTIF</option>
                                            <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary"
                                        id="reset-product-form">Reset</button>
                                    <button type="submit" class="btn btn-primary" id="save-product">Simpan
                                        Produk</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="apply-products">Terapkan</button>
                    <button type="button" class="btn btn-secondary modal-close-btn">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Utilities */
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

        /* Form Styling */
        .umkm-form-entry {
            background-color: #f8f9fc;
            border: 1px solid #e3e6f0;
            border-left: 4px solid #5281ab;
            transition: all 0.3s ease;
        }

        .umkm-form-entry:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-left: 4px solid #2c5282;
        }

        .input-group-text {
            color: #5a5c69;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.1);
        }

        /* Floating action button */
        .btn-tambah-umkm {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            z-index: 1040;
            display: none;
        }

        .main-content {
            margin-left: 220px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .btn-tambah-umkm {
                display: flex;
            }
        }

        /* Validation styling */
        .was-validated .form-control:invalid,
        .form-control.is-invalid {
            border-color: #e74a3b;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23e74a3b'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23e74a3b' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .was-validated .form-select:invalid,
        .form-select.is-invalid {
            border-color: #e74a3b;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23e74a3b'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23e74a3b' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center, center right 2.25rem;
            background-size: 16px 12px, calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    </style>
@endsection