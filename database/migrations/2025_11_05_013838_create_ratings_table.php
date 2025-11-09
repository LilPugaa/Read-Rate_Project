<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->timestamps();

            // index untuk performa
            $table->index(['book_id']);
            $table->index(['rating']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
