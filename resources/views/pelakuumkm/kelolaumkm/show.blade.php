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

            // Show rejection reason when status changes to DITOLAK
            $('#status').on('change', function() {
                if ($(this).val() === 'DITOLAK') {
                    $('#rejection-reason-container').removeClass('d-none');
                } else {
                    $('#rejection-reason-container').addClass('d-none');
                }
            });

            // Trigger the change event on page load to show/hide rejection reason field
            $('#status').trigger('change');
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
        <div class="text-white py-3 px-4 shadow-sm" id="nav" style="background: linear-gradient(145deg, #1c4970, #2F77B6);">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="fw-bold mb-0">
                            <i class="fas fa-edit me-2"></i>
                            <span class="ml-1">Detail Data UMKM</span>
                        </h4>
                        <p class="mb-0 fs-6">Pastikan Data UMKM sesuai dengan UMKM yang anda miliki</p>
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

                    <!-- Display Rejection Reason If Status is DITOLAK -->
                    @if ($umkm->status == 'DITOLAK' && !empty($umkm->alasan_penolakan))
                        <div class="alert alert-danger mb-4">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>UMKM ini ditolak
                                dengan alasan:</h5>
                            <p class="mb-0">{!! nl2br(e($umkm->alasan_penolakan)) !!}</p>
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
                                                    value="{{ $umkm->nama_usaha }}" disabled>
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
                                                    value="{{ $umkm->alamat }}" disabled>
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
                                                <input type="text" class="form-control" id="jumlah_tenaga_kerja"
                                                name="pengelolaan_usaha" value="{{ $umkm->pengelolaan_usaha }}"
                                                disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="klasifikasi_kinerja_usaha"
                                                class="col-sm-4 col-form-label font-weight-bold">Klasifikasi
                                                Kinerja</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="jumlah_tenaga_kerja"
                                                    name="klasifikasi_kinerja_usaha" value="{{ $umkm->klasifikasi_kinerja_usaha }}"
                                                    disabled>
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
                                                <input type="text" class="form-control" id="jumlah_tenaga_kerja"
                                                    name="jumlah_tenaga_kerja" value="{{ $umkm->jumlah_tenaga_kerja }}"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="sektor_usaha"
                                                class="col-sm-4 col-form-label font-weight-bold">Sektor
                                                Usaha</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="sektor_usaha"
                                                name="sektor_usaha" value="{{ $umkm->sektor_usaha }}"
                                                disabled>

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
                                                <input type="text" class="form-control" id="status"
                                                name="status" value="{{ $umkm->status }}"
                                                disabled>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rejection Reason Field - Hidden by default, shown when status is DITOLAK -->
                                <div id="rejection-reason-container"
                                    class="row mb-3 {{ $umkm->status == 'DITOLAK' ? '' : 'd-none' }}">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label for="alasan_penolakan"
                                                class="col-sm-2 col-form-label font-weight-bold">Alasan Penolakan</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="4" disabled>{{ $umkm->alasan_penolakan ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="id" value="{{ $umkm->id }}">
                                <div class="products-section mt-4 pt-3 border-top">

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped"
                                            id="products-table-{{ $umkm->id }}">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-center" width="5%">No</th>
                                                    <th width="35%">Jenis Produk</th>
                                                    <th width="35%">Tipe Produk</th>
                                                    <th width="10%">Status</th>

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

                        </div>
                    </form>
                </div>
            </div>
    </main>
    <style>
        .main-content {
            margin-left: 220px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        /* Style for rejection reason display */
        .alert-danger h5 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .badge-success {
            background-color: #28a745 !important;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545 !important;
            color: white;
        }
    </style>
@endsection
