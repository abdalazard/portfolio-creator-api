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
        
        $user = User::factory()->create();

        $status = Status::create(['id_user' => $user->id]);
        
        $profile = Profile::factory()->create(['id_user' => $user->id]);
        $status->profile = true;
        $status->save();

        $projects = Projects::factory()->count(2)->create(['id_user' => $user->id]);
        $status->projects = true;
        $status->save();

        $skills = Skills::factory()->count(2)->create(['id_user' => $user->id]);
        $status->skills = 1;
        $status->save();

        $others = Others::factory()->count(2)->create(['id_user' => $user->id]);
        $status->others = true;
        $status->save();

        $contacts = Contacts::factory()->create(['id_user' => $user->id]);
        $status->contacts = true;
        $status->save();

        if($status->profile == true && $status->projects == true && $status->skills == true && $status->others == true && $status->contacts == true) {
            $status->is_published = true;
            $status->save();
        }

        // $portfolio = [
        //     'profile' => $profile,
        //     'projects' => $projects,
        //     'skills' => $skills,
        //     'others' => $others,
        //     'contacts' => $contacts
        // ];

        $response = $this->getJson("/api/get-portfolio/$user->id");
        $response->assertStatus(Response::HTTP_OK);
        // $response->assertJsonStructure();
        // $response->assertJson([
        //     [
        //         'id' => $user->id,
                // 'name' => $user->name,
                // 'email' => $user->email,
                // 'profile' => $profile->toArray(),
                // 'projects' => $projects->toArray(),
                // 'skills' => $skills->toArray(),
                // 'others' => $others->toArray(),
                // 'contacts' => $contacts->toArray()
        //     ]
        // ]);
    }
    
}