<?php

namespace Tests\Unit;

use App\Models\Contacts;
use App\Models\Others;
use App\Models\Profile;
use App\Models\Projects;
use App\Models\Skills;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;
use Tests\TestCase;

class GetPortfolioTest extends TestCase
{
    use RefreshDatabase;

    // public function test_index_returns_service_orders_paginated()
    // {
    //     // Arrange
    //     $user = User::factory()->create();
    //     ServiceOrder::factory()->count(15)->create();

    //     // Act
    //     $response = $this->actingAs($user)->getJson('/api/service-orders');

    //     // Assert
    //     $response->assertStatus(Response::HTTP_OK)
    //         ->assertJsonCount(5, 'data')
    //         ->assertJsonStructure([
    //             'data' => [
    //                 '*' => [
    //                     'id',
    //                     'vehiclePlate',
    //                     'entryDateTime',
    //                     'exitDateTime',
    //                     'priceType',
    //                     'price',
    //                     'userId'
    //                 ],
    //             ],
    //         ]);
    // }

    // public function test_index_filters_service_orders_by_vehicle_plate()
    // {
    //     // Arrange
    //     $user = User::factory()->create();
    //     $serviceOrder1 = ServiceOrder::factory()->create(['vehiclePlate' => 'ABC123']);
    //     $serviceOrder2 = ServiceOrder::factory()->create(['vehiclePlate' => 'DEF456']);
    //     $serviceOrder3 = ServiceOrder::factory()->create(['vehiclePlate' => 'GHI789']);

    //     // Act
    //     $response = $this->actingAs($user)->getJson('/api/service-orders?vehiclePlate=DEF456');

    //     // Assert
    //     $response->assertStatus(Response::HTTP_OK)
    //         ->assertJsonCount(1, 'data')
    //         ->assertJsonFragment(['id' => $serviceOrder2->id])
    //         ->assertJsonMissing(['id' => $serviceOrder1->id])
    //         ->assertJsonMissing(['id' => $serviceOrder3->id]);
    // }

    public function test_index_returns_documentation() {
        
        $response = $this->getJson('/');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_get_user_portfolio() {
        
        // Arrange
        $user = User::factory()->create();        
        Status::create([
            'id_user' => $user->id,
            'profile' => true,
            'projects' => true,
            'skills' => true,
            'others' => true,
            'contacts' => true,
            'is_published' => true
        ]);

        $profile = Profile::factory()->create(['id_user' => $user->id]);
        $user->status->profile = true;
        $user->status->save();

        $projects = Projects::factory()->count(2)->create(['id_user' => $user->id]);
        $user->status->projects = true;
        $user->status->save();

        $skills = Skills::factory()->count(2)->create(['id_user' => $user->id]);
        $user->status->skills = 1;
        $user->status->save();

        $others = Others::factory()->count(2)->create(['id_user' => $user->id]);
        $user->status->others = true;
        $user->status->save();

        $contacts = Contacts::factory()->create(['id_user' => $user->id]);
        $user->status->contacts = true;
        $user->status->save();

        if($user->status->profile == true && $user->status->projects == true && $user->status->skills == true && $user->status->others == true && $user->status->contacts == true) {
            $user->status->is_published = true;
            $user->status->save();
        }

        // Act
        $response = $this->getJson("/api/get-portfolio/$user->id");

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'user' => [
                'id' => $user->id,
                'status' => [
                    'id_user' => $user->id,
                    'is_published' => $user->status->is_published,
                ],
                'profile' => $profile->toArray(),
                'projects' => $projects->toArray(),
                'skills' => $skills->toArray(),
                'others' => $others->toArray(),
                'contacts' => $contacts->toArray()
            ]
        ]);

    }
    
}