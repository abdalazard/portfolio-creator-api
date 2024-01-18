<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Projects>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_user' => User::factory(),
            'profile-pic' => $this->faker->imageUrl(),
            'title' => $this->faker->name,
            'subtitle' => $this->faker->sentence,
            // outras colunas aqui
        ];
    }
}
