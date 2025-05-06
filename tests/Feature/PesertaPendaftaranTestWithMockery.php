<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Mockery;
use Illuminate\Foundation\Testing\WithFaker;

class PesertaPendaftaranMockTest extends TestCase
{
    use WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Gunakan user admin yang sudah ada
        $this->adminUser = User::where('nik', '1234512345123456')->first();

        if (!$this->adminUser) {
            // Skip test jika tidak ada admin user
            $this->markTestSkipped('Tidak ada user Admin Kantor untuk testing.');
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test login dengan NIK.
     */
    public function test_login_dengan_nik()
    {
        // Kunjungi halaman login
        $response = $this->get('/login');
        $response->assertStatus(200);

        // Proses login dengan kredensial admin
        $response = $this->post('/login', [
            'nik' => '1234512345123456',
            'password' => '12341234',
        ]);

        // Verifikasi redirect ke dashboard admin kantor
        $response->assertRedirect('/dashboard/admin-kantor');

        // Verifikasi user sudah login
        $this->assertAuthenticatedAs($this->adminUser);
    }

    /**
     * Test melihat daftar peserta dengan mock.
     */
    public function test_melihat_daftar_peserta_dengan_mock()
    {
        // Login sebagai admin
        $this->actingAs($this->adminUser);

        // Buat mock kegiatan
        $kegiatan = Mockery::mock('stdClass');
        $kegiatan->id = 1;
        $kegiatan->nama_kegiatan = 'Kegiatan Test';

        // Buat mock intervensis collection
        $intervensis = collect([
            (object)[
                'id' => 1,
                'kegiatan_id' => 1,
                'status' => 'Terdaftar',
                'dataUmkm' => (object)[
                    'nama_umkm' => 'UMKM Test',
                    'pelakuUmkm' => (object)['name' => 'Pelaku Test']
                ]
            ]
        ]);

        // Mock Kegiatan model
        $kegiatanModel = Mockery::mock('overload:App\Models\Kegiatan');
        $kegiatanModel->shouldReceive('findOrFail')
            ->with('1')
            ->andReturn($kegiatan);

        // Mock Intervensi query builder
        $mockQuery = Mockery::mock('Illuminate\Database\Eloquent\Builder');
        $mockQuery->shouldReceive('where')
            ->with('kegiatan_id', '1')
            ->andReturnSelf();
        $mockQuery->shouldReceive('with')
            ->with(['dataUmkm', 'dataUmkm.pelakuUmkm'])
            ->andReturnSelf();
        $mockQuery->shouldReceive('get')
            ->andReturn($intervensis);

        // Mock Intervensi model
        $intervensiModel = Mockery::mock('overload:App\Models\Intervensi');
        $intervensiModel->shouldReceive('where')
            ->andReturn($mockQuery);

        // Akses halaman daftar peserta
        $response = $this->get('/datakegiatan/1/pendaftar');

        // Periksa response
        $response->assertStatus(200);
    }

    /**
     * Test update status kehadiran dengan mock.
     */
    public function test_update_status_dengan_mock()
    {
        // Login sebagai admin
        $this->actingAs($this->adminUser);

        // Buat mock intervensi
        $intervensi = Mockery::mock('stdClass');
        $intervensi->id = 1;
        $intervensi->kegiatan_id = 1;
        $intervensi->status = 'Terdaftar';
        $intervensi->save = function() { return true; };

        // Mock Intervensi model
        $intervensiModel = Mockery::mock('overload:App\Models\Intervensi');
        $intervensiModel->shouldReceive('findOrFail')
            ->with('1')
            ->andReturn($intervensi);

        // Kirim request update status
        $response = $this->patch('/pendaftar/1/update-status', [
            'status' => 'Hadir'
        ]);

        // Periksa status diubah
        $this->assertEquals('Hadir', $intervensi->status);
    }
}