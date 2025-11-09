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
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->foreignId('author_id')->constrained('authors')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();

            $table->string('title', 255);
            $table->string('isbn', 50)->unique();
            $table->string('publisher', 255)->nullable();
            $table->year('publication_year')->nullable();
            $table->string('store_location', 255)->nullable();
            $table->enum('status', ['available', 'rented', 'reserved'])->default('available');
            $table->timestamps();

            // index untuk performa pencarian/filter
            $table->index(['author_id']);
            $table->index(['category_id']);
            $table->index(['publication_year']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
