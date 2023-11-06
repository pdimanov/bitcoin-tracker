<?php

namespace Tests\Feature\Repository;

use App\Repository\PriceHistoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PriceHistoryRepositoryTest extends TestCase
{
    private PriceHistoryRepository $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new PriceHistoryRepository();
    }

    public function test_example(): void
    {
        $data = [
            'price' => 123,
            'currency' => 'EUR'
        ];
        $this->assertDatabaseMissing('price_histories', $data);

        $this->repository->store($data);

        $this->assertDatabaseHas('price_histories', $data);
    }
}
