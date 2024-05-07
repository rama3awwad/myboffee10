<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

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
        // Simulate file uploads
        Storage::fake('public');
        $filePath = Storage::putFile('books/files', $this->faker->file());
        $coverPath = Storage::putFile('books/cover_images', $this->faker->image());

        return [
            'title' => $this->faker->sentence(),
            'file' =>$filePath,
            'cover'=>$this->faker->imageUrl($width = 400, $height = 600),
            'author_name' => $this->faker->name(),
            'points' => $this->faker->numberBetween(0,10),
            'description' => $this->faker->sentence(),
            'total_pages' => $this->faker->numberBetween(1,2000),
            'type_id' => $this->faker->randomNumber(),
        ];
    }
}
