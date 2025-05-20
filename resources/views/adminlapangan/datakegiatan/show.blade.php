@extends('layouts.app')

@push('styles')
    <style>
        .card-header {
            background: linear-gradient(to right, #4e73e0, #224abe);
            color: white;
        }

        .detail-poster {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .detail-label {
            font-weight: bold;
            color: #4e73e0;
        }
    </style>
@endpush

@section('content')
    @include('layouts.sidebar')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navbar')
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-eye mr-2 text-primary"></i>Detail Kegiatan
                    </h1>
                    <div class="btn-group" role="group">
                        <a href="{{ route('datakegiatan.index') }}" class="btn btn-secondary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                            <span class="text">Kembali</span>
                        </a>
                        <a href="{{ route('datakegiatan.edit', $kegiatan->id) }}"
                            class="btn btn-warning btn-icon-split ml-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span>
                            <span class="text">Edit</span>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Poster Column -->
                    <div class="col-md-4 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold">Poster Kegiatan</h6>
                            </div>
                            <div class="card-body text-center">
                                @if ($kegiatan->poster)
                                    <img src="{{ Storage::url($kegiatan->poster) }}" alt="Poster Kegiatan"
                                        class="detail-poster img-fluid mb-3">
                                @else
                                    <div class="alert alert-secondary">
                                        Tidak ada poster tersedia
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Detail Column -->
                    <div class="col-md-8 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold">Informasi Detail Kegiatan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <span class="detail-label">Nama Kegiatan</span>
                                        <p>{{ $kegiatan->nama_kegiatan }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="detail-label">Jenis Kegiatan</span>
                                        <p>{{ $kegiatan->jenis_kegiatan ?? 'Tidak Ditentukan' }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <span class="detail-label">Tanggal Mulai</span>
                                        <p>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d F Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="detail-label">Tanggal Selesai</span>
                                        <p>{{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d F Y') }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <span class="detail-label">Jam Mulai</span>
                                        <p>{{ \Carbon\Carbon::parse($kegiatan->jam_mulai)->format('H:i') }} WIB</p>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="detail-label">Jam Selesai</span>
                                        <p>{{ \Carbon\Carbon::parse($kegiatan->jam_selesai)->format('H:i') }} WIB</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <span class="detail-label">Status Kegiatan</span>
                                        @php
                                            $badgeClass = 'secondary';
                                            switch ($kegiatan->status_kegiatan) {
                                                case 'Belum Dimulai':
                                                    $badgeClass = 'warning';
                                                    break;
                                                case 'Sedang Berlangsung':
                                                    $badgeClass = 'primary';
                                                    break;
                                                case 'Selesai':
                                                    $badgeClass = 'success';
                                                    break;
                                            }
                                        @endphp
                                        <p>
                                            <span class="badge badge-{{ $badgeClass }} status-badge">
                                                {{ $kegiatan->status_kegiatan ?? 'Tidak Diketahui' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer" style="background-color: #e0e0e0">
            <div class="container  my-auto">
                <div class="copyright text-center my-auto">
                    <span class="text-black">© {{ date('Y') }} UMKM Management System Dinas Koperasi Usaha Kecil dan Menangah dan Perdagangan Kota Surabaya </span> <br>
                </div>
            </div>
        </footer>
    </div>
@endsection
