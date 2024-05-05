<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'file' => $this->faker->filePath(), // Assuming you want to generate a fake file path
            'image' => $this->faker->imageUrl(), // Generates a URL to a random image
            'author_name' => $this->faker->name(),
            'points' => $this->faker->randomNumber(), // Generates a random number
            'description' => $this->faker->paragraph(),
            'total_pages' => $this->faker->randomNumber(),
            'type_id' => $this->faker->randomElement(\App\Models\Type::pluck('id')->toArray()),
        ];
    }
}
