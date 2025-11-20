<?php

namespace App\Providers;

use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Policies\MediaPolicy;
use App\Policies\PagePolicy;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Page::class, PagePolicy::class);

        Gate::define('admin', fn ($user) => (bool) $user?->is_admin);
        Route::aliasMiddleware('auth', \App\Http\Middleware\Authenticate::class);

        View::composer('*', function ($view) {
            $view->with('navPages', cache()->remember('nav_pages', 60, function () {
                return Page::published()
                    ->select('title', 'slug')
                    ->orderBy('title')
                    ->get();
            }));
        });
    }
}
