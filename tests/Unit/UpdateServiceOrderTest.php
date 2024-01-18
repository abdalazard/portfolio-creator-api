<?php

namespace Tests\Unit;

use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateServiceOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_validates_request_data()
    {
        // Arrange
        $user = User::factory()->create();
        $serviceOrder = ServiceOrder::factory()->create();

        // Act
        $response = $this->actingAs($user)->putJson("/api/service-order/{$serviceOrder->id}", []);

        // Assert
        $response->assertStatus(Response::HTTP_OK)
        ->assertJsonMissingValidationErrors([
            'vehiclePlate',
            'price',
        ]);
    }

    public function test_update_updates_service_order()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'vehiclePlate' => 'ABC1234',
            'entryDateTime' => now()->format('Y-m-d H:i:s'),
            'exitDateTime' => now()->tomorrow()->format('Y-m-d H:i:s'),
            'priceType' => 'hourly',
            'price' => 50.00,
            'userId' => $user->id,
        ];
        $serviceOrder = ServiceOrder::create($data);

        $updated = [
            'vehiclePlate' => 'DEF4568',
            'price' => 90.00,
        ];

        // Act
        $response = $this->actingAs($user)->putJson("/api/service-order/{$serviceOrder->id}", $updated);

        // Assert
        
        $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'message' => 'Service order updated successfully',
            [
            'id' => $serviceOrder->id,
            'vehiclePlate' => $updated['vehiclePlate'],
            'entryDateTime' => $serviceOrder->entryDateTime,
            'exitDateTime' => $serviceOrder->exitDateTime,
            'priceType' => $serviceOrder->priceType,
            'price' => $updated['price'],
            'userId' => $serviceOrder->userId,
            ],
        ]);

    $this->assertDatabaseHas('service_orders', $updated);

        $this->assertDatabaseHas('service_orders', $updated);
    }

    public function test_update_returns_not_found_error()
    {
        // Arrange
        $user = User::factory()->create();
        $nonExistingServiceOrderId = 999;

        // Act
        $response = $this->actingAs($user)->putJson("/api/service-order/{$nonExistingServiceOrderId}", []);

        // Assert
        $response->assertStatus(Response::HTTP_NOT_FOUND)
        ->assertJson(['message' => 'Service order not updated.']);
    }

    public function test_only_vehiclePlate_and_price_can_be_updated()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'vehiclePlate' => 'ABC1234',
            'entryDateTime' => now()->format('Y-m-d H:i:s'),
            'exitDateTime' => now()->tomorrow()->format('Y-m-d H:i:s'),
            'priceType' => 'hourly',
            'price' => 50.00,
            'userId' => $user->id,
        ];
        $serviceOrder = ServiceOrder::create($data);

        $updated = [
            'vehiclePlate' => 'XYZ7890',
            'entryDateTime' => now()->addDays(2)->format('Y-m-d H:i:s'), 
            'exitDateTime' => now()->addDays(3)->format('Y-m-d H:i:s'), 
            'priceType' => 'daily',
            'price' => 90.00,
        ];

        // Act
        $response = $this->actingAs($user)->putJson("/api/service-order/{$serviceOrder->id}", $updated);

        // Assert
        $response->assertStatus(Response::HTTP_OK)
        ->assertJson(['message' => 'Service order updated successfully']);

        $this->assertDatabaseHas('service_orders', [
            'id' => $serviceOrder->id,
            'vehiclePlate' => 'XYZ7890',
            'price' => 90.00,
        ]);

        $this->assertDatabaseHas('service_orders', [
            'id' => $serviceOrder->id,
            'vehiclePlate' => $updated['vehiclePlate'],
            'entryDateTime' => $data['entryDateTime'],
            'exitDateTime' => $data['exitDateTime'],
            'priceType' => $data['priceType'],
            'price' => $updated['price'],
        ]);
    }
}