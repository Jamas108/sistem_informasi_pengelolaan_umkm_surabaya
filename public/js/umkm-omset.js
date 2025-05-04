// Improved omset-handler.js - Save this file to public/js/omset-handler.js
$(document).ready(function () {
    console.log("Omset handler script loaded");

    // Function to obtain Pelaku ID from URL
    function getPelakuIdFromUrl() {
        const url = window.location.pathname;
        const urlParts = url.split('/');

        // Check for /dataumkm pattern
        const dataumkmIndex = urlParts.indexOf('dataumkm');
        if (dataumkmIndex !== -1 && dataumkmIndex + 1 < urlParts.length) {
            return urlParts[dataumkmIndex + 1];
        }

        // Check for /pelakukelolaumkm pattern
        const pelakukelolaIndex = urlParts.indexOf('pelakukelolaumkm');
        if (pelakukelolaIndex !== -1 && pelakukelolaIndex + 1 < urlParts.length) {
            return urlParts[pelakukelolaIndex + 1];
        }

        return null;
    }

    // Load omset data
    function loadOmsetData() {
        const pelakuId = getPelakuIdFromUrl();

        if (!pelakuId) {
            console.error('Error: Cannot find Pelaku UMKM ID in URL');
            showAlert('danger', 'Error: Cannot determine Pelaku UMKM ID');
            return;
        }

        console.log('Loading omset data for Pelaku ID:', pelakuId);

        // Show loading indicator
        $('#table-omset tbody').html('<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading data...</td></tr>');

        $.ajax({
            url: `/dataumkm/omset/list/${pelakuId}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Omset data response:', response);
                if (response.success) {
                    updateOmsetTable(response.data);
                } else {
                    // Show error and empty table
                    showAlert('danger', response.message || 'Failed to load omset data');
                    updateOmsetTable([]);
                }
            },
            error: function (xhr) {
                console.error('Error getting omset data:', xhr);
                showAlert('danger', 'Error loading omset data: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Connection error'));
                // Show empty table in case of error
                updateOmsetTable([]);
            }
        });
    }

    // Update omset table with data
    // Update omset table with data
function updateOmsetTable(data) {
    console.log('Updating omset table with data:', data);

    // Destroy existing DataTable if already initialized
    if ($.fn.dataTable && $.fn.dataTable.isDataTable('#table-omset')) {
        $('#table-omset').DataTable().destroy();
    }

    // Completely empty the table
    $('#table-omset tbody').empty();

    let tableRows = '';

    if (data && data.length > 0) {
        data.forEach((item, index) => {
            // Format date
            const date = new Date(item.jangka_waktu);
            const formattedDate = date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).replace(/\//g, '-');

            // Format currency
            const formattedOmset = parseInt(item.total_omset).toLocaleString('id-ID');

            // Generate status badge
            let statusBadge = '';
            switch (item.keterangan) {
                case 'AKTIF':
                    statusBadge = '<span class="badge badge-success">AKTIF</span>';
                    break;
                case 'TIDAK AKTIF':
                    statusBadge = '<span class="badge badge-danger">TIDAK AKTIF</span>';
                    break;
                default:
                    statusBadge = '<span class="badge badge-light">' + item.keterangan + '</span>';
            }

            tableRows += `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>${item.nama_usaha || 'Tidak ada'}</td>
                <td>${formattedDate}</td>
                <td>Rp. ${formattedOmset}</td>
                <td>${statusBadge}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-warning btn-sm edit-omset" data-id="${item.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm delete-omset" data-id="${item.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            `;
        });

        // Insert rows into the table
        $('#table-omset tbody').html(tableRows);

        // Initialize DataTable with simplified options to avoid conflicts
        setTimeout(function () {
            try {
                if ($.fn.dataTable) {
                    $('#table-omset').DataTable({
                        destroy: true,       // Ensure any previous instance is destroyed
                        retrieve: true,      // Retrieve existing instance if already initialized
                        responsive: true,
                        paging: true,        // Enable pagination
                        searching: true,     // Enable search
                        ordering: true,      // Enable ordering
                        info: true,          // Show information
                        lengthChange: true,  // Allow changing page length
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ entri",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                            infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                            infoFiltered: "(disaring dari _MAX_ total entri)",
                            emptyTable: "Belum ada data omset",
                            paginate: {
                                first: "Pertama",
                                last: "Terakhir",
                                next: "Selanjutnya",
                                previous: "Sebelumnya"
                            }
                        }
                    });
                    console.log('DataTable successfully initialized');
                } else {
                    console.warn('DataTable is not available, table will display without advanced features');
                }
            } catch (error) {
                console.error('Error initializing DataTable:', error);
                // Continue without DataTable if there's an error
            }
        }, 100); // Small delay to ensure DOM is fully updated
    } else {
        // For empty data, just show a message without initializing DataTable
        tableRows = `
        <tr>
            <td colspan="6" class="text-center">Belum ada data omset</td>
        </tr>
        `;
        $('#table-omset tbody').html(tableRows);
        console.log('No data available, skipping DataTable initialization');
    }
}

    // Add omset data
    $('#tambah-data-omset').click(function () {
        const umkmId = $('#umkm_id').val();
        const jangkaWaktu = $('#jangka_waktu').val();
        const omset = $('#total_omset').val().replace(/\D/g, ''); // Remove non-digits
        const keterangan = $('#keterangan').val();
        const pelakuId = getPelakuIdFromUrl();

        if (!umkmId || !jangkaWaktu || !omset || !keterangan) {
            showAlert('warning', 'Semua kolom harus diisi');
            return;
        }

        const data = {
            umkm_id: umkmId,
            jangka_waktu: jangkaWaktu,
            total_omset: omset,
            keterangan: keterangan
        };

        $.ajax({
            url: `/dataumkm/omset/save/${pelakuId}`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    // Reset fields
                    $('#umkm_id').val('');
                    $('#jangka_waktu').val('');
                    $('#total_omset').val('');
                    $('#keterangan').val('');

                    // Reload data
                    loadOmsetData();
                    showAlert('success', 'Data omset berhasil ditambahkan');
                } else {
                    showAlert('danger', response.message || 'Terjadi kesalahan');
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr);
                const errorMessage = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Kesalahan menyimpan data';
                showAlert('danger', errorMessage);
            }
        });
    });

    // Enhanced Edit omset handler (click on edit button)
    $(document).on('click', '.edit-omset', function (e) {
        e.preventDefault();
        const omsetId = $(this).data('id');

        // Add visual feedback
        const $button = $(this);
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

        // Make the AJAX request
        $.ajax({
            url: `/dataumkm/omset/${omsetId}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $button.prop('disabled', false).html('<i class="fas fa-edit"></i> Edit');

                if (response.success) {
                    const data = response.data;

                    // Set values in the form
                    $('#edit_omset_id').val(data.id);
                    $('#edit_umkm_id').val(data.umkm_id);

                    // Format date for input
                    try {
                        const dateObj = new Date(data.jangka_waktu);
                        const formattedDate = dateObj.toISOString().split('T')[0];
                        $('#edit_jangka_waktu').val(formattedDate);
                    } catch (e) {
                        $('#edit_jangka_waktu').val(data.jangka_waktu);
                    }

                    // Format currency
                    try {
                        const formattedOmset = parseInt(data.total_omset).toLocaleString('id-ID');
                        $('#edit_omset').val(formattedOmset);
                    } catch (e) {
                        $('#edit_omset').val(data.total_omset);
                    }

                    $('#edit_keterangan').val(data.keterangan);

                    // Show the modal - detect Bootstrap version and use appropriate method
                    var myModal;
                    if (typeof bootstrap !== 'undefined') {
                        // Bootstrap 5
                        myModal = new bootstrap.Modal(document.getElementById('editOmsetModal'));
                        myModal.show();
                    } else {
                        // Bootstrap 4 or earlier
                        $('#editOmsetModal').modal('show');
                    }
                } else {
                    alert("Error loading data: " + (response.message || "Unknown error"));
                }
            },
            error: function (xhr) {
                $button.prop('disabled', false).html('<i class="fas fa-edit"></i> Edit');
                alert("Error loading data. Please check console for details.");
                console.error("Error loading omset data:", xhr);
            }
        });
    });

    // Improved save edited omset with robust error handling
    $(document).on('click', '#save-edit-omset', function () {
        console.log("Save button clicked - Event triggered");

        // Disable the button and show loading state
        const $button = $(this);
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        const omsetId = $('#edit_omset_id').val();
        const umkmId = $('#edit_umkm_id').val();
        const jangkaWaktu = $('#edit_jangka_waktu').val();
        const omset = $('#edit_omset').val().replace(/\D/g, ''); // Remove non-digits
        const keterangan = $('#edit_keterangan').val();

        console.log('Saving edit omset data:', {
            id: omsetId,
            umkm_id: umkmId,
            jangka_waktu: jangkaWaktu,
            total_omset: omset,
            keterangan: keterangan
        });

        if (!umkmId || !jangkaWaktu || !omset || !keterangan) {
            alert('Semua kolom harus diisi');
            $button.prop('disabled', false).html('Simpan Perubahan');
            return;
        }

        const data = {
            _method: 'PUT',
            umkm_id: umkmId,
            jangka_waktu: jangkaWaktu,
            total_omset: omset,
            keterangan: keterangan
        };

        $.ajax({
            url: `/dataumkm/omset/${omsetId}`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Update omset response:', response);
                $button.prop('disabled', false).html('Simpan Perubahan');

                if (response.success) {
                    // Close the modal - detect Bootstrap version and use appropriate method
                    if (typeof bootstrap !== 'undefined') {
                        // Bootstrap 5
                        var modalElement = document.getElementById('editOmsetModal');
                        var modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    } else {
                        // Bootstrap 4 or earlier
                        $('#editOmsetModal').modal('hide');
                    }

                    // Reload data
                    loadOmsetData();

                    // Show success message
                    showAlert('success', 'Data omset berhasil diperbarui');
                } else {
                    showAlert('danger', response.message || 'Terjadi kesalahan', 'editOmsetModal');
                }
            },
            error: function (xhr) {
                console.error('Error saving changes:', xhr);
                $button.prop('disabled', false).html('Simpan Perubahan');

                const errorMessage = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Kesalahan memperbarui data';
                showAlert('danger', errorMessage, 'editOmsetModal');
            }
        });
    });

    // Delete omset
    $(document).on('click', '.delete-omset', function () {
        const omsetId = $(this).data('id');

        if (confirm('Apakah Anda yakin ingin menghapus data omset ini?')) {
            // Disable the button to prevent multiple clicks
            const $button = $(this);
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: `/dataumkm/omset/${omsetId}`,
                type: 'POST',
                data: {
                    _method: 'DELETE'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        loadOmsetData();
                        showAlert('success', 'Data omset berhasil dihapus');
                    } else {
                        showAlert('danger', response.message || 'Kesalahan menghapus data');
                        // Re-enable the button in case of failure
                        $button.prop('disabled', false).html('<i class="fas fa-trash"></i> Hapus');
                    }
                },
                error: function (xhr) {
                    console.error('Error:', xhr);
                    showAlert('danger', 'Kesalahan menghapus data');
                    // Re-enable the button in case of failure
                    $button.prop('disabled', false).html('<i class="fas fa-trash"></i> Hapus');
                }
            });
        }
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
        setTimeout(function () {
            $('.alert').alert('close');
        }, 5000);
    }

    // Enhanced modal debugging
    $('#editOmsetModal').on('show.bs.modal', function () {
        console.log("Modal is about to be shown");
    }).on('shown.bs.modal', function () {
        console.log("Modal has been shown");
        // Focus on the first input field
        $(this).find('input:visible:first').focus();
    }).on('hide.bs.modal', function () {
        console.log("Modal is about to be hidden");
    }).on('hidden.bs.modal', function () {
        console.log("Modal has been hidden");
        // Clear any modal alerts when hidden
        $(this).find('.alert').remove();
    });

    // Test modal directly with button (for debugging)
    $('#test-modal-btn').on('click', function () {
        console.log("Test modal button clicked");
        try {
            if (typeof bootstrap !== 'undefined') {
                // Bootstrap 5
                var myModal = new bootstrap.Modal(document.getElementById('editOmsetModal'));
                myModal.show();
            } else {
                // Bootstrap 4 or earlier
                $('#editOmsetModal').modal('show');
            }
        } catch (e) {
            console.error("Error on test modal:", e);
        }
    });

    // Event when clicking on the omset tab
    $('#omset-tab').on('click', function () {
        loadOmsetData();
    });

    // Load data at startup if omset tab is active
    if ($('#omset-tab').hasClass('active') || window.location.hash === '#omset') {
        loadOmsetData();
    }

    // Check if Bootstrap is properly loaded
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

    // Expose functions for global use
    window.omsetHandler = {
        loadOmsetData: loadOmsetData,
        updateOmsetTable: updateOmsetTable,
        showAlert: showAlert,
        showEditModal: function() {
            if (typeof bootstrap !== 'undefined') {
                // Bootstrap 5
                var myModal = new bootstrap.Modal(document.getElementById('editOmsetModal'));
                myModal.show();
            } else {
                // Bootstrap 4 or earlier
                $('#editOmsetModal').modal('show');
            }
        }
    };
});