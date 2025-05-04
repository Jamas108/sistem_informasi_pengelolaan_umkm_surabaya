// legalitas-handler.js - Save this file to public/js/legalitas-handler.js
$(document).ready(function () {
    console.log("Legalitas handler script loaded");

    // Function to obtain Pelaku ID from URL
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

    // Load legalitas data
    function loadLegalitasData() {
        const pelakuId = getPelakuIdFromUrl();

        if (!pelakuId) {
            console.error('Error: Cannot find Pelaku UMKM ID in URL');
            showAlert('danger', 'Error: Cannot determine Pelaku UMKM ID');
            return;
        }

        console.log('Loading legalitas data for Pelaku ID:', pelakuId);

        // Show loading indicator
        $('#table-legalitas tbody').html('<tr><td colspan="12" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading data...</td></tr>');

        $.ajax({
            url: `/dataumkm/legalitas/list/${pelakuId}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Legalitas data response:', response);
                if (response.success) {
                    updateLegalitasTable(response.data);
                } else {
                    // Show error and empty table
                    showAlert('danger', response.message || 'Failed to load legalitas data');
                    updateLegalitasTable([]);
                }
            },
            error: function (xhr) {
                console.error('Error getting legalitas data:', xhr);
                showAlert('danger', 'Error loading legalitas data: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Connection error'));
                // Show empty table in case of error
                updateLegalitasTable([]);
            }
        });
    }

    // Update legalitas table with data
    function updateLegalitasTable(data) {
        console.log('Updating legalitas table with data:', data);

        // Destroy existing DataTable if already initialized
        if ($.fn.dataTable && $.fn.dataTable.isDataTable('#table-legalitas')) {
            $('#table-legalitas').DataTable().destroy();
        }

        // Completely empty the table
        $('#table-legalitas tbody').empty();

        let tableRows = '';

        if (data && data.length > 0) {
            data.forEach((item, index) => {
                tableRows += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.nama_usaha || 'Tidak ada'}</td>
                    <td>${item.no_sk_nib || '-'}</td>
                    <td>${item.no_sk_siup || '-'}</td>
                    <td>${item.no_sk_tdp || '-'}</td>
                    <td>${item.no_sk_pirt || '-'}</td>
                    <td>${item.no_sk_bpom || '-'}</td>
                    <td>${item.no_sk_halal || '-'}</td>
                    <td>${item.no_sk_merek || '-'}</td>
                    <td>${item.no_sk_haki || '-'}</td>
                    <td>${item.no_surat_keterangan || '-'}</td>
                </tr>
                `;
            });

            // Insert rows into the table
            $('#table-legalitas tbody').html(tableRows);

            // Initialize DataTable with simplified options to avoid conflicts
            setTimeout(function () {
                try {
                    if ($.fn.dataTable) {
                        $('#table-legalitas').DataTable({
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
                                emptyTable: "Belum ada data legalitas",
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
                <td colspan="12" class="text-center">Belum ada data legalitas</td>
            </tr>
            `;
            $('#table-legalitas tbody').html(tableRows);
            console.log('No data available, skipping DataTable initialization');
        }
    }

    // Add legalitas data
    $('#simpan-legalitas').click(function () {
        const umkmId = $('#legalitas_umkm_id').val();
        const noSkNib = $('#no_sk_nib').val();
        const noSkSiup = $('#no_sk_siup').val();
        const noSkTdp = $('#no_sk_tdp').val();
        const noSkPirt = $('#no_sk_pirt').val();
        const noSkBpom = $('#no_sk_bpom').val();
        const noSkHalal = $('#no_sk_halal').val();
        const noSkMerek = $('#no_sk_merek').val();
        const noSkHaki = $('#no_sk_haki').val();
        const noSuratKeterangan = $('#no_surat_keterangan').val();
        const pelakuId = getPelakuIdFromUrl();

        if (!umkmId) {
            showAlert('warning', 'Pilih UMKM terlebih dahulu');
            return;
        }

        const data = {
            umkm_id: umkmId,
            no_sk_nib: noSkNib,
            no_sk_siup: noSkSiup,
            no_sk_tdp: noSkTdp,
            no_sk_pirt: noSkPirt,
            no_sk_bpom: noSkBpom,
            no_sk_halal: noSkHalal,
            no_sk_merek: noSkMerek,
            no_sk_haki: noSkHaki,
            no_surat_keterangan: noSuratKeterangan
        };

        $.ajax({
            url: `/dataumkm/legalitas/save/${pelakuId}`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    // Reset fields
                    $('#legalitas_umkm_id').val('');
                    $('#no_sk_nib').val('');
                    $('#no_sk_siup').val('');
                    $('#no_sk_tdp').val('');
                    $('#no_sk_pirt').val('');
                    $('#no_sk_bpom').val('');
                    $('#no_sk_halal').val('');
                    $('#no_sk_merek').val('');
                    $('#no_sk_haki').val('');
                    $('#no_surat_keterangan').val('');

                    // Reload data
                    loadLegalitasData();
                    showAlert('success', 'Data legalitas berhasil ditambahkan');
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

    // Edit legalitas handler (click on edit button)
    $(document).on('click', '.edit-legalitas', function (e) {
        e.preventDefault();
        const legalitasId = $(this).data('id');

        // Add visual feedback
        const $button = $(this);
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

        // Make the AJAX request
        $.ajax({
            url: `/dataumkm/legalitas/${legalitasId}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $button.prop('disabled', false).html('<i class="fas fa-edit"></i> Edit');

                if (response.success) {
                    const data = response.data;

                    // Set values in the form
                    $('#edit_legalitas_id').val(data.id);
                    $('#edit_legalitas_umkm_id').val(data.umkm_id);
                    $('#edit_no_sk_nib').val(data.no_sk_nib);
                    $('#edit_no_sk_siup').val(data.no_sk_siup);
                    $('#edit_no_sk_tdp').val(data.no_sk_tdp);
                    $('#edit_no_sk_pirt').val(data.no_sk_pirt);
                    $('#edit_no_sk_bpom').val(data.no_sk_bpom);
                    $('#edit_no_sk_halal').val(data.no_sk_halal);
                    $('#edit_no_sk_merek').val(data.no_sk_merek);
                    $('#edit_no_sk_haki').val(data.no_sk_haki);
                    $('#edit_no_surat_keterangan').val(data.no_surat_keterangan);

                    // Show the modal - detect Bootstrap version and use appropriate method
                    if (typeof bootstrap !== 'undefined') {
                        // Bootstrap 5
                        const myModal = new bootstrap.Modal(document.getElementById('editLegalitasModal'));
                        myModal.show();
                    } else {
                        // Bootstrap 4 or earlier
                        $('#editLegalitasModal').modal('show');
                    }
                } else {
                    alert("Error loading data: " + (response.message || "Unknown error"));
                }
            },
            error: function (xhr) {
                $button.prop('disabled', false).html('<i class="fas fa-edit"></i> Edit');
                alert("Error loading data. Please check console for details.");
                console.error("Error loading legalitas data:", xhr);
            }
        });
    });

    // Save edited legalitas
    $(document).on('click', '#save-edit-legalitas', function () {
        console.log("Save button clicked - Event triggered");

        // Disable the button and show loading state
        const $button = $(this);
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        const legalitasId = $('#edit_legalitas_id').val();
        const umkmId = $('#edit_legalitas_umkm_id').val();
        const noSkNib = $('#edit_no_sk_nib').val();
        const noSkSiup = $('#edit_no_sk_siup').val();
        const noSkTdp = $('#edit_no_sk_tdp').val();
        const noSkPirt = $('#edit_no_sk_pirt').val();
        const noSkBpom = $('#edit_no_sk_bpom').val();
        const noSkHalal = $('#edit_no_sk_halal').val();
        const noSkMerek = $('#edit_no_sk_merek').val();
        const noSkHaki = $('#edit_no_sk_haki').val();
        const noSuratKeterangan = $('#edit_no_surat_keterangan').val();

        console.log('Saving edit legalitas data:', {
            id: legalitasId,
            umkm_id: umkmId,
            no_sk_nib: noSkNib,
            no_sk_siup: noSkSiup,
            no_sk_tdp: noSkTdp,
            no_sk_pirt: noSkPirt,
            no_sk_bpom: noSkBpom,
            no_sk_halal: noSkHalal,
            no_sk_merek: noSkMerek,
            no_sk_haki: noSkHaki,
            no_surat_keterangan: noSuratKeterangan
        });

        if (!umkmId) {
            alert('Pilih UMKM terlebih dahulu');
            $button.prop('disabled', false).html('Simpan Perubahan');
            return;
        }

        const data = {
            _method: 'PUT',
            umkm_id: umkmId,
            no_sk_nib: noSkNib,
            no_sk_siup: noSkSiup,
            no_sk_tdp: noSkTdp,
            no_sk_pirt: noSkPirt,
            no_sk_bpom: noSkBpom,
            no_sk_halal: noSkHalal,
            no_sk_merek: noSkMerek,
            no_sk_haki: noSkHaki,
            no_surat_keterangan: noSuratKeterangan
        };

        $.ajax({
            url: `/dataumkm/legalitas/${legalitasId}`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Update legalitas response:', response);
                $button.prop('disabled', false).html('Simpan Perubahan');

                if (response.success) {
                    // Close the modal - detect Bootstrap version and use appropriate method
                    if (typeof bootstrap !== 'undefined') {
                        // Bootstrap 5
                        const modalElement = document.getElementById('editLegalitasModal');
                        const modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    } else {
                        // Bootstrap 4 or earlier
                        $('#editLegalitasModal').modal('hide');
                    }

                    // Reload data
                    loadLegalitasData();

                    // Show success message
                    showAlert('success', 'Data legalitas berhasil diperbarui');
                } else {
                    showAlert('danger', response.message || 'Terjadi kesalahan', 'editLegalitasModal');
                }
            },
            error: function (xhr) {
                console.error('Error saving changes:', xhr);
                $button.prop('disabled', false).html('Simpan Perubahan');

                const errorMessage = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Kesalahan memperbarui data';
                showAlert('danger', errorMessage, 'editLegalitasModal');
            }
        });
    });

    // Delete legalitas
    $(document).on('click', '.delete-legalitas', function () {
        const legalitasId = $(this).data('id');

        if (confirm('Apakah Anda yakin ingin menghapus data legalitas ini?')) {
            // Disable the button to prevent multiple clicks
            const $button = $(this);
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: `/dataumkm/legalitas/${legalitasId}`,
                type: 'POST',
                data: {
                    _method: 'DELETE'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        loadLegalitasData();
                        showAlert('success', 'Data legalitas berhasil dihapus');
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

    // Modal debugging
    $('#editLegalitasModal').on('show.bs.modal', function () {
        console.log("Legalitas Modal is about to be shown");
    }).on('shown.bs.modal', function () {
        console.log("Legalitas Modal has been shown");
        // Focus on the first input field
        $(this).find('input:visible:first').focus();
    }).on('hide.bs.modal', function () {
        console.log("Legalitas Modal is about to be hidden");
    }).on('hidden.bs.modal', function () {
        console.log("Legalitas Modal has been hidden");
        // Clear any modal alerts when hidden
        $(this).find('.alert').remove();
    });

    // Event when clicking on the legalitas tab
    $('#legalitas-tab').on('click', function () {
        loadLegalitasData();
    });

    // Load data at startup if legalitas tab is active
    if ($('#legalitas-tab').hasClass('active') || window.location.hash === '#legalitas') {
        loadLegalitasData();
    }

    // Expose functions for global use
    window.legalitasHandler = {
        loadLegalitasData: loadLegalitasData,
        updateLegalitasTable: updateLegalitasTable,
        showAlert: showAlert
    };
});