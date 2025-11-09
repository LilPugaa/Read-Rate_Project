<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Author;
use App\Models\Category;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => Author::inRandomOrder()->first()?->id ?? Author::factory(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'title' => $this->faker->sentence(3),
            'isbn' => $this->faker->unique()->isbn13(),
            'publisher' => $this->faker->company(),
            'publication_year' => $this->faker->year(),
            'status' => $this->faker->randomElement(['available', 'rented', 'reserved']),
            'store_location' => $this->faker->city(),
        ];
    }
}
