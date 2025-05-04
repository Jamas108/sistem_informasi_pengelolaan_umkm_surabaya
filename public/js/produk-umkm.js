$(document).ready(function() {
    // ONLY execute this script on create pages (not on edit pages)
    const isCreatePage = !window.location.pathname.includes('/edit');

    if (!isCreatePage) {
        console.log("This is not a create page, skipping produk-umkm.js execution");
        return; // Exit early if this is an edit page
    }

    console.log("Product management script loaded for create page");

    // Global variable to store products for each UMKM
    let umkmProducts = {};

    // Initialize the counter for UMKM forms
    let umkmCounter = 0;

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

    // Function to update UMKM numbers
    function updateUmkmNumbers() {
        $('.umkm-form-entry').each(function(index) {
            // Update data-umkm-id for the current form
            const umkmId = $(this).data('umkm-id');

            // Update the text only - don't change the IDs or data attributes
            $(this).find('.umkm-number').text('UMKM #' + (index + 1));
        });
    }

    // Clear any existing UMKM forms first to prevent duplicates
    $('#umkm-entries-container').empty();

    // Then add the first UMKM form by default
    addUmkmForm();

    // Add button to add another UMKM form
    $('#add-umkm-btn').off('click').on('click', function() {
        addUmkmForm();
    });

    // Remove UMKM form when remove button is clicked
    $(document).off('click', '.remove-umkm').on('click', '.remove-umkm', function() {
        const umkmId = $(this).data('umkm-id');
        console.log(`Removing UMKM entry #${umkmId}`);

        $(`#umkm-entry-${umkmId}`).remove();

        // Don't allow removing all entries - ensure at least one remains
        if ($('.umkm-form-entry').length === 0) {
            addUmkmForm();
            console.log("Added new UMKM form after removing all");
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

    // Form validation
    $('#data-umkm-form').submit(function(e) {
        let isValid = true;

        // Validate required fields in all UMKM entries
        $('.umkm-form-entry').each(function() {
            $(this).find('input[required], select[required]').each(function() {
                if ($(this).val() === '') {
                    isValid = false;
                    $(this).addClass('is-invalid');

                    // Add invalid feedback if it doesn't exist
                    if ($(this).siblings('.invalid-feedback').length === 0) {
                        $(this).after('<div class="invalid-feedback">Field ini wajib diisi</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
        });

        if (!isValid) {
            e.preventDefault();
            // Show alert at the top of the form
            showFormAlert('warning', 'Harap lengkapi semua field yang wajib diisi', $('#umkm'));

            // Scroll to the first invalid field
            $('html, body').animate({
                scrollTop: $('.is-invalid:first').offset().top - 100
            }, 500);
        }
    });

    // Remove invalid state when input changes
    $(document).on('input change', 'input, select', function() {
        if ($(this).val() !== '') {
            $(this).removeClass('is-invalid');
        }
    });

    // Tab switching validation
    $('a[data-toggle="tab"]').on('click', function(e) {
        const targetTab = $(this).attr('href');

        // If switching to UMKM tab, validate Pelaku UMKM form first
        if (targetTab === '#umkm') {
            let pelakuFormValid = true;

            $('#pelaku input[required], #pelaku select[required]').each(function() {
                if ($(this).val() === '') {
                    pelakuFormValid = false;
                    $(this).addClass('is-invalid');

                    // Add invalid feedback if it doesn't exist
                    if ($(this).siblings('.invalid-feedback').length === 0) {
                        $(this).after('<div class="invalid-feedback">Field ini wajib diisi</div>');
                    }
                }
            });

            if (!pelakuFormValid) {
                e.preventDefault();
                showFormAlert('warning', 'Harap lengkapi data Pelaku UMKM terlebih dahulu', $('#pelaku'));

                // Scroll to the first invalid field
                $('html, body').animate({
                    scrollTop: $('#pelaku .is-invalid:first').offset().top - 100
                }, 500);
            }
        }
    });

    // Load Font Awesome if needed
    if (typeof FontAwesome === 'undefined' && !$('link[href*="font-awesome"]').length) {
        console.log("Loading Font Awesome from CDN");
        $('head').append('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">');
    }

    // Add version flag to prevent conflicts
    window.umkmCreateVersion = '1.0';
    console.log("UMKM Create form management loaded successfully, version:", window.umkmCreateVersion);
});