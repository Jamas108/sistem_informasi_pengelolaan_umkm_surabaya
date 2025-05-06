<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Umkm;
use App\Models\PelakuUmkm;
use App\Models\Omset;
use App\Models\Kegiatan;
use App\Models\Intervensi;
use App\Models\Legalitas;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard berdasarkan role pengguna
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'adminkantor':
                return $this->adminKantorDashboard();
            case 'adminlapangan':
                return $this->adminLapanganDashboard();
            case 'pelakuumkm':
                return $this->pelakuUmkmDashboard();
            default:
                return abort(403, 'Akses ditolak');
        }
    }

    /**
     * Dashboard untuk Admin Kantor
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function adminKantorDashboard()
    {
        // Data untuk card statistics
        $totalUmkm = Umkm::count();
        $totalKegiatan = Kegiatan::count();
        $rataRataOmset = Intervensi::avg('omset') ?? 0;

        // Hitung jumlah UMKM dengan legalitas lengkap (memiliki minimal 5 dokumen legalitas)
        $umkmLegalitasLengkap = DB::table('legalitas')
            ->select('umkm_id')
            ->whereNotNull('no_sk_nib')
            ->whereNotNull('no_sk_siup')
            ->whereNotNull('no_sk_pirt')
            ->whereNotNull('no_sk_halal')
            ->whereNotNull('no_sk_merek')
            ->count();

        // Data untuk persentase legalitas
        $persentaseLegalitas = [
            'nib' => round((DB::table('legalitas')->whereNotNull('no_sk_nib')->count() / max(1, $totalUmkm)) * 100),
            'siup' => round((DB::table('legalitas')->whereNotNull('no_sk_siup')->count() / max(1, $totalUmkm)) * 100),
            'pirt' => round((DB::table('legalitas')->whereNotNull('no_sk_pirt')->count() / max(1, $totalUmkm)) * 100),
            'halal' => round((DB::table('legalitas')->whereNotNull('no_sk_halal')->count() / max(1, $totalUmkm)) * 100),
            'merek' => round((DB::table('legalitas')->whereNotNull('no_sk_merek')->count() / max(1, $totalUmkm)) * 100),
        ];

        // Data untuk chart pertumbuhan UMKM
        $chartUmkmGrowth = $this->getUmkmGrowthData();

        // Data untuk chart distribusi sektor UMKM
        $distribusiSektor = $this->getSektorDistributionData();

        // Data untuk chart distribusi tenaga kerja
        $distribusiTenagaKerja = $this->getTenagaKerjaData();

        // Daftar UMKM terbaru
        $umkmTerbaru = Umkm::with('pelakuUmkm')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Daftar kegiatan mendatang
        $kegiatanMendatang = Kegiatan::where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        // Warna untuk chart
        $sektorColors = [
            'rgba(78, 115, 223, 0.7)',
            'rgba(28, 200, 138, 0.7)',
            'rgba(54, 185, 204, 0.7)',
            'rgba(246, 194, 62, 0.7)',
            'rgba(231, 74, 59, 0.7)',
            'rgba(133, 135, 150, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(255, 159, 64, 0.7)',
        ];

        return view('adminkantor.dashboard', compact(
            'totalUmkm',
            'totalKegiatan',
            'rataRataOmset',
            'umkmLegalitasLengkap',
            'persentaseLegalitas',
            'chartUmkmGrowth',
            'distribusiSektor',
            'distribusiTenagaKerja',
            'umkmTerbaru',
            'kegiatanMendatang',
            'sektorColors'
        ));
    }

    /**
     * Dashboard untuk Admin Lapangan
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function adminLapanganDashboard()
    {
        // Data untuk card statistics
        $totalUmkmDimonitor = Umkm::count(); // Idealnya filter berdasarkan admin yang bertanggung jawab
        $totalKegiatanTerjadwal = Kegiatan::where('tanggal_mulai', '>=', now())->count();

        // Simulasi progres intervensi (persentase UMKM yang sudah diintervensi)
        $totalUmkm = max(1, Umkm::count());
        $totalIntervensi = Intervensi::distinct('umkm_id')->count('umkm_id');
        $progresIntervensi = round(($totalIntervensi / $totalUmkm) * 100);

        // Jumlah dokumentasi kegiatan
        $totalDokumentasi = Intervensi::whereNotNull('dokumentasi_kegiatan')->count();

        // Data untuk chart intervensi bulanan
        $intervensiMonthlyData = $this->getIntervensiMonthlyData();

        // Data untuk chart distribusi jenis kegiatan
        $distribusiJenisKegiatan = $this->getJenisKegiatanData();

        // Data untuk chart perbandingan omset
        $intervensiOmsetData = $this->getIntervensiOmsetData();

        // Jadwal kegiatan mendatang
        $jadwalKegiatan = Kegiatan::with('intervensi')
            ->where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        // UMKM yang dimonitor
        $umkmDimonitor = Umkm::with('pelakuUmkm')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Simulasi aktivitas terakhir
        $aktivitasTerakhir = $this->getAktivitasTerakhir();

        // Warna untuk chart
        $kegiatanColors = [
            'rgba(28, 200, 138, 0.7)',
            'rgba(78, 115, 223, 0.7)',
            'rgba(54, 185, 204, 0.7)',
            'rgba(246, 194, 62, 0.7)',
            'rgba(231, 74, 59, 0.7)',
        ];

        return view('adminlapangan.dashboard', compact(
            'totalUmkmDimonitor',
            'totalKegiatanTerjadwal',
            'progresIntervensi',
            'totalDokumentasi',
            'intervensiMonthlyData',
            'distribusiJenisKegiatan',
            'intervensiOmsetData',
            'jadwalKegiatan',
            'umkmDimonitor',
            'aktivitasTerakhir',
            'kegiatanColors'
        ));
    }

    /**
     * Dashboard untuk Pelaku UMKM
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function pelakuUmkmDashboard()
    {
        $user = Auth::user();
        $pelakuUmkm = PelakuUmkm::where('users_id', $user->id)->first();

        if (!$pelakuUmkm) {
            return view('pelakuumkm.dashboard', [
                'error' => 'Data pelaku UMKM tidak ditemukan'
            ]);
        }

        // Data UMKM milik pengguna
        $dataUmkm = Umkm::where('pelaku_umkm_id', $pelakuUmkm->id)->get();

        // Jika tidak ada UMKM, tampilkan halaman kosong
        if ($dataUmkm->isEmpty()) {
            return view('pelakuumkm.dashboard', [
                'dataUmkm' => [],
                'pelakuUmkm' => $pelakuUmkm,
                'omsetBulanan' => 0,
                'omsetTahunan' => 0,
                'kegiatanDiikuti' => 0,
                'jumlahLegalitas' => 0,
                'chartOmset' => [
                    'labels' => [],
                    'values' => []
                ],
                'kegiatanMendatang' => []
            ]);
        }

        // ID UMKM pertama untuk data detail
        $umkmId = $dataUmkm->first()->id;

        // Data omset
        $omsetBulanan = Intervensi::where('umkm_id', $umkmId)
            ->where('omset')
            ->first()
            ->total_omset ?? 0;

        $omsetTahunan = Intervensi::where('umkm_id', $umkmId)
            ->where('omset')
            ->first()
            ->total_omset ?? 0;

        // Jumlah kegiatan yang diikuti
        $kegiatanDiikuti = Intervensi::where('umkm_id', $umkmId)->count();

        // Status legalitas
        $legalitas = Legalitas::where('umkm_id', $umkmId)->first();
        $jumlahLegalitas = 0;

        if ($legalitas) {
            $fields = [
                'no_sk_nib',
                'no_sk_siup',
                'no_sk_tdp',
                'no_sk_pirt',
                'no_sk_bpom',
                'no_sk_halal',
                'no_sk_merek',
                'no_sk_haki'
            ];

            foreach ($fields as $field) {
                if (!empty($legalitas->$field)) {
                    $jumlahLegalitas++;
                }
            }
        }

        $totalOmset = Intervensi::getTotalOmsetByUmkm($umkmId);

        // Data untuk chart omset
        $chartOmset = $this->getOmsetChartData($umkmId);

        // Kegiatan mendatang
        $kegiatanMendatang = Kegiatan::where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(3)
            ->get();

        return view('pelakuumkm.dashboard', compact(
            'dataUmkm',
            'pelakuUmkm',
            'omsetBulanan',
            'omsetTahunan',
            'kegiatanDiikuti',
            'jumlahLegalitas',
            'legalitas',
            'totalOmset',
            'chartOmset',
            'kegiatanMendatang'
        ));
    }

    /**
     * Mendapatkan data pertumbuhan UMKM untuk chart
     *
     * @return array
     */
    private function getUmkmGrowthData()
    {
        $data = [
            'labels' => [],
            'values' => []
        ];

        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');
            $data['labels'][] = $month;

            // Hitung jumlah UMKM sampai bulan tersebut
            $count = Umkm::where('created_at', '<=', $date->endOfMonth())->count();
            $data['values'][] = $count;
        }

        return $data;
    }

    /**
     * Mendapatkan distribusi sektor UMKM untuk chart
     *
     * @return array
     */
    private function getSektorDistributionData()
    {
        $data = [];

        $sektorCounts = DB::table('umkm')
            ->select('sektor_usaha', DB::raw('count(*) as total'))
            ->groupBy('sektor_usaha')
            ->get();

        foreach ($sektorCounts as $sektor) {
            if (!empty($sektor->sektor_usaha)) {
                $data[] = [
                    'name' => $sektor->sektor_usaha,
                    'value' => $sektor->total
                ];
            }
        }

        return $data;
    }

    /**
     * Mendapatkan distribusi tenaga kerja UMKM untuk chart
     *
     * @return array
     */
    private function getTenagaKerjaData()
    {
        // Kategori jumlah tenaga kerja
        $categories = [
            '1-4' => [1, 4],
            '5-19' => [5, 19],
            '20-99' => [20, 99],
            '100+' => [100, PHP_INT_MAX]
        ];

        $data = [
            'labels' => array_keys($categories),
            'values' => []
        ];

        foreach ($categories as $category => $range) {
            $count = Umkm::where('jumlah_tenaga_kerja', '>=', $range[0])
                ->where('jumlah_tenaga_kerja', '<=', $range[1])
                ->count();

            $data['values'][] = $count;
        }

        return $data;
    }

    /**
     * Mendapatkan data intervensi bulanan untuk chart
     *
     * @return array
     */
    private function getIntervensiMonthlyData()
    {
        $data = [
            'labels' => [],
            'values' => []
        ];

        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');
            $data['labels'][] = $month;

            // Hitung jumlah intervensi pada bulan tersebut
            $count = Intervensi::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $data['values'][] = $count;
        }

        return $data;
    }

    /**
     * Mendapatkan distribusi jenis kegiatan untuk chart
     *
     * @return array
     */
    private function getJenisKegiatanData()
    {
        $data = [];

        $jenisCounts = DB::table('kegiatan')
            ->select('jenis_kegiatan', DB::raw('count(*) as total'))
            ->groupBy('jenis_kegiatan')
            ->get();

        foreach ($jenisCounts as $jenis) {
            if (!empty($jenis->jenis_kegiatan)) {
                $data[] = [
                    'name' => $jenis->jenis_kegiatan,
                    'value' => $jenis->total
                ];
            }
        }

        return $data;
    }

    /**
     * Mendapatkan data perbandingan omset sebelum dan sesudah intervensi
     *
     * @return array
     */
    private function getIntervensiOmsetData()
    {
        // Simulasi data untuk chart perbandingan omset
        // Dalam implementasi nyata, data ini diambil dari database

        return [
            'labels' => ['UMKM A', 'UMKM B', 'UMKM C', 'UMKM D', 'UMKM E'],
            'before' => [5000000, 8000000, 3000000, 6500000, 4200000],
            'after' => [7500000, 12000000, 5500000, 9800000, 6300000]
        ];
    }

    /**
     * Simulasi data aktivitas terakhir
     *
     * @return array
     */
    private function getAktivitasTerakhir()
    {
        // Simulasi data aktivitas
        // Dalam implementasi nyata, data ini diambil dari database

        $activities = [];

        for ($i = 1; $i <= 5; $i++) {
            $activities[] = (object) [
                'id' => $i,
                'judul' => 'Aktivitas #' . $i,
                'deskripsi' => 'Deskripsi aktivitas ' . $i . ' yang dilakukan oleh admin lapangan.',
                'created_at' => Carbon::now()->subDays(rand(1, 10)),
                'user' => (object) [
                    'name' => 'Admin Lapangan'
                ]
            ];
        }

        return collect($activities)->sortByDesc('created_at');
    }

    /**
     * Mendapatkan data omset untuk chart
     *
     * @param int $umkmId
     * @return array
     */
    private function getOmsetChartData($umkmId)
    {
        $data = [
            'labels' => [],
            'values' => []
        ];

        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');
            $data['labels'][] = $month;

            // Cari omset untuk bulan tersebut
            $monthlyOmset = Intervensi::where('umkm_id', $umkmId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('omset');

            $data['values'][] = $monthlyOmset ?: 0;
        }

        return $data;
    }

    /**
     * Ekspor laporan (untuk Admin Kantor)
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportAdminReport()
    {
        // Implementasi export laporan
        // ...

        return redirect()->back()->with('success', 'Laporan berhasil diunduh');
    }

    /**
     * Ekspor laporan (untuk Admin Lapangan)
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportLapanganReport()
    {
        // Implementasi export laporan
        // ...

        return redirect()->back()->with('success', 'Laporan berhasil diunduh');
    }

    /**
     * Ekspor laporan (untuk Pelaku UMKM)
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportUmkmReport()
    {
        // Implementasi export laporan
        // ...

        return redirect()->back()->with('success', 'Laporan berhasil diunduh');
    }
}
