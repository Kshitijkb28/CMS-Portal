@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->excerpt ?: $page->body), 150))

@section('content')
    <article style="background:#fff;border-radius:1rem;padding:2rem;box-shadow:0 20px 60px rgba(0,0,0,0.08);">
        <h1 style="margin-top:0;">{{ $page->title }}</h1>
        <div style="color:#333;line-height:1.7;">
            {!! $page->body !!}
        </div>
    </article>
@endsection
