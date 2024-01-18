<?php

namespace Tests\Unit;

use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteServiceOrderTest extends TestCase
{
    /**
     * A basic test example.
     */
    use RefreshDatabase;

    public function test_delete_service_order(): void
    {
        // Arrange
        $user = User::factory()->create();
        $serviceOrder = ServiceOrder::factory()->create();

        // Act
        $response = $this->actingAs($user)->deleteJson('/api/service-order/' . $serviceOrder->id);

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Service order deleted successfully'
            ]);

        $this->assertDatabaseMissing('service_orders', ['id' => $serviceOrder->id]);
    }

    public function test_delete_non_existing_service_order(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->deleteJson('/api/service-order/999');

        // Assert
        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Service order not found']);
    }
}
