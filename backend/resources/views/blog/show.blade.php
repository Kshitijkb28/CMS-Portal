@extends('layouts.app')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->body), 150))

@section('content')
    <article style="background:#fff;border-radius:1rem;padding:2rem;box-shadow:0 20px 60px rgba(0,0,0,0.08);">
        <p style="text-transform:uppercase;font-size:0.8rem;letter-spacing:0.2em;color:#888;">
            {{ optional($post->category)->name ?? 'Featured' }}
        </p>
        <h1 style="margin-top:0;">{{ $post->title }}</h1>
        <p style="color:#777;">By {{ $post->author?->name ?? 'Editorial' }} • {{ optional($post->published_at)->format('M d, Y') }}</p>
        <hr style="margin:2rem 0;border:none;border-top:1px solid #eee;">
        <div style="color:#333;line-height:1.7;">
            {!! $post->body !!}
        </div>
    </article>

    @if($related->count())
        <section style="margin-top:3rem;">
            <h2>Related posts</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
                @foreach($related as $item)
                    <article style="background:#fff;border-radius:1rem;padding:1rem;box-shadow:0 10px 30px rgba(0,0,0,0.05);">
                        <h3 style="margin-top:0;font-size:1.1rem;">
                            <a href="{{ route('blog.show', $item->slug) }}">{{ $item->title }}</a>
                        </h3>
                        <p style="color:#777;font-size:0.9rem;">
                            {{ optional($item->published_at)->format('M d, Y') }}
                        </p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
