@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">List of Books</h2>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-relative">
            {{ session('success') }}
            <button type="button" class="btn-close position-absolute" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="GET" action="{{ route('index') }}" class="row g-2 align-items-end mb-4">
        {{-- Search --}}
        <div class="col-md-3">
            <label class="form-label fw-semibold">Search</label>
            <input type="text" name="search" class="form-control" 
                    placeholder="Search title, author, ISBN, or publisher..." 
                    value="{{ request('search') }}">
        </div>

        {{-- Category (multiple) --}}
        <div class="col-md-3">
            <label class="form-label fw-semibold">Category</label>
            <select name="categories[]" class="form-select" multiple>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ collect(request('categories'))->contains($category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Author --}}
        <div class="col-md-2">
            <label class="form-label fw-semibold">Author</label>
            <select name="author_id" class="form-select">
                <option value="">All Authors</option>
                @foreach($authors as $author)
                    <option value="{{ $author->name }}" 
                        {{ request('author_id') == $author->id ? 'selected' : '' }}>
                        {{ $author->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Publication Year Range --}}
        <div class="col-md-2">
            <label class="form-label fw-semibold">Year (From)</label>
            <input type="number" name="year_from" class="form-control" placeholder="e.g. 2000" 
                    value="{{ request('year_from') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Year (To)</label>
            <input type="number" name="year_to" class="form-control" placeholder="e.g. 2025" 
                    value="{{ request('year_to') }}">
        </div>

        {{-- Store Location --}}
        <div class="col-md-3">
            <label class="form-label fw-semibold">Store Location</label>
            <select name="store_location" class="form-select">
                <option value="">All Locations</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc }}" {{ request('store_location') == $loc ? 'selected' : '' }}>
                        {{ $loc }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Rating Range --}}
        <div class="col-md-2">
            <label class="form-label fw-semibold">Rating (From)</label>
            <input type="number" name="rating_min" min="0" max="10" step="0.1" 
                    class="form-control" value="{{ request('rating_min') }}" placeholder="Min">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Rating (To)</label>
            <input type="number" name="rating_max" min="0" max="10" step="0.1" 
                    class="form-control" value="{{ request('rating_max') }}" placeholder="Max">
        </div>

        {{-- Availability --}}
        <div class="col-md-2">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Rented</option>
                <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
            </select>
        </div>

        {{-- Sorting --}}
        <div class="col-md-3">
            <label class="form-label fw-semibold">Sort by</label>
            <select name="sort" class="form-select">
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Weighted Avg. Rating (Default)</option>
                <option value="voters" {{ request('sort') == 'voters' ? 'selected' : '' }}>Total Votes</option>
                <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Recent Popularity (30 Days)</option>
                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Alphabetical</option>
            </select>
        </div>

        {{-- Submit Button --}}
        <div class="col-md-2 d-grid pt-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>
        <div class="col-md-1 d-grid">
            <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('index') }}'">
                <i class="bi bi-funnel"></i> Reset
            </button>
        </div>
    </form>
    {{-- <pre>{{ print_r($books->first()->toArray()) }}</pre> --}}
    <table class="table table-striped">
        <thead class="text-center"> {{-- baru diupdate --}}
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Category</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Publisher</th>
                <th>Year</th>
                <th>Rating</th>
                <th>Voters</th>
                <th>Store Location</th>
                <th>Trend Indicator</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $book)
            <tr>
                <td class="text-center"> {{-- baru diupdate --}}
                    {{ $loop->iteration + ($books->currentPage() - 1) * $books->perPage() }}
                </td>
                <td>{{ $book->title }}</td>
                <td>
                    @forelse ($book->categories as $category)
                        <div>{{ $category->name }}</div>
                    @empty
                        <div>-</div>
                    @endforelse
                </td>
                <td>{{ $book->author->name }}</td>
                <td>
                    @php
                        $isbn = $book->isbn;
                        if(strlen($isbn) == 13) {
                            $isbnFormatted = substr($isbn, 0, 3) . '-' .
                                            substr($isbn, 3, 1) . '-' .
                                            substr($isbn, 4, 2) . '-' .
                                            substr($isbn, 6, 6) . '-' .
                                            substr($isbn, 12, 1);
                        } else {
                            $isbnFormatted = $isbn;
                        }
                    @endphp
                    {{ $isbnFormatted }}
                </td>
                <td>{{ $book->publisher }}</td>
                <td class="text-center">{{ $book->publication_year }}</td>
                <td class="text-center">{{ number_format($book->ratings_avg_rating, 2) }}</td>
                <td class="text-center">{{ $book->voters_count }}</td>
                <td>{{ $book->store_location }}</td>
                <td class="text-center">
                    @if($book->recent_avg_rating && $book->recent_avg_rating > $book->ratings_avg_rating)
                        <span class="badge text-success">↑</span>
                    @elseif($book->recent_avg_rating && $book->recent_avg_rating < $book->ratings_avg_rating)
                        <span class="badge text-danger">↓</span>
                    @else
                        <span class="badge text-secondary">-</span>
                    @endif
                </td>
                <td>
                    @php
                        $statusClass = match($book->status) {
                            'available' => 'badge bg-success',
                            'rented' => 'badge bg-danger',
                            'reserved' => 'badge bg-warning text-dark',
                            default => 'badge bg-secondary',
                        }
                    @endphp
                    <span class="{{ $statusClass }}">{{ ucfirst($book->status) }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $books->links() }}
</div>
@endsection