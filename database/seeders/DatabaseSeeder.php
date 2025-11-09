<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Category;
use App\Models\Book;
use App\Models\Rating;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AuthorSeeder::class,
            CategorySeeder::class,
            BookSeeder::class,
            BookCategorySeeder::class,
            // RatingSeeder::class, // aktifkan nanti setelah relasi sukses
        ]);
    }
}
