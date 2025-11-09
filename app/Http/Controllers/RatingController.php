<?php

namespace App\Http\Controllers;

use App\Models\rating;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Jika user memilih author sebelumnya, ambil author tersebut
        $authors = Author::select('id', 'name')
                            ->orderBy('name', 'asc')
                            ->get();

        $selectedAuthorId = $request->input('author_id');
        $books = collect();

        if ($selectedAuthorId) {
            $books = Book::where('author_id', $selectedAuthorId)
                        ->select('id', 'title', 'author_id')
                        ->orderBy('title', 'asc')
                        ->get();
        }

        return view('ratings.create', compact('authors', 'books', 'selectedAuthorId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'author_id' => 'required|exists:authors,id',
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:10',
        ], [
            'author_id.required' => 'Penulis wajib dipilih.',
            'author_id.exists' => 'Penulis tidak ditemukan.',
            'book_id.required' => 'Buku wajib dipilih.',
            'book_id.exists' => 'Buku tidak ditemukan.',
            'rating.required' => 'Rating wajib diisi.',
            'rating.min' => 'Rating minimal 1.',
            'rating.max' => 'Rating maksimal 10.',
        ]);

        $sessionId = session()->getId(); // identifikasi user

        // Pastikan buku memang milik author
        $bookBelongsToAuthor = Book::where('id', $request->book_id)
            ->where('author_id', $request->author_id)
            ->exists();

        if (!$bookBelongsToAuthor) {
            return back()
                ->withErrors(['book_id' => 'Please select a valid book for the chosen author.'])
                ->withInput();
        }

        // Handle duplicate rating: cek apakah session sudah memberi rating ke buku ini
        $existingRating = Rating::where('book_id', $request->book_id)
            ->where('session_id', $sessionId)
            ->first();

        if ($existingRating) {
            return back()
                ->withErrors(['rating' => 'You have already rated this book.'])
                ->withInput();
        }

        // Handle 24-hour rule: cek rating terakhir session untuk buku apapun
        $lastRating = Rating::where('session_id', $sessionId)
            ->latest()
            ->first();

        if ($lastRating && now()->diffInHours($lastRating->created_at) < 24) {
            return back()
                ->withErrors(['rating' => 'You must wait 24 hours before giving another rating.'])
                ->withInput();
        }

        // Simpan rating baru
        DB::transaction(function() use ($request, $sessionId) {
            Rating::create([
                'session_id' => $sessionId,
                'book_id' => $request->book_id,
                'rating' => $request->rating,
            ]);
        });

        return redirect()->route('index')->with('success', 'Rating submitted successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(rating $rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(rating $rating)
    {
        //
    }
}
