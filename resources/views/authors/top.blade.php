@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Top 20 Authors</h2>

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>No</th>
                <th>Author</th>
                <th>Total Ratings</th>
                <th>Best Book</th>
                <th>Worst Book</th>
                <th>Average Rating</th>
                <th>Trending Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($authors as $index => $author)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $author->name }}</td>
                    <td>{{ $author->total_ratings }}</td>
                    <td>{{ $author->best_book ?? '-' }}</td>
                    <td>{{ $author->worst_book ?? '-' }}</td>
                    <td>{{ number_format($author->average_rating, 2) }}</td>
                    <td>{{ number_format($author->trending_score, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection