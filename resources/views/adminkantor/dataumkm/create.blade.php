 @extends('layouts.app')
 @push('scripts')
    <script src="{{ asset('js/produk-umkm.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize the counter for UMKM forms
            let umkmCounter = 0;

            // Global variable to store products for each UMKM
            let umkmProducts = {};

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

            // Add this function to update UMKM numbers
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
                        <h5 class="m-0 umkm-number">UMKM #${umkmCounter}</h5>
                        <button type="button" class="btn btn-danger btn-sm remove-umkm" data-umkm-id="${umkmCounter}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>

                    <div class="row mb-3">
                        <label for="nama_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Nama Usaha</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_usaha_${umkmCounter}" name="umkm[${umkmCounter}][nama_usaha]" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="alamat_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Alamat Usaha</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat_usaha_${umkmCounter}" name="umkm[${umkmCounter}][alamat]" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="pengelolaan_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Pengelolaan Usaha</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="pengelolaan_usaha_${umkmCounter}" name="umkm[${umkmCounter}][pengelolaan_usaha]" required>
                                <option value="">-- Pilih --</option>
                                <option value="PERSEORANGAN / MANDIRI">PERSEORANGAN / MANDIRI</option>
                                <option value="KELOMPOK / SUBKON / KERJASAMA">KELOMPOK / SUBKON / KERJASAMA</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="klasifikasi_kinerja_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Klasifikasi Kinerja Usaha</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="klasifikasi_kinerja_usaha_${umkmCounter}" name="umkm[${umkmCounter}][klasifikasi_kinerja_usaha]" required>
                                <option value="">-- Pilih --</option>
                                <option value="PEMULA">PEMULA</option>
                                <option value="MADYA">MADYA</option>
                                <option value="MANDIRI">MANDIRI</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="jumlah_tenaga_kerja_${umkmCounter}" class="col-sm-2 col-form-label">Jumlah Tenaga Kerja</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="jumlah_tenaga_kerja_${umkmCounter}" name="umkm[${umkmCounter}][jumlah_tenaga_kerja]" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="sektor_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Sektor Usaha</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="sektor_usaha_${umkmCounter}" name="umkm[${umkmCounter}][sektor_usaha]" required>
                                <option value="">-- Pilih --</option>
                                <option value="INDUSTRI">INDUSTRI</option>
                                <option value="DAGANG">DAGANG</option>
                                <option value="JASA">JASA</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="status_${umkmCounter}" class="col-sm-2 col-form-label">Status Keaktifan UMKM</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="status_${umkmCounter}" name="umkm[${umkmCounter}][status]" required>
                                <option value="">-- Pilih --</option>
                                <option value="AKTIF">AKTIF</option>
                                <option value="CUKUP AKTIF">CUKUP AKTIF</option>
                                <option value="KURANG AKTIF">KURANG AKTIF</option>
                                <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                            <button type="button" class="btn btn-info manage-products-btn" data-umkm-id="${umkmCounter}">
                                <i class="fas fa-boxes"></i> Tambah Produk UMKM
                            </button>
                            <span class="ms-2 badge bg-secondary product-count text-white" data-umkm-id="${umkmCounter}">0 produk</span>
                        </div>
                    </div>

                    <!-- Hidden container for product data -->
                    <div class="hidden-product-fields d-none" id="product-container-${umkmCounter}"></div>
                </div>
                `;

                $('#umkm-entries-container').append(newForm);

                // Initialize the products array for this UMKM
                umkmProducts[umkmCounter] = [];

                console.log(`Added UMKM form #${umkmCounter} with product button`);
            }

            // Check if there are existing UMKM forms
            if ($('.umkm-form-entry').length === 0) {
                // Add the first UMKM form by default
                addUmkmForm();
            }

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

                // Remove products for this UMKM
                if (umkmProducts[umkmId]) {
                    delete umkmProducts[umkmId];
                }
            });

            // Function to detect Bootstrap version
            function detectBootstrapVersion() {
                return typeof bootstrap !== 'undefined' ? 5 : 4;
            }

            // Function to show modal based on Bootstrap version
            function showModal(modalId) {
                console.log(`Attempting to show modal: #${modalId}`);

                try {
                    if (detectBootstrapVersion() === 5) {
                        const modalElement = document.getElementById(modalId);
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                        console.log(`Modal #${modalId} shown using Bootstrap 5`);
                    } else {
                        $(`#${modalId}`).modal('show');
                        console.log(`Modal #${modalId} shown using Bootstrap 4`);
                    }
                    return true;
                } catch (error) {
                    console.error(`Error showing modal:`, error);
                    return false;
                }
            }

            // Function to hide modal based on Bootstrap version
            function hideModal(modalId) {
                const bootstrapVersion = detectBootstrapVersion();

                if (bootstrapVersion === 5) {
                    const modalElement = document.getElementById(modalId);
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                } else {
                    $(`#${modalId}`).modal('hide');
                }
            }

            // Product Modal Open
            $(document).on('click', '.manage-products-btn', function() {
                const umkmId = $(this).data('umkm-id');
                console.log('Product button clicked for UMKM ID:', umkmId);

                // Set the current UMKM ID to the modal
                $('#current-umkm-id').val(umkmId);

                // Reset the form
                resetProductForm();

                // Load existing products for this UMKM
                refreshProductTable(umkmId);

                // Update modal title with UMKM name
                const umkmName = $(`#nama_usaha_${umkmId}`).val() || `UMKM #${umkmId}`;
                $('#productModalLabel').text(`Produk untuk ${umkmName}`);

                // Show modal
                showModal('productModal');
            });

            // Reset product form
            function resetProductForm() {
                $('#product-form-title').text('Tambah Produk Baru');
                $('#product-id').val('');
                $('#product-jenis').val('');
                $('#product-tipe').val('');
                $('#product-status').val('AKTIF'); // Default to AKTIF
                $('#editing-mode').val('add');
            }

            // Reset button click
            $('#reset-product-form').click(function() {
                resetProductForm();
            });

            // Save product form submission
            $('#product-form').submit(function(e) {
                e.preventDefault();

                const umkmId = $('#current-umkm-id').val();
                const productId = $('#product-id').val() || generateProductId();
                const editingMode = $('#editing-mode').val();

                // Validate form fields
                const jenisProduct = $('#product-jenis').val();
                const tipeProduct = $('#product-tipe').val();
                const statusProduct = $('#product-status').val();

                if (!jenisProduct || !tipeProduct || !statusProduct) {
                    showFormAlert('warning', 'Semua field produk harus diisi', $('#product-form'));
                    return;
                }

                const productData = {
                    id: productId,
                    jenis_produk: jenisProduct,
                    tipe_produk: tipeProduct,
                    status: statusProduct
                };

                // Initialize array if needed
                if (!umkmProducts[umkmId]) {
                    umkmProducts[umkmId] = [];
                }

                if (editingMode === 'edit') {
                    // Update existing product
                    const index = umkmProducts[umkmId].findIndex(p => p.id === productId);
                    if (index !== -1) {
                        umkmProducts[umkmId][index] = productData;
                    }
                } else {
                    // Add new product
                    umkmProducts[umkmId].push(productData);
                }

                // Refresh the product table
                refreshProductTable(umkmId);

                // Reset the form
                resetProductForm();

                // Update hidden fields
                createHiddenProductFields(umkmId);

                // Show success message
                showFormAlert('success', `Produk berhasil ${editingMode === 'edit' ? 'diperbarui' : 'ditambahkan'}!`, $('.modal-body'));

                // Update the product count badge
                updateProductCountBadge(umkmId);
            });

            // Helper function to show alerts in forms
            function showFormAlert(type, message, container) {
                // Remove any existing alerts
                container.find('.alert').remove();

                // Create the alert based on Bootstrap version
                let alertHTML;
                if (detectBootstrapVersion() === 5) {
                    alertHTML = `
                        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                } else {
                    alertHTML = `
                        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                            ${message}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `;
                }

                // Insert the alert at the beginning of the container
                container.prepend(alertHTML);

                // Auto dismiss after 5 seconds
                setTimeout(function() {
                    container.find('.alert').alert('close');
                }, 5000);
            }

            // Generate a unique product ID
            function generateProductId() {
                return 'product_' + new Date().getTime() + '_' + Math.floor(Math.random() * 1000);
            }

            // Refresh product table
            function refreshProductTable(umkmId) {
                const products = umkmProducts[umkmId] || [];
                const tableBody = $('#product-list-body');

                // Clear the table
                tableBody.empty();

                // If no products, show a message
                if (products.length === 0) {
                    tableBody.html(`
                        <tr>
                            <td colspan="4" class="text-center">Belum ada produk untuk UMKM ini</td>
                        </tr>
                    `);
                    return;
                }

                // Add each product to the table
                products.forEach(function(product, index) {
                    const row = `
                        <tr>
                            <td>${product.jenis_produk}</td>
                            <td>${product.tipe_produk}</td>
                            <td>
                                <span class="badge ${detectBootstrapVersion() === 5 ? 'bg-' : 'badge-'}${product.status === 'AKTIF' ? 'success' : 'danger'} text-white">
                                    ${product.status}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning edit-product" data-product-id="${product.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-product" data-product-id="${product.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
            }

            // Edit product
            $(document).on('click', '.edit-product', function() {
                const productId = $(this).data('product-id');
                const umkmId = $('#current-umkm-id').val();

                // Find the product in the array
                const product = umkmProducts[umkmId].find(p => p.id === productId);

                if (product) {
                    // Fill the form with product data
                    $('#product-form-title').text('Edit Produk');
                    $('#product-id').val(product.id);
                    $('#product-jenis').val(product.jenis_produk);
                    $('#product-tipe').val(product.tipe_produk);
                    $('#product-status').val(product.status);
                    $('#editing-mode').val('edit');

                    // Scroll to the form
                    $('.modal-body').animate({
                        scrollTop: $('#product-form').offset().top
                    }, 'slow');
                }
            });

            // Delete product
            $(document).on('click', '.delete-product', function() {
                if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                    const productId = $(this).data('product-id');
                    const umkmId = $('#current-umkm-id').val();

                    // Remove the product from the array
                    umkmProducts[umkmId] = umkmProducts[umkmId].filter(p => p.id !== productId);

                    // Refresh the table
                    refreshProductTable(umkmId);

                    // Update the product count badge
                    updateProductCountBadge(umkmId);

                    // Update hidden fields
                    createHiddenProductFields(umkmId);

                    // Show success message
                    showFormAlert('success', 'Produk berhasil dihapus!', $('.modal-body'));
                }
            });

            // Apply products button
            $('#apply-products').click(function() {
                const umkmId = $('#current-umkm-id').val();

                // Update hidden fields
                createHiddenProductFields(umkmId);

                // Close the modal
                hideModal('productModal');

                // Show success message
                showFormAlert('success', 'Data produk berhasil diterapkan!', $('#umkm'));
            });

            // Create hidden fields for the form submission
            function createHiddenProductFields(umkmId) {
                const products = umkmProducts[umkmId] || [];
                const hiddenFieldsContainer = $(`#product-container-${umkmId}`);

                // Clear existing hidden fields
                hiddenFieldsContainer.empty();

                // Add hidden fields for each product
                products.forEach(function(product, index) {
                    hiddenFieldsContainer.append(`
                        <input type="hidden" name="umkm[${umkmId}][products][${index}][jenis_produk]" value="${product.jenis_produk}">
                        <input type="hidden" name="umkm[${umkmId}][products][${index}][tipe_produk]" value="${product.tipe_produk}">
                        <input type="hidden" name="umkm[${umkmId}][products][${index}][status]" value="${product.status}">
                    `);
                });
            }

            // Update product count badge
            function updateProductCountBadge(umkmId) {
                const products = umkmProducts[umkmId] || [];
                const badge = $(`.product-count[data-umkm-id="${umkmId}"]`);

                badge.text(`${products.length} produk`);

                // Change badge color based on count
                if (products.length > 0) {
                    if (detectBootstrapVersion() === 5) {
                        badge.removeClass('bg-secondary').addClass('bg-primary');
                    } else {
                        badge.removeClass('badge-secondary').addClass('badge-primary');
                    }
                } else {
                    if (detectBootstrapVersion() === 5) {
                        badge.removeClass('bg-primary').addClass('bg-secondary');
                    } else {
                        badge.removeClass('badge-primary').addClass('badge-secondary');
                    }
                }
            }

            // Close modal buttons
            $(document).on('click', '.modal-close-btn, [data-dismiss="modal"], [data-bs-dismiss="modal"]', function() {
                const modalId = $(this).closest('.modal').attr('id');
                hideModal(modalId);
            });
        });

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
                                         <label for="pendidikan_terakhir" class="col-sm-2 col-form-label">Status Keaktifan
                                             Pelaku UMKM</label>
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
