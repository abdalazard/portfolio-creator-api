<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ServiceOrderFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $entryDateTime = now();
        $exitDateTime = $entryDateTime->copy()->addHours(rand(1, 5));
        return [
            'vehiclePlate' => Str::random(7),
            'entryDateTime' => $entryDateTime->format('Y-m-d H:i:s'),
            'exitDateTime' => $exitDateTime->format('Y-m-d H:i:s'),
            'priceType' => $this->faker->randomElement(['Hourly', 'Daily', 'Weekly', 'Monthly']),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'userId' => User::factory()->create()->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
