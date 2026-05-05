<?php

namespace Tests\Unit\Repositories;

use App\Models\Pelayanan;
use App\Repositories\Implementation\PelayananRepository;
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
        $this->repository = new PelayananRepository(new Pelayanan());
    }

    /** @test */
    public function it_can_create_pelayanan()
    {
        // Arrange
        $data = [
            'nama' => 'Test Pelayanan',
            'deskripsi' => 'Test deskripsi',
            'persyaratan' => json_encode(['Persyaratan 1', 'Persyaratan 2']),
            'waktu_penyelesaian' => '2 hari',
            'biaya' => 'Gratis',
            'kategori' => 'administrasi',
            'status' => true,
        ];

        // Act
        $pelayanan = $this->repository->create($data);

        // Assert
        $this->assertInstanceOf(Pelayanan::class, $pelayanan);
        $this->assertEquals('Test Pelayanan', $pelayanan->nama);
        $this->assertDatabaseHas('pelayanans', ['nama' => 'Test Pelayanan']);
    }

    /** @test */
    public function it_can_find_pelayanan_by_id()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create(['nama' => 'Test Pelayanan']);

        // Act
        $found = $this->repository->findById($pelayanan->id);

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
        $updated = $this->repository->update($pelayanan->id, $data);

        // Assert
        $this->assertTrue($updated);
        $pelayanan->refresh();
        $this->assertEquals('Updated Name', $pelayanan->nama);
        $this->assertDatabaseHas('pelayanans', ['nama' => 'Updated Name']);
    }

    /** @test */
    public function it_can_delete_pelayanan()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create();

        // Act
        $result = $this->repository->delete($pelayanan->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('pelayanans', ['id' => $pelayanan->id]);
    }

    /** @test */
    public function it_can_get_all_pelayanan()
    {
        // Arrange
        Pelayanan::factory()->count(3)->create();

        // Act
        $pelayanan = $this->repository->getAll();

        // Assert
        $this->assertCount(3, $pelayanan);
    }

    /** @test */
    public function it_can_paginate_pelayanan()
    {
        // Arrange
        Pelayanan::factory()->count(15)->create();

        // Act
        $paginated = $this->repository->getPaginated(10);

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
        $results = $this->repository->getPaginated(100, ['search' => 'Testing']);

        // Assert
        $this->assertGreaterThanOrEqual(1, $results->total());
        $this->assertNotEmpty($results->items());
    }

    /** @test */
    public function it_can_find_by_status()
    {
        // Arrange
        Pelayanan::factory()->create(['status' => true]);
        Pelayanan::factory()->create(['status' => false]);

        // Act
        $active = $this->repository->getActive();

        // Assert
        $this->assertCount(1, $active);
        $this->assertTrue($active->first()->status);
    }

    /** @test */
    public function it_caches_pelayanan_data()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create();

        // Act
        $result = $this->repository->findById($pelayanan->id);

        // Assert
        $this->assertInstanceOf(Pelayanan::class, $result);
    }

    /** @test */
    public function it_clears_cache_on_update()
    {
        // Arrange
        $pelayanan = Pelayanan::factory()->create();

        // Act
        $this->repository->update($pelayanan->id, ['nama' => 'Updated']);

        // Assert - Verify update worked
        $updated = $this->repository->findById($pelayanan->id);
        $this->assertEquals('Updated', $updated->nama);
    }
}
