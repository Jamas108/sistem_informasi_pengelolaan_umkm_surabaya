<!DOCTYPE html>
<html>

<head>
    <title>Bukti Pendaftaran UMKM - {{ $intervensi->dataUmkm->nama_usaha }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        html,
        body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 190mm;
            margin: 0 auto;
        }

        .kop-surat {
            display: table;
            width: 100%;
            margin-top: 20px;
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

        .content {
            padding: 20px 0;
        }

        .header {
            text-align: center;
            margin-top: -30px;
            margin-bottom: -30px ;
            padding-bottom: 10px;
        }

        .details-section {
            margin-bottom: 5px;
        }

        .details-section h3 {
            padding-bottom: 5px;
        }

        .umkm-details {
            border: 1px solid #000;
            padding: 15px;
        }

        .footer {
            position: absolute;
            bottom: 10mm;
            width: 100%;
            text-align: center;
            font-size: 0.8em;
            color: #666;
            padding-top: 10px;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td {
            text-align: left;
            padding: 8px;
        }

        #titik{
            padding-left: 70px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="kop-surat">
            <div class="logo">
                <img src="{{ public_path('images/dinas.png') }}" alt="Logo Dinas">
            </div>
            <div class="kop-text">
                <h1>PEMERINTAH KOTA SURABAYA</h1>
                <h2>DINAS KOPERASI USAHA KECIL DAN MENENGAH DAN PERDAGANGAN</h2>
                <p>Jalan Tunjungan No. 1-3 Lt. 3 Surabaya 60275</p>
                <p>Telepon. (031) 99252288 Faksimile. (031) 99252288</p>
                <p>Laman: surabaya.go.id, Pos-el: dinkopdag@surabaya.go.id</p>
            </div>
        </div>

        <div class="content">
            <div class="header">
                <h2>Bukti Pendaftaran Kegiatan Intervensi</h2>
            </div>

            <div class="details-section">
                <p>Saya yang bertanda tangan di bawah ini :</p>
                <table>
                    <tr>
                        <td>Nama Lengkap</td>
                        <td id="titik">:</td>
                        <td>Dewi Soeriyawati, ST. MT</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td id="titik">:</td>
                        <td>Kepala Dinas</td>
                    </tr>
                    <tr>
                        <td>Nama Perusahaan</td>
                        <td id="titik">:</td>
                        <td>Dinas Koperasi Usaha Kecil dan Menengah dan Perdagangan Kota Surabaya</td>
                    </tr>

                </table>
            </div>

            <div class="details-section">
                <p>Selaku penanggung jawab dari seluruh kegiatan yang diadakah oleh perusahaan. Dalam surat ini resmi menyatakan bahwa data umkm di bawah ini,</p>
                <table>
                    <tr>
                        <td style="width: 200px;">Nama UMKM</td>
                        <td style="width: 10px;">:</td>
                        <td>{{ $intervensi->dataUmkm->nama_usaha  }}</td>
                    </tr>
                    <tr>
                        <td>Pemilik UMKM</td>
                        <td id="titik2">:</td>
                        <td>{{ $intervensi->dataUmkm->pelakuUmkm->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td>Waktu Pendaftaran</td>
                        <td id="titik2">:</td>
                        <td>{{ $intervensi->created_at->format('d M Y H:i') }}</td>
                    </tr>

                </table>
            </div>

            <div class="details-section">
                <p>Berhak untuk mengikuti rangkaian dari kegiatan intervensi UMKM yang di selenggarakan oleh Dinas Koperasi Usaha Kecil dan Menengah dan Perdagangan Kota Surabaya dengan detail kegiatan sebagai berikut</p>
                <table>
                    <tr>
                        <td style="width: 200px;">Nama Kegiatan</td>
                        <td style="width: 10px;">:</td>
                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kegiatan</td>
                        <td id="titik2">:</td>
                        <td>{{ $kegiatan->jenis_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td>Lokasi Kegiatan</td>
                        <td id="titik2">:</td>
                        <td>{{ $kegiatan->lokasi_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Kegiatan</td>
                        <td id="titik2">:</td>
                        <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }} s/d
                            {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Waktu Kegiatan</td>
                        <td id="titik2">:</td>
                        <td>{{ \Carbon\Carbon::parse($kegiatan->jam_mulai)->format('H:i') }} - {{
                            \Carbon\Carbon::parse($kegiatan->jam_selesai)->format('H:i') }}</td>
                    </tr>

                </table>
            </div>

            <div class="details-section">
                <p>Demikian surat ini kami sampaikan sebagai kelengkapan persyaratan mengikuti kegiatan intervensi UMKM. Dimohon untuk dapat membawa undangan ini saat kegiatan dimulai dan menghadiri kegiatan sesuai jadwal yang sudah di tentukan</p>
            </div>
        </div>
    </div>
</body>

</html>