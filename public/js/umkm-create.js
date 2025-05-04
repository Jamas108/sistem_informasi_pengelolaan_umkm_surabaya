$(document).ready(function () {
    // Initialize the counter for UMKM forms
    let umkmCounter = 0;

    // Global variable to store products for each UMKM
    let umkmProducts = {};

    // Add CSS styles for digit validation
    $('<style>').text(`
        .digit-counter {
            display: block;
            margin-top: 5px;
            font-size: 0.8rem;
        }

        .digit-counter.text-danger {
            animation: pulse 1s infinite;
        }

        .form-control.is-valid {
            border-color: #28a745;
            border-width: 2px;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        /* Input fields that need 16 digits will have special styling */
        input.digits-16 {
            letter-spacing: 1px;
        }

        /* Add a highlight effect when the count is exactly 16 */
        .digit-counter.text-success {
            animation: highlight 1s;
        }

        @keyframes highlight {
            0% { background-color: rgba(40, 167, 69, 0.2); }
            100% { background-color: transparent; }
        }

        .nav-link.disabled {
            color: #6c757d;
            pointer-events: none;
            cursor: not-allowed;
            opacity: 0.65;
        }
    `).appendTo('head');

    // Add event listener for the NIK check button
    $("#cek_nik").click(function (e) {
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
                success: function (response) {
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

                            // Update the digit counters after populating fields
                            setupDigitValidation();

                            // Run validation after populating fields
                            validatePelakuTab();
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

                        // Update the digit counters after changing values
                        setupDigitValidation();

                        // Run validation after clearing fields
                        validatePelakuTab();
                    }
                },
                error: function (xhr, status, error) {
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

    // Disable clicking on the UMKM tab by default
    $('#umkm-tab').addClass('disabled');

    // Enhanced NIK and No KK validation function
    function setupDigitValidation() {
        // Add the digits-16 class to NIK and No KK fields
        $('#nik, #no_kk').addClass('digits-16');

        // Set better placeholders
        // Remove any existing counters to avoid duplicates
        $('.digit-counter').remove();

        // Add counters
        $('#nik, #no_kk').each(function() {
            const field = $(this);
            const value = field.val() || '';
            const digitsOnly = value.replace(/\D/g, '');

            // Insert counter after the field
            field.after(`<small class="digit-counter text-muted">${digitsOnly.length}/16 digit</small>`);
        });

        // Handle input event for live validation
        $('#nik, #no_kk').off('input.digitValidation').on('input.digitValidation', function() {
            const field = $(this);
            const fieldId = field.attr('id');
            const fieldLabel = fieldId === 'nik' ? 'NIK' : 'No KK';
            const value = field.val();

            // Remove any non-digit characters
            const digitsOnly = value.replace(/\D/g, '');

            // Update field value to contain only digits
            if (value !== digitsOnly) {
                field.val(digitsOnly);
            }

            // Update the counter
            const counter = field.next('.digit-counter');
            counter.text(`${digitsOnly.length}/16 digit`);

            // Only show validation feedback if user has interacted with the field
            // and there is some input (avoid initial validation errors)
            if (field.data('userInteracted') && digitsOnly.length > 0) {
                if (digitsOnly.length !== 16) {
                    // Invalid - not 16 digits
                    field.removeClass('is-valid').addClass('is-invalid');
                    counter.removeClass('text-muted text-success').addClass('text-danger');

                    // Show appropriate error message
                    let errorMessage = '';
                    if (digitsOnly.length < 16) {
                        errorMessage = `${fieldLabel} kurang dari 16 digit.`;
                    } else {
                        errorMessage = `${fieldLabel} lebih dari 16 digit.`;
                    }

                    // Make sure we have an error feedback element
                    if (field.next('.digit-counter').next('.invalid-feedback').length === 0) {
                        counter.after(`<div class="invalid-feedback">${errorMessage}</div>`);
                    } else {
                        field.next('.digit-counter').next('.invalid-feedback').text(errorMessage);
                    }
                } else {
                    // Valid - exactly 16 digits
                    field.removeClass('is-invalid').addClass('is-valid');
                    counter.removeClass('text-muted text-danger').addClass('text-success');
                    field.next('.digit-counter').next('.invalid-feedback').remove();
                }
            } else if (digitsOnly.length === 16) {
                // Valid on first entry of exactly 16 digits
                field.removeClass('is-invalid').addClass('is-valid');
                counter.removeClass('text-muted text-danger').addClass('text-success');
            } else {
                // Just update counter color for neutral state
                counter.removeClass('text-success text-danger').addClass('text-muted');
            }

            // Mark that user has interacted with this field
            field.data('userInteracted', true);

            // Run tab validation
            validatePelakuTab();
        });

        // Handle blur event - show validation on field exit
        $('#nik, #no_kk').off('blur.digitValidation').on('blur.digitValidation', function() {
            const field = $(this);
            field.data('userInteracted', true);

            const value = field.val();
            if (value && value.length > 0) {
                // Trigger input event to show validation
                field.trigger('input.digitValidation');
            }
        });

        // Handle paste events
        $('#nik, #no_kk').off('paste.digitValidation').on('paste.digitValidation', function() {
            const field = $(this);
            field.data('userInteracted', true);

            // Short delay to allow paste to complete
            setTimeout(function() {
                field.trigger('input.digitValidation');
            }, 10);
        });
    }

    function validatePelakuTab() {
        // Define required fields - add all mandatory fields here
        const requiredFields = [
            'nik',
            'nama_lengkap',
            'no_kk',
            'tempat_lahir',
            'tgl_lahir',
            'jenis_kelamin',
            'status_hubungan_keluarga',
            'status_perkawinan',
            'alamat_sesuai_ktp',
            'kelurahan',
            'rw',
            'rt',
            'no_telp',
            'pendidikan_terakhir',
            'status_keaktifan'
        ];

        // Check if all required fields have values
        let allFilled = true;

        for (let field of requiredFields) {
            const element = $(`#${field}`);
            const value = element.val();

            if (!value || value.trim() === '') {
                allFilled = false;
                break;
            }

            // Additional validation for NIK and No KK (must be 16 digits)
            if ((field === 'nik' || field === 'no_kk') && value.length !== 16) {
                allFilled = false;
                break;
            }
        }

        // Enable or disable the UMKM tab based on validation
        if (allFilled) {
            $('#umkm-tab').removeClass('disabled');
        } else {
            $('#umkm-tab').addClass('disabled');
        }

        return allFilled;
    }

    // Handle tab click event with improved feedback
    $('#umkm-tab').on('click', function(e) {
        if ($(this).hasClass('disabled')) {
            e.preventDefault();
            e.stopPropagation();

            // Mark all fields as interacted with to trigger validation
            $('#pelaku input, #pelaku select').each(function() {
                $(this).data('userInteracted', true);
                // Trigger input event for NIK/KK fields to show validation
                if ($(this).attr('id') === 'nik' || $(this).attr('id') === 'no_kk') {
                    $(this).trigger('input.digitValidation');
                }
            });

            // Find empty required fields and highlight them
            const requiredFields = [
                'nik', 'nama_lengkap', 'no_kk', 'tempat_lahir', 'tgl_lahir',
                'jenis_kelamin', 'status_hubungan_keluarga', 'status_perkawinan',
                'alamat_sesuai_ktp', 'kelurahan', 'rw', 'rt', 'no_telp',
                'pendidikan_terakhir', 'status_keaktifan'
            ];

            let emptyFields = [];
            let invalidDigitFields = [];

            for (let field of requiredFields) {
                const element = $(`#${field}`);
                const value = element.val();

                if (!value || value.trim() === '') {
                    element.addClass('is-invalid');
                    emptyFields.push(field);
                }
                else if ((field === 'nik' || field === 'no_kk') && value.length !== 16) {
                    invalidDigitFields.push(field === 'nik' ? 'NIK' : 'No KK');
                }
            }

            // Show alert to inform user
            if ($('#validation-alert').length === 0) {
                let alertMessage = '<strong>Perhatian!</strong> ';

                if (emptyFields.length > 0) {
                    alertMessage += 'Harap isi semua data pelaku UMKM terlebih dahulu. ';
                }

                if (invalidDigitFields.length > 0) {
                    alertMessage += invalidDigitFields.join(' dan ') + ' harus terdiri dari 16 digit.';
                }

                const alertHTML = `
                    <div id="validation-alert" class="alert alert-warning alert-dismissible fade show" role="alert">
                        ${alertMessage}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                $(alertHTML).insertBefore($('#myTab'));

                // Auto dismiss after 5 seconds
                setTimeout(function() {
                    $('#validation-alert').alert('close');
                }, 5000);

                // Scroll to the first empty field
                if (emptyFields.length > 0) {
                    const firstEmptyField = $(`#${emptyFields[0]}`);
                    $('html, body').animate({
                        scrollTop: firstEmptyField.offset().top - 100
                    }, 500);
                } else if (invalidDigitFields.length > 0) {
                    // Scroll to the first invalid digit field
                    const firstInvalidField = $(`#${invalidDigitFields[0] === 'NIK' ? 'nik' : 'no_kk'}`);
                    $('html, body').animate({
                        scrollTop: firstInvalidField.offset().top - 100
                    }, 500);
                }
            }

            return false;
        }
    });

    // Form submission validation
    $('form').on('submit', function(e) {
        const nik = $('#nik').val();
        const noKk = $('#no_kk').val();
        let hasError = false;

        // Force both fields to be marked as interacted with
        $('#nik, #no_kk').data('userInteracted', true);

        // Check if NIK has exactly 16 digits
        if (nik.length !== 16) {
            $('#nik').removeClass('is-valid').addClass('is-invalid');
            const counter = $('#nik').next('.digit-counter');
            counter.removeClass('text-muted text-success').addClass('text-danger');

            // Add error message if not present
            if ($('#nik').next('.digit-counter').next('.invalid-feedback').length === 0) {
                counter.after(`<div class="invalid-feedback">NIK harus terdiri dari 16 digit.</div>`);
            }

            hasError = true;
        }

        // Check if No KK has exactly 16 digits
        if (noKk.length !== 16) {
            $('#no_kk').removeClass('is-valid').addClass('is-invalid');
            const counter = $('#no_kk').next('.digit-counter');
            counter.removeClass('text-muted text-success').addClass('text-danger');

            // Add error message if not present
            if ($('#no_kk').next('.digit-counter').next('.invalid-feedback').length === 0) {
                counter.after(`<div class="invalid-feedback">No KK harus terdiri dari 16 digit.</div>`);
            }

            hasError = true;
        }

        // If there's an error, prevent form submission
        if (hasError) {
            e.preventDefault();

            // Show a general error message at the top of the form
            if ($('#form-error-message').length === 0) {
                const errorHTML = `
                    <div id="form-error-message" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Harap perbaiki kesalahan pada form sebelum menyimpan.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                $(errorHTML).insertBefore($('#myTab'));

                // Auto scroll to the first error
                $('html, body').animate({
                    scrollTop: $('.is-invalid:first').offset().top - 100
                }, 500);
            }
        }
    });

    // Check validation when any field in the pelaku tab changes
    $('#pelaku input, #pelaku select').on('change input', function () {
        validatePelakuTab();
    });

    // Run validation on page load
    validatePelakuTab();

    // Initialize setup for digit validation
    setupDigitValidation();

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
    $('#add-umkm-btn').click(function () {
        addUmkmForm();
    });

    // Remove UMKM form when remove button is clicked
    $(document).on('click', '.remove-umkm', function () {
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

    // Add this function to update UMKM numbers
    function updateUmkmNumbers() {
        $('.umkm-form-entry').each(function (index) {
            $(this).find('.umkm-number').text('UMKM #' + (index + 1));
        });
    }

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
    $(document).on('click', '.manage-products-btn', function () {
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
    $('#reset-product-form').click(function () {
        resetProductForm();
    });

    // Save product form submission
    $('#product-form').submit(function (e) {
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
        showFormAlert('success',
            `Produk berhasil ${editingMode === 'edit' ? 'diperbarui' : 'ditambahkan'}!`, $(
                '.modal-body'));

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
        setTimeout(function () {
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
        products.forEach(function (product, index) {
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
    $(document).on('click', '.edit-product', function () {
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
    $(document).on('click', '.delete-product', function () {
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
    $('#apply-products').click(function () {
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
        products.forEach(function (product, index) {
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
    $(document).on('click', '.modal-close-btn, [data-dismiss="modal"], [data-bs-dismiss="modal"]',
        function () {
            const modalId = $(this).closest('.modal').attr('id');
            hideModal(modalId);
        });
});