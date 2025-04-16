@extends('layouts.app')
@push('scripts')
    <script type="module">
        $(document).ready(function() {
            $('#dataumkmtable').DataTable();
        });
    </script>
@endpush
@section('content')
    @include('layouts.sidebar')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.navbar')
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manajemen</h1>
                    {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
                </div>

                <div class="container-fluid pt-2 px-2">
                    <div class="bg-white justify-content-between rounded shadow p-4">
                        <div class="justify-content-between d-sm-flex ">
                            <a href="#" class="d-none d-sm-inline-block btn btn-md shadow-sm"
                                style="color: black; background-color: #00FF09"> Export Data All</a>
                            <a href="{{ route('dataumkm.create') }}" class="d-none d-sm-inline-block btn btn-md shadow-sm"
                                style="color: rgb(255, 255, 255); background-color: #1C486F"> Tambah Data</a>
                        </div>
                        <div class="table-responsive p-3 rounded-3">
                            <table class="table table-bordered table-hover table-striped mb-0 bg-white datatable"
                                id="dataumkmtable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>NIK Pemilik</th>
                                        <th>Nama Pemilik</th>
                                        <th>Nama Usaha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datauser as $datauser)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $datauser->nik }}</td>
                                        <td>{{ $datauser->username }}</td>
                                        <td>{{ $datauser->role }}</td>

                                        {{-- <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('dataumkm.show', $dataumkm->pelakuUmkm->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('dataumkm.edit', $dataumkm->pelakuUmkm->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('dataumkm.destroy', $dataumkm->pelakuUmkm->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td> --}}

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>



        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2021</span>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
@endsection
