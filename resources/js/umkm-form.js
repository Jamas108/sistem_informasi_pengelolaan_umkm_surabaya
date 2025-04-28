$(document).ready(function () {
    // Initialize the umkmCounter
    window.umkmCounter = $('.umkm-form-entry').length > 0 ? $('.umkm-form-entry').length : 0;

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

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="nama_usaha_${window.umkmCounter}" class="col-sm-4 col-form-label font-weight-bold">Nama Usaha</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nama_usaha_${window.umkmCounter}" name="umkm[${window.umkmCounter}][nama_usaha]">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="alamat_usaha_${window.umkmCounter}" class="col-sm-4 col-form-label font-weight-bold">Alamat Usaha</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="alamat_usaha_${window.umkmCounter}" name="umkm[${window.umkmCounter}][alamat]">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="jenis_produk_${window.umkmCounter}" class="col-sm-4 col-form-label font-weight-bold">Jenis Produk</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="jenis_produk_${window.umkmCounter}" name="umkm[${window.umkmCounter}][jenis_produk]">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="tipe_produk_${window.umkmCounter}" class="col-sm-4 col-form-label font-weight-bold">Tipe Produk</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="tipe_produk_${window.umkmCounter}" name="umkm[${window.umkmCounter}][tipe_produk]">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="pengelolaan_usaha_${window.umkmCounter}" class="col-sm-4 col-form-label font-weight-bold">Pengelolaan Usaha</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="pengelolaan_usaha_${window.umkmCounter}" name="umkm[${window.umkmCounter}][pengelolaan_usaha]">
                                    <option value="">-- Pilih --</option>
                                    <option value="Perseorangan">Perseorangan</option>
                                    <option value="Kelompok">Kelompok</option>
                                    <option value="Badan Usaha">Badan Usaha</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="klasifikasi_kinerja_usaha_${window.umkmCounter}" class="col-sm-4 col-form-label font-weight-bold">Klasifikasi Kinerja</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="klasifikasi_kinerja_usaha_${window.umkmCounter}" name="umkm[${window.umkmCounter}][klasifikasi_kinerja_usaha]">
                                    <option value="">-- Pilih --</option>
                                    <option value="Sangat Baik">Sangat Baik</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Cukup">Cukup</option>
                                    <option value="Kurang">Kurang</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="jumlah_tenaga_kerja_${window.umkmCounter}" class="col-sm-4 col-form-label font-weight-bold">Jumlah Tenaga Kerja</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="jumlah_tenaga_kerja_${window.umkmCounter}" name="umkm[${window.umkmCounter}][jumlah_tenaga_kerja]">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="sektor_usaha_${window.umkmCounter}" class="col-sm-4 col-form-label font-weight-bold">Sektor Usaha</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="sektor_usaha_${window.umkmCounter}" name="umkm[${window.umkmCounter}][sektor_usaha]">
                                    <option value="">-- Pilih --</option>
                                    <option value="Manufaktur">Manufaktur</option>
                                    <option value="Jasa">Jasa</option>
                                    <option value="Perdagangan">Perdagangan</option>
                                    <option value="Pertanian">Pertanian</option>
                                    <option value="Peternakan">Peternakan</option>
                                    <option value="Perikanan">Perikanan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="status_${window.umkmCounter}" class="col-sm-4 col-form-label font-weight-bold">Status Keaktifan</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="status_${window.umkmCounter}" name="umkm[${window.umkmCounter}][status]">
                                    <option value="">-- Pilih --</option>
                                    <option value="AKTIF">AKTIF</option>
                                    <option value="CUKUP AKTIF">CUKUP AKTIF</option>
                                    <option value="KURANG AKTIF">KURANG AKTIF</option>
                                    <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add the hidden field for pelaku_umkm_id -->
                <input type="hidden" name="umkm[${window.umkmCounter}][pelaku_umkm_id]" value="${pelakuId}">
                <input type="hidden" name="umkm[${window.umkmCounter}][id]" value="">
            </div>
        `;

        $('#umkm-entries-container').append(newForm);
    }

    // Add button to add another UMKM form
    $('#add-umkm-btn').off('click').on('click', function () {
        console.log('Adding new UMKM form');
        addUmkmForm();
    });

    // Remove UMKM form when remove button is clicked
    $(document).off('click', '.remove-umkm').on('click', '.remove-umkm', function () {
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

    // Fix the missing data-pelaku-id attribute for the form
    if ($('form').data('pelaku-id') === undefined) {
        // Extract ID from URL path
        const pelakuId = getPelakuIdFromUrl();
        if (pelakuId) {
            $('form').attr('data-pelaku-id', pelakuId);
        }
    }
});