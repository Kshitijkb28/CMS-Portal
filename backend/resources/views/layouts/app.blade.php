<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Content powered by Laravel CMS assignment')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light dark;
        }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            color: #111;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        header {
            background: #111;
            color: #fff;
        }

        .container {
            width: min(1100px, 90vw);
            margin: 0 auto;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        footer {
            margin-top: 4rem;
            padding: 2rem 0;
            background: #111;
            color: #ddd;
            text-align: center;
        }
    </style>
    @stack('head')
</head>

<body>
    <header>
        <div class="container">
            <nav>
                <a href="{{ route('home') }}" style="font-weight: 600; letter-spacing: 0.05em;">
                    {{ config('app.name', 'Laravel CMS') }}
                </a>
                <div class="nav-links">
                    <a href="{{ route('blog.index') }}">Blog</a>
                    @foreach($navPages ?? [] as $page)
                        <a href="{{ route('pages.show', $page->slug) }}">{{ $page->title }}</a>
                    @endforeach
                </div>
            </nav>
        </div>
    </header>

    <main class="container" style="padding: 3rem 0;">
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.</p>
    </footer>
</body>

</html>
