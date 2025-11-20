<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;

class HomeController extends Controller
{
    public function __invoke()
    {
        $posts = Post::published()
            ->latest('published_at')
            ->with('author:id,name')
            ->take(5)
            ->get();

        $page = Page::published()->where('slug', 'about')->first();

        return view('home', [
            'heroPost' => $posts->first(),
            'posts' => $posts->slice(1),
            'aboutPage' => $page,
        ]);
    }
}
