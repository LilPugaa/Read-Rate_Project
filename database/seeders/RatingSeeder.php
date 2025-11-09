<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use Faker\Factory as Faker;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        ini_set('memory_limit', '1G');
        set_time_limit(0);

        $faker = Faker::create();
        $bookIds = Book::pluck('id')->toArray();

        if (empty($bookIds)) {
            $this->command->warn('Tidak ada data buku! Membuat 10 buku terlebih dahulu');
            $bookIds = Book::factory(10)->create()->pluck('id')->toArray();
        }

        $total = 500000;
        $batchSize = 500;
        $iterations = ceil($total / $batchSize);

        $this->command->info("Memasukkan $total data rating dalam $iterations batch kecil");

        for ($i = 0; $i < $iterations; $i++) {
            $batch = [];

            for ($j = 0; $j < $batchSize; $j++) {
                $batch[] = [
                    'book_id' => $faker->randomElement($bookIds),
                    'rating' => $faker->numberBetween(1, 10),
                    // 'created_at' => now(),
                    // 'updated_at' => now(),
                ];
            }

            // Gunakan query builder agar lebih ringan
            DB::table('ratings')->insert($batch);

            unset($batch); // buang data batch dari memori
            gc_collect_cycles(); // bersihkan memori PHP

            if (($i + 1) % 10 === 0) { // setiap 10 batch, log progress
                $this->command->info("Batch ke-" . ($i + 1) . " selesai (" . (($i + 1) * $batchSize) . " data total)");
            }
        }

        $this->command->info("Selesai! Total $total data rating berhasil dimasukkan.");
    }
}