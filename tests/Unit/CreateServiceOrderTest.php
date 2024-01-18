<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateServiceOrderTest extends TestCase
{
    /**
     * A basic test example.
     */
    use RefreshDatabase;

    public function test_create_service_order() {
        //Arrange
        $authUser = $this->user;
 
         $data = [
             'vehiclePlate' => 'ABC1234',
             'entryDateTime' => now()->format('Y-m-d H:i:s'),
             'exitDateTime' => now()->format('Y-m-d H:i:s'),
             'priceType' => 'Hourly',
             'price' => '100.00',
             'userId' => $authUser->id,
         ];
 
        //Act
        $response = $this->actingAs($authUser)->postJson('/api/service-order', $data);
 
        //Assert 
         $response->assertStatus(201);
     }

     public function test_create_creates_and_returns_service_order_when_valid_data_provided()
    {
        // Arrange
        $user = User::factory()->create();
        $serviceOrderData = [
            'vehiclePlate' => 'ABC1234',
            'entryDateTime' => now()->format('Y-m-d H:i:s'),
            'exitDateTime' => now()->addHour()->format('Y-m-d H:i:s'),
            'priceType' => 'Hourly',
            'price' => '100.00',
            'userId' => $user->id,
        ];

        // Act
        $response = $this->actingAs($user)->postJson('/api/service-order', $serviceOrderData);

        // Assert
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'vehiclePlate',
                'entryDateTime',
                'exitDateTime',
                'priceType',
                'price',
                'userId'
            ]);
    }

    public function test_create_returns_error_when_invalid_data_provided()
    {
        // Arrange
        $user = User::factory()->create();
        $serviceOrderData = [
            'vehiclePlate' => '', 
            'entryDateTime' => now()->toString(),
            'exitDateTime' => now()->addHour()->toString(),
            'priceType' => 'Hourly',
            'price' => '100.00',
            'userId' => $user->id,
        ];

        // Act
        $response = $this->actingAs($user)->postJson('/api/service-order', $serviceOrderData);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['message' => 'The vehicle plate field is required.']);
    }
}
