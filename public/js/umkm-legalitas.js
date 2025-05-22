$(document).ready(function () {
    console.log("Manual Wizard Legalitas handler loaded");

    let currentStep = 1;
    const totalSteps = 9;
    const steps = ['surat-keterangan', 'nib', 'siup', 'tdp', 'pirt', 'bpom', 'halal', 'merek', 'haki'];

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

    $('#legalitas_umkm_id').change(function() {
        const selectedValue = $(this).val();

        if (selectedValue) {
            $('#legalitas-wizard').fadeIn();
            loadExistingLegalitasData(selectedValue);
        } else {
            $('#legalitas-wizard').fadeOut();
            clearForm();
        }
    });

    function showStep(step) {
        $('.form-step').hide();
        $(`#${steps[step - 1]}`).show();
        currentStep = step;
        updateStepIndicators();
        updateNavigationButtons();

        setTimeout(function() {
            $(`#${steps[step - 1]} input[type="text"]:first`).focus();
        }, 300);
    }

    function resetWizard() {
        currentStep = 1;
        showStep(currentStep);
        updateStepIndicators();
        updateProgress();
        updateNavigationButtons();
    }

    function validateStep(step) {
        const stepId = steps[step - 1];
        const $inputs = $(`#${stepId} input[type="text"]`);
        let valid = true;

        $inputs.each(function() {
            const val = $(this).val().trim();
            if (val === '') {
                valid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        return valid;
    }

    function saveStepData(step) {
        const stepId = steps[step - 1];
        const pelakuId = getPelakuIdFromUrl();
        const umkmId = $('#legalitas_umkm_id').val();
        const currentId = $('#current_legalitas_id').val();

        if (!umkmId) {
            showAlert('warning', 'Pilih UMKM terlebih dahulu');
            return $.Deferred().reject();
        }

        // Ambil semua data form, pastikan lengkap untuk update
        const data = {
            umkm_id: umkmId,
            no_surat_keterangan: $('#no_surat_keterangan').val(),
            no_sk_nib: $('#no_sk_nib').val(),
            no_sk_siup: $('#no_sk_siup').val(),
            no_sk_tdp: $('#no_sk_tdp').val(),
            no_sk_pirt: $('#no_sk_pirt').val(),
            no_sk_bpom: $('#no_sk_bpom').val(),
            no_sk_halal: $('#no_sk_halal').val(),
            no_sk_merek: $('#no_sk_merek').val(),
            no_sk_haki: $('#no_sk_haki').val()
        };

        let url = `/dataumkm/legalitas/save/${pelakuId}`;
        let method = 'POST';

        if (currentId) {
            url = `/dataumkm/legalitas/${currentId}`;
            data._method = 'PUT';
        }

        console.log('Saving data step:', step, data);

        return $.ajax({
            url: url,
            type: method,
            data: data,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        }).done((response) => {
            if (response.success && response.data && response.data.id) {
                // Update current_legalitas_id agar update selanjutnya tahu record mana yang diupdate
                $('#current_legalitas_id').val(response.data.id);

                // Update form dengan data terbaru dari server untuk konsistensi
                populateForm(response.data);
            } else {
                showAlert('danger', response.message || 'Gagal menyimpan data.');
            }
        }).fail((xhr) => {
            let msg = 'Terjadi kesalahan saat menyimpan data.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            showAlert('danger', msg);
        });
    }

    window.changeStep = function(direction) {
        const newStep = currentStep + direction;
        console.log(`Change step from ${currentStep} by ${direction} to ${newStep}`);

        if (newStep >= 1 && newStep <= totalSteps) {
            if (direction === 1) {
                if (!validateStep(currentStep)) {
                    showAlert('warning', `Mohon lengkapi data pada langkah ${currentStep} terlebih dahulu`);
                    return;
                }

                saveStepData(currentStep)
                    .done(() => {
                        showStep(newStep);
                        updateProgress();
                    })
                    .fail(() => {
                        // Sudah ada alert di saveStepData, tinggal prevent pindah step
                    });

            } else {
                // Kalau mundur, langsung pindah tanpa simpan
                showStep(newStep);
                updateProgress();
            }
        }
    };

    function loadExistingLegalitasData(umkmId) {
        const pelakuId = getPelakuIdFromUrl();

        if (!pelakuId) {
            console.error('Error: Cannot find Pelaku UMKM ID in URL');
            return;
        }

        $.ajax({
            url: `/dataumkm/legalitas/list/${pelakuId}`,
            type: 'GET',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                if (response.success && response.data && response.data.length > 0) {
                    const umkmData = response.data.find(item => item.umkm_id == umkmId);
                    if (umkmData) {
                        populateForm(umkmData);

                        // Jika step 1 sudah ada nomor surat keterangan, langsung ke step 2
                        if (umkmData.no_surat_keterangan && umkmData.no_surat_keterangan.trim() !== '') {
                            showStep(2);
                        } else {
                            showStep(1);
                        }
                    } else {
                        clearForm();
                        showStep(1);
                    }
                } else {
                    clearForm();
                    showStep(1);
                }
                setTimeout(updateProgress, 100);
            },
            error: function (xhr) {
                console.error('Error loading existing legalitas data:', xhr);
                clearForm();
                showStep(1);
            }
        });
    }

    function populateForm(data) {
        $('#no_surat_keterangan').val(data.no_surat_keterangan || '');
        $('#no_sk_nib').val(data.no_sk_nib || '');
        $('#no_sk_siup').val(data.no_sk_siup || '');
        $('#no_sk_tdp').val(data.no_sk_tdp || '');
        $('#no_sk_pirt').val(data.no_sk_pirt || '');
        $('#no_sk_bpom').val(data.no_sk_bpom || '');
        $('#no_sk_halal').val(data.no_sk_halal || '');
        $('#no_sk_merek').val(data.no_sk_merek || '');
        $('#no_sk_haki').val(data.no_sk_haki || '');

        $('#current_legalitas_id').val(data.id || '');

        setTimeout(updateProgress, 100);
    }

    function clearForm() {
        $('#legalitas-wizard input[type="text"]').val('');
        $('#current_legalitas_id').val('');
        updateProgress();
    }

    function updateProgress() {
        let filledSteps = 0;
        const inputs = [
            '#no_surat_keterangan', '#no_sk_nib', '#no_sk_siup', '#no_sk_tdp',
            '#no_sk_pirt', '#no_sk_bpom', '#no_sk_halal', '#no_sk_merek', '#no_sk_haki'
        ];

        inputs.forEach(function(selector) {
            if ($(selector).val().trim() !== '') {
                filledSteps++;
            }
        });

        const progressPercentage = (filledSteps / totalSteps) * 100;

        $('#progress-fill').css('width', progressPercentage + '%');
        $('#progress-text').text(`${filledSteps}/${totalSteps} Terisi`);
        $('#progress-percentage').text(`${Math.round(progressPercentage)}% Selesai`);

        $('#progress-fill').removeClass();
        $('#progress-fill').addClass('step-progress-fill');

        if (progressPercentage < 30) {
            $('#progress-fill').css('background', 'linear-gradient(90deg, #dc3545, #fd7e14)');
        } else if (progressPercentage < 70) {
            $('#progress-fill').css('background', 'linear-gradient(90deg, #ffc107, #fd7e14)');
        } else {
            $('#progress-fill').css('background', 'linear-gradient(90deg, #007bff, #28a745)');
        }

        updateStepIndicators();
    }

    function updateStepIndicators() {
        $('.step-indicator').each(function(index) {
            const stepNum = index + 1;
            const $indicator = $(this);

            // Reset semua class
            $indicator.removeClass('active completed filled');

            if (stepNum === currentStep) {
                // Tab yang sedang aktif
                $indicator.addClass('active');
            } else {
                // Tab yang tidak aktif - cek apakah sudah terisi
                const inputValue = getInputValueForStep(stepNum);
                if (inputValue && inputValue.trim() !== '') {
                    // Jika sudah terisi, tetap gunakan class 'filled' (warna biru muda)
                    $indicator.addClass('filled');
                }
            }
        });
    }

    function getInputValueForStep(stepNum) {
        const stepId = steps[stepNum - 1];
        const input = $(`#${stepId} input[type="text"]`);
        return input.length > 0 ? input.val() : '';
    }

    function updateNavigationButtons() {
        const $prevBtn = $('#prevBtn');
        const $nextBtn = $('#nextBtn');
        const $saveBtn = $('#saveBtn');

        if (currentStep === 1) {
            $prevBtn.hide();
        } else {
            $prevBtn.show();
        }

        if (currentStep === totalSteps) {
            $nextBtn.hide();
            $saveBtn.show();
        } else {
            $nextBtn.show();
            $saveBtn.hide();
        }
    }

    $('#prevBtn').click(function() {
        changeStep(-1);
    });

    $('#nextBtn').click(function() {
        changeStep(1);
    });

    $('#saveBtn').click(function() {
        saveLegalitasData();
    });

    window.saveLegalitasData = function() {
        const umkmId = $('#legalitas_umkm_id').val();
        const pelakuId = getPelakuIdFromUrl();
        const currentId = $('#current_legalitas_id').val();

        if (!umkmId) {
            showAlert('warning', 'Pilih UMKM terlebih dahulu');
            return;
        }

        const data = {
            umkm_id: umkmId,
            no_surat_keterangan: $('#no_surat_keterangan').val(),
            no_sk_nib: $('#no_sk_nib').val(),
            no_sk_siup: $('#no_sk_siup').val(),
            no_sk_tdp: $('#no_sk_tdp').val(),
            no_sk_pirt: $('#no_sk_pirt').val(),
            no_sk_bpom: $('#no_sk_bpom').val(),
            no_sk_halal: $('#no_sk_halal').val(),
            no_sk_merek: $('#no_sk_merek').val(),
            no_sk_haki: $('#no_sk_haki').val()
        };

        let url = `/dataumkm/legalitas/save/${pelakuId}`;
        let method = 'POST';

        if (currentId) {
            url = `/dataumkm/legalitas/${currentId}`;
            data._method = 'PUT';
        }

        $.ajax({
            url: url,
            type: method,
            data: data,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                if (response.success) {
                    showAlert('success', 'Data legalitas berhasil disimpan.');
                } else {
                    showAlert('danger', response.message || 'Terjadi kesalahan saat menyimpan data.');
                }
            },
            error: function () {
                showAlert('danger', 'Terjadi kesalahan pada server saat menyimpan data.');
            }
        });
    };

    $(document).on('input', '#legalitas-wizard input[type="text"]', function() {
        updateProgress();
        if($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });

    function showAlert(type, message) {
        alert(`${type.toUpperCase()}: ${message}`);
    }

    $(document).on('click', '.step-indicator', function() {
        const targetStep = parseInt($(this).data('step'));

        if (targetStep === currentStep) {
            return; // Sudah di step ini, tidak perlu apa-apa
        }

        if (targetStep < currentStep) {
            // Boleh mundur ke langkah sebelumnya tanpa batasan
            showStep(targetStep);
        } else if (targetStep === currentStep + 1) {
            // Jika ingin pindah ke langkah berikutnya dengan klik tab,
            // pastikan step saat ini sudah valid dan sudah tersimpan

            if (!validateStep(currentStep)) {
                showAlert('warning', `Mohon lengkapi data pada langkah ${currentStep} terlebih dahulu.`);
                return;
            }

            saveStepData(currentStep)
                .done(() => {
                    showStep(targetStep);
                    updateProgress();
                })
                .fail(() => {
                    showAlert('danger', 'Gagal menyimpan data langkah ini. Silakan coba lagi.');
                });
        } else {
            // Cegah lompat lebih dari 1 step tanpa menyimpan step sekarang
            showAlert('warning', 'Silakan simpan data langkah saat ini terlebih dahulu sebelum melanjutkan.');
        }
    });

    $(document).keydown(function(e) {
        if ($('#legalitas-wizard').is(':visible')) {
            if (e.ctrlKey && e.keyCode === 39) {
                e.preventDefault();
                changeStep(1);
            } else if (e.ctrlKey && e.keyCode === 37) {
                e.preventDefault();
                changeStep(-1);
            } else if (e.keyCode === 13) {
                e.preventDefault();
                if (currentStep === totalSteps) {
                    saveLegalitasData();
                } else {
                    changeStep(1);
                }
            }
        }
    });
});