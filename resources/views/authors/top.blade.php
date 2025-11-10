@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Top 20 Authors</h2>

    {{-- Multiple Ranking Tabs --}}
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $type=='popularity'?'active':'' }}" href="?type=popularity">By Popularity</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $type=='rating'?'active':'' }}" href="?type=rating">By Average Rating</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $type=='trending'?'active':'' }}" href="?type=trending">Trending</a>
        </li>
    </ul>

    {{-- Keterangan Tab Aktif --}}
    @if ($type == 'popularity')
        <div class="alert alert-info py-2 mb-4">
            Showing authors ranked by <strong>popularity</strong> — counting only ratings <strong>greater than 5</strong>.
        </div>
    @elseif ($type == 'rating')
        <div class="alert alert-info py-2 mb-4">
            Showing authors ranked by <strong>average rating</strong> of their books.
        </div>
    @elseif ($type == 'trending')
        <div class="alert alert-info py-2 mb-4">
            Showing authors ranked by <strong>trending score</strong> (based on recent activity).
        </div>
    @endif

    {{-- Tabel Author --}}
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Author</th>
                    <th>Total Ratings</th>
                    <th>Popularity (>5)</th>
                    <th>Best Book</th>
                    <th>Worst Book</th>
                    <th>Average Rating</th>
                    <th>Trending Score</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($authors as $index => $author)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $author->name }}</td>
                        <td class="text-center">{{ $author->total_ratings }}</td>
                        <td class="text-center">{{ $author->high_ratings ?? 0 }}</td>
                        <td>{{ $author->best_book ?? '-' }}</td>
                        <td>{{ $author->worst_book ?? '-' }}</td>
                        <td class="text-center">{{ number_format($author->average_rating, 2) }}</td>
                        <td class="text-center">
                            {{ number_format($author->trending_score, 2) }}
                            @if ($author->trending_score > 0)
                                <span class="badge text-success">↑</span>
                            @elseif ($author->trending_score < 0)
                                <span class="badge text-danger">↓</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No author data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection