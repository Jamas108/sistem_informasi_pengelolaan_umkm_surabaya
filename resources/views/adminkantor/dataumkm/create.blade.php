    @extends('layouts.app')
    @push('scripts')
        <script type="text/javascript" src="{{ URL::asset('js/umkm-create.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/produk-umkm.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Cek apakah jQuery berfungsi
                console.log("jQuery is working!");

                // Panggil file JSON dari direktori public
                $.getJSON("{{ asset('data/kelurahan_sby.json') }}")
                    .done(function(data) {
                        var kelurahanSelect = $("#kelurahan");
                        kelurahanSelect.empty(); // Kosongkan opsi terlebih dahulu
                        kelurahanSelect.append('<option value="">Pilih Kelurahan...</option>');

                        // Loop untuk menambahkan data ke dropdown
                        $.each(data, function(key, entry) {
                            kelurahanSelect.append($('<option></option>').attr('value', entry.nama).text(
                                entry.nama));
                        });

                        console.log("Data kelurahan berhasil dimuat:", data);
                    })
                    .fail(function(jqxhr, textStatus, error) {
                        console.error("Gagal mengambil data JSON:", textStatus, error);
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
                        <h1 class="h3 mb-0 text-gray-800">Tambah Data UMKM Usaha Mikro</h1>
                    </div>

                    <div class="container-fluid pt-2 px-2">
                        <div class="bg-white justify-content-between rounded shadow p-4">

                            <div class="row mb-3">
                                <label for="search_nik" class="col-sm-2 col-form-label">Search NIK</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="search_nik" name="search_nik"
                                        value="{{ request('search_nik') }}">
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary" id="cek_nik">
                                        <i class="fas fa-search"></i> Cari NIK
                                    </button>
                                </div>
                            </div>

                            <!-- Alert Container -->
                            @if (session('status'))
                                <div class="alert alert-{{ session('status_type') }} alert-dismissible fade show"
                                    role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <hr>

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
                            </ul>

                            <!-- Tab Content -->
                            <form action="{{ route('dataumkm.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content" id="myTabContent">
                                    <!-- Tab 1: Data Pelaku UMKM -->
                                    <div class="tab-pane fade show active" id="pelaku" role="tabpanel"
                                        aria-labelledby="pelaku-tab">
                                        <div class="row mb-3">
                                            <label for="nik_pemilik" class="col-sm-2 col-form-label">NIK Pemilik</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" id="nik" name="nik"
                                                    placeholder="Masukan NIK Pemilik UMKM">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="nama_pemilik" class="col-sm-2 col-form-label">Nama Pemilik</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="nama_lengkap"
                                                    name="nama_lengkap" placeholder="Masukan Nama Lengkap Pemilik UMKM">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="no_kk_pemilik" class="col-sm-2 col-form-label">No KK Pemilik</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" id="no_kk" name="no_kk"
                                                    placeholder="Masukan No KK Pemilik UMKM">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="tempat_lahir"
                                                    name="tempat_lahir" placeholder="Masukan Tempat Lahir Pemilik UMKM">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                                    <option value="LAKI - LAKI">LAKI - LAKI</option>
                                                    <option value="PEREMPUAN">PEREMPUAN</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="status_hub_keluarga" class="col-sm-2 col-form-label">Status Hub.
                                                Keluarga</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="status_hubungan_keluarga"
                                                    name="status_hubungan_keluarga">
                                                    <option value="">-- Pilih Status Hubungan Keluarga --</option>
                                                    <option value="ANAK">ANAK</option>
                                                    <option value="CUCU">CUCU</option>
                                                    <option value="ISTRI">ISTRI</option>
                                                    <option value="SUAMI">SUAMI</option>
                                                    <option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
                                                    <option value="MENANTU">MENANTU</option>
                                                    <option value="MERTUA">MERTUA</option>
                                                    <option value="ORANG TUA">ORANG TUA</option>
                                                    <option value="FAMILI LAIN">FAMILI LAIN</option>
                                                    <option value="LAINYA">LAINYA</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="status" class="col-sm-2 col-form-label">Status Perkawinan</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="status_perkawinan"
                                                    name="status_perkawinan">
                                                    <option value="">-- Pilih Status Perkawinan --</option>
                                                    <option value="KAWIN">KAWIN</option>
                                                    <option value="KAWIN BELUM TERCATAT">KAWIN BELUM TERCATAT</option>
                                                    <option value="KAWIN TERCATAT">KAWIN TERCATAT</option>
                                                    <option value="BELUM KAWIN">BELUM KAWIN</option>
                                                    <option value="CERAI BELUM TERCATAT">CERAI BELUM TERCATAT</option>
                                                    <option value="CERAI HIDUP">CERAI HIDUP</option>
                                                    <option value="CERAI MATI">CERAI MATI</option>
                                                    <option value="CERAI TERCATAT">CERAI TERCATAT</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="alamat_sesuai_ktp" class="col-sm-2 col-form-label">Alamat Sesuai
                                                KTP</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="alamat_sesuai_ktp"
                                                    name="alamat_sesuai_ktp" placeholder="Masukan Alamat KTP Pemilik UMKM">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="kelurahan_sesuai_ktp" class="col-sm-2 col-form-label">Kelurahan
                                                Sesuai
                                                KTP</label>
                                            <div class="col-sm-10">
                                                <select id="kelurahan"
                                                    class="form-control @error('kelurahan') is-invalid @enderror"
                                                    name="kelurahan" required>
                                                    <option value="">-- Pilih Kelurahan --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="rw" class="col-sm-2 col-form-label">RW</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" id="rw" name="rw"
                                                    placeholder="Masukan RW Pemilik UMKM">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="rt" class="col-sm-2 col-form-label">RT</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" id="rt" name="rt"
                                                    placeholder="Masukan RT Pemilik UMKM">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="telp" class="col-sm-2 col-form-label">No Telepon</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" id="no_telp" name="no_telp"
                                                    placeholder="Masukan No Telepon Pemilik UMKM">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="pendidikan_terakhir" class="col-sm-2 col-form-label">Pendidikan
                                                Terakhir</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="pendidikan_terakhir"
                                                    name="pendidikan_terakhir">
                                                    <option value="">-- Pilih Pendidikan Terakhir --</option>
                                                    <option value="TIDAK/BELUM SEKOLAH">TIDAK/BELUM SEKOLAH</option>
                                                    <option value="BELUM TAMAT SD/SEDERAJAT">BELUM TAMAT SD/SEDERAJAT</option>
                                                    <option value="TAMAT SD/SEDERAJAT">TAMAT SD/SEDERAJAT</option>
                                                    <option value="SLTP/SEDERAJAT">SLTP/SEDERAJAT</option>
                                                    <option value="SLTA/SEDERAJAT">SLTA/SEDERAJAT</option>
                                                    <option value="DIPLOMA I/II">DIPLOMA I/II</option>
                                                    <option value="AKADEMI/DIPLOMA III/S. MUDA">AKADEMI/DIPLOMA III/S. MUDA
                                                    </option>
                                                    <option value="DIPLOMA IV/STRATA I">DIPLOMA IV/STRATA I</option>
                                                    <option value="STRATA II">STRATA II</option>
                                                    <option value="STRATA III">STRATA III</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="row mb-3">
                                            <label for="pendidikan_terakhir" class="col-sm-2 col-form-label">Status
                                                Keaktifan</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="status_keaktifan" name="status_keaktifan">
                                                    <option value="">-- Pilih Status Keaktifan --</option>
                                                    <option value="AKTIF">AKTIF</option>
                                                    <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                                </select>
                                            </div>
                                        </div>


                                    </div>

                                    <!-- Tab 2: Data UMKM (Modified for Multiple Entries) -->
                                    <div class="tab-pane fade" id="umkm" role="tabpanel" aria-labelledby="umkm-tab">
                                        <div class="mb-3">
                                            <h5>Data UMKM</h5>
                                        </div>

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


        <!-- Replace your current Product Modal with this updated version -->
        <!-- Modal Kelola Produk yang Diperbaiki -->
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
                                            <input type="text" class="form-control" id="product-jenis" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="product-tipe" class="col-sm-3 col-form-label">Tipe Produk</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="product-tipe" required>
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

        <!-- Add this script to detect Bootstrap version and adjust the modal buttons accordingly -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Detect Bootstrap version
                if (typeof bootstrap !== 'undefined') {
                    // Bootstrap 5
                    document.querySelectorAll('[data-dismiss="modal"]').forEach(function(el) {
                        el.classList.add('d-none');
                    });
                    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(el) {
                        el.classList.remove('d-none');
                    });
                } else {
                    // Bootstrap 4
                    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(el) {
                        el.classList.add('d-none');
                    });
                    document.querySelectorAll('[data-dismiss="modal"]').forEach(function(el) {
                        el.classList.remove('d-none');
                    });
                }
            });
        </script>

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
