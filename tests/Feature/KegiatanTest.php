<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Intervensi;
use App\Models\Umkm;
use App\Models\PelakuUmkm;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Mockery;

class KegiatanTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    protected $adminUser;
    protected $kegiatan;
    protected $pelakuUmkm;
    protected $umkm;
    protected $intervensi;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->adminUser = User::create([
            'username' => 'admin_test',
            'nik' => $this->faker->unique()->numerify('################'),
            'password' => bcrypt('password123'),
            'role' => 'adminkantor',
        ]);

        // Create a kegiatan
        $this->kegiatan = Kegiatan::create([
            'nama_kegiatan' => 'Kegiatan Test',
            'jenis_kegiatan' => 'Pelatihan',
            'lokasi_kegiatan' => 'Test Lokasi',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDays(1),
            'jam_mulai' => '08:00',
            'jam_selesai' => '16:00',
            'status_kegiatan' => 'Pendaftaran',
            'kuota_pendaftaran' => 50,
            'deskripsi' => 'Deskripsi Test Kegiatan',
        ]);

        // Create a pelaku UMKM
        $this->pelakuUmkm = PelakuUmkm::create([
            'users_id' => $this->adminUser->id, // Using admin for simplicity
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

        // Create UMKM
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

        // Create intervensi
        $this->intervensi = Intervensi::create([
            'umkm_id' => $this->umkm->id,
            'kegiatan_id' => $this->kegiatan->id,
            'no_pendaftaran_kegiatan' => 'REG'.time(),
            'omset' => 5000000.00,
            'dokumentasi_kegiatan' => ['foto1.jpg', 'foto2.jpg'],
        ]);

        // Mock Storage facade for file operations
        Storage::fake('public');
    }

    /**
     * Test index method.
     */
    public function test_index_displays_all_kegiatans()
    {
        // Authenticate as admin
        $this->actingAs($this->adminUser);

        // Access the index route
        $response = $this->get(route('datakegiatan.index'));

        // Assert successful response
        $response->assertStatus(200);

        // Assert the view is correct
        $response->assertViewIs('adminkantor.datakegiatan.index');

        // Assert the view has kegiatan data
        $response->assertViewHas('kegiatans');

        // Assert the kegiatan is in the view data
        $response->assertSee('Kegiatan Test');
    }

    /**
     * Test create method.
     */
    public function test_create_displays_create_form()
    {
        // Authenticate as admin
        $this->actingAs($this->adminUser);

        // Access the create route
        $response = $this->get(route('datakegiatan.create'));

        // Assert successful response
        $response->assertStatus(200);

        // Assert the view is correct
        $response->assertViewIs('adminkantor.datakegiatan.create');
    }

    /**
     * Test store method.
     */
    public function test_store_creates_new_kegiatan()
    {
        // Authenticate as admin
        $this->actingAs($this->adminUser);

        // Create a fake image for testing
        $file = UploadedFile::fake()->image('poster.jpg');

        // Prepare data for the request
        $kegiatanData = [
            'nama_kegiatan' => 'Kegiatan Baru',
            'jenis_kegiatan' => 'Seminar',
            'lokasi_kegiatan' => 'Lokasi Baru',
            'tanggal_mulai' => now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(6)->format('Y-m-d'),
            'jam_mulai' => '09:00',
            'jam_selesai' => '17:00',
            'poster' => $file,
            'status_kegiatan' => 'Pendaftaran',
            'kuota_pendaftaran' => 30,
            'deskripsi' => 'Deskripsi Kegiatan Baru'
        ];

        // Send POST request to store
        $response = $this->post(route('datakegiatan.store'), $kegiatanData);

        // Assert redirection
        $response->assertRedirect(route('datakegiatan.index'));

        // Assert success message
        $response->assertSessionHas('success', 'Kegiatan berhasil ditambahkan.');

        // Assert the kegiatan was created in the database
        $this->assertDatabaseHas('kegiatan', [
            'nama_kegiatan' => 'Kegiatan Baru',
            'jenis_kegiatan' => 'Seminar',
            'lokasi_kegiatan' => 'Lokasi Baru'
        ]);

        // Assert the poster was stored
        $kegiatan = Kegiatan::where('nama_kegiatan', 'Kegiatan Baru')->first();
        $this->assertNotNull($kegiatan->poster);
        Storage::disk('public')->assertExists($kegiatan->poster);
    }

    /**
     * Test show method.
     */
    public function test_show_displays_kegiatan_details()
    {
        // Authenticate as admin
        $this->actingAs($this->adminUser);

        // Access the show route
        $response = $this->get(route('datakegiatan.show', $this->kegiatan->id));

        // Assert successful response
        $response->assertStatus(200);

        // Assert the view is correct
        $response->assertViewIs('adminkantor.datakegiatan.show');

        // Assert the view has kegiatan and intervensis data
        $response->assertViewHas(['kegiatan', 'intervensis']);

        // Assert the kegiatan name is visible
        $response->assertSee('Kegiatan Test');
    }

    /**
     * Test edit method.
     */
    public function test_edit_displays_edit_form()
    {
        // Authenticate as admin
        $this->actingAs($this->adminUser);

        // Access the edit route
        $response = $this->get(route('datakegiatan.edit', $this->kegiatan->id));

        // Assert successful response
        $response->assertStatus(200);

        // Assert the view is correct
        $response->assertViewIs('adminkantor.datakegiatan.edit');

        // Assert the view has kegiatan data
        $response->assertViewHas('kegiatan');

        // Assert the kegiatan name is visible
        $response->assertSee('Kegiatan Test');
    }

    /**
     * Test update method.
     */
    public function test_update_updates_kegiatan()
    {
        // Authenticate as admin
        $this->actingAs($this->adminUser);

        // Create a fake image for testing
        $file = UploadedFile::fake()->image('new_poster.jpg');

        // Prepare updated data
        $updatedData = [
            'nama_kegiatan' => 'Kegiatan Updated',
            'jenis_kegiatan' => 'Workshop',
            'tanggal_mulai' => now()->addDays(7)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(8)->format('Y-m-d'),
            'jam_mulai' => '10:00',
            'jam_selesai' => '18:00',
            'poster' => $file,
            'status_kegiatan' => 'Pendaftaran'
        ];

        // Send PUT request to update
        $response = $this->put(route('datakegiatan.update', $this->kegiatan->id), $updatedData);

        // Assert redirection
        $response->assertRedirect(route('datakegiatan.index'));

        // Assert success message
        $response->assertSessionHas('success', 'Kegiatan berhasil diperbarui.');

        // Reload the kegiatan from database
        $this->kegiatan->refresh();

        // Assert the kegiatan was updated
        $this->assertEquals('Kegiatan Updated', $this->kegiatan->nama_kegiatan);
        $this->assertEquals('Workshop', $this->kegiatan->jenis_kegiatan);
    }

    /**
     * Test destroy method.
     */
    public function test_destroy_deletes_kegiatan()
    {
        // Authenticate as admin
        $this->actingAs($this->adminUser);

        // First, update the kegiatan with a poster
        $file = UploadedFile::fake()->image('poster_to_delete.jpg');
        $posterPath = $file->store('posters', 'public');

        $this->kegiatan->poster = $posterPath;
        $this->kegiatan->save();

        // Send DELETE request
        $response = $this->delete(route('datakegiatan.destroy', $this->kegiatan->id));

        // Assert redirection
        $response->assertRedirect(route('datakegiatan.index'));

        // Assert success message
        $response->assertSessionHas('success', 'Kegiatan berhasil dihapus.');

        // Assert the kegiatan was deleted from database
        $this->assertDatabaseMissing('kegiatan', ['id' => $this->kegiatan->id]);

        // Assert the poster was deleted
        Storage::disk('public')->assertMissing($posterPath);
    }

    /**
     * Test generateBuktiPendaftaran method.
     *
     * This test uses a different approach without directly mocking the PDF facade
     * to avoid the Mockery redeclaration error.
     */
/**
 * Test generateBuktiPendaftaran method using Storage spy.
 */
/**
 * Test generateBuktiPendaftaran method.
 */
/**
 * Test generateBuktiPendaftaran method.
 */
public function test_generate_bukti_pendaftaran()
{
    // Update kegiatan to have the right status
    $this->kegiatan->status_kegiatan = 'Persiapan Acara';
    $this->kegiatan->save();

    // Login sebagai admin kantor
    $this->actingAs($this->adminUser);

    // Use a fake storage disk to avoid actually writing files
    Storage::fake('public');

    // Hit the route with the kegiatan ID parameter using POST
    // Using a generic route pattern based on your controller method
    $response = $this->post(route('datakegiatan.generate-bukti', ['id' => $this->kegiatan->id]));

    // Assert redirect to index page
    $response->assertRedirect(route('datakegiatan.index'));

    // Assert success message exists
    $response->assertSessionHas('success');

    // Refresh the kegiatan from database
    $this->kegiatan->refresh();

    // Assert bukti_pendaftaran_path was updated
    $this->assertNotNull($this->kegiatan->bukti_pendaftaran_path);

    // Assert the directory was created in storage
    Storage::disk('public')->assertExists($this->kegiatan->bukti_pendaftaran_path);
}


    /**
     * Clean up after testing.
     */
    protected function tearDown(): void
    {
        // Remove any test files
        Storage::disk('public')->deleteDirectory('posters');
        Storage::disk('public')->deleteDirectory('bukti-pendaftaran');

        parent::tearDown();
    }
}