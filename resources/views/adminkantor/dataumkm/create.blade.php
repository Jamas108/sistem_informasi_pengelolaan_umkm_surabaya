@extends('layouts.app')
@push('scripts')
    <script>
       $(document).ready(function() {
    // Initialize the counter for UMKM forms
    let umkmCounter = 0;

    // Add event listener for the NIK check button
    $("#cek_nik").click(function(e) {
        e.preventDefault();
        checkNik();
    });

    // Function to check if NIK exists in the pelaku_umkm database table
    function checkNik() {
        const nik = $("#search_nik").val().trim();

        // Only proceed if NIK is not empty
        if (nik.length > 0) {
            // Clear any existing alerts
            $("#nik-alert-container").remove();

            // Add loading indicator
            const loadingIndicator =
                '<div id="loading-indicator" class="text-center my-2"><i class="fas fa-spinner fa-spin"></i> Memeriksa NIK...</div>';
            $(loadingIndicator).insertAfter($(".row:has(#search_nik)"));

            // Make AJAX request to check if NIK exists in the database
            $.ajax({
                url: '/check-nik', // Direct URL path
                type: 'POST',
                data: {
                    nik: nik,
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
                dataType: 'json',
                success: function(response) {
                    // Remove loading indicator
                    $("#loading-indicator").remove();

                    if (response.exists) {
                        // NIK exists in the database - show danger alert
                        $('<div id="nik-alert-container" class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            '<strong>Perhatian!</strong> NIK ' + nik +
                            ' sudah terdaftar dalam sistem. ' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span>' +
                            '</button>' +
                            '</div>'
                        ).insertAfter($(".row:has(#search_nik)"));

                        // Populate the form fields with existing data from the response
                        if (response.data) {
                            // Fill in all form fields
                            $("#nik").val(response.data.nik || '');
                            $("#nama_lengkap").val(response.data.nama_lengkap || '');
                            $("#no_kk").val(response.data.no_kk || '');
                            $("#tempat_lahir").val(response.data.tempat_lahir || '');
                            $("#tgl_lahir").val(response.data.tgl_lahir || '');
                            $("#jenis_kelamin").val(response.data.jenis_kelamin || '');
                            $("#status_hubungan_keluarga").val(response.data
                                .status_hubungan_keluarga || '');
                            $("#status_perkawinan").val(response.data.status_perkawinan || '');
                            $("#alamat_sesuai_ktp").val(response.data.alamat_sesuai_ktp || '');
                            $("#kelurahan").val(response.data.kelurahan || '');
                            $("#rw").val(response.data.rw || '');
                            $("#rt").val(response.data.rt || '');
                            $("#no_telp").val(response.data.no_telp || '');
                            $("#pendidikan_terakhir").val(response.data.pendidikan_terakhir ||
                                '');
                            $("#status_keaktifan").val(response.data.status_keaktifan || '');
                        }
                    } else {
                        // NIK not found in the database - show success message
                        $('<div id="nik-alert-container" class="alert alert-success alert-dismissible fade show" role="alert">' +
                            '<strong>Sukses!</strong> NIK ' + nik +
                            ' belum terdaftar dalam sistem. ' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span>' +
                            '</button>' +
                            '</div>'
                        ).insertAfter($(".row:has(#search_nik)"));

                        // Clear form fields for new entry except NIK
                        $("#nik").val(nik);
                        $("#nama_lengkap").val('');
                        $("#no_kk").val('');
                        $("#tempat_lahir").val('');
                        $("#tgl_lahir").val('');
                        $("#jenis_kelamin").val('');
                        $("#status_hubungan_keluarga").val('');
                        $("#status_perkawinan").val('');
                        $("#alamat_sesuai_ktp").val('');
                        $("#kelurahan").val('');
                        $("#rw").val('');
                        $("#rt").val('');
                        $("#no_telp").val('');
                        $("#pendidikan_terakhir").val('');
                        $("#status_keaktifan").val('');
                    }
                },
                error: function(xhr, status, error) {
                    // Remove loading indicator
                    $("#loading-indicator").remove();

                    // Log detailed error information for debugging
                    console.error("AJAX Error: ", {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });

                    // Show error alert with more information
                    $('<div id="nik-alert-container" class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<strong>Error!</strong> Terjadi kesalahan saat memeriksa NIK: ' +
                        (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON
                            .message : error) +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                        '</div>'
                    ).insertAfter($(".row:has(#search_nik)"));
                }
            });
        }
    }

    // Add this function to update UMKM numbers (already referenced in your code)
    function updateUmkmNumbers() {
        $('.umkm-form-entry').each(function(index) {
            $(this).find('.umkm-number').text('UMKM #' + (index + 1));
        });
    }

    // Function to add a new UMKM form
    function addUmkmForm() {
        umkmCounter++;

        const newForm = `
            <div class="umkm-form-entry border rounded p-3 mb-4" id="umkm-entry-${umkmCounter}" data-umkm-id="${umkmCounter}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="m-0 umkm-number">UMKM #${$('.umkm-form-entry').length + 1}</h5>
                    <button type="button" class="btn btn-danger btn-sm remove-umkm" data-umkm-id="${umkmCounter}">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>

                <div class="row mb-3">
                    <label for="nama_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Nama Usaha</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_usaha_${umkmCounter}" name="umkm[${umkmCounter}][nama_usaha]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="alamat_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Alamat Usaha</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="alamat_usaha_${umkmCounter}" name="umkm[${umkmCounter}][alamat]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="jenis_produk_${umkmCounter}" class="col-sm-2 col-form-label">Jenis Produk</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="jenis_produk_${umkmCounter}" name="umkm[${umkmCounter}][jenis_produk]">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="tipe_produk_${umkmCounter}" class="col-sm-2 col-form-label">Tipe Produk</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="tipe_produk_${umkmCounter}" name="umkm[${umkmCounter}][tipe_produk]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="pengelolaan_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Pengelolaan Usaha</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="pengelolaan_usaha_${umkmCounter}" name="umkm[${umkmCounter}][pengelolaan_usaha]">
                    </div>
                </div>


                <div class="row mb-3">
                    <label for="klasifikasi_kinerja_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Klasifikasi Kinerja Usaha</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="klasifikasi_kinerja_usaha_${umkmCounter}" name="umkm[${umkmCounter}][klasifikasi_kinerja_usaha]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="jumlah_tenaga_kerja_${umkmCounter}" class="col-sm-2 col-form-label">Jumlah Tenaga Kerja</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="jumlah_tenaga_kerja_${umkmCounter}" name="umkm[${umkmCounter}][jumlah_tenaga_kerja]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="sektor_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Sektor Usaha</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="sektor_usaha_${umkmCounter}" name="umkm[${umkmCounter}][sektor_usaha]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="status_${umkmCounter}" class="col-sm-2 col-form-label">Status Keaktifan UMKM</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="status_${umkmCounter}" name="umkm[${umkmCounter}][status]">
                    </div>
                </div>
            </div>
            `;

        $('#umkm-entries-container').append(newForm);
    }

    // Add the first UMKM form by default
    addUmkmForm();

    // Add button to add another UMKM form
    $('#add-umkm-btn').click(function() {
        addUmkmForm();
    });

    // Remove UMKM form when remove button is clicked
    $(document).on('click', '.remove-umkm', function() {
        const umkmId = $(this).data('umkm-id');
        $(`#umkm-entry-${umkmId}`).remove();

        // Don't allow removing all entries - ensure at least one remains
        if ($('.umkm-form-entry').length === 0) {
            addUmkmForm();
        }

        // Update the UMKM numbers after removal
        updateUmkmNumbers();
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
                                            <input type="text" class="form-control" id="nik" name="nik">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="nama_pemilik" class="col-sm-2 col-form-label">Nama Pemilik</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nama_lengkap"
                                                name="nama_lengkap">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="no_kk_pemilik" class="col-sm-2 col-form-label">No KK Pemilik</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="no_kk" name="no_kk">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="tempat_lahir"
                                                name="tempat_lahir">
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
                                            <input type="text" class="form-control" id="jenis_kelamin"
                                                name="jenis_kelamin">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="status_hub_keluarga" class="col-sm-2 col-form-label">Status Hub.
                                            Keluarga</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="status_hubungan_keluarga"
                                                name="status_hubungan_keluarga">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="status" class="col-sm-2 col-form-label">Status Perkawinan</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="status_perkawinan"
                                                name="status_perkawinan">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="alamat_sesuai_ktp" class="col-sm-2 col-form-label">Alamat Sesuai
                                            KTP</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="alamat_sesuai_ktp"
                                                name="alamat_sesuai_ktp">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="kelurahan_sesuai_ktp" class="col-sm-2 col-form-label">Kelurahan Sesuai
                                            KTP</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="kelurahan" name="kelurahan">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="rw" class="col-sm-2 col-form-label">RW</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="rw" name="rw">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="rt" class="col-sm-2 col-form-label">RT</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="rt" name="rt">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="telp" class="col-sm-2 col-form-label">Telp</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="no_telp" name="no_telp">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="pendidikan_terakhir" class="col-sm-2 col-form-label">Pendidikan
                                            Terakhir</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="pendidikan_terakhir"
                                                name="pendidikan_terakhir">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="pendidikan_terakhir" class="col-sm-2 col-form-label">Status Keaktifan
                                            Pelaku UMKM</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="status_keaktifan"
                                                name="status_keaktifan">
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary"
                                            onclick="$('#umkm-tab').tab('show')">
                                            Selanjutnya <i class="fas fa-arrow-right"></i>
                                        </button>
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
                                        <button type="button" class="btn btn-secondary"
                                            onclick="$('#pelaku-tab').tab('show')">
                                            <i class="fas fa-arrow-left"></i> Kembali
                                        </button>
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
