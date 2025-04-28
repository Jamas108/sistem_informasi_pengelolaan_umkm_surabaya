// intervensi-handler.js - Save this file to public/js/intervensi-handler.js
$(document).ready(function () {
    console.log("Intervensi handler script loaded");

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

    // Format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID').format(amount);
    }

    // Format date to Indonesian format
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        }).replace(/\//g, '-');
    }

    // Load intervensi data
    function loadIntervensiData() {
        const pelakuId = getPelakuIdFromUrl();

        if (!pelakuId) {
            console.error('Error: Cannot find Pelaku UMKM ID in URL');
            showAlert('danger', 'Error: Cannot determine Pelaku UMKM ID');
            return;
        }

        console.log('Loading intervensi data for Pelaku ID:', pelakuId);

        // Show loading indicator
        $('#table-intervensi tbody').html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading data...</td></tr>');

        $.ajax({
            url: `/dataumkm/intervensi/list/${pelakuId}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Intervensi data response:', response);
                if (response.success) {
                    updateIntervensiTable(response.data);
                } else {
                    // Show error and empty table
                    showAlert('danger', response.message || 'Failed to load intervensi data');
                    updateIntervensiTable([]);
                }
            },
            error: function (xhr) {
                console.error('Error getting intervensi data:', xhr);
                showAlert('danger', 'Error loading intervensi data: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Connection error'));
                // Show empty table in case of error
                updateIntervensiTable([]);
            }
        });
    }

    // Update intervensi table with data
    function updateIntervensiTable(data) {
        console.log('Updating intervensi table with data:', data);

        // Destroy existing DataTable if already initialized
        if ($.fn.dataTable && $.fn.dataTable.isDataTable('#table-intervensi')) {
            $('#table-intervensi').DataTable().destroy();
        }

        // Completely empty the table
        $('#table-intervensi tbody').empty();

        let tableRows = '';

        if (data && data.length > 0) {
            data.forEach((item, index) => {
                tableRows += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.nama_usaha || 'Tidak ada'}</td>
                    <td>${formatDate(item.tgl_intervensi)}</td>
                    <td>${item.jenis_intervensi || '-'}</td>
                    <td>${item.nama_kegiatan || '-'}</td>
                    <td>Rp. ${formatCurrency(item.omset)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-warning btn-sm edit-intervensi" data-id="${item.id}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm delete-intervensi" data-id="${item.id}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                `;
            });
            
            // Insert rows into the table
            $('#table-intervensi tbody').html(tableRows);

            // Initialize DataTable with simplified options to avoid conflicts
            setTimeout(function () {
                try {
                    if ($.fn.dataTable) {
                        $('#table-intervensi').DataTable({
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
                                emptyTable: "Belum ada data intervensi",
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
                <td colspan="7" class="text-center">Belum ada data intervensi</td>
            </tr>
            `;
            $('#table-intervensi tbody').html(tableRows);
            console.log('No data available, skipping DataTable initialization');
        }
    }

    // Add intervensi data
    $('#tambah-data-intervensi').click(function () {
        const umkmId = $('#intervensi_umkm_id').val();
        const tanggalIntervensi = $('#tgl_intervensi').val();
        const jenisIntervensi = $('#jenis_intervensi').val();
        const namaKegiatan = $('#nama_kegiatan').val();
        const omset = $('#omset_intervensi').val().replace(/\./g, '').replace(',', '.');
        const pelakuId = getPelakuIdFromUrl();

        if (!umkmId) {
            showAlert('warning', 'Pilih UMKM terlebih dahulu');
            return;
        }

        if (!tanggalIntervensi) {
            showAlert('warning', 'Tanggal intervensi harus diisi');
            return;
        }

        if (!jenisIntervensi) {
            showAlert('warning', 'Jenis intervensi harus diisi');
            return;
        }

        const data = {
            umkm_id: umkmId,
            tgl_intervensi: tanggalIntervensi,
            jenis_intervensi: jenisIntervensi,
            nama_kegiatan: namaKegiatan,
            omset: omset
        };

        $.ajax({
            url: `/dataumkm/intervensi/save/${pelakuId}`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    // Reset fields
                    $('#intervensi_umkm_id').val('');
                    $('#tgl_intervensi').val('');
                    $('#jenis_intervensi').val('');
                    $('#nama_kegiatan').val('');
                    $('#omset_intervensi').val('');

                    // Reload data
                    loadIntervensiData();
                    showAlert('success', 'Data intervensi berhasil ditambahkan');
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

    // Edit intervensi handler (click on edit button)
    $(document).on('click', '.edit-intervensi', function (e) {
        e.preventDefault();
        const intervensiId = $(this).data('id');

        // Add visual feedback
        const $button = $(this);
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

        // Make the AJAX request
        $.ajax({
            url: `/dataumkm/intervensi/${intervensiId}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $button.prop('disabled', false).html('<i class="fas fa-edit"></i> Edit');

                if (response.success) {
                    const data = response.data;

                    // Set values in the form
                    $('#edit_intervensi_id').val(data.id);
                    $('#edit_umkm_id').val(data.umkm_id);
                    $('#tgl_intervensi').val(data.tgl_intervensi);
                    $('#edit_jenis_intervensi').val(data.jenis_intervensi);
                    $('#edit_nama_kegiatan').val(data.nama_kegiatan);
                    $('#edit_omset').val(data.omset);

                    // Show the modal - detect Bootstrap version and use appropriate method
                    if (typeof bootstrap !== 'undefined') {
                        // Bootstrap 5
                        const myModal = new bootstrap.Modal(document.getElementById('editIntervensiModal'));
                        myModal.show();
                    } else {
                        // Bootstrap 4 or earlier
                        $('#editIntervensiModal').modal('show');
                    }
                } else {
                    alert("Error loading data: " + (response.message || "Unknown error"));
                }
            },
            error: function (xhr) {
                $button.prop('disabled', false).html('<i class="fas fa-edit"></i> Edit');
                alert("Error loading data. Please check console for details.");
                console.error("Error loading intervensi data:", xhr);
            }
        });
    });

    // Save edited intervensi
    $(document).on('click', '#save-edit-intervensi', function () {
        console.log("Save button clicked - Event triggered");

        // Disable the button and show loading state
        const $button = $(this);
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        const intervensiId = $('#edit_intervensi_id').val();
        const umkmId = $('#edit_umkm_id').val();
        const tanggalIntervensi = $('#tgl_intervensi').val();
        const jenisIntervensi = $('#edit_jenis_intervensi').val();
        const namaKegiatan = $('#edit_nama_kegiatan').val();
        const omset = $('#edit_omset').val().replace(/\./g, '').replace(',', '.');

        console.log('Saving edit intervensi data:', {
            id: intervensiId,
            umkm_id: umkmId,
            tgl_intervensi: tanggalIntervensi,
            jenis_intervensi: jenisIntervensi,
            nama_kegiatan: namaKegiatan,
            omset: omset
        });

        if (!umkmId) {
            alert('Pilih UMKM terlebih dahulu');
            $button.prop('disabled', false).html('Simpan Perubahan');
            return;
        }

        if (!tanggalIntervensi) {
            alert('Tanggal intervensi harus diisi');
            $button.prop('disabled', false).html('Simpan Perubahan');
            return;
        }

        const data = {
            _method: 'PUT',
            umkm_id: umkmId,
            tgl_intervensi: tanggalIntervensi,
            jenis_intervensi: jenisIntervensi,
            nama_kegiatan: namaKegiatan,
            omset: omset
        };

        $.ajax({
            url: `/dataumkm/intervensi/${intervensiId}`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Update intervensi response:', response);
                $button.prop('disabled', false).html('Simpan Perubahan');

                if (response.success) {
                    // Close the modal - detect Bootstrap version and use appropriate method
                    if (typeof bootstrap !== 'undefined') {
                        // Bootstrap 5
                        const modalElement = document.getElementById('editIntervensiModal');
                        const modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    } else {
                        // Bootstrap 4 or earlier
                        $('#editIntervensiModal').modal('hide');
                    }

                    // Reload data
                    loadIntervensiData();

                    // Show success message
                    showAlert('success', 'Data intervensi berhasil diperbarui');
                } else {
                    showAlert('danger', response.message || 'Terjadi kesalahan', 'editIntervensiModal');
                }
            },
            error: function (xhr) {
                console.error('Error saving changes:', xhr);
                $button.prop('disabled', false).html('Simpan Perubahan');

                const errorMessage = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Kesalahan memperbarui data';
                showAlert('danger', errorMessage, 'editIntervensiModal');
            }
        });
    });

    // Delete intervensi
    $(document).on('click', '.delete-intervensi', function () {
        const intervensiId = $(this).data('id');

        if (confirm('Apakah Anda yakin ingin menghapus data intervensi ini?')) {
            // Disable the button to prevent multiple clicks
            const $button = $(this);
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: `/dataumkm/intervensi/${intervensiId}`,
                type: 'POST',
                data: {
                    _method: 'DELETE'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        loadIntervensiData();
                        showAlert('success', 'Data intervensi berhasil dihapus');
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

    // Format currency inputs
    $(document).on('input', '.currency-input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value) {
            value = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(value);
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
    $('#editIntervensiModal').on('show.bs.modal', function () {
        console.log("Intervensi Modal is about to be shown");
    }).on('shown.bs.modal', function () {
        console.log("Intervensi Modal has been shown");
        // Focus on the first input field
        $(this).find('input:visible:first').focus();
    }).on('hide.bs.modal', function () {
        console.log("Intervensi Modal is about to be hidden");
    }).on('hidden.bs.modal', function () {
        console.log("Intervensi Modal has been hidden");
        // Clear any modal alerts when hidden
        $(this).find('.alert').remove();
    });

    // Event when clicking on the intervensi tab
    $('#intervensi-tab').on('click', function () {
        loadIntervensiData();
    });

    // Load data at startup if intervensi tab is active
    if ($('#intervensi-tab').hasClass('active') || window.location.hash === '#intervensi') {
        loadIntervensiData();
    }

    // Expose functions for global use
    window.intervensiHandler = {
        loadIntervensiData: loadIntervensiData,
        updateIntervensiTable: updateIntervensiTable,
        showAlert: showAlert
    };
});