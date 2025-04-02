<?php

namespace Tests\Feature\Api;

use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarBrandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_car_brands()
    {
        CarBrand::factory()->count(3)->create();

        $response = $this->getJson('/api/car-brands');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'createdAt']
                ],
                'links',
                'meta'
            ]);
    }

    /** @test */
    public function it_can_filter_car_brands_by_title()
    {
        $brand1 = CarBrand::factory()->create(['title' => 'Toyota']);
        $brand2 = CarBrand::factory()->create(['title' => 'Ford']);
        CarBrand::factory()->create(['title' => 'BMW']);

        $response = $this->getJson('/api/car-brands?title=Toy');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $brand1->id)
            ->assertJsonPath('data.0.title', 'Toyota');
    }

    /** @test */
    public function it_returns_404_when_no_brands_found()
    {
        $response = $this->getJson('/api/car-brands');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_create_a_car_brand()
    {
        $data = ['title' => 'New Brand'];

        $response = $this->postJson('/api/car-brands', $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'New Brand'
                ]
            ]);

        $this->assertDatabaseHas('car_brands', ['title' => 'New Brand']);
    }

    /** @test */
    public function it_validates_store_request()
    {
        $response = $this->postJson('/api/car-brands', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function it_can_show_a_car_brand()
    {
        $brand = CarBrand::factory()->create();

        $response = $this->getJson("/api/car-brands/{$brand->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $brand->id,
                    'title' => $brand->title
                ]
            ]);
    }

    /** @test */
    public function it_includes_models_when_showing_brand()
    {
        $brand = CarBrand::factory()
            ->has(CarModel::factory()->count(2), 'models')
            ->create();

        $response = $this->getJson("/api/car-brands/{$brand->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'createdAt',
                    'models' => [
                        '*' => ['id', 'title', 'createdAt']
                    ]
                ]
            ])
            ->assertJsonCount(2, 'data.models');
    }

    /** @test */
    public function it_returns_404_when_brand_not_found()
    {
        $response = $this->getJson('/api/car-brands/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_car_brand()
    {
        $brand = CarBrand::factory()->create(['title' => 'Old Title']);

        $response = $this->putJson("/api/car-brands/{$brand->id}", [
            'title' => 'New Title'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $brand->id,
                    'title' => 'New Title'
                ]
            ]);

        $this->assertDatabaseHas('car_brands', [
            'id' => $brand->id,
            'title' => 'New Title'
        ]);
    }

    /** @test */
    public function it_validates_update_request()
    {
        $brand = CarBrand::factory()->create();

        $response = $this->putJson("/api/car-brands/{$brand->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function it_can_delete_a_car_brand()
    {
        $brand = CarBrand::factory()->create();

        $response = $this->deleteJson("/api/car-brands/{$brand->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Car brand deleted!']);

        $this->assertDatabaseMissing('car_brands', ['id' => $brand->id]);
    }

    /** @test */
    public function it_returns_404_when_deleting_non_existent_brand()
    {
        $response = $this->deleteJson('/api/car-brands/999');

        $response->assertStatus(404);
    }
}

