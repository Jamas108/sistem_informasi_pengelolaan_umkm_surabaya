// intervensi-handler.js - Updated to match new controller logic
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
        $('#table-intervensi tbody').html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading data...</td></tr>');

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
                // Format data yang konsisten dengan tampilan pelaku
                const umkm = item.nama_usaha || 'Tidak ada';
                const noPendaftaran = item.no_pendaftaran_kegiatan || '-';
                const namaKegiatan = item.nama_kegiatan || '-';
                const statusKegiatan = item.status_kegiatan || 'PROSES';
                const tanggal = item.tanggal_kegiatan || item.tgl_intervensi;
                const formattedDate = formatDate(tanggal);

                tableRows += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${umkm}</td>
                    <td>${noPendaftaran}</td>
                    <td>${namaKegiatan}</td>
                    <td>${statusKegiatan}</td>
                    <td>${formattedDate}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-warning btn-sm edit-intervensi" data-id="${item.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm delete-intervensi" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
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
                <td colspan="8" class="text-center">Belum ada data intervensi</td>
            </tr>
            `;
            $('#table-intervensi tbody').html(tableRows);
            console.log('No data available, skipping DataTable initialization');
        }
    }

    // Add intervensi data - UPDATED to match new controller logic
    $('#tambah-data-intervensi').click(function () {
        const umkmId = $('#intervensi_umkm_id').val();
        const kegiatanId = $('#kegiatan_id').val(); // New field for kegiatan_id
        const pelakuId = getPelakuIdFromUrl();

        if (!umkmId) {
            showAlert('warning', 'Pilih UMKM terlebih dahulu');
            return;
        }

        if (!kegiatanId) {
            showAlert('warning', 'Pilih kegiatan terlebih dahulu');
            return;
        }

        // Prepare data object - simplified to match new controller requirements
        const data = {
            umkm_id: umkmId,
            kegiatan_id: kegiatanId
        };

        console.log('Sending intervensi data:', data);

        // Show loading effect on button
        const $button = $(this);
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: `/dataumkm/intervensi/save/${pelakuId}`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $button.prop('disabled', false).html('<i class="fas fa-plus-circle mr-2"></i> Tambah Data Intervensi');

                if (response.success) {
                    // Reset fields
                    $('#intervensi_umkm_id').val('');
                    $('#kegiatan_id').val('');

                    // Reload data
                    loadIntervensiData();
                    showAlert('success', 'Intervensi berhasil ditambahkan dengan nomor pendaftaran: ' + response.registration_number);
                } else {
                    showAlert('danger', response.message || 'Terjadi kesalahan');
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr);
                $button.prop('disabled', false).html('<i class="fas fa-plus-circle mr-2"></i> Tambah Data Intervensi');

                const errorMessage = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Kesalahan menyimpan data';
                showAlert('danger', errorMessage);
            }
        });
    });

    // This code should be added to your intervensi-handler.js file

    // Edit intervensi
    // This code should be added to your intervensi-handler.js file

    // Edit intervensi
    $(document).on('click', '.edit-intervensi', function () {
        const intervensiId = $(this).data('id');

        // Show loading state
        $(this).html('<i class="fas fa-spinner fa-spin"></i>');

        // Get the pelakuId
        const pelakuId = getPelakuIdFromUrl();

        if (!pelakuId) {
            showAlert('danger', 'Error: Cannot determine Pelaku UMKM ID');
            return;
        }

        // Fetch the intervensi data for editing - using pelakuId in the URL
        $.ajax({
            url: `/dataumkm/intervensi/edit/${pelakuId}/${intervensiId}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Reset button state
                $('.edit-intervensi[data-id="' + intervensiId + '"]').html('<i class="fas fa-edit"></i>');

                if (response.success) {
                    const data = response.data;

                    // Populate modal fields
                    $('#edit_intervensi_id').val(data.id);

                    // Populate UMKM dropdown
                    $('#edit_umkm_id').empty();
                    response.umkms.forEach(function (umkm) {
                        const option = new Option(umkm.nama_usaha, umkm.id, false, umkm.id == data.umkm_id);
                        $('#edit_umkm_id').append(option);
                    });

                    // Populate Kegiatan dropdown
                    $('#edit_kegiatan_id').empty();
                    response.kegiatans.forEach(function (kegiatan) {
                        const option = new Option(kegiatan.nama_kegiatan, kegiatan.id, false, kegiatan.id == data.kegiatan_id);
                        const $option = $(option);

                        // Add data attributes to option
                        $option.attr('data-jenis', kegiatan.jenis_kegiatan);
                        $option.attr('data-lokasi', kegiatan.lokasi_kegiatan);
                        $option.attr('data-tanggal-mulai', kegiatan.tanggal_mulai);
                        $option.attr('data-tanggal-selesai', kegiatan.tanggal_selesai);
                        $option.attr('data-jam-mulai', kegiatan.jam_mulai);
                        $option.attr('data-jam-selesai', kegiatan.jam_selesai);
                        $option.attr('data-status', kegiatan.status_kegiatan);

                        $('#edit_kegiatan_id').append($option);
                    });

                    // Set other field values
                    $('#edit_no_pendaftaran').val(data.no_pendaftaran_kegiatan || '');

                    // Format omset if present
                    if (data.omset) {
                        $('#edit_omset').val(formatCurrency(data.omset));
                    } else {
                        $('#edit_omset').val('');
                    }

                    // Update readonly fields based on selected kegiatan
                    updateEditKegiatanDetails();

                    // Show the modal
                    $('#editIntervensiModal').modal('show');
                } else {
                    showAlert('danger', response.message || 'Failed to load intervensi data');
                }
            },
            error: function (xhr) {
                // Reset button state
                $('.edit-intervensi[data-id="' + intervensiId + '"]').html('<i class="fas fa-edit"></i>');

                console.error('Error getting intervensi data:', xhr);
                showAlert('danger', 'Error loading intervensi data: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Connection error'));
            }
        });
    });

    // Function to update kegiatan details in edit modal
    function updateEditKegiatanDetails() {
        const selectedOption = $('#edit_kegiatan_id option:selected');

        if (selectedOption.val()) {
            // Update read-only fields with data from the selected option
            $('#edit_jenis_kegiatan').val(selectedOption.data('jenis') || '');
            $('#edit_lokasi_kegiatan').val(selectedOption.data('lokasi') || '');
            $('#edit_tanggal_mulai').val(selectedOption.data('tanggal-mulai') || '');
            $('#edit_tanggal_selesai').val(selectedOption.data('tanggal-selesai') || '');
            $('#edit_jam_mulai').val(selectedOption.data('jam-mulai') || '');
            $('#edit_jam_selesai').val(selectedOption.data('jam-selesai') || '');
        } else {
            // Clear fields if no option is selected
            $('#edit_jenis_kegiatan, #edit_lokasi_kegiatan, #edit_tanggal_mulai, #edit_tanggal_selesai, #edit_jam_mulai, #edit_jam_selesai').val('');
        }
    }

    // Add change event listener for kegiatan dropdown in edit modal
    $(document).on('change', '#edit_kegiatan_id', function () {
        updateEditKegiatanDetails();
    });

    // Save edited intervensi
    $('#save-edit-intervensi').click(function () {
        const intervensiId = $('#edit_intervensi_id').val();
        const umkmId = $('#edit_umkm_id').val();
        const kegiatanId = $('#edit_kegiatan_id').val();
        const omsetValue = $('#edit_omset').val().replace(/\D/g, ''); // Remove non-digits for server side processing

        // Get the pelakuId
        const pelakuId = getPelakuIdFromUrl();

        if (!pelakuId) {
            showAlert('danger', 'Error: Cannot determine Pelaku UMKM ID', 'editIntervensiModal');
            return;
        }

        if (!umkmId) {
            showAlert('warning', 'Pilih UMKM terlebih dahulu', 'editIntervensiModal');
            return;
        }

        if (!kegiatanId) {
            showAlert('warning', 'Pilih kegiatan terlebih dahulu', 'editIntervensiModal');
            return;
        }

        // Prepare data object
        const data = {
            umkm_id: umkmId,
            kegiatan_id: kegiatanId,
            omset: omsetValue || null
        };

        console.log('Updating intervensi data:', data);

        // Show loading effect on button
        const $button = $(this);
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: `/dataumkm/intervensi/update/${pelakuId}/${intervensiId}`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $button.prop('disabled', false).html('Simpan Perubahan');

                if (response.success) {
                    // Close modal
                    $('#editIntervensiModal').modal('hide');

                    // Reload data
                    loadIntervensiData();
                    showAlert('success', 'Data intervensi berhasil diperbarui');
                } else {
                    showAlert('danger', response.message || 'Terjadi kesalahan', 'editIntervensiModal');
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr);
                $button.prop('disabled', false).html('Simpan Perubahan');

                const errorMessage = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Kesalahan menyimpan data';
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
                        $button.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                    }
                },
                error: function (xhr) {
                    console.error('Error:', xhr);
                    showAlert('danger', 'Kesalahan menghapus data');
                    // Re-enable the button in case of failure
                    $button.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            });
        }
    });

    // Format currency inputs
    $(document).on('input', '.currency-input', function () {
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

    $(document).on('change', '#kegiatan_id', function () {
        const selectedOption = $(this).find('option:selected');

        if (selectedOption.val()) {
            // Update read-only fields with data from the selected option
            $('#jenis_kegiatan').val(selectedOption.data('jenis') || '');
            $('#lokasi_kegiatan').val(selectedOption.data('lokasi') || '');
            $('#tanggal_mulai').val(selectedOption.data('tanggal-mulai') || '');
            $('#tanggal_selesai').val(selectedOption.data('tanggal-selesai') || '');
            $('#jam_mulai').val(selectedOption.data('jam-mulai') || '');
            $('#jam_selesai').val(selectedOption.data('jam-selesai') || '');

            // Update kuota info
            const kuota = parseInt(selectedOption.data('kuota') || 0);
            const sisa = parseInt(selectedOption.data('sisa') || 0);
            const percentageFilled = ((kuota - sisa) / kuota) * 100;

            $('#kuota-progress').css('width', percentageFilled + '%');
            $('#kuota-text').text(`Kuota terisi: ${kuota - sisa} dari ${kuota} (${Math.round(percentageFilled)}%)`);
            $('#kuota-info').show();

            // Update status info
            const status = selectedOption.data('status');
            let statusClass = 'badge-secondary';

            if (status === 'Pendaftaran') {
                statusClass = 'badge-success';
            } else if (status === 'Sedang Berlangsung') {
                statusClass = 'badge-warning';
            } else if (status === 'Selesai') {
                statusClass = 'badge-danger';
            }

            $('#status-info').html(`<span class="badge ${statusClass}">${status}</span>`).show();
        } else {
            // Clear fields if no option is selected
            $('#jenis_kegiatan, #lokasi_kegiatan, #tanggal_mulai, #tanggal_selesai, #jam_mulai, #jam_selesai').val('');
            $('#kuota-info, #status-info').hide();
        }
    });

    // Expose functions for global use
    window.intervensiHandler = {
        loadIntervensiData: loadIntervensiData,
        updateIntervensiTable: updateIntervensiTable,
        showAlert: showAlert
    };
});