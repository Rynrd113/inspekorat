<?php

namespace Tests\Unit\Services;

use App\Models\Pelayanan;
use App\Repositories\Contracts\PelayananRepositoryInterface;
use App\Services\PelayananService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Mockery;

class PelayananServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $pelayananRepository;
    protected $pelayananService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->pelayananRepository = Mockery::mock(PelayananRepositoryInterface::class);
        $this->pelayananService = new PelayananService($this->pelayananRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_create_pelayanan()
    {
        // Arrange
        $data = [
            'nama' => 'Test Pelayanan',
            'deskripsi' => 'Test deskripsi',
            'persyaratan' => 'Test persyaratan',
            'waktu_pelayanan' => '2 hari',
            'biaya' => 'Gratis',
            'status' => 'aktif',
        ];

        $pelayanan = new Pelayanan($data);
        $pelayanan->id = 1;

        $this->pelayananRepository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($pelayanan);

        Event::fake();

        // Act
        $result = $this->pelayananService->create($data);

        // Assert
        $this->assertInstanceOf(Pelayanan::class, $result);
        $this->assertEquals('Test Pelayanan', $result->nama);
        Event::assertDispatched(\App\Events\PelayananCreated::class);
    }

    /** @test */
    public function it_can_update_pelayanan()
    {
        // Arrange
        $pelayanan = new Pelayanan([
            'id' => 1,
            'nama' => 'Old Name',
            'deskripsi' => 'Old deskripsi',
        ]);

        $data = [
            'nama' => 'Updated Name',
            'deskripsi' => 'Updated deskripsi',
        ];

        $this->pelayananRepository->shouldReceive('update')
            ->once()
            ->with($pelayanan, $data)
            ->andReturn($pelayanan);

        Event::fake();

        // Act
        $result = $this->pelayananService->update($pelayanan, $data);

        // Assert
        $this->assertInstanceOf(Pelayanan::class, $result);
        Event::assertDispatched(\App\Events\PelayananUpdated::class);
    }

    /** @test */
    public function it_can_delete_pelayanan()
    {
        // Arrange
        $pelayanan = new Pelayanan(['id' => 1, 'nama' => 'Test']);

        $this->pelayananRepository->shouldReceive('delete')
            ->once()
            ->with($pelayanan)
            ->andReturn(true);

        Event::fake();

        // Act
        $result = $this->pelayananService->delete($pelayanan);

        // Assert
        $this->assertTrue($result);
        Event::assertDispatched(\App\Events\PelayananDeleted::class);
    }

    /** @test */
    public function it_can_handle_file_upload()
    {
        // Arrange
        Storage::fake('public');
        
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $data = [
            'nama' => 'Test Pelayanan',
            'deskripsi' => 'Test deskripsi',
            'dokumen' => $file,
        ];

        $expectedData = $data;
        $expectedData['dokumen'] = 'pelayanan/test.pdf';

        $pelayanan = new Pelayanan($expectedData);
        $pelayanan->id = 1;

        $this->pelayananRepository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return isset($arg['dokumen']) && str_contains($arg['dokumen'], 'pelayanan/');
            }))
            ->andReturn($pelayanan);

        Event::fake();

        // Act
        $result = $this->pelayananService->create($data);

        // Assert
        $this->assertInstanceOf(Pelayanan::class, $result);
        Storage::disk('public')->assertExists('pelayanan/' . $file->hashName());
    }

    /** @test */
    public function it_can_get_paginated_pelayanan()
    {
        // Arrange
        $this->pelayananRepository->shouldReceive('paginate')
            ->once()
            ->with(10)
            ->andReturn(collect());

        // Act
        $result = $this->pelayananService->getPaginated(10);

        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /** @test */
    public function it_can_search_pelayanan()
    {
        // Arrange
        $query = 'test';
        
        $this->pelayananRepository->shouldReceive('search')
            ->once()
            ->with($query)
            ->andReturn(collect());

        // Act
        $result = $this->pelayananService->search($query);

        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }
}
