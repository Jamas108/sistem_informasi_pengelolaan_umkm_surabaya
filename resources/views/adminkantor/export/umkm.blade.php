<table>
    <thead>
        <tr>
            <th>NIK</th>
            <th>Nama Lengkap</th>
            <th>No KK</th>
            <!-- Kolom lainnya -->
        </tr>
    </thead>
    <tbody>
        @foreach($pelakuUmkms as $pelakuUmkm)
            <tr>
                <td>{{ $pelakuUmkm->nik }}</td>
                <td>{{ $pelakuUmkm->nama_lengkap }}</td>
                <td>{{ $pelakuUmkm->no_kk }}</td>
                <!-- Data lainnya -->
            </tr>
        @endforeach
    </tbody>
</table>