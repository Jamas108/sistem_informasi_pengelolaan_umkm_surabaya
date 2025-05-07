<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PelakuUmkm;
use App\Models\Umkm;
use App\Models\Kegiatan;
use App\Models\Intervensi;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IntervensiExport;
use Mockery;
use Illuminate\Support\Str;

class IntervensiTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    protected $adminUser;
    protected $pelakuUmkm;
    protected $umkm;
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

        // Cek apakah sudah ada pelaku UMKM untuk testing
        $this->pelakuUmkm = PelakuUmkm::first();

        if (!$this->pelakuUmkm) {
            // Buat pelaku UMKM untuk testing jika belum ada
            $this->pelakuUmkm = PelakuUmkm::create([
                'users_id' => $this->adminUser->id, // Temporary for testing
                'nama_lengkap' => 'Pelaku UMKM Test',
                'nik' => $this->faker->unique()->numerify('################'),
                'no_kk' => $this->faker->numerify('################'),
                'tempat_lahir' => 'Test Kota',
                'tgl_lahir' => now()->subYears(30),
                'jenis_kelamin' => 'Laki-laki',
                'status_hubungan_keluarga' => 'Kepala Keluarga',
                'status_perkawinan' => 'Kawin',
                'kelurahan' => 'Test Kelurahan',
                'rt' => '001',
                'rw' => '002',
                'alamat_sesuai_ktp' => 'Jalan Test No. 123',
                'no_telp' => $this->faker->numerify('08##########'),
                'pendidikan_terakhir' => 'S1',
                'status_keaktifan' => 'Aktif',
            ]);
        }

        // Cek apakah sudah ada UMKM untuk testing
        $this->umkm = Umkm::where('pelaku_umkm_id', $this->pelakuUmkm->id)->first();

        if (!$this->umkm) {
            // Buat UMKM untuk testing jika belum ada
            $this->umkm = Umkm::create([
                'pelaku_umkm_id' => $this->pelakuUmkm->id,
                'nama_usaha' => 'UMKM Test',
                'alamat' => 'Alamat Test UMKM',
                'status' => 'AKTIF',
                'jumlah_tenaga_kerja' => 5,
                'klasifikasi_kinerja_usaha' => 'Mikro',
                'pengelolaan_usaha' => 'Individu',
                'sektor_usaha' => 'Makanan',
            ]);
        }

        // Cek apakah sudah ada kegiatan untuk testing
        $this->kegiatan = Kegiatan::first();

        if (!$this->kegiatan) {
            // Buat kegiatan untuk testing jika belum ada
            $this->kegiatan = Kegiatan::create([
                'nama_kegiatan' => 'Kegiatan Test',
                'jenis_kegiatan' => 'Pelatihan',
                'lokasi_kegiatan' => 'Test Lokasi',
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addDays(1),
                'jam_mulai' => '08:00',
                'jam_selesai' => '16:00',
                'status_kegiatan' => 'AKTIF',
                'kuota_pendaftaran' => 50,
                'deskripsi' => 'Deskripsi Test Kegiatan',
            ]);
        }

        // Cek apakah sudah ada intervensi untuk testing
        $this->intervensi = Intervensi::where('umkm_id', $this->umkm->id)->first();

        if (!$this->intervensi) {
            // Buat intervensi untuk testing jika belum ada
            $this->intervensi = Intervensi::create([
                'umkm_id' => $this->umkm->id,
                'kegiatan_id' => $this->kegiatan->id,
                'no_pendaftaran_kegiatan' => 'REG'.time(),
                'omset' => 5000000.00,
                'dokumentasi_kegiatan' => ['foto1.jpg', 'foto2.jpg'],
            ]);
        }
    }

    /**
     * Test login sebagai admin kantor dengan NIK.
     */
    public function test_mengakses_halaman_menggunakan_role_admin()
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
     * Test melihat halaman daftar intervensi.
     */
    public function test_dapat_melihat_halaman_intervensi()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\IntervensiController');
        $response = $controller->index();

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('adminkantor.export.intervensi', $response->getName());
        $this->assertArrayHasKey('dataintervensis', $response->getData());
        $this->assertArrayHasKey('kegiatans', $response->getData());
    }

    /**
     * Test mendapatkan daftar intervensi untuk pelaku UMKM tertentu.
     */
    public function test_dapat_mendapatkan_daftar_intervensi()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\IntervensiController');
        $response = $controller->getIntervensiList($this->pelakuUmkm->id);

        // Decode JSON response
        $responseData = json_decode($response->getContent(), true);

        // Verifikasi response
        $this->assertTrue($responseData['success']);
        $this->assertIsArray($responseData['data']);
    }

    /**
     * Test mendapatkan data spesifik intervensi.
     */
    public function test_dapat_mendapatkan_data_intervensi()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\IntervensiController');
        $response = $controller->getIntervensi($this->intervensi->id);

        // Decode JSON response
        $responseData = json_decode($response->getContent(), true);

        // Verifikasi response
        $this->assertTrue($responseData['success']);
        $this->assertIsArray($responseData['data']);
        $this->assertEquals($this->intervensi->id, $responseData['data']['id']);
    }

    /**
     * Test mendapatkan data intervensi untuk edit.
     */
    public function test_dapat_mendapatkan_data_intervensi_untuk_edit()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\IntervensiController');
        $response = $controller->getIntervensiForEdit($this->pelakuUmkm->id, $this->intervensi->id);

        // Decode JSON response
        $responseData = json_decode($response->getContent(), true);

        // Verifikasi response
        $this->assertTrue($responseData['success']);
        $this->assertIsArray($responseData['data']);
        $this->assertIsArray($responseData['umkms']);
        $this->assertIsArray($responseData['kegiatans']);
        $this->assertEquals($this->intervensi->id, $responseData['data']['id']);
    }

    /**
     * Test menyimpan data intervensi baru.
     */
    public function test_dapat_menyimpan_intervensi_baru()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Buat request data
        $request = new Request([
            'umkm_id' => $this->umkm->id,
            'kegiatan_id' => $this->kegiatan->id,
            'tgl_intervensi' => now()->format('Y-m-d'),
            'jenis_intervensi' => 'Pelatihan Digital Marketing',
            'nama_kegiatan' => 'Workshop UMKM',
            'omset' => 7500000.00
        ]);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\IntervensiController');
        $response = $controller->saveIntervensi($request, $this->pelakuUmkm->id);

        // Decode JSON response
        $responseData = json_decode($response->getContent(), true);

        // Verifikasi response
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('registration_number', $responseData);

        // Verify data was saved in database
        $this->assertDatabaseHas('intervensi', [
            'umkm_id' => $this->umkm->id,
            'kegiatan_id' => $this->kegiatan->id,
            'no_pendaftaran_kegiatan' => $responseData['registration_number'],
        ]);
    }

    /**
     * Test update data intervensi.
     */
    public function test_dapat_update_data_intervensi()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Persiapkan data untuk update
        $newOmset = 8500000.00;

        // Buat request dengan data update
        $request = new Request([
            'umkm_id' => $this->umkm->id,
            'kegiatan_id' => $this->kegiatan->id,
            'omset' => $newOmset
        ]);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\IntervensiController');
        $response = $controller->updateIntervensi($request, $this->pelakuUmkm->id, $this->intervensi->id);

        // Decode JSON response
        $responseData = json_decode($response->getContent(), true);

        // Verifikasi response
        $this->assertTrue($responseData['success']);

        // Verify data was updated in database
        $updatedIntervensi = Intervensi::find($this->intervensi->id)->fresh();
        $this->assertEquals($newOmset, $updatedIntervensi->omset);
    }

    /**
     * Test hapus data intervensi.
     */
    public function test_dapat_hapus_data_intervensi()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Create a temporary intervensi to delete
        $tempIntervensi = Intervensi::create([
            'umkm_id' => $this->umkm->id,
            'kegiatan_id' => $this->kegiatan->id,
            'no_pendaftaran_kegiatan' => 'REG'.time().'-DELETE',
            'omset' => 3000000.00,
        ]);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\IntervensiController');
        $response = $controller->deleteIntervensi($tempIntervensi->id);

        // Decode JSON response
        $responseData = json_decode($response->getContent(), true);

        // Verifikasi response
        $this->assertTrue($responseData['success']);

        // Verify record was deleted from database
        $this->assertDatabaseMissing('intervensi', [
            'id' => $tempIntervensi->id
        ]);
    }

    /**
     * Test export data intervensi.
     */
    public function test_dapat_export_data_intervensi()
{
    // Login sebagai admin kantor
    $this->actingAs($this->adminUser);

    // Hit the route instead of calling the controller method directly
    $response = $this->get(route('intervensi.exportexcel')); // Adjust the route name as needed

    // Verify the response has the correct headers for a file download
    $response->assertStatus(200);
    $this->assertTrue($response->headers->contains('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'));
}

    /**
     * Clean up after testing.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}