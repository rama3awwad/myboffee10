<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {


        $fileName = 'أذكار الصباح';
        $coverName = 'book112';

        $file = '/books/files/'. $fileName . '.pdf';
        $cover = '/books/cover_images/'. $coverName . '.jpg';
       /* $coverImageUrl = $this->faker->imageUrl($width = 400, $height = 600);
        // Download the image
        $client = new Client();
        $response = $client->get($coverImageUrl);
        $imageContent = $response->getBody()->getContents();
        // Define the cover path
        $coverPath = 'books/cover_images/'. basename($coverImageUrl). '.jpg';
        // Store the image
        Storage::disk('local')->put($coverPath, $imageContent);*/

        // Ensure the title is unique
      /*  $title = function ($attributes) {
            $count = Book::where('title', $attributes['title'])->count();
            $reset = $count == 0;

            return $this->faker->unique($reset)->word();
        };
        $uniqueTitle = $title(['title' => '123']);*/

       // $title = '';
        do {
            $title = $this->faker->word();
        } while (Book::where('title_en', $title)->exists());


        return [
            'title_en'=>$title,
            'title_ar'=>$title,
            'file' => $file,
           // 'cover'=>fake()->imageUrl($width = 400, $height = 600),
            'cover' => $cover,
            'author_name_en' => fake()->name(),
            'author_name_ar' => fake()->name(),
            'points' => fake()->numberBetween(0, 10),
            'description_en' => 'It is useful',
            'description_ar' => 'هذا الكتاب مفيد.',
            'total_pages' => 4,
            'type_id' => fake()->numberBetween(1, 6),
        ];
    }
}
