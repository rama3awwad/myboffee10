<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shelf>
 */
class ShelveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'book_id' => \App\Models\Book::factory(), // Assuming you have a BookFactory
            'user_id' => \App\Models\User::factory(), // Assuming you have a UserFactory
            'status' => $this->faker->randomElement(['reading', 'watch_later', 'finished']),
            'progress' => $this->faker->randomNumber(),
        ];
    }
}
