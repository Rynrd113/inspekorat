<?php

namespace Tests\Unit\Repositories;

use App\Models\Pelayanan;
use App\Repositories\PelayananRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class PelayananRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new PelayananRepository();
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

        // Act
        $pelayanan = $this->repository->create($data);

        // Assert
        $this->assertInstanceOf(Pelayanan::class, $pelayanan);
        $this->assertEquals('Test Pelayanan', $pelayanan->nama);
        $this->assertDatabaseHas('pelayanan', ['nama' => 'Test Pelayanan']);
    }

    /** @test */
    public function it_can_find_pelayanan_by_id()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create(['nama' => 'Test Pelayanan']);

        // Act
        $found = $this->repository->find($pelayanan->id);

        // Assert
        $this->assertInstanceOf(Pelayanan::class, $found);
        $this->assertEquals('Test Pelayanan', $found->nama);
    }

    /** @test */
    public function it_can_update_pelayanan()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create(['nama' => 'Original Name']);
        $data = ['nama' => 'Updated Name'];

        // Act
        $updated = $this->repository->update($pelayanan, $data);

        // Assert
        $this->assertInstanceOf(Pelayanan::class, $updated);
        $this->assertEquals('Updated Name', $updated->nama);
        $this->assertDatabaseHas('pelayanan', ['nama' => 'Updated Name']);
    }

    /** @test */
    public function it_can_delete_pelayanan()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create();

        // Act
        $result = $this->repository->delete($pelayanan);

        // Assert
        $this->assertTrue($result);
        $this->assertSoftDeleted('pelayanan', ['id' => $pelayanan->id]);
    }

    /** @test */
    public function it_can_get_all_pelayanan()
    {
        // Arrange
        Pelayanan::factory()->count(3)->create();

        // Act
        $pelayanan = $this->repository->all();

        // Assert
        $this->assertCount(3, $pelayanan);
    }

    /** @test */
    public function it_can_paginate_pelayanan()
    {
        // Arrange
        Pelayanan::factory()->count(15)->create();

        // Act
        $paginated = $this->repository->paginate(10);

        // Assert
        $this->assertEquals(10, $paginated->perPage());
        $this->assertEquals(15, $paginated->total());
    }

    /** @test */
    public function it_can_search_pelayanan()
    {
        // Arrange
        Pelayanan::factory()->create(['nama' => 'Pelayanan Testing']);
        Pelayanan::factory()->create(['nama' => 'Other Service']);

        // Act
        $results = $this->repository->search('Testing');

        // Assert
        $this->assertCount(1, $results);
        $this->assertEquals('Pelayanan Testing', $results->first()->nama);
    }

    /** @test */
    public function it_can_find_by_status()
    {
        // Arrange
        Pelayanan::factory()->create(['status' => 'aktif']);
        Pelayanan::factory()->create(['status' => 'nonaktif']);

        // Act
        $active = $this->repository->findByStatus('aktif');

        // Assert
        $this->assertCount(1, $active);
        $this->assertEquals('aktif', $active->first()->status);
    }

    /** @test */
    public function it_caches_pelayanan_data()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create();
        Cache::shouldReceive('remember')
            ->once()
            ->with("pelayanan.{$pelayanan->id}", 3600, \Closure::class)
            ->andReturn($pelayanan);

        // Act
        $result = $this->repository->find($pelayanan->id);

        // Assert
        $this->assertInstanceOf(Pelayanan::class, $result);
    }

    /** @test */
    public function it_clears_cache_on_update()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create();
        Cache::shouldReceive('forget')
            ->once()
            ->with("pelayanan.{$pelayanan->id}");

        // Act
        $this->repository->update($pelayanan, ['nama' => 'Updated']);

        // Assert - Cache forget should be called
        $this->assertTrue(true);
    }
}
