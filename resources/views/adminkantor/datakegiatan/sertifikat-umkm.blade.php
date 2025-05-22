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
            background-image: url("{{ public_path('images/sertif2.png') }}");
            background-size: 100% 100%;
            /* FULL SIZE */
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        .nosk{
            font-size: 18px;
            line-height: 1.6;
            text-align: center;
            margin-left: -120px;
            margin-top: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
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
            margin-left: -120px;
            margin-top: 35px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .content .nama {
            font-size: 26px;
            font-weight: bold;
            margin: 20px 0;
            color: #4e73e0;
        }

        .content p {
            max-width: 800px;
            /* Atur lebar maksimum */
            text-align: center;
            margin: 10px auto;
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
        <div class="nosk">No. {{ $intervensi->no_pendaftaran_kegiatan }}</div>
        <div class="content">
            <div class="nama">{{ $intervensi->dataUmkm->nama_usaha }}</div>
            <p>Atas nama <strong>{{ $intervensi->dataUmkm->pelakuUmkm->nama_lengkap }}</strong></p>
            <p>Atas partisipasi aktif dan kontribusi yang telah diberikan dalam mengikuti rangkaian kegiatan
                <strong>{{ $kegiatan->nama_kegiatan }}</strong>, yang diselenggarakan oleh Dinas Koperasi Usaha Kecil
                dan Menengah dan Perdagangan Kota Surabaya sebagai upaya peningkatan kapasitas dan daya saing pelaku
                UMKM.</p>
        </div>


    </div>
</body>

</html>
