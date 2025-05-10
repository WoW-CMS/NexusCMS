@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Latest News</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            @forelse ($data as $news)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($news->featured_image)
                            <img src="{{ asset('storage/' . $news->featured_image) }}" class="card-img-top" alt="{{ $news->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $news->title }}</h5>
                            <p class="card-text text-muted">
                                <small>
                                    Published {{ $news->published_at ? $news->published_at->diffForHumans() : 'Not published yet' }}
                                </small>
                            </p>
                            <p class="card-text">{{ Str::limit($news->excerpt ?? $news->content, 150) }}</p>
                            <a href="{{ route('news.show', $news->id) }}" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        No news articles available at the moment.
                    </div>
                </div>
            @endforelse
        </div>

        @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="d-flex justify-content-center mt-4">
                {{ $data->links() }}
            </div>
        @endif
    </div>
</div>
@endsection