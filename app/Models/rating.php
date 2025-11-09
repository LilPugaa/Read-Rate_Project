<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'rating',
        'session_id'
    ];

    public function books() {
        return $this->belongsTo(Book::class);
    }
}
