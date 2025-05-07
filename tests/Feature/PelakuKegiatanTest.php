<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\PelakuUmkm;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

class PelakuKegiatanTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    protected $user;
    protected $kegiatan;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user with "pelakuumkm" role
        $this->user = User::create([
            'username' => 'pelaku_test_user',
            'nik' => $this->faker->unique()->numerify('################'),
            'password' => Hash::make('password123'),
            'role' => 'pelakuumkm',
        ]);

        // Create a PelakuUmkm record for this user
        $pelakuUmkm = PelakuUmkm::create([
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

        // Create a sample kegiatan
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

    /**
     * Test accessing kegiatan index page as an authenticated pelaku UMKM.
     */
    public function test_pelakuumkm_dapat_akses_halaman_kegiatan()
    {
        // Authenticate as the pelaku UMKM user
        $this->actingAs($this->user);

        // Access the kegiatan index route
        $response = $this->get(route('pelakukegiatan.index'));

        // Assert successful response
        $response->assertStatus(200);

        // Assert the view is correct
        $response->assertViewIs('pelakuumkm.kegiatan.index');

        // Assert the view has kegiatan data
        $response->assertViewHas('kegiatans');
    }

    /**
     * Test the kegiatan index displays all kegiatans.
     */
    public function test_menampilkan_data_kegiatan()
    {
        // Authenticate as the pelaku UMKM user
        $this->actingAs($this->user);

        // Create additional kegiatans
        $kegiatan2 = Kegiatan::create([
            'nama_kegiatan' => 'Kegiatan Test 2',
            'jenis_kegiatan' => 'Seminar',
            'lokasi_kegiatan' => 'Test Lokasi 2',
            'tanggal_mulai' => now()->addDays(5),
            'tanggal_selesai' => now()->addDays(6),
            'jam_mulai' => '09:00',
            'jam_selesai' => '17:00',
            'status_kegiatan' => 'AKTIF',
            'kuota_pendaftaran' => 30,
            'deskripsi' => 'Deskripsi Test Kegiatan 2',
        ]);

        // Access the controller method directly
        $controller = app()->make('App\Http\Controllers\PelakuKegiatanController');
        $response = $controller->index();

        // Assert the response contains both kegiatans
        $this->assertEquals(Kegiatan::count(), $response->getData()['kegiatans']->count());
        $this->assertTrue($response->getData()['kegiatans']->contains('nama_kegiatan', 'Kegiatan Test'));
        $this->assertTrue($response->getData()['kegiatans']->contains('nama_kegiatan', 'Kegiatan Test 2'));
    }



    /**
     * Test kegiatan index with empty data.
     */
    public function test_akses_halaman_kegiatan_ketika_data_kosong()
    {
        // Authenticate as the pelaku UMKM user
        $this->actingAs($this->user);

        // Delete all existing kegiatans
        Kegiatan::query()->delete();

        // Access the kegiatan index route
        $response = $this->get(route('pelakukegiatan.index'));

        // Assert successful response
        $response->assertStatus(200);

        // Assert the view is correct
        $response->assertViewIs('pelakuumkm.kegiatan.index');

        // Assert the view has empty kegiatan data
        $response->assertViewHas('kegiatans', function($kegiatans) {
            return $kegiatans->isEmpty();
        });
    }

    /**
     * Test kegiatan index filtering only active kegiatans.
     *
     * Note: This test assumes your controller might be extended later to filter kegiatans.
     * If it's not planned, you can omit this test.
     */
    public function test_filter_kegiatan()
    {
        // Authenticate as the pelaku UMKM user
        $this->actingAs($this->user);

        // Create an inactive kegiatan
        $inactiveKegiatan = Kegiatan::create([
            'nama_kegiatan' => 'Kegiatan Inactive',
            'jenis_kegiatan' => 'Workshop',
            'lokasi_kegiatan' => 'Test Lokasi 3',
            'tanggal_mulai' => now()->addDays(10),
            'tanggal_selesai' => now()->addDays(11),
            'jam_mulai' => '10:00',
            'jam_selesai' => '15:00',
            'status_kegiatan' => 'TIDAK AKTIF',
            'kuota_pendaftaran' => 25,
            'deskripsi' => 'Deskripsi Test Kegiatan Inactive',
        ]);

        // This test simulates a potential future functionality where you might want to filter
        // kegiatans based on status. Currently, your controller returns all kegiatans.

        // Access the controller method directly with a hypothetical request to filter active kegiatans
        $request = new \Illuminate\Http\Request(['status' => 'AKTIF']);
        $controller = app()->make('App\Http\Controllers\PelakuKegiatanController');

        // For now, just call the index method without parameters
        // If you implement filtering later, you can modify this line
        $response = $controller->index();

        // Since your current implementation returns all kegiatans,
        // we expect to see both active and inactive kegiatans
        $this->assertEquals(Kegiatan::count(), $response->getData()['kegiatans']->count());

        // If/when you implement filtering, you would change this test to:
        // $this->assertTrue($response->getData()['kegiatans']->contains('status_kegiatan', 'AKTIF'));
        // $this->assertFalse($response->getData()['kegiatans']->contains('status_kegiatan', 'TIDAK AKTIF'));
    }
}