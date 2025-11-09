@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Give Book Rating</h2>

    {{-- Error --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show position-relative">
            @foreach($errors->all() as $error)
                {{ $error }}
                <button type="button" class="btn-close position-absolute" data-bs-dismiss="alert" aria-label="Close"></button>
            @endforeach
        </div>
    @endif

    {{-- Form --}}
    <form method="GET" action="{{ route('ratings.create') }}" class="mb-3">
        <label for="author_id" class="form-label fw-semibold">Select Author</label>
        <select name="author_id" id="author_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Choose Author --</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" {{ $selectedAuthorId == $author->id ? 'selected' : '' }}>
                    {{ $author->name }}
                </option>
            @endforeach
        </select>
    </form>

    @if($selectedAuthorId)
    <form method="POST" action="{{ route('ratings.store') }}">
        @csrf
        <input type="hidden" name="author_id" value="{{ old('author_id', $selectedAuthorId) }}">

        <div class="mb-3">
            <label for="book_id" class="form-label fw-semibold">Book</label>
            <select name="book_id" id="book_id" class="form-select" required>
                <option value="">-- Choose Book --</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="rating" class="form-label fw-semibold">Rating (1–10)</label>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">-- Select Rating --</option>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit Rating</button>
        <a href="{{ route('index') }}" class="btn btn-secondary">Back</a>
    </form>
    @endif
</div>
@endsection