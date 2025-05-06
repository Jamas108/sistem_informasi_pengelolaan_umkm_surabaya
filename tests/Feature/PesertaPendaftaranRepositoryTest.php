<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Repositories\Interfaces\IntervensiRepositoryInterface;
use App\Repositories\Interfaces\KegiatanRepositoryInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;

class PesertaPendaftaranRepositoryTest extends TestCase
{
    use WithFaker;

    protected $adminUser;
    protected $kegiatanRepository;
    protected $intervensiRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat mock user
        $this->adminUser = Mockery::mock(User::class)->makePartial();
        $this->adminUser->id = 1;
        $this->adminUser->role = 'adminkantor';

        // Mock repository
        $this->kegiatanRepository = Mockery::mock(KegiatanRepositoryInterface::class);
        $this->intervensiRepository = Mockery::mock(IntervensiRepositoryInterface::class);

        // Binding mock repositories ke container
        $this->app->instance(KegiatanRepositoryInterface::class, $this->kegiatanRepository);
        $this->app->instance(IntervensiRepositoryInterface::class, $this->intervensiRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test melihat daftar peserta dengan repository pattern.
     */
    public function test_dapat_melihat_daftar_peserta_dengan_repository()
    {
        // Data dummy untuk mocking
        $kegiatanId = 1;
        $kegiatan = (object)[
            'id' => $kegiatanId,
            'nama_kegiatan' => 'Kegiatan Test Repository'
        ];

        $intervensis = [
            (object)[
                'id' => 1,
                'kegiatan_id' => $kegiatanId,
                'status' => 'Terdaftar',
                'dataUmkm' => (object)[
                    'nama_umkm' => 'UMKM Test',
                    'pelakuUmkm' => (object)[
                        'name' => 'Pelaku UMKM Test'
                    ]
                ]
            ]
        ];

        // Set expectation repository
        $this->kegiatanRepository->shouldReceive('findById')
            ->with($kegiatanId)
            ->once()
            ->andReturn($kegiatan);

        $this->intervensiRepository->shouldReceive('getByKegiatanId')
            ->with($kegiatanId)
            ->once()
            ->andReturn($intervensis);

        // Bind PesertaPendaftaranController dengan mock repositories
        $this->app->bind('App\Http\Controllers\PesertaPendaftaranController', function ($app) {
            return new \App\Http\Controllers\PesertaPendaftaranController(
                $app->make(KegiatanRepositoryInterface::class),
                $app->make(IntervensiRepositoryInterface::class)
            );
        });

        // Login sebagai admin
        $this->actingAs($this->adminUser);

        // Akses halaman daftar peserta
        $response = $this->get('/datakegiatan/' . $kegiatanId . '/pendaftar');

        // Periksa response
        $response->assertStatus(200);
    }

    /**
     * Test update status kehadiran dengan repository pattern.
     */
    public function test_dapat_update_status_dengan_repository()
    {
        // Data dummy
        $intervensiId = 1;
        $newStatus = 'Hadir';

        // Set expectation repository
        $this->intervensiRepository->shouldReceive('findById')
            ->with($intervensiId)
            ->once()
            ->andReturn((object)[
                'id' => $intervensiId,
                'kegiatan_id' => 1,
                'status' => 'Terdaftar'
            ]);

        $this->intervensiRepository->shouldReceive('updateStatus')
            ->with($intervensiId, $newStatus)
            ->once()
            ->andReturn(true);

        // Login sebagai admin
        $this->actingAs($this->adminUser);

        // Kirim request update status
        $response = $this->patch('/pendaftar/' . $intervensiId . '/update-status', [
            'status' => $newStatus
        ]);

        // Periksa response
        $response->assertRedirect();
    }
}