@extends('layouts.app')

@section('title', 'Blog | '.config('app.name'))
@section('meta_description', 'Browse the latest published posts.')

@section('content')
    <h1 style="margin-top:0;">Blog</h1>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;">
        @forelse($posts as $post)
            <article style="background:#fff;border-radius:1rem;padding:1.5rem;box-shadow:0 10px 30px rgba(0,0,0,0.05);">
                <p style="color:#888;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.2em;">
                    {{ optional($post->category)->name ?? 'General' }}
                </p>
                <h2 style="margin-top:0.5rem;font-size:1.3rem;">
                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                </h2>
                <p style="color:#555;">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->body), 140) }}</p>
                <p style="color:#777;font-size:0.9rem;margin-top:1rem;">
                    {{ $post->author?->name ?? 'Editorial' }} • {{ optional($post->published_at)->format('M d, Y') }}
                </p>
            </article>
        @empty
            <p>No posts yet. Check back soon.</p>
        @endforelse
    </div>

    <div style="margin-top:2rem;">
        {{ $posts->links() }}
    </div>
@endsection
