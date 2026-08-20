<?php

namespace Tests\Unit\Services;

use App\Models\Pelayanan;
use App\Repositories\Contracts\PelayananRepositoryInterface;
use App\Services\Implementation\PelayananService;
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

    protected function mockStoreRequest(array $data, bool $hasFile = false)
    {
        $request = Mockery::mock(\App\Http\Requests\StorePelayananRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($data);
        $request->shouldReceive('has')->once()->with('status')->andReturn(false);
        $request->shouldReceive('hasFile')->with('file_formulir')->andReturn($hasFile);

        return $request;
    }

    protected function mockUpdateRequest(array $data, bool $hasFile = false)
    {
        $request = Mockery::mock(\App\Http\Requests\UpdatePelayananRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($data);
        $request->shouldReceive('has')->once()->with('status')->andReturn(false);
        $request->shouldReceive('hasFile')->with('file_formulir')->andReturn($hasFile);

        return $request;
    }

    /** @test */
    public function it_can_create_pelayanan()
    {
        // Arrange
        $data = [
            'nama' => 'Test Pelayanan',
            'deskripsi' => 'Test deskripsi',
            'persyaratan' => 'Test persyaratan',
            'waktu_penyelesaian' => '2 hari',
            'biaya' => 'Gratis',
        ];

        $expectedData = array_merge($data, ['created_by' => null, 'status' => false]);

        $pelayanan = new Pelayanan($expectedData);
        $pelayanan->id = 1;

        $this->pelayananRepository->shouldReceive('create')
            ->once()
            ->with($expectedData)
            ->andReturn($pelayanan);

        $request = $this->mockStoreRequest($data);

        // Act
        $result = $this->pelayananService->createPelayanan($request);

        // Assert
        $this->assertInstanceOf(Pelayanan::class, $result);
        $this->assertEquals('Test Pelayanan', $result->nama);
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

        $expectedData = array_merge($data, ['updated_by' => null, 'status' => false]);

        $this->pelayananRepository->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($pelayanan);

        $this->pelayananRepository->shouldReceive('update')
            ->once()
            ->with(1, $expectedData)
            ->andReturn(true);

        $request = $this->mockUpdateRequest($data);

        // Act
        $result = $this->pelayananService->updatePelayanan(1, $request);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_delete_pelayanan()
    {
        // Arrange
        Event::fake();

        $pelayanan = new Pelayanan(['id' => 1, 'nama' => 'Test']);

        $this->pelayananRepository->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($pelayanan);

        $this->pelayananRepository->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        // Act
        $result = $this->pelayananService->deletePelayanan(1);

        // Assert
        $this->assertTrue($result);
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
        ];

        $pelayanan = new Pelayanan([
            'nama' => 'Test Pelayanan',
            'deskripsi' => 'Test deskripsi',
            'file_formulir' => 'pelayanan/formulir/' . $file->hashName(),
            'created_by' => null,
            'status' => false,
        ]);
        $pelayanan->id = 1;

        $this->pelayananRepository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return isset($arg['file_formulir']) && str_contains($arg['file_formulir'], 'pelayanan/formulir/');
            }))
            ->andReturn($pelayanan);

        $request = Mockery::mock(\App\Http\Requests\StorePelayananRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($data);
        $request->shouldReceive('has')->once()->with('status')->andReturn(false);
        $request->shouldReceive('hasFile')->with('file_formulir')->andReturn(true);
        $request->shouldReceive('file')->once()->with('file_formulir')->andReturn($file);

        // Act
        $result = $this->pelayananService->createPelayanan($request);

        // Assert
        $this->assertInstanceOf(Pelayanan::class, $result);
        Storage::disk('public')->assertExists('pelayanan/formulir/' . $file->hashName());
    }

    /** @test */
    public function it_can_get_paginated_pelayanan()
    {
        // Arrange
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            collect(),
            0,
            10,
            1,
            ['path' => url('test')]
        );

        $this->pelayananRepository->shouldReceive('getPaginated')
            ->once()
            ->with(10, [])
            ->andReturn($paginator);

        // Act
        $result = $this->pelayananService->getAllPaginated([], 10);

        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
    }

    /** @test */
    public function it_can_search_pelayanan()
    {
        // Arrange
        $query = 'test';

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            collect([new Pelayanan(['nama' => 'Test'])]),
            1,
            100,
            1,
            ['path' => url('test')]
        );

        $this->pelayananRepository->shouldReceive('getPaginated')
            ->once()
            ->with(100, ['search' => $query])
            ->andReturn($paginator);

        // Act
        $result = $this->pelayananService->searchPelayanan($query);

        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    /** @test */
    public function it_returns_false_when_pelayanan_not_found_on_update()
    {
        // Arrange
        $this->pelayananRepository->shouldReceive('findById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $request = Mockery::mock(\App\Http\Requests\UpdatePelayananRequest::class);

        // Act
        $result = $this->pelayananService->updatePelayanan(999, $request);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function it_returns_false_when_pelayanan_not_found_on_delete()
    {
        // Arrange
        $this->pelayananRepository->shouldReceive('findById')
            ->once()
            ->with(999)
            ->andReturn(null);

        // Act
        $result = $this->pelayananService->deletePelayanan(999);

        // Assert
        $this->assertFalse($result);
    }
}
