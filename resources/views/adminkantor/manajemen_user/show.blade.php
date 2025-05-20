@extends('layouts.app')

@section('content')
    @include('layouts.sidebar')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navbar')
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Detail User</h1>
                </div>

                <div class="container-fluid pt-2 px-2">
                    <div class="bg-white rounded shadow p-4">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="font-weight-bold">Informasi {{ ucfirst($user->role) }}</h5>
                                <div>
                                    <a href="{{ route('manajemenuser.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                                    </a>
                                </div>
                            </div>
                            <hr>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-6">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Data Akun</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="username">Username <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                value="{{ old('username', $user->username) }}" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="username">Role <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                value="{{ old('username', $user->role) }}" disabled>
                                        </div>
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
        <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
@endsection
