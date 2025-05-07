<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Intervensi;
use App\Models\Umkm;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PesertaPendaftaranTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    protected $adminUser;
    protected $kegiatan;
    protected $intervensi;

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

        // Cek apakah sudah ada kegiatan untuk testing
        $this->kegiatan = Kegiatan::first();

        if (!$this->kegiatan) {
            // Buat kegiatan untuk testing jika belum ada
            $this->kegiatan = Kegiatan::create([
                'nama_kegiatan' => 'Kegiatan Test',
                'tanggal_pelaksanaan' => now(),
                'lokasi' => 'Test Lokasi',
                'deskripsi' => 'Deskripsi Test Kegiatan',
                'kuota' => 50,
                // Sesuaikan dengan field lain yang wajib di tabel kegiatan
            ]);
        }

        // Cek apakah sudah ada UMKM untuk testing
        $umkm = Umkm::first();

        if (!$umkm) {
            // Buat UMKM jika belum ada
            $umkm = Umkm::create([
                'nama_umkm' => 'UMKM Test',
                'nik_pelaku' => $this->faker->unique()->numerify('################'),
                'alamat' => 'Alamat Test',
                // Sesuaikan dengan field lain yang wajib di tabel umkm
            ]);
        }

        // Cek apakah sudah ada intervensi untuk kegiatan tersebut
        $this->intervensi = Intervensi::where('kegiatan_id', $this->kegiatan->id)->first();

        if (!$this->intervensi) {
            // Buat intervensi untuk testing jika belum ada
            $this->intervensi = Intervensi::create([
                'umkm_id' => $umkm->id,
                'kegiatan_id' => $this->kegiatan->id,
                'omset' => 5000000.00,
                'dokumentasi_kegiatan' => ['foto1.jpg', 'foto2.jpg'],
                'no_pendaftaran_kegiatan' => 'REG'.time(),
                // Tambahkan field lain yang diperlukan
            ]);
        } elseif (!isset($this->intervensi->status)) {
            // Pastikan field status ada
            $this->intervensi->save();
        }
    }

    /**
     * Test login sebagai admin kantor dengan NIK (menggunakan cara manual).
     */
    public function test_login_sebagai_admin_kantor()
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
     * Test melihat daftar peserta kegiatan menggunakan controller langsung.
     */
    public function test_dapat_melihat_daftar_peserta()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\PesertaPendaftaranController');
        $response = $controller->index($this->kegiatan->id);

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('adminkantor.datakegiatan.peserta-kegiatan', $response->getName());
        $this->assertArrayHasKey('kegiatan', $response->getData());
        $this->assertArrayHasKey('intervensis', $response->getData());
    }

    /**
     * Test update status kehadiran peserta.
     */
    
    /**
     * Test cetak daftar hadir menggunakan response mockup.
     */
    public function test_dapat_cetak_daftar_hadir()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Mock PDF class
        $this->mockPdfService();

        // Panggil controller method langsung
        $controller = app()->make('App\Http\Controllers\PesertaPendaftaranController');
        $response = $controller->printAttendance($this->kegiatan->id);

        // Verifikasi response
        $this->assertNotNull($response);
    }

    /**
     * Test hapus peserta dari kegiatan.
     */
    public function test_dapat_hapus_peserta()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Ambil ID kegiatan untuk redirect nanti
        $kegiatanId = $this->intervensi->kegiatan_id;

        // Simpan data intervensi untuk diverifikasi setelah dihapus
        $intervensiId = $this->intervensi->id;
        $umkmId = $this->intervensi->umkm_id;

        // Akses controller method langsung
        $controller = app()->make('App\Http\Controllers\PesertaPendaftaranController');
        $response = $controller->destroy($intervensiId);

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);

        // Verifikasi data sudah dihapus dari database
        $this->assertDatabaseMissing('intervensi', [
            'id' => $intervensiId
        ]);

        // Buat ulang data intervensi untuk test berikutnya
        $this->intervensi = Intervensi::create([
            'umkm_id' => $umkmId,
            'kegiatan_id' => $kegiatanId,
            'omset' => 5000000.00,
            'dokumentasi_kegiatan' => ['foto1.jpg', 'foto2.jpg'],
            'no_pendaftaran_kegiatan' => 'REG'.time(),

            // Tambahkan field lain yang diperlukan
        ]);
    }

    /**
     * Mock PDF service untuk test cetak daftar hadir.
     */
    protected function mockPdfService()
    {
        // Buat mock untuk PDF facade
        $pdfMock = \Mockery::mock('alias:Barryvdh\DomPDF\Facade\Pdf');

        // Mock method loadView
        $pdfMock->shouldReceive('loadView')
            ->withAnyArgs()
            ->andReturnSelf();

        // Mock method setPaper
        $pdfMock->shouldReceive('setPaper')
            ->withAnyArgs()
            ->andReturnSelf();

        // Mock method stream
        $pdfMock->shouldReceive('stream')
            ->withAnyArgs()
            ->andReturn(response()->make('PDF Content', 200, [
                'Content-Type' => 'application/pdf',
            ]));
    }

    /**
     * Cleanup mockery setelah test.
     */
    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}