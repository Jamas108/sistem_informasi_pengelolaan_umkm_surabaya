$(document).ready(function () {
    // Initialize the umkmCounter
    let umkmCounter = $('.umkm-form-entry').length > 0 ? $('.umkm-form-entry').length : 0;

    // Function to update UMKM numbers
    function updateUmkmNumbers() {
        $('.umkm-form-entry').each(function (index) {
            $(this).find('.umkm-number').text('UMKM #' + (index + 1));
        });
    }

    // Function to add a new UMKM form
    function addUmkmForm() {
        umkmCounter++;

        const newForm = `
            <div class="umkm-form-entry border rounded p-3 mb-4" id="umkm-entry-${umkmCounter}" data-umkm-id="${umkmCounter}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="m-0 umkm-number">UMKM #${$('.umkm-form-entry').length + 1}</h5>
                    <button type="button" class="btn btn-danger btn-sm remove-umkm" data-umkm-id="${umkmCounter}">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>

                <!-- UMKM form fields here -->
                <div class="row mb-3">
                    <label for="nama_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Nama Usaha</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_usaha_${umkmCounter}" name="umkm[${umkmCounter}][nama_usaha]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="alamat_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Alamat Usaha</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="alamat_usaha_${umkmCounter}" name="umkm[${umkmCounter}][alamat]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="jenis_produk_${umkmCounter}" class="col-sm-2 col-form-label">Jenis Produk</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="jenis_produk_${umkmCounter}" name="umkm[${umkmCounter}][jenis_produk]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="tipe_produk_${umkmCounter}" class="col-sm-2 col-form-label">Tipe Produk</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="tipe_produk_${umkmCounter}" name="umkm[${umkmCounter}][tipe_produk]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="pengelolaan_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Pengelolaan Usaha</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="pengelolaan_usaha_${umkmCounter}" name="umkm[${umkmCounter}][pengelolaan_usaha]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="klasifikasi_kinerja_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Klasifikasi Kinerja Usaha</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="klasifikasi_kinerja_usaha_${umkmCounter}" name="umkm[${umkmCounter}][klasifikasi_kinerja_usaha]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="jumlah_tenaga_kerja_${umkmCounter}" class="col-sm-2 col-form-label">Jumlah Tenaga Kerja</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="jumlah_tenaga_kerja_${umkmCounter}" name="umkm[${umkmCounter}][jumlah_tenaga_kerja]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="sektor_usaha_${umkmCounter}" class="col-sm-2 col-form-label">Sektor Usaha</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="sektor_usaha_${umkmCounter}" name="umkm[${umkmCounter}][sektor_usaha]">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="status_${umkmCounter}" class="col-sm-2 col-form-label">Status Keaktifan UMKM</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="status_${umkmCounter}" name="umkm[${umkmCounter}][status]">
                    </div>
                </div>

                <input type="hidden" name="umkm[${umkmCounter}][id]" value="">
            </div>
        `;

        $('#umkm-entries-container').append(newForm);
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
    });

    // Handle tab navigation with jQuery
    $('button[data-target]').click(function () {
        const targetTab = $(this).data('target');
        $(`#${targetTab}`).tab('show');
    });

    // Fix the missing data-pelaku-id attribute for the form
    if ($('form').data('pelaku-id') === undefined) {
        // Extract ID from URL or set it manually
        const url = window.location.href;
        const pelakuId = url.substring(url.lastIndexOf('/') + 1);
        $('form').attr('data-pelaku-id', pelakuId);
    }

    // Initialize the Omset functionality
    // Add omset data
    $('#tambah-data-omset').click(function () {
        const umkmId = $('#umkm_id').val();
        const jangkaWaktu = $('#jangka_waktu').val();
        const omset = $('#total_omset').val();
        const keterangan = $('#keterangan').val();

        // Validate inputs
        if (!umkmId || !jangkaWaktu || !keterangan) {
            showAlert('warning', 'Semua field harus diisi');
            return;
        }

        // Get form data
        const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            umkm_id: umkmId,
            jangka_waktu: jangkaWaktu,
            total_omset: omset,
            keterangan: keterangan
        };

        // Send AJAX request to save omset data
        $.ajax({
            url: `/dataumkm/omset/save/${umkmId}`,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.success) {
                    // Reset form fields
                    $('#umkm_id').val('');
                    $('#jangka_waktu').val('');
                    $('#total_omset').val('');
                    $('#keterangan').val('');

                    // Update table with new omset data
                    updateOmsetTable();

                    showAlert('success', 'Data omset berhasil ditambahkan');
                } else {
                    showAlert('danger', response.message || 'Terjadi kesalahan');
                }
            },
            error: function () {
                showAlert('danger', 'Terjadi kesalahan saat menyimpan data');
            }
        });
    });

    // Save edited omset data
    $('#save-edit-omset').click(function () {
        const omsetId = $('#edit_omset_id').val();
        const umkmId = $('#edit_umkm_id').val();
        const jangkaWaktu = $('#edit_jangka_waktu').val();
        const omset = $('#edit_omset').val();
        const keterangan = $('#edit_keterangan').val();

        if (!umkmId || !jangkaWaktu || !keterangan) {
            showAlert('warning', 'Semua field harus diisi', 'editOmsetModal');
            return;
        }

        // Get form data
        const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'PUT',
            umkm_id: umkmId,
            jangka_waktu: jangkaWaktu,
            total_omset: omset,
            keterangan: keterangan
        };

        // Send AJAX request to update omset data
        $.ajax({
            url: `/dataumkm/omset/${omsetId}`,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.success) {
                    // Hide modal
                    $('#editOmsetModal').modal('hide');

                    // Update table with new omset data
                    updateOmsetTable();

                    showAlert('success', 'Data omset berhasil diperbarui');
                } else {
                    showAlert('danger', response.message || 'Terjadi kesalahan', 'editOmsetModal');
                }
            },
            error: function () {
                showAlert('danger', 'Terjadi kesalahan saat memperbarui data', 'editOmsetModal');
            }
        });
    });

    // Add a missing function for showAlert
    function showAlert(type, message, modalId = null) {
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
            $('.container-fluid').prepend(alertHTML);
        }
    }

    // Fix for navigation between tabs
    $('.nav-tabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Fix for buttons that navigate between tabs
    $('button[onclick]').each(function () {
        const onclickAttr = $(this).attr('onclick');
        if (onclickAttr && onclickAttr.includes('.tab(\'show\')')) {
            $(this).removeAttr('onclick');
            $(this).click(function () {
                const tabId = onclickAttr.match(/#([^']+)/)[1];
                $(`#${tabId}`).tab('show');
            });
        }
    });
    $(document).ready(function () {
        // Add omset data
        $('#tambah-data-omset').click(function () {
            const umkmId = $('#umkm_id').val();
            const jangkaWaktu = $('#jangka_waktu').val();
            const omset = $('#total_omset').val();
            const keterangan = $('#keterangan').val();

            // Validate inputs
            if (!umkmId || !jangkaWaktu || !keterangan) {
                showAlert('warning', 'Semua field harus diisi');
                return;
            }

            // Get form data
            const data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                umkm_id: umkmId,
                jangka_waktu: jangkaWaktu,
                total_omset: omset,
                keterangan: keterangan
            };

            // Send AJAX request to save omset data
            $.ajax({
                url: `/dataumkm/omset/save/${$('form').data('pelaku-id')}`,
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response.success) {
                        // Reset form fields
                        $('#umkm_id').val('');
                        $('#jangka_waktu').val('');
                        $('#total_omset').val('');
                        $('#keterangan').val('');

                        // Update table with new omset data
                        updateOmsetTable();

                        showAlert('success', 'Data omset berhasil ditambahkan');
                    } else {
                        showAlert('danger', response.message || 'Terjadi kesalahan');
                    }
                },
                error: function (xhr) {
                    console.error('Error:', xhr);
                    showAlert('danger', 'Terjadi kesalahan saat menyimpan data');
                }
            });
        });

        // Function to update the omset table after adding/editing
        // Updated updateOmsetTable function
        function updateOmsetTable() {
            const umkmId = $('#umkm_id').val();
            $.ajax({
                url: `/dataumkm/omset/list/${umkmId}`,
                type: 'GET',
                success: function (response) {
                    if (response.success) {
                        const omsetData = response.data;
                        let tableRows = '';

                        if (omsetData.length > 0) {
                            omsetData.forEach((item, index) => {
                                // Table row generation code...
                            });
                        } else {
                            tableRows = `
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data omset</td>
                        </tr>
                    `;
                        }

                        $('#table-omset tbody').html(tableRows);
                    }
                },
                error: function (xhr) {
                    console.error('Error fetching omset data:', xhr);
                }
            });
        }
    });
});