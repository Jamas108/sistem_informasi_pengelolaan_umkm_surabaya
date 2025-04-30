@extends('layouts.pelakuumkm.app')
@section('content')
    @include('layouts.pelakuumkm.sidebar')
    <main class="main-content">
        <!-- Header Section -->
        <div class="text-white py-3 px-4 shadow-sm" id="nav" style="background-color: #5281ab">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        <span>Tambah UMKM Baru</span>
                    </h4>
                    <p class="mb-0 fs-6">Tambahkan usaha baru untuk mengembangkan bisnis Anda</p>
                </div>
                <div>
                    <a href="{{ route('pelakukelolaumkm.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4 py-4">
            <!-- Progress Indicator -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="p-4 pb-2">
                        <h5 class="fw-semibold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Informasi Tambah UMKM</h5>
                        <p class="text-muted mb-0">Lengkapi formulir di bawah ini dengan data usaha yang valid. Data Anda akan diverifikasi oleh admin sebelum disetujui.</p>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar" id="formProgress" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer">
                @if (session('status'))
                    <div class="alert alert-{{ session('status_type') }} alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <!-- Form Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4 border-0">
                    <h5 class="card-title mb-0 fw-bold text-primary">
                        <i class="fas fa-store-alt me-2"></i>Form Tambah UMKM
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('pelakukelolaumkm.store') }}" method="POST" id="createUmkmForm">
                        @csrf

                        <!-- UMKM Container -->
                        <div id="umkm-entries-container">
                            <!-- Template UMKM Entry -->
                            <div class="umkm-form-entry rounded p-3 mb-4" id="umkm-entry-1" data-umkm-id="1">
                                <div class="border-bottom pb-3 mb-4">
                                    <h5 class="fw-semibold text-primary umkm-number">
                                        <i class="fas fa-building me-2"></i>Data UMKM #1
                                    </h5>
                                </div>

                                <div class="row g-3">
                                    <!-- Kolom Kiri -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nama_usaha_1" class="form-label fw-medium">Nama Usaha <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-store"></i></span>
                                                <input type="text" class="form-control border-start-0 required-field" id="nama_usaha_1" name="umkm[1][nama_usaha]" placeholder="Masukkan nama usaha" required>
                                            </div>
                                            <div class="invalid-feedback">Nama usaha tidak boleh kosong</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="alamat_1" class="form-label fw-medium">Alamat Usaha <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-marker-alt"></i></span>
                                                <input type="text" class="form-control border-start-0 required-field" id="alamat_1" name="umkm[1][alamat]" placeholder="Masukkan alamat lengkap" required>
                                            </div>
                                            <div class="invalid-feedback">Alamat usaha tidak boleh kosong</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="jenis_produk_1" class="form-label fw-medium">Jenis Produk</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-tag"></i></span>
                                                <input type="text" class="form-control border-start-0" id="jenis_produk_1" name="umkm[1][jenis_produk]" placeholder="Masukkan jenis produk">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="tipe_produk_1" class="form-label fw-medium">Tipe Produk</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-boxes"></i></span>
                                                <input type="text" class="form-control border-start-0" id="tipe_produk_1" name="umkm[1][tipe_produk]" placeholder="Masukkan tipe produk">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kolom Kanan -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="pengelolaan_usaha_1" class="form-label fw-medium">Pengelolaan Usaha <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-users-cog"></i></span>
                                                <select class="form-control border-start-0 required-field" id="pengelolaan_usaha_1" name="umkm[1][pengelolaan_usaha]" required>
                                                    <option value="">-- Pilih Pengelolaan --</option>
                                                    <option value="PERSEORANGAN / MANDIRI">PERSEORANGAN / MANDIRI</option>
                                                    <option value="KELOMPOK / SUBKON / KERJASAMA">KELOMPOK / SUBKON / KERJASAMA</option>
                                                </select>
                                            </div>
                                            <div class="invalid-feedback">Pengelolaan usaha harus dipilih</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="sektor_usaha_1" class="form-label fw-medium">Sektor Usaha <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-industry"></i></span>
                                                <select class="form-control border-start-0 required-field" id="sektor_usaha_1" name="umkm[1][sektor_usaha]" required>
                                                    <option value="">-- Pilih Sektor --</option>
                                                    <option value="Makanan & Minuman">Makanan & Minuman</option>
                                                    <option value="Fashion">Fashion</option>
                                                    <option value="Kerajinan">Kerajinan</option>
                                                    <option value="Jasa">Jasa</option>
                                                    <option value="INDUSTRI">Industri</option>
                                                    <option value="DAGANG">Dagang</option>
                                                </select>
                                            </div>
                                            <div class="invalid-feedback">Sektor usaha harus dipilih</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="klasifikasi_kinerja_usaha_1" class="form-label fw-medium">Klasifikasi Kinerja <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-chart-line"></i></span>
                                                <select class="form-control border-start-0 required-field" id="klasifikasi_kinerja_usaha_1" name="umkm[1][klasifikasi_kinerja_usaha]" required>
                                                    <option value="">-- Pilih Klasifikasi --</option>
                                                    <option value="PEMULA">PEMULA</option>
                                                    <option value="MADYA">MADYA</option>
                                                    <option value="MANDIRI">MANDIRI</option>
                                                </select>
                                            </div>
                                            <div class="invalid-feedback">Klasifikasi kinerja harus dipilih</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="jumlah_tenaga_kerja_1" class="form-label fw-medium">Jumlah Tenaga Kerja <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user-friends"></i></span>
                                                <input type="number" class="form-control border-start-0 required-field" id="jumlah_tenaga_kerja_1" name="umkm[1][jumlah_tenaga_kerja]" placeholder="Masukkan jumlah tenaga kerja" min="0" required>
                                            </div>
                                            <div class="invalid-feedback">Jumlah tenaga kerja harus diisi</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end mt-2">
                                    <button type="button" class="btn btn-outline-danger remove-umkm" data-umkm-id="1">
                                        <i class="fas fa-trash-alt me-1"></i> Hapus Data UMKM
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Tambah UMKM -->
                        <div class="text-center my-4">
                            <button type="button" class="btn btn-outline-primary rounded-pill px-4" id="add-umkm-btn">
                                <i class="fas fa-plus-circle me-2"></i> Tambah UMKM Lain
                            </button>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('pelakukelolaumkm.index') }}" class="btn btn-light px-4 me-md-2">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                <i class="fas fa-save me-1"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Floating Action Button (Mobile) -->
        <button class="btn btn-primary btn-tambah-umkm shadow" id="mobile-add-umkm">
            <i class="fas fa-plus"></i>
        </button>
    </main>

    <style>
        /* Utilities */
        .main-content {
            margin-left: 220px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
            }
        }

        body.sidebar-toggled .main-content {
            margin-left: 80px;
        }

        @media (max-width: 992px) {
            body.sidebar-toggled .main-content {
                margin-left: 0;
            }
        }

        /* Form Styling */
        .umkm-form-entry {
            background-color: #f8f9fc;
            border: 1px solid #e3e6f0;
            border-left: 4px solid #5281ab;
            transition: all 0.3s ease;
        }

        .umkm-form-entry:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-left: 4px solid #2c5282;
        }

        .input-group-text {
            color: #5a5c69;
        }

        .form-control:focus, .form-select:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.1);
        }

        /* Floating action button */
        .btn-tambah-umkm {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            z-index: 1040;
            display: none;
        }

        @media (max-width: 768px) {
            .btn-tambah-umkm {
                display: flex;
            }
        }

        /* Validation styling */
        .was-validated .form-control:invalid,
        .form-control.is-invalid {
            border-color: #e74a3b;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23e74a3b'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23e74a3b' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .was-validated .form-select:invalid,
        .form-select.is-invalid {
            border-color: #e74a3b;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23e74a3b'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23e74a3b' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center, center right 2.25rem;
            background-size: 16px 12px, calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize counter for UMKM forms
            let umkmCounter = 1;

            // Progress bar update
            function updateProgress() {
                const form = document.getElementById('createUmkmForm');
                const requiredFields = form.querySelectorAll('.required-field');
                const totalRequired = requiredFields.length;
                let filledRequired = 0;

                requiredFields.forEach(field => {
                    if (field.value.trim() !== '') {
                        filledRequired++;
                    }
                });

                const progressPercentage = Math.round((filledRequired / totalRequired) * 100);
                const progressBar = document.getElementById('formProgress');
                progressBar.style.width = progressPercentage + '%';
                progressBar.setAttribute('aria-valuenow', progressPercentage);
            }

            // Format currency (Rupiah)
            function formatRupiah() {
                const omzetInputs = document.querySelectorAll('.omzet-input');

                omzetInputs.forEach(input => {
                    input.addEventListener('input', function(e) {
                        // Remove non-numeric characters
                        let value = this.value.replace(/\D/g, '');

                        // Format with thousands separator
                        if (value) {
                            value = new Intl.NumberFormat('id-ID').format(value);
                        }

                        this.value = value;
                    });
                });
            }

            // Initialize format for existing inputs
            formatRupiah();

            // Update UMKM numbers
            function updateUmkmNumbers() {
                document.querySelectorAll('.umkm-form-entry').forEach((entry, index) => {
                    entry.querySelector('.umkm-number').innerHTML = '<i class="fas fa-building me-2"></i>Data UMKM #' + (index + 1);
                });
            }

            // Add a new UMKM form
            function addUmkmForm() {
                umkmCounter++;

                // Clone the first entry
                const firstEntry = document.querySelector('.umkm-form-entry');
                const newEntry = firstEntry.cloneNode(true);

                // Update IDs and names
                newEntry.id = 'umkm-entry-' + umkmCounter;
                newEntry.setAttribute('data-umkm-id', umkmCounter);

                // Update all input IDs, names, and clear values
                const inputs = newEntry.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    const idParts = input.id.split('_');
                    const baseName = idParts.slice(0, -1).join('_');
                    input.id = baseName + '_' + umkmCounter;

                    const nameParts = input.name.match(/umkm\[(\d+)\]\[(.*?)\](?:\[(.*?)\])?/);
                    if (nameParts) {
                        if (nameParts[3]) {
                            input.name = `umkm[${umkmCounter}][${nameParts[2]}][${nameParts[3]}]`;
                        } else {
                            input.name = `umkm[${umkmCounter}][${nameParts[2]}]`;
                        }
                    }

                    // Clear values
                    if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    } else {
                        input.value = '';
                    }
                });

                // Update the remove button
                const removeButton = newEntry.querySelector('.remove-umkm');
                removeButton.setAttribute('data-umkm-id', umkmCounter);

                // Append the new entry
                document.getElementById('umkm-entries-container').appendChild(newEntry);

                // Update UMKM numbers
                updateUmkmNumbers();

                // Re-initialize currency formatting for new inputs
                formatRupiah();

                // Scroll to the new entry
                newEntry.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            // Add UMKM form when button is clicked
            document.getElementById('add-umkm-btn').addEventListener('click', addUmkmForm);
            document.getElementById('mobile-add-umkm').addEventListener('click', addUmkmForm);

            // Remove UMKM form
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-umkm') ||
                    e.target.parentElement && e.target.parentElement.classList.contains('remove-umkm')) {

                    const button = e.target.classList.contains('remove-umkm') ? e.target : e.target.parentElement;
                    const umkmId = button.getAttribute('data-umkm-id');
                    const umkmEntry = document.getElementById('umkm-entry-' + umkmId);

                    // Only remove if there's more than one entry
                    if (document.querySelectorAll('.umkm-form-entry').length > 1) {
                        umkmEntry.remove();

                        // Update UMKM numbers
                        updateUmkmNumbers();

                        // Update progress
                        updateProgress();
                    } else {
                        // Show alert if trying to remove the last entry
                        const alertHTML = `
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i> Minimal harus ada satu data UMKM
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                        document.getElementById('alertContainer').innerHTML = alertHTML;
                    }
                }
            });

            // Form validation
            document.getElementById('createUmkmForm').addEventListener('submit', function(e) {
                e.preventDefault();

                let isValid = true;
                const requiredFields = this.querySelectorAll('.required-field');

                requiredFields.forEach(field => {
                    if (field.value.trim() === '') {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (isValid) {
                    this.submit();
                } else {
                    // Show alert
                    const alertHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> Harap lengkapi semua field yang wajib diisi
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    document.getElementById('alertContainer').innerHTML = alertHTML;

                    // Scroll to the first invalid field
                    const firstInvalid = this.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });

            // Update progress when input changes
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('required-field')) {
                    updateProgress();
                }
            });

            // Initial progress update
            updateProgress();
        });
    </script>
@endsection