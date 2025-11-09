<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bookId = Book::query()->inRandomOrder()->value('id');

        if(!$bookId) {
            $bookId = Book::factory()->create()->id;
        }

        return [
            'book_id' => $bookId,
            'rating' => $this->faker->numberBetween(1, 10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
