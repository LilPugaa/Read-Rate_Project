<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Author;

class book extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id', 
        'category_id', 
        'title', 
        'isbn', 
        'publisher', 
        'publication_year', 
        'status', 
        'store_location'
    ];

    public function author() {
        return $this->belongsTo(Author::class,'author_id');
    }

    // public function category() {
    //     return $this->belongsTo(Category::class);
    // }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_category', 'book_id', 'category_id');
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }
}
