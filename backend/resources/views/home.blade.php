@extends('layouts.app')

@section('title', $heroPost?->title ? "{$heroPost->title} | ".config('app.name') : config('app.name'))
@section('meta_description', $heroPost?->meta_description ?? 'Latest insights from our CMS demo.')

@section('content')
    <section style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:2rem;align-items:stretch;">
        @if($heroPost)
            <article style="background:#fff;padding:2rem;border-radius:1rem;box-shadow:0 20px 60px rgba(0,0,0,0.08);">
                <p style="text-transform:uppercase;font-size:0.8rem;letter-spacing:0.2em;color:#888;">Latest post</p>
                <h1 style="font-size:2rem;margin:0 0 1rem;">
                    <a href="{{ route('blog.show', $heroPost->slug) }}">{{ $heroPost->title }}</a>
                </h1>
                <p style="color:#555;line-height:1.6;">{{ $heroPost->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($heroPost->body), 180) }}</p>
                <div style="margin-top:1.5rem;font-size:0.9rem;color:#777;">
                    By {{ $heroPost->author?->name ?? 'Editorial' }} • {{ optional($heroPost->published_at)->format('M d, Y') }}
                </div>
            </article>
        @endif

        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div style="background:#fff;padding:1.5rem;border-radius:1rem;box-shadow:0 20px 60px rgba(0,0,0,0.05);">
                <h2 style="margin-top:0;">About us</h2>
                <p style="color:#555;">
                    {{ $aboutPage?->excerpt ?? 'A lightweight CMS demo built with Laravel 12 and React 18.' }}
                </p>
                @if($aboutPage)
                    <a href="{{ route('pages.show', $aboutPage->slug) }}" style="color:#2563eb;font-weight:500;">Read more →</a>
                @endif
            </div>
            <div style="background:#111;color:#fff;padding:1.5rem;border-radius:1rem;">
                <h3 style="margin-top:0;">Need a custom page?</h3>
                <p style="color:#ddd;">All editable from the React admin panel via Laravel APIs.</p>
            </div>
        </div>
    </section>

    @if($posts->count())
        <section style="margin-top:3rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                <h2>More stories</h2>
                <a href="{{ route('blog.index') }}" style="color:#2563eb;font-weight:600;">View all →</a>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem;">
                @foreach($posts as $post)
                    <article style="background:#fff;border-radius:1rem;padding:1.5rem;box-shadow:0 10px 30px rgba(0,0,0,0.05);">
                        <h3 style="margin-top:0;font-size:1.2rem;">
                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        <p style="color:#555;">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->body), 120) }}</p>
                        <p style="color:#777;font-size:0.9rem;margin-top:1rem;">
                            {{ $post->author?->name ?? 'Editorial' }} &middot; {{ optional($post->published_at)->format('M d, Y') }}
                        </p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
