<?php

namespace App\Http\Controllers;

use App\Models\author;
use App\Models\book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PharIo\Manifest\Author as ManifestAuthor;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Langkah 1: ambil hanya 20 author dengan total rating terbanyak
        $topAuthors = Author::select('authors.id', 'authors.name')
            ->join('books', 'books.author_id', '=', 'authors.id')
            ->join('ratings', 'ratings.book_id', '=', 'books.id')
            ->groupBy('authors.id', 'authors.name')
            ->selectRaw('COUNT(ratings.id) as total_ratings')
            ->orderByDesc('total_ratings')
            ->take(20)
            ->get();

        // Langkah 2: ambil detail buku dan rating untuk 20 author itu
        $authors = Author::whereIn('id', $topAuthors->pluck('id'))
            ->with(['books.ratings'])
            ->get()
            ->map(function ($author) {
                $allRatings = $author->books->flatMap->ratings;

                $totalRatings = $allRatings->count();
                $averageRating = $allRatings->avg('rating') ?? 0;

                // Cari best & worst book
                $bestBook = $author->books
                    ->reduce(function ($carry, $book) {
                        return (!$carry || $book->ratings->avg('rating') > $carry->ratings->avg('rating'))
                            ? $book
                            : $carry;
                    });

                $worstBook = $author->books
                    ->reduce(function ($carry, $book) {
                        return (!$carry || $book->ratings->avg('rating') < $carry->ratings->avg('rating'))
                            ? $book
                            : $carry;
                    });

                // Trending score = selisih avg rating bulan ini vs bulan lalu x total voter
                $recentAvg = $allRatings->where('created_at', '>=', now()->subDays(30))->avg('rating') ?? 0;
                $previousAvg = $allRatings
                    ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
                    ->avg('rating') ?? 0;

                $trendingScore = ($recentAvg - $previousAvg) * max($totalRatings, 1);

                return (object) [
                    'id' => $author->id,
                    'name' => $author->name,
                    'total_ratings' => $totalRatings,
                    'average_rating' => $averageRating,
                    'best_book' => $bestBook?->title,
                    'worst_book' => $worstBook?->title,
                    'trending_score' => $trendingScore,
                ];
            })
            ->sortByDesc('average_rating')
            ->values();

        return view('authors.top', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, author $author)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(author $author)
    {
        //
    }
}
