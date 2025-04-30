@extends('layouts.app')
@push('scripts')
    <script src="{{ asset('js/umkm-form.js') }}"></script>
    <script src="{{ asset('js/umkm-legalitas.js') }}"></script>
    <script src="{{ asset('js/umkm-omset.js') }}"></script>
    <script src="{{ asset('js/umkm-intervensi.js') }}"></script>
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
    @include('layouts.sidebar')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navbar')
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Detail Data UMKM</h1>
                </div>

                <div class="container-fluid pt-2 px-2">
                    <div class="bg-white justify-content-between rounded shadow p-4">
                        <!-- Alert Container -->
                        @if (session('status'))
                            <div class="alert alert-{{ session('status_type') }} alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif



                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pelaku-tab" data-toggle="tab" href="#pelaku" role="tab"
                                    aria-controls="pelaku" aria-selected="true">Data Pelaku UMKM</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="umkm-tab" data-toggle="tab" href="#umkm" role="tab"
                                    aria-controls="umkm" aria-selected="false">Data UMKM</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="omset-tab" data-toggle="tab" href="#omset" role="tab"
                                    aria-controls="omset" aria-selected="false">Omset</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="legalitas-tab" data-toggle="tab" href="#legalitas" role="tab"
                                    aria-controls="legalitas" aria-selected="false">Legalitas</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="intervensi-tab" data-toggle="tab" href="#intervensi" role="tab"
                                    aria-controls="intervensi" aria-selected="false">Intervensi</a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <form action="" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="tab-content" id="myTabContent">
                                <!-- Tab 1: Data Pelaku UMKM -->
                                <!-- Tab 1: Data Pelaku UMKM (Revised) -->
                                <div class="tab-pane fade show active" id="pelaku" role="tabpanel"
                                    aria-labelledby="pelaku-tab">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-gradient-primary text-white py-3">
                                            <h5 class="m-0 font-weight-bold">Data Pelaku UMKM</h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="row mb-3">
                                                <label for="nik_pemilik"
                                                    class="col-sm-3 col-form-label font-weight-bold">NIK Pemilik</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="nik" name="nik"
                                                        value="{{ $pelakuUmkm->nik }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="nama_pemilik"
                                                    class="col-sm-3 col-form-label font-weight-bold" >Nama Pemilik</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="nama_lengkap"
                                                        name="nama_lengkap" value="{{ $pelakuUmkm->nama_lengkap }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="no_kk_pemilik"
                                                    class="col-sm-3 col-form-label font-weight-bold">No KK Pemilik</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="no_kk"
                                                        name="no_kk" value="{{ $pelakuUmkm->no_kk }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="tempat_lahir"
                                                    class="col-sm-3 col-form-label font-weight-bold">Tempat Lahir</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="tempat_lahir"
                                                        name="tempat_lahir" value="{{ $pelakuUmkm->tempat_lahir }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="tanggal_lahir"
                                                    class="col-sm-3 col-form-label font-weight-bold">Tanggal Lahir</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" id="tgl_lahir"
                                                        name="tgl_lahir" value="{{ $pelakuUmkm->tgl_lahir }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="jenis_kelamin"
                                                    class="col-sm-3 col-form-label font-weight-bold">Jenis Kelamin</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="jenis_kelamin"
                                                        name="jenis_kelamin" value="{{ $pelakuUmkm->jenis_kelamin }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="status_hub_keluarga"
                                                    class="col-sm-3 col-form-label font-weight-bold">Status Hub.
                                                    Keluarga</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="status_hubungan_keluarga"
                                                        name="status_hubungan_keluarga" value="{{ $pelakuUmkm->status_hubungan_keluarga }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="status"
                                                    class="col-sm-3 col-form-label font-weight-bold">Status
                                                    Perkawinan</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="status_perkawinan"
                                                    name="status_perkawinan" value="{{ $pelakuUmkm->status_perkawinan }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="alamat_sesuai_ktp"
                                                    class="col-sm-3 col-form-label font-weight-bold">Alamat Sesuai
                                                    KTP</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="alamat_sesuai_ktp" name="alamat_sesuai_ktp" value="{{ $pelakuUmkm->alamat_sesuai_ktp }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="kelurahan_sesuai_ktp"
                                                    class="col-sm-3 col-form-label font-weight-bold">Kelurahan Sesuai
                                                    KTP</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="kelurahan"
                                                        name="kelurahan" value="{{ $pelakuUmkm->kelurahan }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="rw"
                                                    class="col-sm-3 col-form-label font-weight-bold">RW</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="rw"
                                                        name="rw" value="{{ $pelakuUmkm->rw }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="rt"
                                                    class="col-sm-3 col-form-label font-weight-bold">RT</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="rt"
                                                        name="rt" value="{{ $pelakuUmkm->rt }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="telp"
                                                    class="col-sm-3 col-form-label font-weight-bold">No Telepon</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="no_telp"
                                                        name="no_telp" value="{{ $pelakuUmkm->no_telp }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="telp"
                                                    class="col-sm-3 col-form-label font-weight-bold">Pendidikan Terakhir</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="pendidikan_terakhir"
                                                        name="pendidikan_terakhir" value="{{ $pelakuUmkm->pendidikan_terakhir }}" disabled>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="telp"
                                                    class="col-sm-3 col-form-label font-weight-bold">Status Keaktifan Pelaku UMKM</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="status_keaktifan"
                                                        name="status_keaktifan" value="{{ $pelakuUmkm->status_keaktifan }}" disabled>
                                                </div>
                                            </div>
                                         </div>
                                    </div>
                                </div>

                                <!-- Tab 2: Data UMKM (Enhanced Professional Design) -->
                                <div class="tab-pane fade" id="umkm" role="tabpanel" aria-labelledby="umkm-tab">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-gradient-primary text-white py-3">
                                            <h5 class="m-0 font-weight-bold">Data UMKM</h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <!-- Container for UMKM entries -->
                                            <div id="umkm-entries-container">
                                                @foreach ($pelakuUmkm->dataUmkm as $index => $umkm)
                                                    <div class="umkm-form-entry border rounded p-4 mb-4 shadow-sm"
                                                        id="umkm-entry-{{ $index }}"
                                                        data-umkm-id="{{ $index }}">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                                            <h5 class="m-0 umkm-number font-weight-bold text-primary">UMKM
                                                                {{ $loop->iteration }}</h5>
                                                            @if ($loop->count > 1)
                                                            @endif
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="nama_usaha_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Nama
                                                                        Usaha</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="nama_usaha_{{ $index }}"
                                                                            name="umkm[{{ $index }}][nama_usaha]"
                                                                            value="{{ $umkm->nama_usaha }}" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="alamat_usaha_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Alamat
                                                                        Usaha</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="alamat_usaha_{{ $index }}"
                                                                            name="umkm[{{ $index }}][alamat]"
                                                                            value="{{ $umkm->alamat }}" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="jenis_produk_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Jenis
                                                                        Produk</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="jenis_produk_{{ $index }}"
                                                                            name="umkm[{{ $index }}][jenis_produk]"
                                                                            value="{{ $umkm->jenis_produk }}" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="tipe_produk_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Tipe
                                                                        Produk</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="tipe_produk_{{ $index }}"
                                                                            name="umkm[{{ $index }}][tipe_produk]"
                                                                            value="{{ $umkm->tipe_produk }}" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="pengelolaan_usaha_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Pengelolaan
                                                                        Usaha</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="pengelolaan_usaha_{{ $index }}"
                                                                        name="umkm[{{ $index }}][pengelolaan_usaha]" value="{{ $umkm->pengelolaan_usaha }}" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label
                                                                        for="klasifikasi_kinerja_usaha_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Klasifikasi
                                                                        Kinerja</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="klasifikasi_kinerja_usaha_{{ $index }}"
                                                                        name="umkm[{{ $index }}][klasifikasi_kinerja_usaha]" value="{{ $umkm->klasifikasi_kinerja_usaha }}" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="jumlah_tenaga_kerja_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Jumlah
                                                                        Tenaga Kerja</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="number" class="form-control"
                                                                            id="jumlah_tenaga_kerja_{{ $index }}"
                                                                            name="umkm[{{ $index }}][jumlah_tenaga_kerja]"
                                                                            value="{{ $umkm->jumlah_tenaga_kerja }}" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="sektor_usaha_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Sektor
                                                                        Usaha</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"id="sektor_usaha_{{ $index }}"
                                                                        name="umkm[{{ $index }}][sektor_usaha]" value="{{ $umkm->sektor_usaha }}" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label for="status_{{ $index }}"
                                                                        class="col-sm-4 col-form-label font-weight-bold">Status
                                                                        Keaktifan</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="status_{{ $index }}"
                                                                        name="umkm[{{ $index }}][status]" value="{{ $umkm->status }}" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="umkm[{{ $index }}][id]"
                                                            value="{{ $umkm->id }}" readonly>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="omset" role="tabpanel" aria-labelledby="omset-tab">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-gradient-primary text-white py-3">
                                            <h5 class="m-0 font-weight-bold">Data Omset UMKM</h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <!-- Table of existing omset data -->
                                            <div class="mt-2">
                                                <div class="card border-left-primary shadow">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered" id="table-omset"
                                                                width="100%" cellspacing="0">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th class="text-center" width="5%">NO</th>
                                                                        <th width="15%">UMKM</th>
                                                                        <th width="15%">Jangka Waktu</th>
                                                                        <th width="20%">Nilai Omset</th>
                                                                        <th width="15%">Keterangan</th>

                                                                        <th class="text-center" width="10%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if (isset($omsetData) && count($omsetData) > 0)
                                                                        @foreach ($omsetData as $index => $item)
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    {{ $index + 1 }}</td>
                                                                                <td>{{ $item->dataUmkm->nama_usaha }}</td>
                                                                                <td>{{ date('d-m-Y', strtotime($item->jangka_waktu)) }}
                                                                                </td>
                                                                                <td>Rp.
                                                                                    {{ number_format($item->omset, 0, ',', '.') }}
                                                                                </td>
                                                                                <td>
                                                                                    @if ($item->keterangan == 'AKTIF')
                                                                                        <span
                                                                                            class="badge badge-success">AKTIF</span>
                                                                                        @elseif ($item->keterangan == 'TIDAK AKTIF')
                                                                                        <span
                                                                                            class="badge badge-danger">TIDAK
                                                                                            AKTIF</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button"
                                                                                        class="btn btn-warning btn-sm edit-omset"
                                                                                        data-id="{{ $item->id }}">
                                                                                        <i class="fas fa-edit"></i> Edit
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td colspan="6" class="text-center">Belum
                                                                                ada data omset</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal for editing omset -->
                                            <div class="modal fade" id="editOmsetModal" tabindex="-1" role="dialog"
                                                aria-labelledby="editOmsetModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-gradient-primary text-white">
                                                            <h5 class="modal-title" id="editOmsetModalLabel">Edit Data
                                                                Omset</h5>
                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="edit-omset-form">
                                                                <input type="hidden" id="edit_omset_id" name="id">

                                                                <div class="form-group row mb-3">
                                                                    <label for="edit_umkm_id"
                                                                        class="col-sm-3 col-form-label">UMKM</label>
                                                                    <div class="col-sm-9">
                                                                        <select class="form-control" id="edit_umkm_id"
                                                                            name="umkm_id">
                                                                            @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                                <option value="{{ $umkm->id }}">
                                                                                    {{ $umkm->nama_usaha }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row mb-3">
                                                                    <label for="edit_jangka_waktu"
                                                                        class="col-sm-3 col-form-label">Jangka
                                                                        Waktu</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="date" class="form-control"
                                                                            id="edit_jangka_waktu" name="jangka_waktu">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row mb-3">
                                                                    <label for="edit_omset"
                                                                        class="col-sm-3 col-form-label">Nilai Omset</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">Rp.</span>
                                                                            </div>
                                                                            <input type="text"
                                                                                class="form-control currency-input"
                                                                                id="edit_omset" name="omset">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row mb-3">
                                                                    <label for="edit_keterangan"
                                                                        class="col-sm-3 col-form-label">Keterangan</label>
                                                                    <div class="col-sm-9">
                                                                        <select class="form-control" id="edit_keterangan"
                                                                            name="keterangan">
                                                                            <option value="AKTIF">AKTIF</option>
                                                                            <option value="TIDAK AKTIF">TIDAK AKTIF
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Batal</button>
                                                            <button type="button" class="btn btn-primary"
                                                                id="save-edit-omset">Simpan Perubahan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tab 5: Legalitas UMKM -->
                                <div class="tab-pane fade" id="legalitas" role="tabpanel"
                                    aria-labelledby="legalitas-tab">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-gradient-primary text-white py-3">
                                            <h5 class="m-0 font-weight-bold">Data Legalitas UMKM</h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <!-- Container for legalitas form -->
                                            <div id="legalitas-container">
                                                <div class="mt-2">
                                                    <div class="card border-left-primary shadow">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" id="table-legalitas"
                                                                    width="100%" cellspacing="0">
                                                                    <thead class="bg-light">
                                                                        <tr>
                                                                            <th class="text-center" width="5%">NO</th>
                                                                            <th>UMKM</th>
                                                                            <th>No NIB</th>
                                                                            <th>No SIUP</th>
                                                                            <th>No TDP</th>
                                                                            <th>No PIRT</th>
                                                                            <th>No BPOM</th>
                                                                            <th>No HALAL</th>
                                                                            <th>No MERK</th>
                                                                            <th>No HAKI</th>
                                                                            <th>No SK</th>
                                                                            <th>Aksi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if (isset($legalitasData) && count($legalitasData) > 0)
                                                                            @foreach ($legalitasData as $index => $item)
                                                                                <tr>
                                                                                    <td class="text-center">
                                                                                        {{ $index + 1 }}</td>
                                                                                    <td>{{ $item->dataUmkm->nama_usaha ?? 'Tidak ada' }}
                                                                                    </td>
                                                                                    <td>{{ $item->no_sk_nib ?: '-' }}</td>
                                                                                    <td>{{ $item->no_sk_siup ?: '-' }}
                                                                                    </td>
                                                                                    <td>{{ $item->no_sk_tdp ?: '-' }}</td>
                                                                                    <td>{{ $item->no_sk_pirt ?: '-' }}
                                                                                    </td>
                                                                                    <td>{{ $item->no_sk_bpom ?: '-' }}
                                                                                    </td>
                                                                                    <td>{{ $item->no_sk_halal ?: '-' }}
                                                                                    </td>
                                                                                    <td>{{ $item->no_sk_merk ?: '-' }}
                                                                                    </td>
                                                                                    <td>{{ $item->no_sk_haki ?: '-' }}
                                                                                    </td>
                                                                                    <td>{{ $item->no_surat_keterangan ?: '-' }}
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <button type="button"
                                                                                            class="btn btn-warning btn-sm edit-legalitas"
                                                                                            data-id="{{ $item->id }}">
                                                                                            <i class="fas fa-edit"></i>
                                                                                            Edit
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @else
                                                                            <tr>
                                                                                <td colspan="7" class="text-center">
                                                                                    Belum ada data legalitas</td>
                                                                            </tr>
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal for editing legalitas -->
                                                <div class="modal fade" id="editLegalitasModal" tabindex="-1"
                                                    role="dialog" aria-labelledby="editLegalitasModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-gradient-primary text-white">
                                                                <h5 class="modal-title" id="editLegalitasModalLabel">Edit
                                                                    Data Legalitas</h5>
                                                                <button type="button" class="close text-white"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="edit-legalitas-form">
                                                                    <input type="hidden" id="edit_legalitas_id"
                                                                        name="id">

                                                                    <div class="form-group row">
                                                                        <label for="edit_legalitas_umkm_id"
                                                                            class="col-sm-3 col-form-label">UMKM</label>
                                                                        <div class="col-sm-9">
                                                                            <select class="form-control"
                                                                                id="edit_legalitas_umkm_id"
                                                                                name="umkm_id">
                                                                                @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                                                                    <option value="{{ $umkm->id }}">
                                                                                        {{ $umkm->nama_usaha }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_nib"
                                                                            class="col-sm-3 col-form-label">No SK
                                                                            NIB</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_no_sk_nib" name="no_sk_nib">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_siup"
                                                                            class="col-sm-3 col-form-label">No SK
                                                                            SIUP</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_no_sk_siup" name="no_sk_siup">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_tdp"
                                                                            class="col-sm-3 col-form-label">No SK
                                                                            TDP</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_no_sk_tdp" name="no_sk_tdp">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_pirt"
                                                                            class="col-sm-3 col-form-label">No SK
                                                                            PIRT</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_no_sk_pirt" name="no_sk_pirt">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_bpom"
                                                                            class="col-sm-3 col-form-label">No SK
                                                                            BPOM</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_no_sk_bpom" name="no_sk_bpom">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_halal"
                                                                            class="col-sm-3 col-form-label">No SK
                                                                            HALAL</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_no_sk_halal" name="no_sk_halal">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_merek"
                                                                            class="col-sm-3 col-form-label">No SK
                                                                            MEREK</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_no_sk_merek" name="no_sk_merek">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_sk_haki"
                                                                            class="col-sm-3 col-form-label">No SK
                                                                            HAKI</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_no_sk_haki" name="no_sk_haki">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="edit_no_surat_keterangan"
                                                                            class="col-sm-3 col-form-label">No Surat
                                                                            Keterangan</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control"
                                                                                id="edit_no_surat_keterangan"
                                                                                name="no_surat_keterangan">
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Batal</button>
                                                                <button type="button" class="btn btn-primary"
                                                                    id="save-edit-legalitas">Simpan Perubahan</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tab 4: Intervensi UMKM -->
                                <div class="tab-pane fade" id="intervensi" role="tabpanel"
                                    aria-labelledby="intervensi-tab">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-gradient-primary text-white py-3">
                                            <h5 class="m-0 font-weight-bold">Data Intervensi UMKM</h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <!-- Table of existing interventions -->
                                            <div class="mt-2">
                                                <div class="card border-left-primary shadow">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered" id="table-intervensi"
                                                                width="100%" cellspacing="0">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th class="text-center" width="5%">NO</th>
                                                                        <th width="15%">UMKM</th>
                                                                        <th width="15%">Tanggal</th>
                                                                        <th width="15%">Jenis</th>
                                                                        <th width="20%">Nama Kegiatan</th>
                                                                        <th width="15%">Omset</th>
                                                                        <th class="text-center" width="10%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if (isset($pelakuUmkm->intervensi) && $pelakuUmkm->intervensi->count() > 0)
                                                                        @foreach ($pelakuUmkm->intervensi as $index => $item)
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    {{ $index + 1 }}</td>
                                                                                <td>{{ $item->dataUmkm->nama_usaha ?? 'Tidak ada' }}
                                                                                </td>
                                                                                <td>{{ \Carbon\Carbon::parse($item->tgl_intervensi)->format('d-m-Y') }}
                                                                                </td>
                                                                                <td>{{ $item->jenis_intervensi }}</td>
                                                                                <td>{{ $item->nama_kegiatan }}</td>
                                                                                <td>Rp.
                                                                                    {{ number_format($item->omset, 0, ',', '.') }}
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button"
                                                                                        class="btn btn-warning btn-sm edit-intervensi"
                                                                                        data-id="{{ $item->id }}">
                                                                                        <i class="fas fa-edit"></i> Edit
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td colspan="7" class="text-center">Belum
                                                                                ada data intervensi</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for editing intervention -->
                                <div class="modal fade" id="editIntervensiModal" tabindex="-1" role="dialog"
                                    aria-labelledby="editIntervensiModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-gradient-primary text-white">
                                                <h5 class="modal-title" id="editIntervensiModalLabel">Edit Data Intervensi
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="edit-intervensi-form">
                                                    <input type="hidden" id="edit_intervensi_id" name="id">

                                                    <div class="form-group row">
                                                        <label for="tgl_intervensi"
                                                            class="col-sm-3 col-form-label">Tanggal Intervensi</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" class="form-control"
                                                                id="tgl_intervensi" name="tgl_intervensi">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="edit_jenis_intervensi"
                                                            class="col-sm-3 col-form-label">Jenis Intervensi</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" id="edit_jenis_intervensi"
                                                                name="jenis_intervensi">
                                                                <option value="">-- Pilih Jenis Intervensi --
                                                                </option>
                                                                <option value="PEMASARAN">PEMASARAN</option>
                                                                <option value="PELATIHAN">PELATIHAN</option>
                                                                <option value="LEGALITAS/SERTIFIKASI">LEGALITAS/SERTIFIKASI</option>
                                                                <option value="KEMITRAAN">KEMITRAAN</option>
                                                                <option value="PEMBINAAN">PEMBINAAN</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="edit_nama_kegiatan"
                                                            class="col-sm-3 col-form-label">Nama Kegiatan</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control"
                                                                id="edit_nama_kegiatan" name="nama_kegiatan">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="edit_omset"
                                                            class="col-sm-3 col-form-label">Omset</label>
                                                        <div class="col-sm-9">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input type="text" class="form-control currency-input"
                                                                    id="edit_omset" name="omset">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-primary"
                                                    id="save-edit-intervensi">Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white mt-4">
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
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
@endsection
