// Product UMKM management script
$(document).ready(function () {
    console.log("Product UMKM handler script loaded");

    // Initialize umkmCounter for both inline script and external script
    window.umkmCounter = $('.umkm-form-entry').length > 0 ? $('.umkm-form-entry').length : 0;

    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Add UMKM Button Click Event
    $('#add-umkm-btn-edit').off('click').on('click', function() {
        addNewUmkmEdit();
    });

    // Add Product Button Click Event - Using document for dynamically added elements
    $(document).on('click', '.add-product-btn', function () {
        const umkmId = $(this).data('umkm-id');
        console.log("Add product button clicked for UMKM ID:", umkmId);

        // Reset form fields
        $('#add-product-form')[0].reset();
        $('#product_id').val('');
        $('#add_product_umkm_id').val(umkmId);
        $('#editing_mode').val('add');

        // Check if this is a temp UMKM (not yet saved to database)
        if (String(umkmId).startsWith('temp_')) {
            $('#is_temp_product').val('1');
        } else {
            $('#is_temp_product').val('0');
        }

        // Update modal title
        $('#addProductModalLabel').text('Tambah Produk');

        // Show modal with robust fallback approach
        showModalWithFallback('addProductModal');
    });

    // Edit Product Button Click Event
    $(document).on('click', '.edit-product-btn', function () {
        const productId = $(this).data('product-id');
        const umkmId = $(this).data('umkm-id');
        const jenisProduk = $(this).data('jenis-produk');
        const tipeProduk = $(this).data('tipe-produk');
        const status = $(this).data('status');

        // Fill form with product data
        $('#product_id').val(productId);
        $('#add_product_umkm_id').val(umkmId);
        $('#add_product_jenis').val(jenisProduk);
        $('#add_product_tipe').val(tipeProduk);
        $('#add_product_status').val(status);
        $('#editing_mode').val('edit');

        // Check if this is a temp UMKM
        if (String(umkmId).startsWith('temp_')) {
            $('#is_temp_product').val('1');
        } else {
            $('#is_temp_product').val('0');
        }

        // Update modal title
        $('#addProductModalLabel').text('Edit Produk');

        // Show modal with robust fallback
        showModalWithFallback('addProductModal');
    });

    // Delete Product Button Click Event - FIX: Remove redundant confirmation
    // Use a flag to prevent multiple confirmations
    $(document).on('click', '.delete-product-btn', function (e) {
        // Prevent any default action or event bubbling
        e.preventDefault();
        e.stopPropagation();

        // Use data attribute to track if button has been clicked
        if ($(this).data('processing')) {
            console.log("Delete operation already in progress");
            return;
        }

        const productId = $(this).data('product-id');
        const umkmId = $(this).data('umkm-id');

        // Mark as processing
        $(this).data('processing', true);

        // Show confirmation
        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
            deleteProduct(productId, umkmId);
        } else {
            // Reset processing flag if cancelled
            $(this).data('processing', false);
        }
    });

    // Product Form Submit Handler
    $('#add-product-form').off('submit').on('submit', function (e) {
        e.preventDefault();
        console.log("Product form submitted");

        // Add flag to prevent double submissions
        if ($(this).data('submitting')) {
            console.log("Preventing duplicate submission");
            return;
        }

        $(this).data('submitting', true);

        // Validate form
        if (!validateProductForm()) {
            showAlert('warning', 'Mohon lengkapi semua field yang wajib diisi', 'addProductModal');
            $(this).data('submitting', false);
            return;
        }

        // Get form data
        const umkmId = $('#add_product_umkm_id').val();
        const productId = $('#product_id').val();
        const jenisProduk = $('#add_product_jenis').val();
        const tipeProduk = $('#add_product_tipe').val();
        const status = $('#add_product_status').val();
        const isEdit = $('#editing_mode').val() === 'edit';
        const isTemp = $('#is_temp_product').val() === '1';

        if (isEdit) {
            // Update existing product
            if (isTemp) {
                // For temp product - update in the DOM only
                updateTempProduct(productId, umkmId, jenisProduk, tipeProduk, status);
                hideModal('addProductModal');
                showAlert('success', 'Produk berhasil diperbarui (lokal)');
                $(this).data('submitting', false);
            } else {
                // For real product - send to server via API
                updateServerProduct(productId, jenisProduk, tipeProduk, status);
                $(this).data('submitting', false);
            }
        } else {
            // Add new product
            if (isTemp) {
                // For temp UMKM - add to DOM only
                addTempProduct(umkmId, jenisProduk, tipeProduk, status);
                hideModal('addProductModal');
                showAlert('success', 'Produk berhasil ditambahkan (lokal)');
                $(this).data('submitting', false);
            } else {
                // For real UMKM - send to server via API
                addServerProduct(umkmId, jenisProduk, tipeProduk, status);
                // Note: The submitting flag is reset inside the completion of the AJAX call
            }
        }
    });

    // Function to validate product form
    function validateProductForm() {
        let isValid = true;

        // Validate jenis produk
        const jenisProduk = $('#add_product_jenis').val().trim();
        if (!jenisProduk) {
            $('#add_product_jenis').addClass('is-invalid');
            isValid = false;
        } else {
            $('#add_product_jenis').removeClass('is-invalid');
        }

        // Validate tipe produk
        const tipeProduk = $('#add_product_tipe').val().trim();
        if (!tipeProduk) {
            $('#add_product_tipe').addClass('is-invalid');
            isValid = false;
        } else {
            $('#add_product_tipe').removeClass('is-invalid');
        }

        // Validate status
        const status = $('#add_product_status').val();
        if (!status) {
            $('#add_product_status').addClass('is-invalid');
            isValid = false;
        } else {
            $('#add_product_status').removeClass('is-invalid');
        }

        return isValid;
    }

    function addNewUmkmEdit() {
        // Prevent multiple rapid clicks
        $('#add-umkm-btn-edit').prop('disabled', true);

        // Increment the counter once
        window.umkmCounter++;
        console.log("Adding new UMKM, counter:", window.umkmCounter);

        const currentIndex = window.umkmCounter - 1;

        const newUmkmHtml = `
            <div class="umkm-form-entry border rounded p-4 mb-4 shadow-sm" id="umkm-entry-${currentIndex}" data-umkm-id="${currentIndex}">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="m-0 umkm-number font-weight-bold text-primary">UMKM ${window.umkmCounter}</h5>
                    <button type="button" class="btn btn-sm btn-danger remove-umkm-btn" data-umkm-index="${currentIndex}">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="nama_usaha_${currentIndex}" class="col-sm-4 col-form-label font-weight-bold">Nama Usaha <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control required-field" id="nama_usaha_${currentIndex}" name="umkm[${currentIndex}][nama_usaha]" value="">
                                <div class="invalid-feedback">Nama usaha wajib diisi</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="alamat_usaha_${currentIndex}" class="col-sm-4 col-form-label font-weight-bold">Alamat Usaha <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control required-field" id="alamat_usaha_${currentIndex}" name="umkm[${currentIndex}][alamat]" value="">
                                <div class="invalid-feedback">Alamat usaha wajib diisi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="pengelolaan_usaha_${currentIndex}" class="col-sm-4 col-form-label font-weight-bold">Pengelolaan Usaha <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control required-field" id="pengelolaan_usaha_${currentIndex}" name="umkm[${currentIndex}][pengelolaan_usaha]">
                                    <option value="">-- Pilih --</option>
                                    <option value="PERSEORANGAN / MANDIRI">PERSEORANGAN / MANDIRI</option>
                                    <option value="KELOMPOK / SUBKON / KERJASAMA">KELOMPOK / SUBKON / KERJASAMA</option>
                                </select>
                                <div class="invalid-feedback">Pengelolaan usaha wajib dipilih</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="klasifikasi_kinerja_usaha_${currentIndex}" class="col-sm-4 col-form-label font-weight-bold">Klasifikasi Kinerja <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control required-field" id="klasifikasi_kinerja_usaha_${currentIndex}" name="umkm[${currentIndex}][klasifikasi_kinerja_usaha]">
                                    <option value="">-- Pilih --</option>
                                    <option value="PEMULA">PEMULA</option>
                                    <option value="MADYA">MADYA</option>
                                    <option value="MANDIRI">MANDIRI</option>
                                </select>
                                <div class="invalid-feedback">Klasifikasi kinerja wajib dipilih</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="jumlah_tenaga_kerja_${currentIndex}" class="col-sm-4 col-form-label font-weight-bold">Jumlah Tenaga Kerja <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control required-field" id="jumlah_tenaga_kerja_${currentIndex}" name="umkm[${currentIndex}][jumlah_tenaga_kerja]" value="">
                                <div class="invalid-feedback">Jumlah tenaga kerja wajib diisi</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="sektor_usaha_${currentIndex}" class="col-sm-4 col-form-label font-weight-bold">Sektor Usaha <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control required-field" id="sektor_usaha_${currentIndex}" name="umkm[${currentIndex}][sektor_usaha]">
                                    <option value="">-- Pilih --</option>
                                    <option value="INDUSTRI">INDUSTRI</option>
                                    <option value="DAGANG">DAGANG</option>
                                    <option value="JASA">JASA</option>
                                </select>
                                <div class="invalid-feedback">Sektor usaha wajib dipilih</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="status_${currentIndex}" class="col-sm-4 col-form-label font-weight-bold">Status Keaktifan <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control required-field" id="status_${currentIndex}" name="umkm[${currentIndex}][status]">
                                    <option value="">-- Pilih --</option>
                                    <option value="AKTIF">AKTIF</option>
                                    <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                </select>
                                <div class="invalid-feedback">Status keaktifan wajib dipilih</div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="umkm[${currentIndex}][id]" value="">

                <div class="products-section mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="m-0 font-weight-bold text-primary">Produk UMKM</h5>
                        <button type="button" class="btn btn-sm btn-primary add-product-btn" data-umkm-id="temp_${currentIndex}">
                            <i class="fas fa-plus-circle"></i> Tambah Produk
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="products-table-temp_${currentIndex}">
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

        $('#umkm-entries-container').append(newUmkmHtml);

        // Add event listener for the remove button
        $(`#umkm-entry-${currentIndex} .remove-umkm-btn`).on('click', function () {
            if (confirm('Apakah Anda yakin ingin menghapus UMKM ini?')) {
                $(`#umkm-entry-${currentIndex}`).remove();

                // Re-order remaining UMKMs if needed
                updateUmkmNumbers();
            }
        });

        // Re-enable the add button after a short delay
        setTimeout(function() {
            $('#add-umkm-btn').prop('disabled', false);
        }, 500);
    }

    // Function to update UMKM numbering after deletion
    function updateUmkmNumbers() {
        $('.umkm-form-entry').each(function(index) {
            $(this).find('.umkm-number').text('UMKM ' + (index + 1));
        });
    }

    // Function to add a product to the server via API
    function addServerProduct(umkmId, jenisProduk, tipeProduk, status) {
        // Show loading indicator

        // Disable submit button during request
        $('#add-product-form button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: '/store-product',
            type: 'POST',
            data: {
                umkm_id: umkmId,
                jenis_produk: jenisProduk,
                tipe_produk: tipeProduk,
                status: status
            },
            success: function (response) {
                hideLoading();
                $('#add-product-form').data('submitting', false);
                $('#add-product-form button[type="submit"]').prop('disabled', false);

                if (response.success) {
                    // Get the product table
                    const productTable = $(`#products-table-${umkmId} tbody`);

                    // Clear "no products" row if it exists
                    if (productTable.find('tr td[colspan="5"]').length > 0) {
                        productTable.empty();
                    }

                    // Add new row to the table
                    const rowCount = productTable.find('tr').length + 1;
                    const newRow = `
                        <tr id="product-${response.data.id}" data-product-id="${response.data.id}">
                            <td class="text-center">${rowCount}</td>
                            <td>${response.data.jenis_produk}</td>
                            <td>${response.data.tipe_produk}</td>
                            <td>
                                ${response.data.status === 'AKTIF' ?
                            '<span class="badge badge-success">AKTIF</span>' :
                            '<span class="badge badge-danger">TIDAK AKTIF</span>'}
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning edit-product-btn"
                                    data-product-id="${response.data.id}"
                                    data-umkm-id="${umkmId}"
                                    data-jenis-produk="${response.data.jenis_produk}"
                                    data-tipe-produk="${response.data.tipe_produk}"
                                    data-status="${response.data.status}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-product-btn"
                                    data-product-id="${response.data.id}"
                                    data-umkm-id="${umkmId}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    productTable.append(newRow);

                    // Hide modal
                    hideModal('addProductModal');

                    // Show success alert
                    showAlert('success', 'Produk berhasil ditambahkan');
                } else {
                    // Show error message
                    showAlert('danger', 'Gagal menambahkan produk: ' + response.message, 'addProductModal');
                }
            },
            error: function (xhr) {
                hideLoading();
                $('#add-product-form').data('submitting', false);
                $('#add-product-form button[type="submit"]').prop('disabled', false);

                let errorMessage = 'Terjadi kesalahan saat menyimpan produk';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('danger', errorMessage, 'addProductModal');
            }
        });
    }

    // Function to update a product on the server via API
    function updateServerProduct(productId, jenisProduk, tipeProduk, status) {
        // Show loading indicator

        // Disable submit button during request
        $('#add-product-form button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: `/update-product/${productId}`,
            type: 'PUT',
            data: {
                jenis_produk: jenisProduk,
                tipe_produk: tipeProduk,
                status: status
            },
            success: function (response) {
                hideLoading();
                $('#add-product-form').data('submitting', false);
                $('#add-product-form button[type="submit"]').prop('disabled', false);

                if (response.success) {
                    // Update row in the table
                    const productRow = $(`#product-${productId}`);

                    // Update the row content
                    productRow.find('td:nth-child(2)').text(response.data.jenis_produk);
                    productRow.find('td:nth-child(3)').text(response.data.tipe_produk);
                    productRow.find('td:nth-child(4)').html(
                        response.data.status === 'AKTIF' ?
                            '<span class="badge badge-success">AKTIF</span>' :
                            '<span class="badge badge-danger">TIDAK AKTIF</span>'
                    );

                    // Update data attributes for edit button
                    productRow.find('.edit-product-btn')
                        .data('jenis-produk', response.data.jenis_produk)
                        .attr('data-jenis-produk', response.data.jenis_produk)
                        .data('tipe-produk', response.data.tipe_produk)
                        .attr('data-tipe-produk', response.data.tipe_produk)
                        .data('status', response.data.status)
                        .attr('data-status', response.data.status);

                    // Hide modal
                    hideModal('addProductModal');

                    // Show success alert
                    showAlert('success', 'Produk berhasil diperbarui');
                } else {
                    // Show error message
                    showAlert('danger', 'Gagal memperbarui produk: ' + response.message, 'addProductModal');
                }
            },
            error: function (xhr) {
                hideLoading();
                $('#add-product-form').data('submitting', false);
                $('#add-product-form button[type="submit"]').prop('disabled', false);

                let errorMessage = 'Terjadi kesalahan saat memperbarui produk';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('danger', errorMessage, 'addProductModal');
            }
        });
    }

    // Function to delete a product from the server via API
    function deleteProduct(productId, umkmId) {
        // Check if this is a temp or local product
        const isTemp = String(productId).startsWith('temp_prod_');
        const isLocal = String(productId).startsWith('local_prod_');

        if (isTemp || isLocal) {
            // For temp/local products (not yet in database)
            const productRow = $(`#product-${productId}`);

            if (isTemp) {
                // For temp products (new UMKM)
                const umkmIndex = umkmId.replace('temp_', '');
                const productIndex = productRow.index();

                // Remove the hidden inputs
                $(`input[name^="umkm[${umkmIndex}][products][${productIndex}]"]`).remove();
            } else {
                // For local products (existing UMKM)
                // Remove any hidden fields for this product
                $(`input[name="new_products[${umkmId}][]"][value^="${productRow.find('td:nth-child(2)').text()}|"]`).remove();
            }

            // Remove the row
            productRow.remove();

            // Update row numbers
            updateProductRowNumbers(umkmId);

            // Show success alert
            showAlert('success', 'Produk berhasil dihapus (lokal)');

            // Reset processing flag on all delete buttons
            $('.delete-product-btn').data('processing', false);
        } else {
            // For existing products in the database - delete via API
            // Show loading indicator

            $.ajax({
                url: `/delete-product/${productId}`,
                type: 'DELETE',
                success: function (response) {
                    hideLoading();

                    if (response.success) {
                        // Remove row from the table
                        $(`#product-${productId}`).remove();

                        // Update row numbers
                        updateProductRowNumbers(umkmId);

                        // Show success alert
                        showAlert('success', 'Produk berhasil dihapus');
                    } else {
                        // Show error message
                        showAlert('danger', 'Gagal menghapus produk: ' + response.message);
                    }

                    // Reset processing flag on all delete buttons
                    $('.delete-product-btn').data('processing', false);
                },
                error: function (xhr) {
                    hideLoading();
                    let errorMessage = 'Terjadi kesalahan saat menghapus produk';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showAlert('danger', errorMessage);

                    // Reset processing flag on all delete buttons
                    $('.delete-product-btn').data('processing', false);
                }
            });
        }
    }

    // Function to add temporary product (for new UMKMs not yet saved)
    function addTempProduct(umkmId, jenisProduk, tipeProduk, status) {
        // Generate temporary product ID
        const tempProductId = 'temp_prod_' + new Date().getTime();

        // Get the product table for this temp UMKM
        const productTable = $(`#products-table-${umkmId} tbody`);

        // Clear "no products" row if it exists
        if (productTable.find('tr td[colspan="5"]').length > 0) {
            productTable.empty();
        }

        // Add new row to the table
        const rowCount = productTable.find('tr').length + 1;
        const newRow = `
            <tr id="product-${tempProductId}" data-product-id="${tempProductId}">
                <td class="text-center">${rowCount}</td>
                <td>${jenisProduk}</td>
                <td>${tipeProduk}</td>
                <td>
                    ${status === 'AKTIF' ?
                '<span class="badge badge-success">AKTIF</span>' :
                '<span class="badge badge-danger">TIDAK AKTIF</span>'}
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-warning edit-product-btn"
                        data-product-id="${tempProductId}"
                        data-umkm-id="${umkmId}"
                        data-jenis-produk="${jenisProduk}"
                        data-tipe-produk="${tipeProduk}"
                        data-status="${status}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger delete-product-btn"
                        data-product-id="${tempProductId}"
                        data-umkm-id="${umkmId}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        productTable.append(newRow);

        // Add hidden inputs to the form to include this product data on form submission
        const umkmIndex = umkmId.replace('temp_', '');
        const productCount = productTable.find('tr').length;

        const hiddenFields = `
            <input type="hidden" name="umkm[${umkmIndex}][products][${productCount - 1}][jenis_produk]" value="${jenisProduk}">
            <input type="hidden" name="umkm[${umkmIndex}][products][${productCount - 1}][tipe_produk]" value="${tipeProduk}">
            <input type="hidden" name="umkm[${umkmIndex}][products][${productCount - 1}][status]" value="${status}">
        `;

        $('#umkm-entry-' + umkmIndex).append(hiddenFields);
    }

    // Function to update temp product
    function updateTempProduct(productId, umkmId, jenisProduk, tipeProduk, status) {
        // Find the product row
        const productRow = $(`#product-${productId}`);

        if (!productRow.length) {
            console.error('Product row not found:', productId);
            return;
        }

        // Update the row content
        productRow.find('td:nth-child(2)').text(jenisProduk);
        productRow.find('td:nth-child(3)').text(tipeProduk);
        productRow.find('td:nth-child(4)').html(
            status === 'AKTIF' ?
                '<span class="badge badge-success">AKTIF</span>' :
                '<span class="badge badge-danger">TIDAK AKTIF</span>'
        );

        // Update data attributes for edit button
        productRow.find('.edit-product-btn')
            .data('jenis-produk', jenisProduk)
            .attr('data-jenis-produk', jenisProduk)
            .data('tipe-produk', tipeProduk)
            .attr('data-tipe-produk', tipeProduk)
            .data('status', status)
            .attr('data-status', status);

        // Update hidden form fields
        const umkmIndex = umkmId.replace('temp_', '');
        const productIndex = productRow.index();

        $(`input[name="umkm[${umkmIndex}][products][${productIndex}][jenis_produk]"]`).val(jenisProduk);
        $(`input[name="umkm[${umkmIndex}][products][${productIndex}][tipe_produk]"]`).val(tipeProduk);
        $(`input[name="umkm[${umkmIndex}][products][${productIndex}][status]"]`).val(status);
    }

    // Function to update row numbers in product table
    function updateProductRowNumbers(umkmId) {
        const productTable = $(`#products-table-${umkmId} tbody`);
        const rows = productTable.find('tr');

        if (rows.length === 0) {
            // Add empty message
            productTable.html('<tr><td colspan="5" class="text-center">Belum ada produk untuk UMKM ini</td></tr>');
            return;
        }

        if (rows.find('td[colspan="5"]').length > 0) {
            // Already has empty message, no need to update
            return;
        }

        // Update row numbers
        rows.each(function (index) {
            $(this).find('td:first-child').text(index + 1);
        });
    }

    // Function to show modal with fallback methods
    function showModalWithFallback(modalId) {
        console.log("Attempting to show modal:", modalId);

        try {
            // Method 1: jQuery Bootstrap
            if (typeof $.fn.modal === 'function') {
                $('#' + modalId).modal('show');
                console.log('Modal opened with jQuery Bootstrap');
                return;
            }

            // Method 2: Bootstrap 5
            const modalElement = document.getElementById(modalId);
            if (modalElement && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modalInstance = new bootstrap.Modal(modalElement);
                modalInstance.show();
                console.log('Modal opened with Bootstrap 5 JS');
                return;
            }

            // Method 3: Manual DOM manipulation
            $('#' + modalId).addClass('show');
            $('#' + modalId).css('display', 'block');
            $('body').addClass('modal-open');
            $('<div class="modal-backdrop fade show"></div>').appendTo('body');
            console.log('Modal opened with manual DOM manipulation');
        } catch (error) {
            console.error('Error showing modal:', error);
            alert('Gagal membuka modal. Silakan coba lagi.');
        }
    }

    // Function to hide modal
    function hideModal(modalId) {
        try {
            // Method 1: jQuery Bootstrap
            if (typeof $.fn.modal === 'function') {
                $('#' + modalId).modal('hide');
                return;
            }

            // Method 2: Bootstrap 5
            const modalElement = document.getElementById(modalId);
            if (modalElement && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) modalInstance.hide();
                return;
            }

            // Method 3: Manual DOM manipulation
            $('#' + modalId).removeClass('show');
            $('#' + modalId).css('display', 'none');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        } catch (error) {
            console.error('Error hiding modal:', error);
        }
    }

    // Function to show loading indicator
    function showLoading(message = 'Memproses...') {
        // Create loading overlay if it doesn't exist
        if ($('#loading-overlay').length === 0) {
            $('body').append(`
    <div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999; display: flex; justify-content: center; align-items: center; display: none;">
        <div class="card p-4 shadow-sm">
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="loading-message mb-0 font-weight-bold">${message}</p>
            </div>
        </div>
    </div>
`);
        } else {
            $('#loading-overlay .loading-message').text(message);
        }

        $('#loading-overlay').fadeIn(200);
    }

    // Function to hide loading indicator
    function hideLoading() {
        $('#loading-overlay').fadeOut(200);
    }

    // Function to show alert notifications
    function showAlert(type, message, modalId = null) {
        // Remove existing alerts first
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
            // Add only to first container-fluid
            $('.container-fluid:first').prepend(alertHTML);
        }

        // Auto-close after 5 seconds
        setTimeout(function () {
            $('.alert').alert('close');
        }, 5000);
    }

    // Load existing products for each UMKM when the page loads
    function loadExistingProducts() {
        // Find all UMKMs with IDs (existing UMKMs)
        $('.umkm-form-entry').each(function () {
            const umkmId = $(this).find('input[name$="[id]"]').val();
            if (umkmId && !$(this).data('products-loaded')) {
                loadProductsForUmkm(umkmId);
                $(this).data('products-loaded', true);
            }
        });
    }

    // Function to load products for a specific UMKM
    function loadProductsForUmkm(umkmId) {
        if (!umkmId) return;

        const productTable = $(`#products-table-${umkmId} tbody`);
        if (!productTable.length) return;

        // Show loading message in table
        productTable.html('<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data produk...</td></tr>');

        $.ajax({
            url: `/get-products/${umkmId}`,
            type: 'GET',
            success: function (response) {
                if (response.success && response.data && response.data.length > 0) {
                    // Clear loading message
                    productTable.empty();

                    // Populate table with products
                    response.data.forEach(function (product, index) {
                        const productRow = `
                <tr id="product-${product.id}" data-product-id="${product.id}">
                    <td class="text-center">${index + 1}</td>
                    <td>${product.jenis_produk}</td>
                    <td>${product.tipe_produk}</td>
                    <td>
                        ${product.status === 'AKTIF' ?
                                '<span class="badge badge-success">AKTIF</span>' :
                                '<span class="badge badge-danger">TIDAK AKTIF</span>'}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-warning edit-product-btn"
                            data-product-id="${product.id}"
                            data-umkm-id="${umkmId}"
                            data-jenis-produk="${product.jenis_produk}"
                            data-tipe-produk="${product.tipe_produk}"
                            data-status="${product.status}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-product-btn"
                            data-product-id="${product.id}"
                            data-umkm-id="${umkmId}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
                        productTable.append(productRow);
                    });
                } else {
                    // Show no products message
                    productTable.html('<tr><td colspan="5" class="text-center">Belum ada produk untuk UMKM ini</td></tr>');
                }
            },
            error: function (xhr) {
                // Show error message
                productTable.html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data produk</td></tr>');
                console.error('Error loading products:', xhr);
            }
        });
    }

    // Add event handler for close button in modal
    $(document).on('click', '[data-dismiss="modal"]', function () {
        const modalId = $(this).closest('.modal').attr('id');
        hideModal(modalId);
    });

    // Success alert management script
    $(document).ready(function () {
        // Function to show success alert after form submission
        function showSuccessAlert(message) {
            // Remove any existing alerts first
            $('.container-fluid > .alert').remove();

            // Create alert HTML
            const alertHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;

            // Insert alert at the top of the content area
            $('.container-fluid:first').prepend(alertHTML);

            // Auto-dismiss after 5 seconds
            setTimeout(function () {
                $('.alert').alert('close');
            }, 5000);

            // Scroll to top to ensure alert is visible
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Handle form submission
        $('form').on('submit', function () {
            // Store the submission status in localStorage
            localStorage.setItem('formSubmitted', 'true');
            return true; // Allow form to submit normally
        });

        // Check if there's a successful submission when page loads
        if (localStorage.getItem('formSubmitted') === 'true') {
            // Clear the flag immediately to prevent showing alert on refresh
            localStorage.removeItem('formSubmitted');

            // Show success alert
            showSuccessAlert('Data UMKM berhasil diperbarui!');
        }

        // If there's a PHP session flash message, it means it's already handled by the backend
        if ($('.alert-success').length > 0) {
            // Scroll to top to make sure the existing alert is visible
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Auto-dismiss existing alerts after 5 seconds
            setTimeout(function () {
                $('.alert').alert('close');
            }, 5000);
        }
    });

    // Add event handler for Escape key to close modals
    $(document).on('keydown', function (event) {
        if (event.key === 'Escape') {
            // Find any visible modal
            const visibleModal = $('.modal.show').attr('id');
            if (visibleModal) {
                hideModal(visibleModal);
            }
        }
    });

    // Save all products for all UMKMs before form submission
    $('form').on('submit', function () {
        // Add hidden field to indicate that the form includes product data
        $(this).append('<input type="hidden" name="has_product_data" value="1">');

        // Return true to continue with form submission
        return true;
    });

    // Modal debugging and event handlers
    $('#addProductModal').on('show.bs.modal', function () {
        console.log("Product Modal is about to be shown");
    }).on('shown.bs.modal', function () {
        console.log("Product Modal has been shown");
        // Focus on the first input field
        $(this).find('input:visible:first').focus();
    }).on('hide.bs.modal', function () {
        console.log("Product Modal is about to be hidden");
    }).on('hidden.bs.modal', function () {
        console.log("Product Modal has been hidden");
        // Reset submit button and form state when modal is hidden
        $('#add-product-form').data('submitting', false);
        $('#add-product-form button[type="submit"]').prop('disabled', false);
    });

    // Initialize page
    loadExistingProducts();
});