<?php

namespace Feature\Api;

use App\Models\CarBrand;
use App\Models\CarModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_car_models()
    {
        CarModel::factory()->count(3)->create();

        $response = $this->getJson('/api/car-models');

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
    public function it_can_filter_car_models_by_title()
    {
        $model1 = CarModel::factory()->create(['title' => 'Corolla']);
        $model2 = CarModel::factory()->create(['title' => 'Focus']);
        CarModel::factory()->create(['title' => 'X5']);

        $response = $this->getJson('/api/car-models?title=Cor');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $model1->id)
            ->assertJsonPath('data.0.title', 'Corolla');
    }

    /** @test */
    public function it_returns_404_when_no_models_found()
    {
        $response = $this->getJson('/api/car-models');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_create_a_car_model()
    {
        $brand = CarBrand::factory()->create();
        $data = [
            'title' => 'New Model',
            'brand_id' => $brand->id
        ];

        $response = $this->postJson('/api/car-models', $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'New Model'
                ]
            ]);

        $this->assertDatabaseHas('car_models', ['title' => 'New Model']);
    }

    /** @test */
    public function it_validates_store_request()
    {
        $response = $this->postJson('/api/car-models', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'brand_id']);
    }

    /** @test */
    public function it_can_show_a_car_model()
    {
        $model = CarModel::factory()->create();

        $response = $this->getJson("/api/car-models/{$model->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $model->id,
                    'title' => $model->title
                ]
            ]);
    }

    /** @test */
    public function it_includes_brand_when_showing_model()
    {
        $model = CarModel::factory()
            ->for(CarBrand::factory(), 'brand')
            ->create();

        $response = $this->getJson("/api/car-models/{$model->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'createdAt',
                    'brand' => ['id', 'title', 'createdAt']
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_when_model_not_found()
    {
        $response = $this->getJson('/api/car-models/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_car_model()
    {
        $model = CarModel::factory()->create(['title' => 'Old Title']);
        $newBrand = CarBrand::factory()->create();

        $response = $this->putJson("/api/car-models/{$model->id}", [
            'title' => 'New Title',
            'brand_id' => $newBrand->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $model->id,
                    'title' => 'New Title'
                ]
            ]);

        $this->assertDatabaseHas('car_models', [
            'id' => $model->id,
            'title' => 'New Title',
            'brand_id' => $newBrand->id
        ]);
    }

    /** @test */
    public function it_validates_update_request()
    {
        $model = CarModel::factory()->create();

        $response = $this->putJson("/api/car-models/{$model->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'brand_id']);
    }

    /** @test */
    public function it_can_delete_a_car_model()
    {
        $model = CarModel::factory()->create();

        $response = $this->deleteJson("/api/car-models/{$model->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Car model has been deleted!']);

        $this->assertDatabaseMissing('car_models', ['id' => $model->id]);
    }

    /** @test */
    public function it_returns_404_when_deleting_non_existent_model()
    {
        $response = $this->deleteJson('/api/car-models/999');

        $response->assertStatus(404);
    }
}

