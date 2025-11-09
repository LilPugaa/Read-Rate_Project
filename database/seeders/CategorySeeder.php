<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        Category::all()->each(function ($category) use ($faker) {
            $category->update([
                'name' => ucfirst($faker->unique()->words(2, true))
            ]);
        });

        $this->command->info('Nama kategori berhasil diperbarui tanpa menghapus relasi.');
    }
}
