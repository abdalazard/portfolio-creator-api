<?php

namespace Tests\Unit;

use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetServiceOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_service_orders_paginated()
    {
        // Arrange
        $user = User::factory()->create();
        ServiceOrder::factory()->count(15)->create();

        // Act
        $response = $this->actingAs($user)->getJson('/api/service-orders');

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'vehiclePlate',
                        'entryDateTime',
                        'exitDateTime',
                        'priceType',
                        'price',
                        'userId'
                    ],
                ],
            ]);
    }

    public function test_index_filters_service_orders_by_vehicle_plate()
    {
        // Arrange
        $user = User::factory()->create();
        $serviceOrder1 = ServiceOrder::factory()->create(['vehiclePlate' => 'ABC123']);
        $serviceOrder2 = ServiceOrder::factory()->create(['vehiclePlate' => 'DEF456']);
        $serviceOrder3 = ServiceOrder::factory()->create(['vehiclePlate' => 'GHI789']);

        // Act
        $response = $this->actingAs($user)->getJson('/api/service-orders?vehiclePlate=DEF456');

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $serviceOrder2->id])
            ->assertJsonMissing(['id' => $serviceOrder1->id])
            ->assertJsonMissing(['id' => $serviceOrder3->id]);
    }

    
}