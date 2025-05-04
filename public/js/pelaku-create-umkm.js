$(document).ready(function() {
    // ONLY execute this script on create pages (not on edit pages)
    const isCreatePage = !window.location.pathname.includes('/edit');

    if (!isCreatePage) {
        console.log("This is not a create page, skipping pelaku-create-umkm.js execution");
        return; // Exit early if this is an edit page
    }

    console.log("UMKM management script loaded for create page");

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
                    <input type="text" class="form-control" id="nama_usaha_${umkmCounter}" name="umkm[${umkmCounter}][nama_usaha]" placeholder="Masukan Nama Usaha" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="alamat_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Alamat Usaha</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="alamat_usaha_${umkmCounter}" name="umkm[${umkmCounter}][alamat]" placeholder="Masukan Alamat Usaha"  required>
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
                    <input type="number" class="form-control" id="jumlah_tenaga_kerja_${umkmCounter}" name="umkm[${umkmCounter}][jumlah_tenaga_kerja]" placeholder="Masukan Jumlah Tenaga Kerja"  required>
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

    // Form validation
    $('form').submit(function(e) {
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
            // Show alert
            $('#alertContainer').html(`
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Harap lengkapi semua field yang wajib diisi
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);

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

    // Progress bar functionality
    function updateProgress() {
        let totalFields = 0;
        let filledFields = 0;

        // Count all required fields
        $('.umkm-form-entry').each(function() {
            $(this).find('input[required], select[required]').each(function() {
                totalFields++;
                if ($(this).val() !== '') {
                    filledFields++;
                }
            });
        });

        // Calculate percentage
        const percentage = totalFields > 0 ? Math.round((filledFields / totalFields) * 100) : 0;

        // Update progress bar
        $('#formProgress').css('width', percentage + '%').attr('aria-valuenow', percentage);
    }

    // Update progress when any field changes
    $(document).on('input change', 'input, select', function() {
        updateProgress();
    });

    // Initial progress update
    updateProgress();

    // Mobile Add UMKM button
    $('#mobile-add-umkm').click(function() {
        addUmkmForm();

        // Scroll to the new form
        $('html, body').animate({
            scrollTop: $('.umkm-form-entry:last').offset().top - 100
        }, 500);
    });

    // Add version flag to prevent conflicts
    window.pelakuUmkmCreateVersion = '1.0';
    console.log("Pelaku UMKM Create form management loaded successfully, version:", window.pelakuUmkmCreateVersion);
});