<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PelakuUmkm;
use App\Models\Umkm;
use App\Models\ProdukUmkm;
use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UmkmCompleteExport;

class DataUMKMTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    protected $adminUser;
    protected $pelakuUmkm;
    protected $umkm;
    protected $produkUmkm;
    protected $kegiatan;

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

        // Cek apakah sudah ada Produk UMKM untuk testing
        $this->produkUmkm = ProdukUmkm::where('umkm_id', $this->umkm->id)->first();

        if (!$this->produkUmkm) {
            // Buat Produk UMKM untuk testing jika belum ada
            $this->produkUmkm = ProdukUmkm::create([
                'umkm_id' => $this->umkm->id,
                'jenis_produk' => 'Makanan',
                'tipe_produk' => 'Kue',
                'status' => 'AKTIF',
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
            ]);
        }
    }

    /**
     * Test login sebagai admin kantor dengan NIK.
     */
    public function test_admin_dapat_akses_halaman_kelola_umkm()
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
     * Test menampilkan daftar Pelaku UMKM.
     */
    // public function test_dapat_melihat_daftar_pelaku_umkm()
    // {
    //     // Login sebagai admin kantor
    //     $this->actingAs($this->adminUser);

    //     // Akses controller langsung
    //     $controller = app()->make('App\Http\Controllers\DataUmkmController');
    //     $response = $controller->index();

    //     // Verifikasi response
    //     $this->assertInstanceOf(\Illuminate\View\View::class, $response);
    //     $this->assertEquals('adminkantor.dataumkm.index', $response->getName());
    //     $this->assertArrayHasKey('datapelakuumkms', $response->getData());
    //     $this->assertArrayHasKey('pageTitle', $response->getData());
    // }

    /**
     * Test menampilkan form tambah data UMKM.
     */
    public function test_dapat_menampilkan_form_tambah_umkm()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\DataUmkmController');
        $response = $controller->create();

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('adminkantor.dataumkm.create', $response->getName());
    }

    /**
     * Test detail pelaku UMKM.
     */
    public function test_dapat_melihat_detail_pelaku_umkm()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\DataUmkmController');
        $response = $controller->show($this->pelakuUmkm->id);

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('adminkantor.dataumkm.show', $response->getName());
        $this->assertArrayHasKey('pelakuUmkm', $response->getData());
        $this->assertArrayHasKey('kegiatans', $response->getData());

        // Verifikasi data pelaku UMKM yang ditampilkan
        $pelakuUmkm = $response->getData()['pelakuUmkm'];
        $this->assertEquals($this->pelakuUmkm->id, $pelakuUmkm->id);
    }

    /**
     * Test form edit pelaku UMKM.
     */
    public function test_dapat_menampilkan_form_edit_pelaku_umkm()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\DataUmkmController');
        $response = $controller->edit($this->pelakuUmkm->id);

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('adminkantor.dataumkm.edit', $response->getName());
        $this->assertArrayHasKey('pelakuUmkm', $response->getData());
        $this->assertArrayHasKey('kegiatans', $response->getData());
    }

    /**
     * Test update data pelaku UMKM.
     */
/**
 * Test update data pelaku UMKM.
 */
/**
 * Test update data pelaku UMKM.
 */
/**
 * Test update data pelaku UMKM.
 */
public function test_dapat_update_data_pelaku_umkm()
{
    // Login sebagai admin kantor
    $this->actingAs($this->adminUser);

    // Buat request dengan data update
    $request = new Request();
    $newName = 'Pelaku UMKM Updated';

    $requestData = [
        'nik' => $this->pelakuUmkm->nik,
        'nama_lengkap' => $newName,
        'no_kk' => $this->pelakuUmkm->no_kk,
        'tempat_lahir' => $this->pelakuUmkm->tempat_lahir,
        'tgl_lahir' => $this->pelakuUmkm->tgl_lahir,
        'jenis_kelamin' => $this->pelakuUmkm->jenis_kelamin,
        'status_hubungan_keluarga' => $this->pelakuUmkm->status_hubungan_keluarga,
        'status_perkawinan' => $this->pelakuUmkm->status_perkawinan,
        'alamat_sesuai_ktp' => $this->pelakuUmkm->alamat_sesuai_ktp,
        'kelurahan' => $this->pelakuUmkm->kelurahan,
        'rt' => $this->pelakuUmkm->rt,
        'rw' => $this->pelakuUmkm->rw,
        'no_telp' => $this->pelakuUmkm->no_telp,
        'pendidikan_terakhir' => $this->pelakuUmkm->pendidikan_terakhir,
        'status_keaktifan' => $this->pelakuUmkm->status_keaktifan,

    ];

    $request->replace($requestData);

    // Akses controller method langsung
    $controller = app()->make('App\Http\Controllers\DataUmkmController');
    $response = $controller->update($request, $this->pelakuUmkm->id);

    // Verifikasi response
    $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);

}

    /**
     * Test checkNik method.
     */
    public function test_dapat_cek_nik()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Buat request dengan NIK yang ada
        $request = new Request();
        $request->replace(['nik' => $this->pelakuUmkm->nik]);

        // Akses controller method langsung
        $controller = app()->make('App\Http\Controllers\DataUmkmController');
        $response = $controller->checkNik($request);

        // Verifikasi response
        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['exists']);
        $this->assertEquals($this->pelakuUmkm->nik, $responseData['data']['nik']);

        // Test dengan NIK yang tidak ada
        $request->replace(['nik' => '9999999999999999']);
        $response = $controller->checkNik($request);
        $responseData = json_decode($response->getContent(), true);
        $this->assertFalse($responseData['exists']);
    }

    /**
     * Test getUmkmOptions method.
     */
    public function test_dapat_mendapatkan_opsi_umkm()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller method langsung
        $controller = app()->make('App\Http\Controllers\DataUmkmController');
        $response = $controller->getUmkmOptions($this->pelakuUmkm->id);

        // Verifikasi response
        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertIsArray($responseData['data']);

        // Verifikasi UMKM yang dikembalikan
        $umkmData = collect($responseData['data'])->firstWhere('id', $this->umkm->id);
        $this->assertNotNull($umkmData);
        $this->assertEquals($this->umkm->nama_usaha, $umkmData['nama_usaha']);
    }

    /**
     * Test melihat halaman export UMKM.
     */
    public function test_dapat_melihat_halaman_export_umkm()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Akses controller langsung
        $controller = app()->make('App\Http\Controllers\DataUmkmController');
        $response = $controller->ExportUmkmIndex();

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('adminkantor.export.dataumkm', $response->getName());
        $this->assertArrayHasKey('dataumkms', $response->getData());
        $this->assertArrayHasKey('pageTitle', $response->getData());
    }

    /**
     * Test export data UMKM.
     */
   /**
 * Test export data UMKM route.
 */
public function test_dapat_export_data_umkm()
{
    // Login sebagai admin kantor
    $this->actingAs($this->adminUser);

    // Hit the route instead of calling the controller method directly
    $response = $this->get(route('dataumkm.export')); // Adjust the route name as needed

    // Verify the response has the correct headers for a file download
    $response->assertStatus(200);
    $this->assertTrue($response->headers->contains('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'));
}

    /**
     * Test tambah data UMKM baru.
     */
    public function test_dapat_tambah_data_umkm_baru()
    {
        // Login sebagai admin kantor
        $this->actingAs($this->adminUser);

        // Buat NIK unik untuk testing
        $uniqueNik = $this->faker->unique()->numerify('################');

        // Buat data request
        $request = new Request();
        $requestData = [
            'nik' => $uniqueNik,
            'nama_lengkap' => 'Pelaku UMKM Baru',
            'no_kk' => $this->faker->numerify('################'),
            'tempat_lahir' => 'Test Kota',
            'tgl_lahir' => now()->subYears(25)->format('Y-m-d'),
            'jenis_kelamin' => 'Perempuan',
            'status_hubungan_keluarga' => 'Kepala Keluarga',
            'status_perkawinan' => 'Kawin',
            'kelurahan' => 'Test Kelurahan',
            'rt' => '001',
            'rw' => '002',
            'alamat_sesuai_ktp' => 'Jalan Test No. 456',
            'no_telp' => $this->faker->numerify('08##########'),
            'pendidikan_terakhir' => 'S1',
            'status_keaktifan' => 'Aktif',
            'umkm' => [
                [
                    'nama_usaha' => 'UMKM Baru Test',
                    'alamat' => 'Alamat UMKM Baru',
                    'pengelolaan_usaha' => 'Individu',
                    'klasifikasi_kinerja_usaha' => 'Mikro',
                    'jumlah_tenaga_kerja' => 3,
                    'sektor_usaha' => 'Kerajinan',
                    'status' => 'AKTIF',
                    'products' => [
                        [
                            'jenis_produk' => 'Kerajinan Tangan',
                            'tipe_produk' => 'Ukiran',
                            'status' => 'AKTIF'
                        ]
                    ]
                ]
            ]
        ];
        $request->replace($requestData);

        // Akses controller method langsung
        $controller = app()->make('App\Http\Controllers\DataUmkmController');
        $response = $controller->store($request);

        // Verifikasi response
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);

        // Verifikasi data disimpan di database
        $this->assertDatabaseHas('pelaku_umkm', [
            'nik' => $uniqueNik,
            'nama_lengkap' => 'Pelaku UMKM Baru'
        ]);

        // Dapatkan pelaku UMKM yang baru dibuat untuk verifikasi UMKM
        $newPelakuUmkm = PelakuUmkm::where('nik', $uniqueNik)->first();
        $this->assertNotNull($newPelakuUmkm);

        // Verifikasi UMKM dibuat
        $this->assertDatabaseHas('umkm', [
            'pelaku_umkm_id' => $newPelakuUmkm->id,
            'nama_usaha' => 'UMKM Baru Test'
        ]);

        // Verifikasi user dibuat dengan password default (NIK)
        $this->assertDatabaseHas('users', [
            'nik' => $uniqueNik,
            'role' => 'pelakuumkm'
        ]);
    }

    /**
     * Cleanup setelah test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}