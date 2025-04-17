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



    $(document).ready(function () {
        // Initialize the umkmCounter
        let umkmCounter = $('.umkm-form-entry').length > 0 ? $('.umkm-form-entry').length : 0;

        // Function to extract Pelaku ID from URL path
        function getPelakuIdFromUrl() {
            const url = window.location.pathname;
            const urlParts = url.split('/');
            // For URL like /dataumkm/16/edit - we want the 16
            for (let i = 0; i < urlParts.length; i++) {
                if (urlParts[i] === 'dataumkm' && i + 1 < urlParts.length) {
                    return urlParts[i + 1];
                }
            }
            return null;
        }

        // Function to update UMKM numbers
        function updateUmkmNumbers() {
            $('.umkm-form-entry').each(function (index) {
                $(this).find('.umkm-number').text('UMKM #' + (index + 1));
            });
        }

        // Function to add a new UMKM form
        function addUmkmForm() {
            umkmCounter++;

            // Get the pelaku ID from the URL
            const pelakuId = getPelakuIdFromUrl();

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

                    <!-- Additional fields -->

                    <!-- Add the hidden field for pelaku_umkm_id -->
                    <input type="hidden" name="umkm[${umkmCounter}][pelaku_umkm_id]" value="${pelakuId}">
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
            // Extract ID from URL path
            const pelakuId = getPelakuIdFromUrl();
            if (pelakuId) {
                $('form').attr('data-pelaku-id', pelakuId);
            }
        }

        // MANAJEMEN OMSET
        // Modified function to load omset data
        // Script específico para la gestión de omset
        // Este archivo debe guardarse como omset-handler.js

        $(document).ready(function () {
            console.log("Omset handler script cargado");

            // Función para obtener ID del pelaku de la URL
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

            // Formatear entradas de moneda
            $(document).on('input', '.currency-input', function () {
                let value = $(this).val();
                value = value.replace(/[^\d]/g, ''); // Eliminar todo lo que no sea dígito
                if (value !== '') {
                    value = parseInt(value).toLocaleString('id-ID');
                    $(this).val(value);
                }
            });

            // Cargar datos de omset
            function loadOmsetData() {
                const pelakuId = getPelakuIdFromUrl();

                if (!pelakuId) {
                    console.error('Error: No se puede encontrar ID Pelaku UMKM en la URL');
                    showAlert('danger', 'Error: No se puede determinar el ID del Pelaku UMKM');
                    return;
                }

                console.log('Cargando datos de omset para Pelaku ID:', pelakuId);

                // Mostrar indicador de carga
                $('#table-omset tbody').html('<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</td></tr>');

                $.ajax({
                    url: `/dataumkm/omset/list/${pelakuId}`,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        console.log('Respuesta de datos omset:', response);
                        if (response.success) {
                            updateOmsetTable(response.data);
                        } else {
                            // Mostrar error y tabla vacía
                            showAlert('danger', response.message || 'Falló la carga de datos de omset');
                            updateOmsetTable([]);
                        }
                    },
                    error: function (xhr) {
                        console.error('Error al obtener datos de omset:', xhr);
                        showAlert('danger', 'Error al cargar datos de omset: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Error de conexión'));
                        // Mostrar tabla vacía en caso de error
                        updateOmsetTable([]);
                    }
                });
            }



            // Actualizar tabla de omset con datos
            function updateOmsetTable(data) {
                console.log('Actualizando tabla de omset con datos:', data);

                // Destruir DataTable existente si ya está inicializado
                if ($.fn.dataTable && $.fn.dataTable.isDataTable('#table-omset')) {
                    $('#table-omset').DataTable().destroy();
                }

                // Vaciar completamente la tabla
                $('#table-omset tbody').empty();

                let tableRows = '';

                if (data && data.length > 0) {
                    data.forEach((item, index) => {
                        // Formatear fecha
                        const date = new Date(item.jangka_waktu);
                        const formattedDate = date.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        }).replace(/\//g, '-');

                        // Formatear moneda
                        const formattedOmset = parseInt(item.total_omset).toLocaleString('id-ID');

                        // Generar badge de estado
                        let statusBadge = '';
                        switch (item.keterangan) {
                            case 'aktif':
                                statusBadge = '<span class="badge badge-success">Aktif</span>';
                                break;
                            case 'tidak_aktif':
                                statusBadge = '<span class="badge badge-danger">Tidak Aktif</span>';
                                break;
                            case 'meningkat':
                                statusBadge = '<span class="badge badge-info">Meningkat</span>';
                                break;
                            case 'menurun':
                                statusBadge = '<span class="badge badge-warning">Menurun</span>';
                                break;
                            case 'stabil':
                                statusBadge = '<span class="badge badge-secondary">Stabil</span>';
                                break;
                            default:
                                statusBadge = '<span class="badge badge-light">' + item.keterangan + '</span>';
                        }

                        tableRows += `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td>${item.nama_usaha || 'N/A'}</td>
                            <td>${formattedDate}</td>
                            <td>Rp. ${formattedOmset}</td>
                            <td>${statusBadge}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning btn-sm edit-omset" data-id="${item.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-danger btn-sm delete-omset" data-id="${item.id}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                } else {
                    tableRows = `
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data omset</td>
                    </tr>
                    `;
                }

                // Insertar las filas en la tabla
                $('#table-omset tbody').html(tableRows);

                // Inicializar DataTable con opciones simplificadas para evitar conflictos
                setTimeout(function () {
                    try {
                        if ($.fn.dataTable) {
                            $('#table-omset').DataTable({
                                destroy: true,       // Asegurar que se destruya cualquier instancia previa
                                retrieve: true,      // Recuperar la instancia existente si ya está inicializada
                                responsive: true,
                                paging: true,        // Activar paginación
                                searching: true,     // Activar búsqueda
                                ordering: true,      // Activar ordenamiento
                                info: true,          // Mostrar información
                                lengthChange: true,  // Permitir cambiar la longitud de página
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
                            console.log('DataTable inicializado correctamente');
                        } else {
                            console.warn('DataTable no está disponible, la tabla se mostrará sin funcionalidades avanzadas');
                        }
                    } catch (error) {
                        console.error('Error al inicializar DataTable:', error);
                        // Continuar sin DataTable si hay un error
                    }
                }, 100); // Pequeño retraso para asegurar que el DOM esté completamente actualizado
            }


            // Añadir datos de omset
            $('#tambah-data-omset').click(function () {
                const umkmId = $('#umkm_id').val();
                const jangkaWaktu = $('#jangka_waktu').val();
                const omset = $('#total_omset').val().replace(/\D/g, ''); // Eliminar no dígitos
                const keterangan = $('#keterangan').val();
                const pelakuId = getPelakuIdFromUrl();

                if (!umkmId || !jangkaWaktu || !omset || !keterangan) {
                    showAlert('warning', 'Todos los campos deben ser completados');
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
                            // Restablecer campos
                            $('#umkm_id').val('');
                            $('#jangka_waktu').val('');
                            $('#total_omset').val('');
                            $('#keterangan').val('');

                            // Recargar datos
                            loadOmsetData();
                            showAlert('success', 'Datos de omset añadidos con éxito');
                        } else {
                            showAlert('danger', response.message || 'Ha ocurrido un error');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.message
                            ? xhr.responseJSON.message
                            : 'Error al guardar datos';
                        showAlert('danger', errorMessage);
                    }
                });
            });

            // Editar omset (click en botón editar)
            $(document).on('click', '.edit-omset', function () {
                const omsetId = $(this).data('id');

                $.ajax({
                    url: `/dataumkm/omset/${omsetId}`,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            const data = response.data;

                            // Establecer valores en el formulario
                            $('#edit_omset_id').val(data.id);
                            $('#edit_umkm_id').val(data.umkm_id);
                            $('#edit_jangka_waktu').val(data.jangka_waktu);

                            // Formatear moneda
                            const formattedOmset = parseInt(data.total_omset).toLocaleString('id-ID');
                            $('#edit_omset').val(formattedOmset);

                            $('#edit_keterangan').val(data.keterangan);

                            // Mostrar modal
                            $('#editOmsetModal').modal('show');
                        } else {
                            showAlert('danger', response.message || 'Error al cargar datos');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        showAlert('danger', 'Error al cargar datos');
                    }
                });
            });

            // Guardar omset editado
            $('#save-edit-omset').click(function () {
                const omsetId = $('#edit_omset_id').val();
                const umkmId = $('#edit_umkm_id').val();
                const jangkaWaktu = $('#edit_jangka_waktu').val();
                const omset = $('#edit_omset').val().replace(/\D/g, '');
                const keterangan = $('#edit_keterangan').val();

                if (!umkmId || !jangkaWaktu || !omset || !keterangan) {
                    showAlert('warning', 'Todos los campos deben ser completados', 'editOmsetModal');
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
                        if (response.success) {
                            // Ocultar modal
                            $('#editOmsetModal').modal('hide');

                            // Eliminar alertas existentes
                            $('#editOmsetModal .alert').remove();

                            // Recargar datos
                            loadOmsetData();
                            showAlert('success', 'Datos de omset actualizados con éxito');
                        } else {
                            showAlert('danger', response.message || 'Ha ocurrido un error', 'editOmsetModal');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.message
                            ? xhr.responseJSON.message
                            : 'Error al actualizar datos';
                        showAlert('danger', errorMessage, 'editOmsetModal');
                    }
                });
            });

            // Eliminar omset
            $(document).on('click', '.delete-omset', function () {
                const omsetId = $(this).data('id');

                if (confirm('¿Está seguro que desea eliminar estos datos de omset?')) {
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
                                showAlert('success', 'Datos de omset eliminados con éxito');
                            } else {
                                showAlert('danger', response.message || 'Error al eliminar datos');
                            }
                        },
                        error: function (xhr) {
                            console.error('Error:', xhr);
                            showAlert('danger', 'Error al eliminar datos');
                        }
                    });
                }
            });

            // Función para mostrar alertas
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
                    // Eliminar alertas existentes
                    $(`#${modalId} .alert`).remove();
                    $(`#${modalId} .modal-body`).prepend(alertHTML);
                } else {
                    // Eliminar alertas existentes
                    $('.container-fluid > .alert').remove();
                    $('.container-fluid').prepend(alertHTML);
                }

                // Auto-cerrar después de 5 segundos
                setTimeout(function () {
                    $('.alert').alert('close');
                }, 5000);
            }

            // Evento al hacer clic en la pestaña de omset
            $('#omset-tab').on('click', function () {
                loadOmsetData();
            });

            // Cargar datos al inicio si la pestaña de omset está activa
            if ($('#omset-tab').hasClass('active') || window.location.hash === '#omset') {
                loadOmsetData();
            }

            // Exponer funciones para uso global
            window.omsetHandler = {
                loadOmsetData: loadOmsetData,
                updateOmsetTable: updateOmsetTable,
                showAlert: showAlert
            };
        });

    });

});