<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PelakuUmkm;
use App\Models\Umkm;
use App\Models\ProdukUmkm;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelakuKelolaUmkmTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    protected $user;
    protected $pelakuUmkm;
    protected $umkm;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user with pelakuumkm role
        $this->user = User::create([
            'username' => 'pelaku_test',
            'nik' => $this->faker->unique()->numerify('################'),
            'password' => bcrypt('password123'),
            'role' => 'pelakuumkm',
        ]);

        // Create a pelaku UMKM record for this user
        $this->pelakuUmkm = PelakuUmkm::create([
            'users_id' => $this->user->id,
            'nama_lengkap' => 'Pelaku UMKM Test',
            'nik' => $this->user->nik,
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

        // Create UMKM for this pelaku
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

        // Authenticate as the pelaku UMKM user
        $this->actingAs($this->user);
    }

    /**
     * Test index method with existing UMKM.
     */
    public function test_halaman_index_kelola_umkm()
    {
        // Create another UMKM for this pelaku
        $umkm2 = Umkm::create([
            'pelaku_umkm_id' => $this->pelakuUmkm->id,
            'nama_usaha' => 'UMKM Test 2',
            'alamat' => 'Alamat Test UMKM 2',
            'status' => 'AKTIF',
            'jumlah_tenaga_kerja' => 3,
            'klasifikasi_kinerja_usaha' => 'Mikro',
            'pengelolaan_usaha' => 'Individu',
            'sektor_usaha' => 'Jasa',
        ]);

        // Make request to the index page
        $response = $this->get(route('pelakukelolaumkm.index'));

        // Assert the response
        $response->assertStatus(200);
        $response->assertViewIs('pelakuumkm.kelolaumkm.index');
        $response->assertViewHas('dataumkms');
        $response->assertViewHas('pageTitle', 'Kelola UMKM');

        // Assert that the view contains both UMKMs
        $response->assertSee('UMKM Test');
        $response->assertSee('UMKM Test 2');
    }

    /**
     * Test index method with no UMKM records.
     */
    public function test_halaman_index_tanpa_data_umkm()
    {
        // Delete all UMKM records for this pelaku
        Umkm::where('pelaku_umkm_id', $this->pelakuUmkm->id)->delete();

        // Make request to the index page
        $response = $this->get(route('pelakukelolaumkm.index'));

        // Assert the response
        $response->assertStatus(200);
        $response->assertViewIs('pelakuumkm.kelolaumkm.index');
        $response->assertViewHas('dataumkms');
        $response->assertViewHas('pageTitle', 'Kelola UMKM');

        // Assert that the collection is empty
        $response->assertViewHas('dataumkms', function($dataumkms) {
            return $dataumkms->isEmpty();
        });
    }

    // /**
    //  * Test create method.
    //  */
    // public function test_create_displays_form()
    // {
    //     // Make request to the create page
    //     $response = $this->get(route('pelakukelolaumkm.create'));

    //     // Assert the response
    //     $response->assertStatus(200);
    //     $response->assertViewIs('pelakuumkm.kelolaumkm.create');
    //     $response->assertViewHas('pelakuUmkm');
    //     $response->assertViewHas('pageTitle', 'Tambah UMKM Baru');

    //     // Assert that the view contains the pelaku name
    //     $response->assertSee('Pelaku UMKM Test');
    // }

    /**
     * Test store method with valid data.
     */
    public function test_menambah_data_umkm()
    {
        // Prepare data for a new UMKM
        $umkmData = [
            'umkm' => [
                [
                    'nama_usaha' => 'UMKM Baru Test',
                    'alamat' => 'Alamat UMKM Baru',
                    'pengelolaan_usaha' => 'Individu',
                    'klasifikasi_kinerja_usaha' => 'Mikro',
                    'jumlah_tenaga_kerja' => 3,
                    'sektor_usaha' => 'Jasa',
                    'products' => [
                        [
                            'jenis_produk' => 'Produk Test',
                            'tipe_produk' => 'Fisik',
                            'status' => 'Aktif'
                        ]
                    ]
                ]
            ]
        ];

        // Make request to store new UMKM
        $response = $this->post(route('pelakukelolaumkm.store'), $umkmData);

        // Assert redirection
        $response->assertRedirect(route('pelakukelolaumkm.index'));
        $response->assertSessionHas('status', 'UMKM berhasil ditambahkan dan menunggu verifikasi admin');
        $response->assertSessionHas('status_type', 'success');

        // Assert UMKM was created in database
        $this->assertDatabaseHas('umkm', [
            'pelaku_umkm_id' => $this->pelakuUmkm->id,
            'nama_usaha' => 'UMKM Baru Test',
            'alamat' => 'Alamat UMKM Baru',
            'status' => 'Menunggu Verifikasi'
        ]);

        // Get the newly created UMKM
        $newUmkm = Umkm::where('nama_usaha', 'UMKM Baru Test')->first();

        // Assert product was created
        $this->assertDatabaseHas('produk_umkm', [
            'umkm_id' => $newUmkm->id,
            'jenis_produk' => 'Produk Test',
            'tipe_produk' => 'Fisik'
        ]);
    }

    /**
     * Test store method with multiple UMKMs.
     */
    public function test_menambah_data_umkm_lebih_dari_satu_umkm()
    {
        // Prepare data for multiple UMKMs
        $data = [
            'umkm' => [
                [
                    'nama_usaha' => 'UMKM Multiple 1',
                    'alamat' => 'Alamat Multiple 1',
                    'pengelolaan_usaha' => 'Individu',
                    'klasifikasi_kinerja_usaha' => 'Mikro',
                    'jumlah_tenaga_kerja' => 2,
                    'sektor_usaha' => 'Jasa'
                ],
                [
                    'nama_usaha' => 'UMKM Multiple 2',
                    'alamat' => 'Alamat Multiple 2',
                    'pengelolaan_usaha' => 'Kelompok',
                    'klasifikasi_kinerja_usaha' => 'Kecil',
                    'jumlah_tenaga_kerja' => 5,
                    'sektor_usaha' => 'Makanan'
                ]
            ]
        ];

        // Make request to store new UMKMs
        $response = $this->post(route('pelakukelolaumkm.store'), $data);

        // Assert redirection
        $response->assertRedirect(route('pelakukelolaumkm.index'));
        $response->assertSessionHas('status', '2 UMKM berhasil ditambahkan dan menunggu verifikasi admin');
        $response->assertSessionHas('status_type', 'success');

        // Assert UMKMs were created in database
        $this->assertDatabaseHas('umkm', [
            'pelaku_umkm_id' => $this->pelakuUmkm->id,
            'nama_usaha' => 'UMKM Multiple 1'
        ]);
        $this->assertDatabaseHas('umkm', [
            'pelaku_umkm_id' => $this->pelakuUmkm->id,
            'nama_usaha' => 'UMKM Multiple 2'
        ]);
    }


    /**
     * Test show method.
     */
    public function test_halaman_detail_umkm()
    {
        // Make request to show UMKM details
        $response = $this->get(route('pelakukelolaumkm.show', $this->umkm->id));

        // Assert the response
        $response->assertStatus(200);
        $response->assertViewIs('pelakuumkm.kelolaumkm.show');
        $response->assertViewHas('umkm');

        // Assert the UMKM details are displayed
        $response->assertSee('UMKM Test');
        $response->assertSee('Alamat Test UMKM');
    }

    /**
     * Test edit method.
     */
    public function test_menampilkan_halaman_edit_umkm()
    {
        // Make request to edit UMKM
        $response = $this->get(route('pelakukelolaumkm.edit', $this->umkm->id));

        // Assert the response
        $response->assertStatus(200);
        $response->assertViewIs('pelakuumkm.kelolaumkm.edit');
        $response->assertViewHas('umkm');

        // Assert the UMKM details are displayed in form
        $response->assertSee('UMKM Test');
        $response->assertSee('Alamat Test UMKM');
    }

    /**
     * Test update method with valid data.
     */
    public function test_edit_data_umkm()
    {
        // Prepare data for updating
        $updatedData = [
            'nama_usaha' => 'UMKM Updated',
            'alamat' => 'Alamat Updated',
            'pengelolaan_usaha' => 'Kelompok',
            'klasifikasi_kinerja_usaha' => 'Kecil',
            'jumlah_tenaga_kerja' => 10,
            'sektor_usaha' => 'Kerajinan'
        ];

        // Make request to update UMKM
        $response = $this->put(route('pelakukelolaumkm.update', $this->umkm->id), $updatedData);

        // Assert redirection
        $response->assertRedirect(route('pelakukelolaumkm.index'));
        $response->assertSessionHas('status', 'Data UMKM berhasil diperbarui');
        $response->assertSessionHas('status_type', 'success');

        // Refresh the model
        $this->umkm->refresh();

        // Assert UMKM was updated
        $this->assertEquals('UMKM Updated', $this->umkm->nama_usaha);
        $this->assertEquals('Alamat Updated', $this->umkm->alamat);
        $this->assertEquals('Kelompok', $this->umkm->pengelolaan_usaha);
        $this->assertEquals('Kecil', $this->umkm->klasifikasi_kinerja_usaha);
        $this->assertEquals(10, $this->umkm->jumlah_tenaga_kerja);
        $this->assertEquals('Kerajinan', $this->umkm->sektor_usaha);
    }

    /**
     * Test update method with validation errors.
     */
    public function test_gagal_update_ketika_data_umkm_kosong()
    {
        // Prepare data with validation errors
        $invalidData = [
            'nama_usaha' => '', // Required field is empty
            'alamat' => '', // Required field is empty
            'jumlah_tenaga_kerja' => 'bukan angka' // Should be integer
        ];

        // Make request to update with invalid data
        $response = $this->put(route('pelakukelolaumkm.update', $this->umkm->id), $invalidData);

        // Assert redirection back with errors
        $response->assertRedirect();
        $response->assertSessionHasErrors(['nama_usaha', 'alamat', 'jumlah_tenaga_kerja']);

        // Refresh the model
        $this->umkm->refresh();

        // Assert UMKM was not updated
        $this->assertEquals('UMKM Test', $this->umkm->nama_usaha);
    }

    /**
     * Test destroy method.
     */
    public function test_hapus_data_umkm()
    {
        // Create products for the UMKM
        $product1 = ProdukUmkm::create([
            'umkm_id' => $this->umkm->id,
            'jenis_produk' => 'Produk Test 1',
            'tipe_produk' => 'Fisik',
            'status' => 'Aktif'
        ]);

        $product2 = ProdukUmkm::create([
            'umkm_id' => $this->umkm->id,
            'jenis_produk' => 'Produk Test 2',
            'tipe_produk' => 'Digital',
            'status' => 'Aktif'
        ]);

        // Make request to delete UMKM
        $response = $this->delete(route('pelakukelolaumkm.destroy', $this->umkm->id));

        // Assert redirection
        $response->assertRedirect(route('pelakukelolaumkm.index'));
        $response->assertSessionHas('status', 'UMKM berhasil dihapus');
        $response->assertSessionHas('status_type', 'success');

        // Assert UMKM was deleted
        $this->assertDatabaseMissing('umkm', [
            'id' => $this->umkm->id
        ]);

        // Assert products were deleted
        $this->assertDatabaseMissing('produk_umkm', [
            'id' => $product1->id
        ]);
        $this->assertDatabaseMissing('produk_umkm', [
            'id' => $product2->id
        ]);
    }

    /**
     * Clean up after testing.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}