@extends('layouts.pelakuumkm.app')
@push('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/pelaku-edit-umkm.js') }}"></script>
    <script>
        $(document).ready(function() {
            // This initializes umkmCounter for both inline script and the external script
            window.umkmCounter = $('.umkm-form-entry').length > 0 ? $('.umkm-form-entry').length : 0;

            // Your other event handlers and functions can stay here...
        });
        $(document).ready(function() {
            // This initializes umkmCounter for both inline script and the external script
            window.umkmCounter = $('.umkm-form-entry').length > 0 ? $('.umkm-form-entry').length : 0;

            // Manual test for Bootstrap and jQuery
            console.log("Document ready in main script");
            console.log("jQuery version:", $.fn.jquery);

            // Test modal functionality directly
            $('#test-modal-btn').on('click', function() {
                console.log("Test modal button clicked");
                $('#editOmsetModal').modal('show');
            });
        });

        $(document).ready(function() {
            // Init Bootstrap components
            if (typeof $.fn.modal === 'function') {
                // Initialize modal with options
                $('#editOmsetModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: false
                });

                console.log("Bootstrap modal initialized successfully");
            } else {
                console.error("Bootstrap modal function not available");
            }
        });
    </script>
@endpush

@section('content')
    @include('layouts.pelakuumkm.sidebar')

    <main class="main-content">
        <div class=" text-white py-3 px-4 shadow-sm mb-4" style="background: linear-gradient(145deg, #1c4970, #2F77B6);">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="fw-bold mb-0">
                            <i class="fas fa-edit me-2"></i>Edit Data UMKM
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <!-- Alert Container -->
                    @if (session('status'))
                        <div class="alert alert-{{ session('status_type') }} alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('pelakukelolaumkm.update', $umkm->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-gradient-primary text-white py-3">
                                <h5 class="m-0 font-weight-bold">Detail UMKM</h5>
                            </div>
                            <div class="card-body p-4">

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="nama_usaha" class="col-sm-4 col-form-label font-weight-bold">Nama
                                                Usaha</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="nama_usaha" name="nama_usaha"
                                                    value="{{ $umkm->nama_usaha }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="alamat_usaha"
                                                class="col-sm-4 col-form-label font-weight-bold">Alamat
                                                Usaha</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="alamat_usaha" name="alamat"
                                                    value="{{ $umkm->alamat }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="pengelolaan_usaha"
                                                class="col-sm-4 col-form-label font-weight-bold">Pengelolaan
                                                Usaha</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="pengelolaan_usaha"
                                                    name="pengelolaan_usaha">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="PERSEORANGAN / MANDIRI"
                                                        {{ $umkm->pengelolaan_usaha == 'PERSEORANGAN / MANDIRI' ? 'selected' : '' }}>
                                                        PERSEORANGAN / MANDIRI</option>
                                                    <option value="KELOMPOK / SUBKON / KERJASAMA"
                                                        {{ $umkm->pengelolaan_usaha == 'KELOMPOK / SUBKON / KERJASAMA' ? 'selected' : '' }}>
                                                        KELOMPOK / SUBKON / KERJASAMA</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="klasifikasi_kinerja_usaha"
                                                class="col-sm-4 col-form-label font-weight-bold">Klasifikasi
                                                Kinerja</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="klasifikasi_kinerja_usaha"
                                                    name="klasifikasi_kinerja_usaha">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="PEMULA"
                                                        {{ $umkm->klasifikasi_kinerja_usaha == 'PEMULA' ? 'selected' : '' }}>
                                                        PEMULA</option>
                                                    <option value="MADYA"
                                                        {{ $umkm->klasifikasi_kinerja_usaha == 'MADYA' ? 'selected' : '' }}>
                                                        MADYA</option>
                                                    <option value="MANDIRI"
                                                        {{ $umkm->klasifikasi_kinerja_usaha == 'MANDIRI' ? 'selected' : '' }}>
                                                        MANDIRI</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="jumlah_tenaga_kerja"
                                                class="col-sm-4 col-form-label font-weight-bold">Jumlah
                                                Tenaga Kerja</label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control" id="jumlah_tenaga_kerja"
                                                    name="jumlah_tenaga_kerja" value="{{ $umkm->jumlah_tenaga_kerja }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="sektor_usaha"
                                                class="col-sm-4 col-form-label font-weight-bold">Sektor
                                                Usaha</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="sektor_usaha" name="sektor_usaha">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="INDUSTRI"
                                                        {{ $umkm->sektor_usaha == 'INDUSTRI' ? 'selected' : '' }}>
                                                        INDUSTRI</option>
                                                    <option value="DAGANG"
                                                        {{ $umkm->sektor_usaha == 'DAGANG' ? 'selected' : '' }}>
                                                        DAGANG</option>
                                                    <option value="JASA"
                                                        {{ $umkm->sektor_usaha == 'JASA' ? 'selected' : '' }}>
                                                        JASA</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="status" class="col-sm-4 col-form-label font-weight-bold">Status
                                                Keaktifan</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="AKTIF"
                                                        {{ $umkm->status == 'AKTIF' ? 'selected' : '' }}>
                                                        AKTIF</option>
                                                    <option value="TIDAK AKTIF"
                                                        {{ $umkm->status == 'TIDAK AKTIF' ? 'selected' : '' }}>
                                                        TIDAK AKTIF</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="id" value="{{ $umkm->id }}">
                                <div class="products-section mt-4 pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="m-0 font-weight-bold text-primary">Produk UMKM</h5>
                                        <button type="button" class="btn btn-sm btn-primary add-product-btn"
                                            data-umkm-id="{{ $umkm->id }}">
                                            <i class="fas fa-plus-circle"></i> Tambah Produk
                                        </button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped"
                                            id="products-table-{{ $umkm->id }}">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-center" width="5%">No</th>
                                                    <th width="35%">Jenis Produk</th>
                                                    <th width="35%">Tipe Produk</th>
                                                    <th width="10%">Status</th>
                                                    <th class="text-center" width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($umkm->produkUmkm as $index => $produk)
                                                    <tr id="product-{{ $produk->id }}"
                                                        data-product-id="{{ $produk->id }}">
                                                        <td class="text-center">{{ $index + 1 }}
                                                        </td>
                                                        <td>{{ $produk->jenis_produk }}</td>
                                                        <td>{{ $produk->tipe_produk }}</td>
                                                        <td>
                                                            @if ($produk->status == 'AKTIF')
                                                                <span class="badge badge-success">AKTIF</span>
                                                            @else
                                                                <span class="badge badge-danger">TIDAK
                                                                    AKTIF</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning edit-product-btn"
                                                                data-product-id="{{ $produk->id }}"
                                                                data-umkm-id="{{ $umkm->id }}"
                                                                data-jenis-produk="{{ $produk->jenis_produk }}"
                                                                data-tipe-produk="{{ $produk->tipe_produk }}"
                                                                data-status="{{ $produk->status }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger delete-product-btn"
                                                                data-product-id="{{ $produk->id }}"
                                                                data-umkm-id="{{ $umkm->id }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">Belum
                                                            ada produk untuk UMKM ini</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="d-sm-flex align-items-center justify-content-center my-4">
                                <button type="button" class="btn btn-primary btn-md" id="add-umkm-btn">
                                    <i class="fas fa-plus-circle mr-2"></i> Tambah UMKM Baru
                                </button>
                                <button type="submit" class="btn btn-success btn-md ml-2">
                                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-primary text-white">
                            <h5 class="modal-title" id="addProductModalLabel">Tambah Produk</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="add-product-form-edit">
                                @csrf
                                <input type="hidden" id="add_product_umkm_id" name="umkm_id">
                                <input type="hidden" id="product_id" name="id" value="">
                                <input type="hidden" id="editing_mode" value="add">
                                <input type="hidden" id="is_temp_product" value="0">

                                <div class="form-group">
                                    <label for="add_product_jenis" class="font-weight-bold">Jenis Produk</label>
                                    <input type="text" class="form-control" id="add_product_jenis"
                                        name="jenis_produk" required>
                                    <div class="invalid-feedback">Jenis produk wajib diisi</div>
                                </div>

                                <div class="form-group">
                                    <label for="add_product_tipe" class="font-weight-bold">Tipe Produk</label>
                                    <select class="form-control" id="add_product_tipe" name="tipe_produk" required>
                                        <option value="Makanan dan Minuman">Makanan dan Minuman</option>
                                        <option value="Makanan">Makanan</option>
                                        <option value="Minuman">Minuman</option>
                                        <option value="Fashion">Fashion</option>
                                        <option value="Handycraft">Handycraft</option>
                                        <option value="Lainya">Lainya</option>
                                    </select>
                                    <div class="invalid-feedback">Tipe produk wajib diisi</div>
                                </div>

                                <div class="form-group">
                                    <label for="add_product_status" class="font-weight-bold">Status</label>
                                    <select class="form-control" id="add_product_status" name="status" required>
                                        <option value="AKTIF">AKTIF</option>
                                        <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                    </select>
                                    <div class="invalid-feedback">Status wajib dipilih</div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" form="add-product-form-edit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
    </main>
    <style>
        .main-content {
            margin-left: 220px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
    </style>

@endsection
