<?php

namespace App\Http\Controllers;

use App\Models\book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Author;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locations = Book::query()
            ->whereNotNull('store_location')
            ->distinct()
            ->orderBy('store_location')
            ->limit(100)
            ->pluck('store_location');

        $query = Book::query();

        $query->when($request->search, function ($bookQuery) use ($request) {
            $search = $request->search;
            $bookQuery->where(function ($searchQuery) use ($search) {
                $searchQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%")
                        ->orWhere('publisher', 'like', "%{$search}%");
            })
            ->orWhereHas('author', function ($authorSearchQuery) use ($search) {
                $authorSearchQuery->where('name', 'like', "%{$search}%");
            });
        });

        $query->when($request->store_location, function ($bookQuery) use ($request) {
            $bookQuery->where('store_location', 'like', '%' . $request->store_location . '%');
        });

        $query->when($request->status, function ($bookQuery) use ($request) {
            $bookQuery->where('status', 'like', '%' . $request->status . '%');
        });

        $query->when($request->author_id, function ($q) use ($request) {
            $q->whereHas('author', function ($authorQuery) use ($request) {
                $authorQuery->where('name', 'like', '%' . $request->author_id . '%');
            });
        });

        if ($yearFrom = $request->input('year_from')) {
            $query->where('publication_year', '>=', $yearFrom);
        }

        if ($yearTo = $request->input('year_to')) {
            $query->where('publication_year', '<=', $yearTo);
        }

        // if ($categories = $request->input('categories')) {
        //     $query->whereIn('category_id', $categories);
        // }

        $categoryIds = $request->input('categories');
        $categoryLogic = $request->input('category_logic', 'or'); // default: or

        $query->when($categoryIds, function ($bookQuery) use ($categoryIds, $categoryLogic) {
            if ($categoryLogic === 'and') {
                // Buku harus memiliki SEMUA kategori yang dipilih
                foreach ($categoryIds as $categoryId) {
                    $bookQuery->whereHas('categories', function ($subQuery) use ($categoryId) {
                        $subQuery->where('categories.id', $categoryId);
                    });
                }
            } else {
                // Buku memiliki SALAH SATU dari kategori yang dipilih
                $bookQuery->whereHas('categories', function ($subQuery) use ($categoryIds) {
                    $subQuery->whereIn('categories.id', $categoryIds);
                });
            }
        });

        $books = $query->with(['author', 'categories'])
            ->withCount('ratings as voters_count')   
            ->withAvg('ratings', 'rating')              
            ->withAvg(['ratings as recent_avg_rating' => function($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            }], 'rating');                              

        if ($ratingMin = $request->input('rating_min')) {
            $query->having('ratings_avg_rating', '>=', $ratingMin);
        }

        if ($ratingMax = $request->input('rating_max')) {
            $query->having('ratings_avg_rating', '<=', $ratingMax);
        }

        $sort = $request->input('sort', 'rating');

        switch($sort) {
            case 'rating':
                $query->orderByDesc('ratings_avg_rating');
                break;

            case 'voters':
                $query->withCount('ratings')->orderByDesc('ratings_count');
                break;

            case 'popularity':
                $query->withCount(['ratings as recent_popularity' => function($ratingQuery) {
                    $ratingQuery->where('created_at', '>=', now()->subDays(30));
                }])->orderByDesc('recent_popularity');
                break;

            case 'title':
                $query->orderBy('title', 'asc');
                break;
        }

        $categories = Category::all();
        $authors = Author::orderBy('name', 'asc')->get();

        $books = $query->with(['author', 'categories', 'ratings'])
            ->paginate(100)
            ->withQueryString(); // withQueryString() hanya error pada packagesnya, bukan error dari laravel.

        return view('index', compact('books', 'categories', 'authors', 'locations'));
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
    public function show(book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(book $book)
    {
        //
    }
}
