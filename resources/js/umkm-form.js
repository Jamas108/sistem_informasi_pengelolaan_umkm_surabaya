// Add this code to your umkm-form.js file or create it if it doesn't exist
$(document).ready(function() {
    // Initialize umkmCounter
    window.umkmCounter = $('.umkm-form-entry').length > 0 ? $('.umkm-form-entry').length : 0;

    // Function to extract Pelaku ID from URL path
    function getPelakuIdFromUrl() {
        const url = window.location.pathname;
        const urlParts = url.split('/');
        for (let i = 0; i < urlParts.length; i++) {
            if (urlParts[i] === 'dataumkm' && i + 1 < urlParts.length) {
                return urlParts[i + 1];
            }
        }
        return null;
    }

    // Function to update UMKM numbers
    function updateUmkmNumbers() {
        $('.umkm-form-entry').each(function(index) {
            $(this).find('.umkm-number').text('UMKM #' + (index + 1));
        });
    }

    // Function to add a new UMKM form
    function addUmkmForm() {
        window.umkmCounter++;

        // Get the pelaku ID from the URL
        const pelakuId = getPelakuIdFromUrl();

        const newForm = `
            <div class="umkm-form-entry border rounded p-4 mb-4 shadow-sm" id="umkm-entry-${window.umkmCounter}" data-umkm-id="${window.umkmCounter}">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="m-0 umkm-number font-weight-bold text-primary">UMKM #${$('.umkm-form-entry').length + 1}</h5>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-umkm" data-umkm-id="${window.umkmCounter}">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>

                <!-- Form fields here (same as in your original code) -->
                <!-- ... -->

                <input type="hidden" name="umkm[${window.umkmCounter}][pelaku_umkm_id]" value="${pelakuId}">
                <input type="hidden" name="umkm[${window.umkmCounter}][id]" value="">
            </div>
        `;

        $('#umkm-entries-container').append(newForm);
    }

    // Add button to add another UMKM form
    $('#add-umkm-btn').off('click').on('click', function() {
        console.log('Adding new UMKM form');
        addUmkmForm();
    });

    // Remove UMKM form when remove button is clicked
    $(document).off('click', '.remove-umkm').on('click', '.remove-umkm', function() {
        const umkmId = $(this).data('umkm-id');
        console.log('Removing UMKM form with ID:', umkmId);
        $(`#umkm-entry-${umkmId}`).remove();

        // Don't allow removing all entries - ensure at least one remains
        if ($('.umkm-form-entry').length === 0) {
            addUmkmForm();
        }

        // Update the UMKM numbers after removal
        updateUmkmNumbers();
    });

    // Fix the form submission
    $('.btn-success').on('click', function(e) {
        // Check if this is a specific tab save button or the main submit button
        if ($(this).attr('id') !== 'tambah-data-omset' &&
            $(this).attr('id') !== 'simpan-legalitas' &&
            $(this).attr('id') !== 'tambah-data-intervensi') {

            console.log('Submitting form...');

            // Submit the closest form
            $(this).closest('form').submit();

            // Prevent default only for tab-specific buttons
            if ($(this).hasClass('tab-save-btn')) {
                e.preventDefault();
            }
        }
    });

    // Fix the missing data-pelaku-id attribute for the form
    if ($('form').data('pelaku-id') === undefined) {
        // Extract ID from URL path
        const pelakuId = getPelakuIdFromUrl();
        if (pelakuId) {
            $('form').attr('data-pelaku-id', pelakuId);
        }
    }
});