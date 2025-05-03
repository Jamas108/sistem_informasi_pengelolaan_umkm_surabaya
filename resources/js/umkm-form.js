// Product Management Script (umkm-product.js)
$(document).ready(function() {
    console.log("Product management script loaded");

    // Check jQuery and Bootstrap
    console.log("jQuery version:", $.fn.jquery);
    if ($.fn.modal) {
        console.log("Bootstrap modal is available");
    } else if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal !== 'undefined') {
        console.log("Bootstrap 5 modal is available");
    } else {
        console.error("Bootstrap modal is NOT available! This is a critical issue.");
        // Try to give a helpful message about what might be wrong
        const bsScript = $('script[src*="bootstrap"]');
        if (bsScript.length === 0) {
            console.error("No Bootstrap script found on the page. Make sure to include bootstrap.js or bootstrap.bundle.js");
        } else {
            console.error("Bootstrap script is included but modal functionality is not available. Check script order - jQuery must be loaded before Bootstrap.");
        }
    }

    // Remove duplicate modals if they exist
    if ($('#addProductModal').length > 1) {
        console.log("Found multiple product modals, removing duplicates");
        $('#addProductModal').not(':first').remove();
    }

    // Initialize temporary products storage for new UMKMs
    window.tempProducts = {};
    window.tempProductCounter = {};

    // Add product button handler - MODIFIED to handle both existing and new UMKMs
    $(document).on('click', '.add-product-btn', function() {
        const umkmId = $(this).data('umkm-id');
        console.log("Add product clicked for UMKM ID:", umkmId);

        // Reset form and set values
        $('#add-product-form')[0].reset();
        $('#add_product_umkm_id').val(umkmId);
        $('#product_id').val('');
        $('#editing_mode').val('add');

        // Set modal title
        $('#addProductModalLabel').text('Tambah Produk');

        // Show modal - same approach as omset modal
        try {
            if (typeof bootstrap !== 'undefined') {
                // Bootstrap 5
                var myModal = new bootstrap.Modal(document.getElementById('addProductModal'));
                myModal.show();
            } else {
                // Bootstrap 4 or earlier
                $('#addProductModal').modal('show');
            }
            console.log("Product modal showing successfully");
        } catch (e) {
            console.error("Error showing modal:", e);
            alert("Terjadi kesalahan saat menampilkan modal. Silakan coba lagi.");
        }
    });

    // Edit product button handler - MODIFIED to handle both existing and new UMKMs
    $(document).on('click', '.edit-product-btn', function() {
        const productId = $(this).data('product-id');
        const umkmId = $(this).data('umkm-id');
        const jenisProduk = $(this).data('jenis-produk');
        const tipeProduk = $(this).data('tipe-produk');
        const status = $(this).data('status');
        const isTemp = $(this).data('is-temp');

        console.log("Edit product clicked:", {
            productId,
            umkmId,
            jenisProduk,
            tipeProduk,
            status,
            isTemp
        });

        // Set form values
        $('#add_product_umkm_id').val(umkmId);
        $('#product_id').val(productId);
        $('#add_product_jenis').val(jenisProduk);
        $('#add_product_tipe').val(tipeProduk);
        $('#add_product_status').val(status);
        $('#editing_mode').val('edit');
        $('#is_temp_product').val(isTemp ? '1' : '0');

        // Set modal title
        $('#addProductModalLabel').text('Edit Produk');

        // Show modal - same approach as omset modal
        try {
            if (typeof bootstrap !== 'undefined') {
                // Bootstrap 5
                var myModal = new bootstrap.Modal(document.getElementById('addProductModal'));
                myModal.show();
            } else {
                // Bootstrap 4 or earlier
                $('#addProductModal').modal('show');
            }
            console.log("Product modal showing successfully for edit");
        } catch (e) {
            console.error("Error showing modal for edit:", e);
            alert("Terjadi kesalahan saat menampilkan modal. Silakan coba lagi.");
        }
    });

    // Form submission handler - MODIFIED to handle both existing and new UMKMs
    $('#add-product-form').on('submit', function(e) {
        e.preventDefault();
        console.log("Product form submitted");

        // Disable the submit button to prevent multiple submissions
        $('button[form="add-product-form"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        const umkmId = $('#add_product_umkm_id').val();
        const productId = $('#product_id').val();
        const editingMode = $('#editing_mode').val();
        const isTemp = $('#is_temp_product').val() === '1' || umkmId.startsWith('new-');

        // Create data object
        const formData = {
            umkm_id: umkmId,
            jenis_produk: $('#add_product_jenis').val(),
            tipe_produk: $('#add_product_tipe').val(),
            status: $('#add_product_status').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        // Validate form
        if (!formData.jenis_produk || !formData.tipe_produk || !formData.status) {
            showAlert('warning', 'Semua kolom harus diisi', 'addProductModal');
            $('button[form="add-product-form"]').prop('disabled', false).html('Simpan');
            return;
        }

        console.log("Form data:", formData);
        console.log("Is temp product?", isTemp);

        // Handle differently based on whether the UMKM is new (temporary) or existing
        if (isTemp) {
            // This is a new UMKM - use local storage
            handleTempProduct(umkmId, productId, formData, editingMode);
        } else {
            // This is an existing UMKM - use AJAX
            if (editingMode === 'add') {
                // Add new product
                $.ajax({
                    url: '/store-product',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('button[form="add-product-form"]').prop('disabled', false).html('Simpan');

                        if (response.success) {
                            // Close modal
                            try {
                                if (typeof bootstrap !== 'undefined') {
                                    // Bootstrap 5
                                    var modalElement = document.getElementById('addProductModal');
                                    var modalInstance = bootstrap.Modal.getInstance(modalElement);
                                    if (modalInstance) {
                                        modalInstance.hide();
                                    }
                                } else {
                                    // Bootstrap 4 or earlier
                                    $('#addProductModal').modal('hide');
                                }
                            } catch (e) {
                                console.error("Error hiding modal:", e);
                            }

                            // Show success message
                            showAlert('success', 'Produk berhasil ditambahkan');

                            // Refresh page to see new product
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showAlert('danger', response.message || 'Terjadi kesalahan saat menyimpan produk', 'addProductModal');
                        }
                    },
                    error: function(xhr) {
                        $('button[form="add-product-form"]').prop('disabled', false).html('Simpan');

                        let errorMessage = 'Terjadi kesalahan saat menyimpan produk';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert('danger', errorMessage, 'addProductModal');
                    }
                });
            } else {
                // Update existing product
                $.ajax({
                    url: '/update-product/' + productId,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('button[form="add-product-form"]').prop('disabled', false).html('Simpan');

                        if (response.success) {
                            // Close modal
                            try {
                                if (typeof bootstrap !== 'undefined') {
                                    // Bootstrap 5
                                    var modalElement = document.getElementById('addProductModal');
                                    var modalInstance = bootstrap.Modal.getInstance(modalElement);
                                    if (modalInstance) {
                                        modalInstance.hide();
                                    }
                                } else {
                                    // Bootstrap 4 or earlier
                                    $('#addProductModal').modal('hide');
                                }
                            } catch (e) {
                                console.error("Error hiding modal:", e);
                            }

                            // Show success message
                            showAlert('success', 'Produk berhasil diperbarui');

                            // Refresh page to see updated product
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showAlert('danger', response.message || 'Terjadi kesalahan saat memperbarui produk', 'addProductModal');
                        }
                    },
                    error: function(xhr) {
                        $('button[form="add-product-form"]').prop('disabled', false).html('Simpan');

                        let errorMessage = 'Terjadi kesalahan saat memperbarui produk';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert('danger', errorMessage, 'addProductModal');
                    }
                });
            }
        }
    });

    // NEW FUNCTION: Handle temporary products for new UMKMs
    function handleTempProduct(umkmId, productId, formData, editingMode) {
        // Initialize temp products array for this UMKM if it doesn't exist
        if (!window.tempProducts[umkmId]) {
            window.tempProducts[umkmId] = [];
            window.tempProductCounter[umkmId] = 0;
        }

        if (editingMode === 'add') {
            // Add new temp product
            window.tempProductCounter[umkmId]++;
            const newTempProduct = {
                temp_id: 'temp-' + window.tempProductCounter[umkmId],
                umkm_id: umkmId,
                jenis_produk: formData.jenis_produk,
                tipe_produk: formData.tipe_produk,
                status: formData.status
            };

            window.tempProducts[umkmId].push(newTempProduct);

            // Close modal
            try {
                if (typeof bootstrap !== 'undefined') {
                    // Bootstrap 5
                    var modalElement = document.getElementById('addProductModal');
                    var modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                } else {
                    // Bootstrap 4 or earlier
                    $('#addProductModal').modal('hide');
                }
            } catch (e) {
                console.error("Error hiding modal:", e);
            }

            // Show success message
            showAlert('success', 'Produk berhasil ditambahkan secara lokal. Klik "Simpan Perubahan" untuk menyimpan ke database.', 'addProductModal');

            // Update the product table UI
            updateTempProductsTable(umkmId);

            // Enable submit button
            $('button[form="add-product-form"]').prop('disabled', false).html('Simpan');
        } else {
            // Update existing temp product
            const tempProductIndex = window.tempProducts[umkmId].findIndex(p => p.temp_id === productId);
            if (tempProductIndex !== -1) {
                window.tempProducts[umkmId][tempProductIndex] = {
                    ...window.tempProducts[umkmId][tempProductIndex],
                    jenis_produk: formData.jenis_produk,
                    tipe_produk: formData.tipe_produk,
                    status: formData.status
                };

                // Close modal
                try {
                    if (typeof bootstrap !== 'undefined') {
                        // Bootstrap 5
                        var modalElement = document.getElementById('addProductModal');
                        var modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    } else {
                        // Bootstrap 4 or earlier
                        $('#addProductModal').modal('hide');
                    }
                } catch (e) {
                    console.error("Error hiding modal:", e);
                }

                // Show success message
                showAlert('success', 'Produk berhasil diperbarui secara lokal. Klik "Simpan Perubahan" untuk menyimpan ke database.', 'addProductModal');

                // Update the product table UI
                updateTempProductsTable(umkmId);

                // Enable submit button
                $('button[form="add-product-form"]').prop('disabled', false).html('Simpan');
            }
        }
    }

    // NEW FUNCTION: Update the product table UI for temporary products
    function updateTempProductsTable(umkmId) {
        const tableId = `#products-table-${umkmId}`;
        const tableExists = $(tableId).length > 0;

        if (tableExists && window.tempProducts[umkmId] && window.tempProducts[umkmId].length > 0) {
            let tableRows = '';

            // Generate the table rows for each temp product
            window.tempProducts[umkmId].forEach((product, index) => {
                tableRows += `
                    <tr id="product-${product.temp_id}" data-product-id="${product.temp_id}">
                        <td class="text-center">${index + 1}</td>
                        <td>${product.jenis_produk}</td>
                        <td>${product.tipe_produk}</td>
                        <td>
                            ${product.status == 'AKTIF' ?
                                '<span class="badge badge-success">AKTIF</span>' :
                                '<span class="badge badge-danger">TIDAK AKTIF</span>'}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-warning edit-product-btn"
                                data-product-id="${product.temp_id}"
                                data-umkm-id="${product.umkm_id}"
                                data-jenis-produk="${product.jenis_produk}"
                                data-tipe-produk="${product.tipe_produk}"
                                data-status="${product.status}"
                                data-is-temp="1">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-temp-product-btn"
                                data-product-id="${product.temp_id}"
                                data-umkm-id="${product.umkm_id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            // Replace the empty row with our generated rows
            $(tableId + ' tbody').html(tableRows);
        }
    }

    // Delete product handler - MODIFIED to handle both existing and new UMKMs
    $(document).on('click', '.delete-product-btn', function() {
        const productId = $(this).data('product-id');
        const umkmId = $(this).data('umkm-id');

        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
            // Disable button
            const $button = $(this);
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: '/delete-product/' + productId,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        showAlert('success', 'Produk berhasil dihapus');

                        // Refresh page to see updated list
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        $button.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                        showAlert('danger', response.message || 'Terjadi kesalahan saat menghapus produk');
                    }
                },
                error: function(xhr) {
                    $button.prop('disabled', false).html('<i class="fas fa-trash"></i>');

                    let errorMessage = 'Terjadi kesalahan saat menghapus produk';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showAlert('danger', errorMessage);
                }
            });
        }
    });

    // NEW HANDLER: Delete temporary product
    $(document).on('click', '.delete-temp-product-btn', function() {
        const productId = $(this).data('product-id');
        const umkmId = $(this).data('umkm-id');

        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
            // Disable button
            const $button = $(this);
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            // Remove from temp storage
            if (window.tempProducts[umkmId]) {
                window.tempProducts[umkmId] = window.tempProducts[umkmId].filter(p => p.temp_id !== productId);

                // Update the UI
                updateTempProductsTable(umkmId);

                // Show success message
                showAlert('success', 'Produk lokal berhasil dihapus');

                // Enable button
                $button.prop('disabled', false).html('<i class="fas fa-trash"></i>');
            }
        }
    });

    // Modal debugging
    $('#addProductModal').on('show.bs.modal', function() {
        console.log("Product modal is about to be shown");
    }).on('shown.bs.modal', function() {
        console.log("Product modal has been shown");
        // Focus on the first input field
        $(this).find('input:visible:first').focus();
    }).on('hide.bs.modal', function() {
        console.log("Product modal is about to be hidden");
    }).on('hidden.bs.modal', function() {
        console.log("Product modal has been hidden");
        // Clear any modal alerts when hidden
        $(this).find('.alert').remove();
    });

    // Function to show alerts
    function showAlert(type, message, modalId = null) {
        // Hapus alert yang sudah ada dulu
        if (modalId) {
            $(`#${modalId} .alert`).remove();
        } else {
            $('.container-fluid > .alert').remove();
        }

        const alertHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        `;

        if (modalId) {
            $(`#${modalId} .modal-body`).prepend(alertHTML);
        } else {
            // Tambahkan hanya ke elemen container-fluid pertama
            $('.container-fluid:first').prepend(alertHTML);
        }

        // Auto-close after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // UMKM counter and management
    window.umkmCounter = $('.umkm-form-entry').length;
    console.log("Initial UMKM count:", window.umkmCounter);

    // Add UMKM button handler
    $('#add-umkm-btn').on('click', function() {
        addNewUmkmForm();
    });

    // Add remove button handlers for existing UMKMs
    $('.remove-umkm-btn').on('click', function() {
        const umkmId = $(this).data('umkm-id');
        if (confirm('Apakah Anda yakin ingin menghapus UMKM ini?')) {
            $(`#umkm-entry-${umkmId}`).remove();
            updateUmkmNumbers();
        }
    });

    // MODIFIED: Main form submission to include temp products
    $('form[action*="dataumkm.update"]').on('submit', function(e) {
        // Add temp products to form data
        for (const umkmId in window.tempProducts) {
            if (window.tempProducts[umkmId] && window.tempProducts[umkmId].length > 0) {
                window.tempProducts[umkmId].forEach((product, index) => {
                    // Extract the numeric part if it's a new-X format
                    let umkmIndex = umkmId;
                    if (umkmId.startsWith('new-')) {
                        umkmIndex = umkmId.replace('new-', '');
                    }

                    // Add hidden fields for each product
                    $(this).append(`<input type="hidden" name="temp_products[${umkmIndex}][${index}][jenis_produk]" value="${product.jenis_produk}">`);
                    $(this).append(`<input type="hidden" name="temp_products[${umkmIndex}][${index}][tipe_produk]" value="${product.tipe_produk}">`);
                    $(this).append(`<input type="hidden" name="temp_products[${umkmIndex}][${index}][status]" value="${product.status}">`);
                });
            }
        }

        // Let the form submit normally
        return true;
    });

    // Expose functions globally
    window.productHandler = {
        showAlert: showAlert,
        showAddProductModal: function(umkmId) {
            $('#add_product_umkm_id').val(umkmId);
            $('#product_id').val('');
            $('#editing_mode').val('add');
            $('#is_temp_product').val(umkmId.startsWith('new-') ? '1' : '0');
            $('#addProductModalLabel').text('Tambah Produk');

            try {
                if (typeof bootstrap !== 'undefined') {
                    var myModal = new bootstrap.Modal(document.getElementById('addProductModal'));
                    myModal.show();
                } else {
                    $('#addProductModal').modal('show');
                }
            } catch (e) {
                console.error("Error showing modal:", e);
            }
        },
        addTempProduct: function(umkmId, product) {
            if (!window.tempProducts[umkmId]) {
                window.tempProducts[umkmId] = [];
                window.tempProductCounter[umkmId] = 0;
            }
            window.tempProductCounter[umkmId]++;
            product.temp_id = 'temp-' + window.tempProductCounter[umkmId];
            window.tempProducts[umkmId].push(product);
            updateTempProductsTable(umkmId);
        },
        getTempProducts: function(umkmId) {
            return window.tempProducts[umkmId] || [];
        }
    };
});

/**
 * Add a new UMKM form with sequential numbering
 */
function addNewUmkmForm() {
    // Count visible entries to determine next visible index
    const visibleEntries = $('.umkm-form-entry').length;
    const nextIndex = visibleEntries;

    console.log("Adding UMKM with index:", nextIndex);

    // Create HTML for new UMKM form
    const newUmkmForm = `
        <div class="umkm-form-entry border rounded p-4 mb-4 shadow-sm" id="umkm-entry-${nextIndex}" data-umkm-id="${nextIndex}">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h5 class="m-0 umkm-number font-weight-bold text-primary">UMKM ${nextIndex + 1}</h5>
                <button type="button" class="btn btn-sm btn-danger remove-umkm-btn" data-umkm-id="${nextIndex}">
                    <i class="fas fa-times"></i> Hapus
                </button>
            </div>

            <!-- UMKM form fields -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="nama_usaha_${nextIndex}" class="col-sm-4 col-form-label font-weight-bold">Nama Usaha</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nama_usaha_${nextIndex}" name="umkm[${nextIndex}][nama_usaha]" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="alamat_usaha_${nextIndex}" class="col-sm-4 col-form-label font-weight-bold">Alamat Usaha</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="alamat_usaha_${nextIndex}" name="umkm[${nextIndex}][alamat]" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="pengelolaan_usaha_${nextIndex}" class="col-sm-4 col-form-label font-weight-bold">Pengelolaan Usaha</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="pengelolaan_usaha_${nextIndex}" name="umkm[${nextIndex}][pengelolaan_usaha]">
                                <option value="">-- Pilih --</option>
                                <option value="PERSEORANGAN / MANDIRI">PERSEORANGAN / MANDIRI</option>
                                <option value="KELOMPOK / SUBKON / KERJASAMA">KELOMPOK / SUBKON / KERJASAMA</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="klasifikasi_kinerja_usaha_${nextIndex}" class="col-sm-4 col-form-label font-weight-bold">Klasifikasi Kinerja</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="klasifikasi_kinerja_usaha_${nextIndex}" name="umkm[${nextIndex}][klasifikasi_kinerja_usaha]">
                                <option value="">-- Pilih --</option>
                                <option value="PEMULA">PEMULA</option>
                                <option value="MADYA">MADYA</option>
                                <option value="MANDIRI">MANDIRI</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="jumlah_tenaga_kerja_${nextIndex}" class="col-sm-4 col-form-label font-weight-bold">Jumlah Tenaga Kerja</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="jumlah_tenaga_kerja_${nextIndex}" name="umkm[${nextIndex}][jumlah_tenaga_kerja]">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="sektor_usaha_${nextIndex}" class="col-sm-4 col-form-label font-weight-bold">Sektor Usaha</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="sektor_usaha_${nextIndex}" name="umkm[${nextIndex}][sektor_usaha]">
                                <option value="">-- Pilih --</option>
                                <option value="INDUSTRI">INDUSTRI</option>
                                <option value="DAGANG">DAGANG</option>
                                <option value="JASA">JASA</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="status_${nextIndex}" class="col-sm-4 col-form-label font-weight-bold">Status Keaktifan</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="status_${nextIndex}" name="umkm[${nextIndex}][status]">
                                <option value="">-- Pilih --</option>
                                <option value="AKTIF" selected>AKTIF</option>
                                <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products section -->
            <div class="products-section mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="m-0 font-weight-bold text-primary">Produk UMKM</h5>
                    <button type="button" class="btn btn-sm btn-primary add-product-btn" data-umkm-id="new-${nextIndex}">
                        <i class="fas fa-plus-circle"></i> Tambah Produk
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="products-table-new-${nextIndex}">
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
                            <tr>
                                <td colspan="5" class="text-center">Belum ada produk untuk UMKM ini</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;

    // Add to container
    $('#umkm-entries-container').append(newUmkmForm);

    // Add event handler for the new remove button
    $(`#umkm-entry-${nextIndex} .remove-umkm-btn`).on('click', function() {
        if (confirm('Apakah Anda yakin ingin menghapus UMKM ini?')) {
            // Clear any temp products for this UMKM
            delete window.tempProducts[`new-${nextIndex}`];
            $(`#umkm-entry-${nextIndex}`).remove();
            updateUmkmNumbers();
        }
    });

    // Update numbering after adding
    updateUmkmNumbers();

    // Scroll to the new form
    $(`#umkm-entry-${nextIndex}`)[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/**
 * Update UMKM numbering
 */
function updateUmkmNumbers() {
    // Update visible numbering
    $('.umkm-form-entry').each(function(index) {
        $(this).find('.umkm-number').text(`UMKM ${index + 1}`);
    });

    // Update counter
    window.umkmCounter = $('.umkm-form-entry').length;
    console.log("Updated UMKM count:", window.umkmCounter);
}