<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class BookCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categoryIds = Category::pluck('id')->toArray();

        // Proses per 1000 buku untuk efisiensi
        Book::chunk(1000, function ($books) use ($categoryIds) {
            $pivotData = [];

            foreach ($books as $book) {
                // Setiap buku akan punya 1–3 kategori acak
                $randomCategories = collect($categoryIds)
                    ->random(rand(1, 3))
                    ->toArray();

                foreach ($randomCategories as $categoryId) {
                    $pivotData[] = [
                        'book_id' => $book->id,
                        'category_id' => $categoryId,
                    ];
                }
            }

            // Masukkan data ke tabel pivot secara batch (lebih cepat)
            DB::table('book_category')->insert($pivotData);
        });
    }
}
