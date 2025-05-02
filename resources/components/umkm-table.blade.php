@if(Auth::user()->role == 'pelakuumkm')
    <!-- UMKM Legalitas Status -->
    <div class="card-body">
        <h4 class="small font-weight-bold">NIB <span class="float-right">Sudah</span></h4>
        <div class="progress mb-4">
            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <h4 class="small font-weight-bold">PIRT <span class="float-right">Sudah</span></h4>
        <div class="progress mb-4">
            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <h4 class="small font-weight-bold">Halal <span class="float-right">Proses</span></h4>
        <div class="progress mb-4">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <h4 class="small font-weight-bold">BPOM <span class="float-right">Belum</span></h4>
        <div class="progress mb-4">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <h4 class="small font-weight-bold">Merek <span class="float-right">Belum</span></h4>
        <div class="progress mb-4">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
@else
    <!-- Admin View - UMKM Table -->
    <div class="table-responsive">
        <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama UMKM</th>
                    <th>Pemilik</th>
                    <th>Jenis Produk</th>
                    <th>Status</th>
                    <th>Progres</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= 5; $i++)
                    <tr>
                        <td>UMKM Sample {{ $i }}</td>
                        <td>Pemilik {{ $i }}</td>
                        <td>{{ ['Makanan', 'Minuman', 'Kerajinan', 'Jasa', 'Fashion'][$i-1] }}</td>
                        <td>
                            <span class="badge badge-{{ ['success', 'success', 'warning', 'warning', 'danger'][$i-1] }}">
                                {{ ['Aktif', 'Aktif', 'Proses', 'Proses', 'Nonaktif'][$i-1] }}
                            </span>
                        </td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar bg-{{ ['success', 'info', 'warning', 'info', 'danger'][$i-1] }}"
                                    role="progressbar"
                                    style="width: {{ [100, 80, 60, 75, 20][$i-1] }}%"
                                    aria-valuenow="{{ [100, 80, 60, 75, 20][$i-1] }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{ [100, 80, 60, 75, 20][$i-1] }}%
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@endif