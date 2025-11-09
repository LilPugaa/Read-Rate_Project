<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;
use App\Models\Rating;

class author extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function books() {
        return $this->hasMany(Book::class, 'author_id');
    }

    public function ratings() {
        return $this->hasManyThrough(Rating::class, Book::class);
    }
}
