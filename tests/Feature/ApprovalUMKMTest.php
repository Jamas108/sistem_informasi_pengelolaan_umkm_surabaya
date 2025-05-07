<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Umkm;
use App\Models\PelakuUmkm;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApprovalUMKMTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    protected $adminUser;
    protected $umkm;
    protected $pelakuUmkm;

    protected function setUp(): void
    {
        parent::setUp();

        // Cek apakah sudah ada user admin kantor dengan NIK yang diberikan
        $this->adminUser = User::where('nik', '1234512345123456')->first();

        if (!$this->adminUser) {
            // Buat user adminkantor untuk testing jika belum ada
            $this->adminUser = User::create([
                'name' => 'Admin Kantor Test',
                'nik' => '1234512345123456',
                'role' => 'adminkantor',
                'password' => Hash::make('12341234'),
            ]);
        }

        // Cek apakah sudah ada pelaku UMKM untuk testing
        $this->pelakuUmkm = PelakuUmkm::first();

        if (!$this->pelakuUmkm) {
            // Buat pelaku UMKM untuk testing jika belum ada
            $this->pelakuUmkm = PelakuUmkm::create([
                'nama_lengkap' => 'Pelaku UMKM Test',
                'nik' => $this->faker->unique()->numerify('################'),
                'alamat' => 'Alamat Test',
                'no_hp' => $this->faker->numerify('08##########'),
                // Sesuaikan dengan field lain yang wajib di tabel pelaku_umkm
            ]);
        }

        // Cek apakah sudah ada UMKM dengan status Menunggu Verifikasi untuk testing
        $this->umkm = Umkm::where('status', 'Menunggu Verifikasi')->first();

        if (!$this->umkm) {
            // Buat UMKM untuk testing jika belum ada
            $this->umkm = Umkm::create([
                'pelaku_umkm_id' => $this->pelakuUmkm->id,
                'nama_usaha' => 'UMKM Test Approval',
                'alamat' => 'Alamat Test UMKM',
                'status' => 'Menunggu Verifikasi',
                'jumlah_tenaga_kerja' => 5,
                'klasifikasi_kinerja_usaha' => 'Mikro',
                'pengelolaan_usaha' => 'Individu',
                'sektor_usaha' => 'Makanan',
                // Sesuaikan dengan field lain yang wajib di tabel umkm
            ]);
        }
    }

    /**
     * Test login sebagai admin kantor dengan NIK (menggunakan cara manual).
     */
    public function test_admin_dapat_mengakses_halaman_approval()
    {
        // Login manual tanpa akses halaman login
        $credentials = [
            'nik' => '1234512345123456',
            'password' => '12341234',
        ];

        $this->assertTrue(Auth::attempt($credentials));
        $this->assertAuthenticatedAs($this->adminUser);
    }

    /**
     * Test melihat daftar UMKM yang menunggu verifikasi.
     */
    public function test_dapat_melihat_daftar_umkm_menunggu_verifikasi()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\ApprovalUMKMController');
        $response = $controller->index();

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('adminkantor.approval.approvalumkm', $response->getName());
        $this->assertArrayHasKey('dataumkms', $response->getData());
        $this->assertArrayHasKey('pageTitle', $response->getData());
    }

    /**
     * Test melihat detail UMKM.
     */
    public function test_dapat_melihat_detail_umkm()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\ApprovalUMKMController');
        $response = $controller->show($this->umkm->id);

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('adminkantor.approval.show', $response->getName());
        $this->assertArrayHasKey('dataumkm', $response->getData());

        // Verifikasi data UMKM yang ditampilkan
        $dataumkm = $response->getData()['dataumkm'];
        $this->assertEquals($this->umkm->id, $dataumkm->id);
        $this->assertEquals($this->umkm->nama_usaha, $dataumkm->nama_usaha);
    }

    /**
     * Test approval UMKM.
     */
    public function test_dapat_approve_umkm()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Simpan status awal
        $originalStatus = $this->umkm->status;

        // Akses controller method langsung
        $controller = app()->make('App\Http\Controllers\ApprovalUMKMController');
        $response = $controller->approve($this->umkm->id);

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);

        // Verifikasi perubahan status di database
        $updatedUmkm = Umkm::find($this->umkm->id);
        $this->assertEquals('AKTIF', $updatedUmkm->status);

        // Kembalikan ke status awal untuk test berikutnya
        $updatedUmkm->status = $originalStatus;
        $updatedUmkm->save();
    }

    /**
     * Test rejection UMKM.
     */
    public function test_dapat_reject_umkm()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Simpan status awal
        $originalStatus = $this->umkm->status;
        $originalAlasanPenolakan = $this->umkm->alasan_penolakan;

        // Buat request dengan alasan penolakan
        $request = new Request();
        $alasanPenolakan = 'Data UMKM tidak lengkap';
        $request->replace(['alasan_penolakan' => $alasanPenolakan]);

        // Akses controller method langsung
        $controller = app()->make('App\Http\Controllers\ApprovalUMKMController');
        $response = $controller->reject($request, $this->umkm->id);

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);

        // Verifikasi perubahan status di database
        $updatedUmkm = Umkm::find($this->umkm->id);
        $this->assertEquals('DITOLAK', $updatedUmkm->status);
        $this->assertEquals($alasanPenolakan, $updatedUmkm->alasan_penolakan);

        // Kembalikan ke status awal untuk test berikutnya
        $updatedUmkm->status = $originalStatus;
        $updatedUmkm->alasan_penolakan = $originalAlasanPenolakan;
        $updatedUmkm->save();
    }

    /**
     * Test validasi alasan penolakan saat reject UMKM.
     */
    public function test_reject_umkm_memerlukan_alasan_penolakan()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Buat request tanpa alasan penolakan
        $request = new Request();
        $request->replace([]);

        // Siapkan untuk menangkap exception ValidationException
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        // Akses controller method langsung
        $controller = app()->make('App\Http\Controllers\ApprovalUMKMController');
        $response = $controller->reject($request, $this->umkm->id);
    }

    /**
     * Cleanup setelah test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}