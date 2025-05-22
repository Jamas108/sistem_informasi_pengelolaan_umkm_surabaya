<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir - {{ $kegiatan->nama_kegiatan }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12pt;
        }

        .kop-surat {
            display: table;
            width: 100%;
            margin-top: -30px;
            border-bottom: 2px solid #00346d;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            display: table-cell;
            vertical-align: middle;
            width: 20%;
            text-align: left;
        }

        .logo img {
            max-width: 100px;
            height: auto;
        }

        .kop-text {
            display: table-cell;
            vertical-align: middle;
            width: 80%;
            text-align: center;
        }

        .kop-text h1 {
            color: #00346d;
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .kop-text h2 {
            color: #00346d;
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .kop-text p {
            margin: 3px 0;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            display: table-cell;
            vertical-align: middle;
            width: 20%;
            text-align: left;
        }

        .logo img {
            max-width: 100px;
            height: auto;
        }

        h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
        }

        h2 {
            font-size: 14pt;
            margin: 5px 0 20px 0;
        }

        .event-details {
            margin-bottom: 20px;
        }

        .event-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .event-details td {
            padding: 5px;
        }

        .event-details td:first-child {
            width: 150px;
            font-weight: bold;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .attendance-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .attendance-table .ttd-column {
            width: 150px;
        }

        .signature-section {
            margin-top: 30px;
            text-align: right;
            padding-right: 40px;
        }

        .page-break {
            page-break-after: always;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10pt;
        }
    </style>
</head>

<body>

    <div class="kop-surat">
        <div class="logo">
            <img src="{{ public_path('images/dinas.png') }}" alt="Logo Dinas" width="70" height="70">
        </div>
        <div class="kop-text">
            <h1>PEMERINTAH KOTA SURABAYA</h1>
            <h2>DINAS KOPERASI USAHA KECIL DAN MENENGAH DAN PERDAGANGAN</h2>
            <p>Jalan Tunjungan No. 1-3 Lt. 3 Surabaya 60275</p>
            <p>Telepon. (031) 99252288 Faksimile. (031) 99252288</p>
            <p>Laman: surabaya.go.id, Pos-el: dinkopdag@surabaya.go.id</p>
        </div>
    </div>

    <div class="header">
        <h1>DAFTAR HADIR PESERTA KEGIATAN</h1>
        <h2>{{ strtoupper($kegiatan->nama_kegiatan) }}</h2>
    </div>

    <div class="event-details">
        <table>
            <tr>
                <td>Tanggal Kegiatan</td>
                <td>: {{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>: {{ $kegiatan->tanggal_mulai }}</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>: {{ $kegiatan->lokasi_kegiatan }}</td>
            </tr>
            <tr>
                <td>Jumlah Peserta</td>
                <td>: {{ $intervensis->count() }} UMKM</td>
            </tr>
        </table>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Nama Pemilik UMKM</th>
                <th>Nama UMKM</th>
                <th class="ttd-column">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($intervensis as $index => $intervensi)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $intervensi->dataUmkm->pelakuUmkm->nama_lengkap }}</td>
                <td>{{ $intervensi->dataUmkm->nama_usaha }}</td>
                <td></td>
            </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>


<!DOCTYPE html>
<html>

<head>
    <title>Sertifikat Kegiatan - {{ $intervensi->dataUmkm->nama_usaha }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
            font-family: Arial, sans-serif;
        }

        body {
            background-image: url("sertif.png");
            background-size: 100% 100%;
            /* FULL SIZE */
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        .content-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
            box-sizing: border-box;
            padding: 40px 60px;
        }

        .header {
            margin-bottom: 40px;
            padding-bottom: 10px;
            text-align: center;
        }

        .header h1 {
            color: #224abe;
            margin: 0;
            font-size: 28px;
            letter-spacing: 2px;
        }

        .content {
            font-size: 18px;
            line-height: 1.6;
            text-align: center;
            margin-top: 270px;
        }

        .content .nama {
            font-size: 26px;
            font-weight: bold;
            margin: 20px 0;
            color: #4e73e0;
        }

        .signature {
            position: absolute;
            bottom: 40px;
            right: 60px;
            text-align: center;
        }

        .signature img {
            max-height: 80px;
            margin-bottom: 5px;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="content-wrapper">
        <!-- Contoh isi konten -->
        <!-- <div class="header">
            <h1>SERTIFIKAT</h1>
        </div> -->
        <div class="content">
            <div class="nama">UMKM XYZ</div>
            <p>Atas nama <strong>Budi Efendi</strong></p>
            <p>Atas partisipasi aktif dan kontribusi yang telah diberikan dalam mengikuti rangkaian kegiatan <strong>	SURABAYA GREAT EXPO 2024</strong>, yang diselenggarakan oleh Dinas Koperasi Usaha Kecil dan Menengah dan Perdagangan Kota Surabaya sebagai upaya peningkatan kapasitas dan daya saing pelaku UMKM.</p>
        </div>


    </div>
</body>

</html>