@extends('layouts.pelakuumkm.app')
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all necessary DOM elements
            const elements = {
                kegiatan: document.getElementById('kegiatan_id'),
                umkm: document.getElementById('umkm_id'),
                dokumentasiInput: document.getElementById('dokumentasi_kegiatan'),
                statusKegiatan: document.getElementById('status_kegiatan'),
                dokumentasiPreview: document.getElementById('dokumentasi-preview'),
                cameraButton: document.getElementById('camera-button'),
                omsetInput: document.getElementById('omset')
            };

            // Validate required elements exist
            function validateElements() {
                const requiredElements = [
                    'dokumentasiPreview',
                    'cameraButton',
                    'dokumentasiInput',
                    'statusKegiatan'
                ];

                const missingElements = requiredElements.filter(key => !elements[key]);

                if (missingElements.length > 0) {
                    console.error("Missing required elements:", missingElements);
                    return false;
                }
                return true;
            }

            // Create toast container if not exists
            function ensureToastContainer() {
                if (!document.getElementById('toast-container')) {
                    const toastContainer = document.createElement('div');
                    toastContainer.id = 'toast-container';
                    toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
                    toastContainer.style.zIndex = '5';
                    document.body.appendChild(toastContainer);
                }
                return document.getElementById('toast-container');
            }

            // Show toast message
            function showToast(message, type = 'success') {
                const toastContainer = ensureToastContainer();
                const toast = document.createElement('div');

                toast.className = `toast align-items-center text-white bg-${type} border-0`;
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');

                toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

                toastContainer.appendChild(toast);

                const bsToast = new bootstrap.Toast(toast, {
                    autohide: true,
                    delay: 3000
                });
                bsToast.show();

                toast.addEventListener('hidden.bs.toast', () => toast.remove());
            }

            // Function to update camera button visibility based on the status of kegiatan
            function updateCameraButtonVisibility() {
                const status = elements.statusKegiatan.value;

                // Hide the camera button if the status is not 'Sedang Berlangsung'
                if (status !== 'Sedang Berlangsung') {
                    if (elements.cameraButton) {
                        elements.cameraButton.style.display = 'none';
                    }
                } else {
                    if (elements.cameraButton) {
                        elements.cameraButton.style.display = 'block';
                    }
                }
            }

            // Call this function to initially update the button visibility based on current status
            updateCameraButtonVisibility();

            // Add event listener to 'status_kegiatan' dropdown if status changes
            if (elements.statusKegiatan) {
                elements.statusKegiatan.addEventListener('change', updateCameraButtonVisibility);
            }

            // Open camera for capturing image
            function openCamera() {
                const {
                    dokumentasiPreview,
                    dokumentasiInput,
                    statusKegiatan,
                    cameraButton
                } = elements;

                // Check camera permission based on status
                if (statusKegiatan.value === 'Pendaftaran') {
                    showToast('Dokumentasi tidak dapat diambil pada status Pendaftaran', 'warning');
                    return;
                }

                // Check browser support
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    showToast('Browser tidak mendukung akses kamera', 'danger');
                    return;
                }

                // Hide camera button
                if (cameraButton) cameraButton.style.display = 'none';

                // Request camera access
                navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'environment'
                        }
                    })
                    .then(handleCameraStream)
                    .catch(handleCameraError);

                // Handle successful camera stream
                function handleCameraStream(stream) {
                    const videoPreview = createVideoPreview(stream);
                    const capturedCanvas = document.createElement('canvas');

                    const cameraContainer = createCameraInterface(
                        videoPreview,
                        capturedCanvas,
                        stream
                    );

                    // Clear previous camera interface
                    const oldCamera = dokumentasiPreview.querySelector('.camera-container');
                    if (oldCamera) oldCamera.remove();

                    // Add camera container
                    dokumentasiPreview.insertBefore(
                        cameraContainer,
                        dokumentasiPreview.firstChild
                    );
                }

                // Create video preview element
                function createVideoPreview(stream) {
                    const videoPreview = document.createElement('video');
                    videoPreview.srcObject = stream;
                    videoPreview.autoplay = true;
                    videoPreview.style.maxWidth = '100%';
                    videoPreview.style.height = 'auto';
                    videoPreview.style.borderRadius = '8px';
                    videoPreview.style.marginBottom = '10px';
                    return videoPreview;
                }

                // Create camera interface
                function createCameraInterface(videoPreview, capturedCanvas, stream) {
                    const captureButton = createButton('Ambil Foto', 'btn-primary');
                    const cancelButton = createButton('Batal', 'btn-secondary');

                    const buttonContainer = document.createElement('div');
                    buttonContainer.classList.add('mt-2');
                    buttonContainer.append(captureButton, cancelButton);

                    const cameraContainer = document.createElement('div');
                    cameraContainer.classList.add('text-center', 'mb-3', 'camera-container');
                    cameraContainer.append(videoPreview, buttonContainer);

                    // Cancel button logic
                    cancelButton.addEventListener('click', () => {
                        stream.getTracks().forEach(track => track.stop());
                        cameraContainer.remove();
                        if (cameraButton) cameraButton.style.display = 'block';
                    });

                    // Capture button logic
                    captureButton.addEventListener('click', () => capturePhoto(
                        videoPreview,
                        capturedCanvas,
                        stream,
                        cameraContainer
                    ));

                    return cameraContainer;
                }

                // Create a button with specified text and class
                function createButton(text, btnClass) {
                    const button = document.createElement('button');
                    button.textContent = text;
                    button.classList.add('btn', btnClass, 'me-2');
                    return button;
                }

                // Capture photo from video stream
                function capturePhoto(videoPreview, capturedCanvas, stream, cameraContainer) {
                    try {
                        // Validate video dimensions
                        if (videoPreview.videoWidth === 0 || videoPreview.videoHeight === 0) {
                            throw new Error('Invalid video dimensions');
                        }

                        // Set canvas dimensions
                        capturedCanvas.width = videoPreview.videoWidth;
                        capturedCanvas.height = videoPreview.videoHeight;

                        // Draw video frame to canvas
                        const context = capturedCanvas.getContext('2d');
                        context.drawImage(
                            videoPreview,
                            0, 0,
                            capturedCanvas.width,
                            capturedCanvas.height
                        );

                        // Stop video stream
                        stream.getTracks().forEach(track => track.stop());
                        cameraContainer.remove();

                        // Convert canvas to blob
                        capturedCanvas.toBlob(processCapture, 'image/jpeg', 0.9);
                    } catch (error) {
                        handleCaptureError(error, stream, cameraContainer);
                    }
                }

                function createImagePreview(imageUrl) {
                const {
                    dokumentasiPreview,
                    cameraButton
                } = elements;

                // Clear previous previews
                dokumentasiPreview.innerHTML = '';

                // Create image container
                const imageContainer = document.createElement('div');
                imageContainer.classList.add('position-relative', 'me-2', 'mt-2');

                // Create image element
                const img = document.createElement('img');
                img.src = imageUrl;
                img.classList.add('img-thumbnail');
                img.style.maxWidth = '150px';
                img.style.maxHeight = '150px';

                // Create replace button
                const replaceButton = document.createElement('button');
                replaceButton.type = 'button';
                replaceButton.classList.add(
                    'btn', 'btn-sm', 'btn-primary',
                    'position-absolute', 'top-0', 'end-0',
                    'm-1', 'btn-replace'
                );
                replaceButton.innerHTML = '<i class="fas fa-sync-alt"></i>';
                replaceButton.title = 'Ganti Foto';
                replaceButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    openCamera();
                });

                // Create temporary label
                const tempLabel = document.createElement('div');
                tempLabel.classList.add(
                    'badge', 'bg-warning',
                    'position-absolute', 'bottom-0', 'start-0', 'm-1'
                );


                // Assemble container
                imageContainer.appendChild(img);
                imageContainer.appendChild(replaceButton);
                imageContainer.appendChild(tempLabel);

                // Add to preview
                dokumentasiPreview.appendChild(imageContainer);

                // Update camera button
                if (cameraButton) {
                    cameraButton.innerHTML = '<i class="fas fa-camera me-2"></i> Ganti Foto Dokumentasi';
                }
            }

                // Process captured image
                function processCapture(blob) {
                    const file = new File([blob], 'captured-image.jpg', {
                        type: 'image/jpeg'
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);

                    // Set file input
                    dokumentasiInput.files = dataTransfer.files;

                    // Create preview
                    createImagePreview(URL.createObjectURL(blob));

                    // Show camera button
                    if (cameraButton) cameraButton.style.display = 'block';

                    // Success message
                    showToast(
                        'Foto berhasil diambil! Klik "Simpan Perubahan" untuk menyimpan.',
                        'success'
                    );
                }

                // Handle capture errors
                function handleCaptureError(error, stream, cameraContainer) {
                    console.error("Error capturing image:", error);
                    showToast("Error capturing image. Please try again.", "danger");

                    // Cleanup
                    stream.getTracks().forEach(track => track.stop());
                    cameraContainer.remove();
                    if (cameraButton) cameraButton.style.display = 'block';
                }

                // Handle camera access error
                function handleCameraError(err) {
                    console.error("Error accessing camera:", err);
                    showToast(
                        'Tidak dapat mengakses kamera. Pastikan Anda memberikan izin.',
                        'danger'
                    );
                    if (cameraButton) cameraButton.style.display = 'block';
                }
            }

            function setupExistingImages() {
                const {
                    dokumentasiPreview,
                    statusKegiatan
                } = elements;
                const existingImages = dokumentasiPreview.querySelectorAll('img');

                // Keep only first image
                if (existingImages.length > 1) {
                    for (let i = 1; i < existingImages.length; i++) {
                        existingImages[i].closest('.position-relative').remove();
                    }
                }

                // Add replace buttons to existing images
                existingImages.forEach(img => {
                    const status = statusKegiatan.value;
                    if (['Sedang Berlangsung', 'Selesai'].includes(status)) {
                        const container = img.closest('.position-relative');

                        // Add replace button if not exists
                        if (!container.querySelector('.btn-replace')) {
                            const replaceButton = document.createElement('button');
                            replaceButton.type = 'button';
                            replaceButton.classList.add(
                                'btn', 'btn-sm', 'btn-primary',
                                'position-absolute', 'top-0', 'end-0',
                                'm-1', 'btn-replace'
                            );
                            replaceButton.innerHTML = '<i class="fas fa-sync-alt"></i>';
                            replaceButton.title = 'Ganti Foto';
                            replaceButton.addEventListener('click', (e) => {
                                e.preventDefault();
                                openCamera();
                            });

                            container.appendChild(replaceButton);
                        }
                    }
                });
            }


            // Initialize page
            function initPage() {
                // Validate required elements
                if (!validateElements()) return;

                // Add camera button event
                if (elements.cameraButton) {
                    elements.cameraButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        openCamera();
                    });
                }

                // Setup existing images
                setupExistingImages();
                setTimeout(setupExistingImages, 500);
            }

            // Start initialization
            initPage();
        });
    </script>
@endpush

@section('content')
    @include('layouts.pelakuumkm.sidebar')
    <main class="main-content">
        <!-- Gradient Header -->
        <div class="text-white py-3 px-4 shadow-sm" id="nav" style="background: linear-gradient(145deg, #1c4970, #2F77B6);">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="fw-bold mb-0">
                            <i class="fas fa-edit me-2"></i>
                            <span class="ml-1">Edit Intervensi</span>
                        </h4>
                        <p class="mb-0 fs-6">Silahkan melakukan update sesuai kebutuhan pada intervensi</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid px-4 py-4">
            <div class="row">
                <div class="col-xl-8 col-lg-10 mx-auto">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Kesalahan Validasi!</strong> Silakan periksa kembali input Anda.
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-white py-4 border-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-soft-primary rounded-circle p-3 me-3">
                                        <i class="fas fa-briefcase text-primary fa-2x"></i>
                                    </div>
                                    <div class="ml-2">
                                        <h4 class="mb-1 fw-bold text-primary">Formulir Edit Intervensi</h4>
                                        <p class="text-muted mb-0">Perbarui informasi detail kegiatan intervensi UMKM</p>
                                    </div>
                                </div>
                                <div class="status-indicator">
                                    @php
                                        $statusClass =
                                            [
                                                'Pendaftaran' => 'bg-warning text-white',
                                                'Persiapan Acara' => 'bg-secondary text-white',
                                                'Sedang Berlangsung' => 'bg-info text-white',
                                                'Selesai' => 'bg-success text-white',
                                            ][$intervensi->kegiatan->status_kegiatan] ?? 'bg-secondary';

                                        $statusIcon =
                                            [
                                                'Pendaftaran' => 'fa-clock',
                                                'Persiapan Acara' => 'fa-calendar',
                                                'Sedang Berlangsung' => 'fa-running',
                                                'Selesai' => 'fa-check-circle',
                                            ][$intervensi->kegiatan->status_kegiatan] ?? 'fa-question-circle';
                                    @endphp
                                    <span id="status-kegiatan-display" class="badge {{ $statusClass }} py-2 px-3 fs-6">
                                        <i class="fas {{ $statusIcon }} me-1"></i>
                                        {{ $intervensi->kegiatan->status_kegiatan }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('pelakukelolaintervensi.update', $intervensi->id) }}" method="POST"
                                class="needs-validation" novalidate enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Hidden status_kegiatan field -->
                                <input type="hidden" id="status_kegiatan" name="status_kegiatan"
                                    value="{{ $intervensi->kegiatan->status_kegiatan }}">

                                <!-- Status Timeline Visualization -->
                                <div class="status-timeline p-3 bg-light rounded mb-4">
                                    <div class="d-flex justify-content-between position-relative">
                                        <!-- Timeline track -->
                                        <div class="timeline-track position-absolute"
                                            style="height: 4px; background-color: #e9ecef; width: 100%; top: 25px; z-index: 1;">
                                        </div>

                                        <!-- Status nodes -->
                                        @php
                                            $statuses = [
                                                'Pendaftaran',
                                                'Persiapan Acara',
                                                'Sedang Berlangsung',
                                                'Selesai',
                                            ];
                                            $currentStatusIndex = array_search(
                                                $intervensi->kegiatan->status_kegiatan,
                                                $statuses,
                                            );
                                        @endphp

                                        @foreach ($statuses as $index => $status)
                                            <div class="status-node text-center" style="z-index: 2; width: 25%;">
                                                <!-- Adjusted width to fit the 4 statuses -->
                                                <div id="status-node-{{ $index }}"
                                                    class="
        {{ $index <= $currentStatusIndex ? 'bg-success' : 'bg-light border' }}
        rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                                    style="width: 50px; height: 50px;">
                                                    @if ($index < $currentStatusIndex)
                                                        <i class="fas fa-check text-white"></i>
                                                    @elseif($index == $currentStatusIndex)
                                                        <i
                                                            class="fas
                {{ $status == 'Pendaftaran'
                    ? 'fa-clock'
                    : ($status == 'Persiapan Acara'
                        ? 'fa-calendar-alt'
                        : ($status == 'Sedang Berlangsung'
                            ? 'fa-running'
                            : 'fa-check-circle')) }}
                {{ $index <= $currentStatusIndex ? 'text-white' : 'text-muted' }}"></i>
                                                    @else
                                                        <i
                                                            class="fas
                {{ $status == 'Pendaftaran'
                    ? 'fa-clock'
                    : ($status == 'Persiapan Acara'
                        ? 'fa-calendar-alt'
                        : ($status == 'Sedang Berlangsung'
                            ? 'fa-running'
                            : 'fa-check-circle')) }}
                text-muted"></i>
                                                    @endif
                                                </div>
                                                <div id="status-text-{{ $index }}"
                                                    class="mt-2 fw-bold {{ $index <= $currentStatusIndex ? 'text-success' : 'text-muted' }}">
                                                    {{ $status }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="fas fa-info-circle fa-lg me-3"></i>
                                    <div>
                                        <strong>Ketentuan formulir berdasarkan status:</strong>
                                        <ul class="mb-0">
                                            <li><strong>Pendaftaran:</strong> Data UMKM dan kegiatan dapat diubah</li>
                                            <li><strong>Persiapan Acara:</strong> Data UMKM dan kegiatan tidak dapat diubah dan tunggu admin untuk membuat bukti pendaftaran</li>
                                            <li><strong>Sedang Berlangsung:</strong> Hanya dokumentasi yang dapat diunggah
                                                melalui kamera</li>
                                            <li><strong>Selesai:</strong> Hanya omset yang dapat diubah dan dokumentasi
                                                dapat diunggah</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="umkm_id" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-store me-2 text-primary"></i>
                                                <span>Pilih UMKM</span>
                                            </label>
                                            <div class="position-relative">
                                                <select class="form-control" id="umkm_id" name="umkm_id" required
                                                    {{ $intervensi->kegiatan->status_kegiatan != 'Pendaftaran' ? 'disabled' : '' }}>
                                                    <option value="">Pilih UMKM yang akan diintervensi</option>
                                                    @foreach ($umkms as $umkm)
                                                        <option value="{{ $umkm->id }}"
                                                            data-sector="{{ $umkm->sektor_usaha }}"
                                                            {{ $intervensi->umkm_id == $umkm->id ? 'selected' : '' }}>
                                                            {{ $umkm->nama_usaha }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Harap pilih UMKM untuk intervensi
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="kegiatan_id" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-clipboard-list me-2 text-primary"></i>
                                                <span>Pilih Kegiatan</span>
                                            </label>
                                            <div class="position-relative">
                                                <select class="form-control" id="kegiatan_id" name="kegiatan_id" required
                                                    {{ $intervensi->kegiatan->status_kegiatan != 'Pendaftaran' ? 'disabled' : '' }}>
                                                    <option value="">Pilih Kegiatan Intervensi</option>
                                                    @foreach ($kegiatans as $kegiatan)
                                                        @php
                                                            $existingInterventions = \App\Models\Intervensi::where(
                                                                'kegiatan_id',
                                                                $kegiatan->id,
                                                            )->count();
                                                            $isQuotaFull =
                                                                $existingInterventions >=
                                                                    $kegiatan->kuota_pendaftaran &&
                                                                $intervensi->kegiatan_id != $kegiatan->id;
                                                            $isAllowed =
                                                                $kegiatan->status_kegiatan === 'Pendaftaran' ||
                                                                $intervensi->kegiatan_id == $kegiatan->id;
                                                        @endphp
                                                        <option value="{{ $kegiatan->id }}"
                                                            data-jenis="{{ $kegiatan->jenis_kegiatan }}"
                                                            data-lokasi="{{ $kegiatan->lokasi_kegiatan }}"
                                                            data-tanggal-mulai="{{ $kegiatan->tanggal_mulai }}"
                                                            data-tanggal-selesai="{{ $kegiatan->tanggal_selesai }}"
                                                            data-jam-mulai="{{ $kegiatan->jam_mulai }}"
                                                            data-jam-selesai="{{ $kegiatan->jam_selesai }}"
                                                            data-status="{{ $kegiatan->status_kegiatan }}"
                                                            {{ $isQuotaFull || !$isAllowed ? 'disabled' : '' }}
                                                            {{ $intervensi->kegiatan_id == $kegiatan->id ? 'selected' : '' }}>
                                                            {{ $kegiatan->nama_kegiatan }}
                                                            @if ($intervensi->kegiatan_id != $kegiatan->id)
                                                                ({{ $existingInterventions }}/{{ $kegiatan->kuota_pendaftaran }}
                                                                Slot)
                                                                @if (!$isAllowed)
                                                                    - Status: {{ $kegiatan->status_kegiatan }}
                                                                @endif
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Harap pilih kegiatan intervensi
                                                </div>
                                            </div>
                                            <div class="form-text mt-1">
                                                <i class="fas fa-info-circle text-info me-1"></i>
                                                Hanya kegiatan dengan status "Pendaftaran" dan kuota tersedia yang dapat
                                                dipilih
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="jenis_kegiatan" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-calendar me-2 text-primary"></i>
                                                <span>Jenis Kegiatan Intervensi</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="jenis_kegiatan"
                                                    name="jenis_kegiatan"
                                                    value="{{ $intervensi->kegiatan->jenis_kegiatan }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="lokasi_kegiatan" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                <span>Lokasi Kegiatan Intervensi</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="lokasi_kegiatan"
                                                    name="lokasi_kegiatan"
                                                    value="{{ $intervensi->kegiatan->lokasi_kegiatan }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_mulai" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                                <span>Tanggal Mulai</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="tanggal_mulai"
                                                    name="tanggal_mulai"
                                                    value="{{ $intervensi->kegiatan->tanggal_mulai }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_selesai" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                                <span>Tanggal Selesai</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="tanggal_selesai"
                                                    name="tanggal_selesai"
                                                    value="{{ $intervensi->kegiatan->tanggal_selesai }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jam_mulai" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-clock me-2 text-primary"></i>
                                                <span>Waktu Mulai</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="jam_mulai"
                                                    name="jam_mulai" value="{{ $intervensi->kegiatan->jam_mulai }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jam_selesai" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-clock me-2 text-primary"></i>
                                                <span>Waktu Selesai</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" id="jam_selesai"
                                                    name="jam_selesai" value="{{ $intervensi->kegiatan->jam_selesai }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="omset" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-chart-line me-2 text-primary"></i>
                                                <span>Omset Setelah Intervensi</span>
                                                @if ($intervensi->kegiatan->status_kegiatan === 'Selesai')
                                                    <span class="badge bg-success">Wajib Diisi</span>
                                                @endif
                                            </label>
                                            <div class="position-relative input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" id="omset" name="omset"
                                                    value="{{ $intervensi->omset }}"
                                                    placeholder="Masukkan omset dalam rupiah"
                                                    {{ $intervensi->kegiatan->status_kegiatan !== 'Selesai' ? 'disabled' : '' }}
                                                    {{ $intervensi->kegiatan->status_kegiatan === 'Selesai' ? 'required' : '' }}>
                                                <div class="invalid-tooltip">
                                                    Harap masukkan omset setelah intervensi
                                                </div>
                                            </div>
                                            <div class="form-text mt-1">
                                                <i class="fas fa-info-circle text-info me-1"></i>
                                                Data omset hanya dapat diisi saat status kegiatan "Selesai"
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="dokumentasi_kegiatan" class="form-label fw-bold text-muted mb-2">
                                                <i class="fas fa-camera me-2 text-primary"></i>
                                                <span>Dokumentasi Kegiatan</span>
                                            </label>

                                            <div class="dokumentasi-container border rounded p-3 bg-light">
                                                <!-- Status-based guidance -->
                                                <div class="alert alert-info p-2 mb-3" role="alert">
                                                    @php
                                                        $status = $intervensi->kegiatan->status_kegiatan;
                                                    @endphp
                                                    @if ($status === 'Pendaftaran')
                                                        Dokumentasi belum dapat diambil. Tunggu hingga kegiatan berlangsung.
                                                    @elseif ($status === 'Sedang Berlangsung')
                                                        Anda dapat mengambil dokumentasi selama kegiatan berlangsung.
                                                    @elseif ($status === 'Selesai')
                                                        Anda dapat menambahkan atau mengganti dokumentasi kegiatan.
                                                    @endif
                                                </div>

                                                <!-- Hidden file input for camera capture -->
                                                <input type="file" class="form-control d-none"
                                                    id="dokumentasi_kegiatan" name="dokumentasi_kegiatan[]"
                                                    accept="image/*">

                                                <!-- Camera button -->
                                                <div class="text-center mb-3">
                                                    <button type="button" id="camera-button"
                                                        class="btn btn-primary btn-lg"
                                                        {{ $status === 'Pendaftaran' ? 'disabled' : '' }}>
                                                        <i class="fas fa-camera me-2"></i>
                                                        @php
                                                            // Safely determine if dokumentasi is empty
                                                            $hasImages = false;

                                                            if (!empty($intervensi->dokumentasi_kegiatan)) {
                                                                $dokValue = $intervensi->dokumentasi_kegiatan;

                                                                // If it's already an array, use it directly
    if (is_array($dokValue)) {
        $hasImages = !empty($dokValue);
    }
    // If it's a string that might be JSON, try to decode
                                                                elseif (is_string($dokValue)) {
                                                                    try {
                                                                        $decodedValue = json_decode($dokValue, true);
                                                                        // Use decoded value only if successful
                                                                        if (json_last_error() === JSON_ERROR_NONE) {
                                                                            $hasImages = !empty($decodedValue);
                                                                        }
                                                                    } catch (\Exception $e) {
                                                                        // If decoding fails, check if the string itself has content
                                                                        $hasImages = !empty($dokValue);
                                                                    }
                                                                }
                                                            }
                                                        @endphp

                                                        {{ $hasImages ? 'Ganti Foto Dokumentasi' : 'Ambil Foto Dokumentasi' }}
                                                    </button>
                                                </div>

                                                <!-- Dokumentasi preview area -->
                                                <div id="dokumentasi-preview" class="dokumentasi-preview mt-3">
                                                    @if ($intervensi->dokumentasi_kegiatan)
                                                        @php
                                                            // Check if dokumentasi_kegiatan is already an array or a JSON string
                                                            $dokumentasis = is_array($intervensi->dokumentasi_kegiatan)
                                                                ? $intervensi->dokumentasi_kegiatan
                                                                : json_decode(
                                                                        $intervensi->dokumentasi_kegiatan,
                                                                        true,
                                                                    ) ?? [];
                                                        @endphp
                                                        @forelse ($dokumentasis as $dok)
                                                            <div class="dokumentasi-item position-relative">
                                                                @if (pathinfo($dok, PATHINFO_EXTENSION) == 'pdf')
                                                                    <div class="pdf-preview">
                                                                        <i class="fas fa-file-pdf text-danger fa-3x"></i>
                                                                        <div class="mt-2 text-muted">PDF Dokumen</div>
                                                                    </div>
                                                                @else
                                                                    <img src="{{ asset('storage/' . $dok) }}"
                                                                        class="dokumentasi-image img-fluid rounded"
                                                                        alt="Dokumentasi Kegiatan">
                                                                @endif
                                                            </div>
                                                        @empty
                                                            <div class="text-center text-muted py-3">
                                                                <i class="fas fa-camera-retro fa-3x mb-3"></i>
                                                                <p>Belum ada dokumentasi kegiatan</p>
                                                            </div>
                                                        @endforelse
                                                    @else
                                                        <div class="text-center text-muted py-3">
                                                            <i class="fas fa-camera-retro fa-3x mb-3"></i>
                                                            <p>Belum ada dokumentasi kegiatan</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Guidance text -->
                                            <div class="form-text mt-2">
                                                <i class="fas fa-info-circle text-info me-1"></i>
                                                Dokumentasi dapat diambil menggunakan kamera saat status "Sedang
                                                Berlangsung" atau "Selesai"
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <a href="{{ route('pelakukelolaintervensi.index') }}"
                                        class="btn btn-outline-secondary btn-md">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        <span>Kembali</span>
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-md">
                                        <i class="fas fa-save me-2"></i>
                                        <span>Simpan Perubahan</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .main-content {
            margin-left: 220px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        /* Custom background for soft colors */
        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }

        /* Enhanced select styling */
        .form-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Tooltip positioning for validation */
        .was-validated .form-select:invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
        }

        .was-validated .form-select:invalid~.invalid-tooltip,
        .form-select.is-invalid~.invalid-tooltip {
            display: block;
        }

        /* Status badge styling */
        .status-indicator .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        /* Status timeline styling */
        .status-timeline {
            padding: 1.5rem 1rem;
            margin-bottom: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
        }

        .timeline-track {
            height: 4px;
            background-color: #dee2e6;
        }

        .status-node .rounded-circle {
            transition: all 0.3s ease;
            box-shadow: 0 0 0 4px #ffffff;
            width: 50px;
            height: 50px;
        }

        /* Form field disabled state styling */
        .form-control:disabled,
        .form-control[readonly],
        .form-select:disabled {
            background-color: #f8f9fa;
            opacity: 0.8;
            cursor: not-allowed;
        }

        /* PDF preview styling */
        .pdf-preview {
            width: 150px;
            height: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        /* Alert styling */
        .alert-info {
            background-color: #e8f4fd;
            border-color: #b8e0ff;
        }

        /* Select option disabled styling */
        select option:disabled {
            color: #6c757d;
            font-style: italic;
        }

        /* Camera button styling */
        #camera-button {
            transition: all 0.3s ease;
        }

        #camera-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Replace button styling */
        .btn-replace {
            background-color: rgba(13, 110, 253, 0.8);
            z-index: 10;
            transition: all 0.2s ease;
        }

        .btn-replace:hover {
            background-color: rgba(13, 110, 253, 1);
            transform: scale(1.1);
        }

        /* Image hover effect */
        .position-relative:hover img {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection
