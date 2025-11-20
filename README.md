## Laravel + React CMS

This repository contains a Laravel 12 backend (REST API + Blade public site) and a React 18 admin SPA that communicate over Sanctum-secured APIs.

```
/backend  → Laravel 12 project (API + Blade site)
/admin    → React 18 + Vite admin panel
```

### Core features

- User authentication via Laravel Sanctum with admin-only access.
- CRUD for Posts, Pages, Categories, and Media uploads.
- Publish/unpublish workflow with SEO-friendly slugs and metadata.
- Public-facing Blade pages (home, blog listing/detail, dynamic pages).
- React admin panel with protected routes, Redux-free Context auth, WYSIWYG editor, and media manager.

---

## Backend setup (`/backend`)

1. **Install PHP dependencies**
   ```bash
   cd backend
   composer install
   ```
2. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update `.env` with your MySQL credentials. The defaults expect a database named `cms_portal`.

3. **Run migrations & seed data**
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```
   Seed creates an admin user: `admin@example.com / password`.

4. **Serve the backend**
   ```bash
   php artisan serve
   ```

### API quick reference

| Method | Endpoint                    | Description                    |
|--------|-----------------------------|--------------------------------|
| POST   | `/api/login`                | Issue Sanctum token            |
| POST   | `/api/logout`               | Revoke current token           |
| GET    | `/api/me`                   | Authenticated user + stats     |
| CRUD   | `/api/posts`                | Post management                |
| PATCH  | `/api/posts/{post}/publish` | Toggle publish state           |
| CRUD   | `/api/pages`                | Page management                |
| CRUD   | `/api/categories`           | Category management            |
| GET    | `/api/media`                | List uploads                   |
| POST   | `/api/media/upload`         | Upload media file              |
| DELETE | `/api/media/{media}`        | Delete file + record           |

The Blade site is available at `/`, `/blog`, `/blog/{slug}`, and `/{slug}` for dynamic pages.

### Testing

`php artisan test` relies on the `pdo_sqlite` extension (in-memory). Enable `pdo_sqlite` in PHP or point PHPUnit to a MySQL testing database before running the suite.

---

## Admin setup (`/admin`)

1. **Install dependencies**
   ```bash
   cd admin
   npm install
   cp .env.example .env
   ```
   Ensure `VITE_API_URL` points to your Laravel `/api` base URL.

2. **Run the dev server**
   ```bash
   npm run dev
   ```
   Visit `http://localhost:5173`. Use the seeded admin credentials to log in.

3. **Production build**
   ```bash
   npm run build
   npm run preview   # optional preview server
   ```

The admin panel provides dashboard stats, post/page editors with React Quill, media uploads, and category management, all backed by the Laravel API.

---

## Notes

- Update `SANCTUM_STATEFUL_DOMAINS` and `SESSION_DOMAIN` if you host on a custom domain.
- Remember to run `php artisan storage:link` so media URLs resolve correctly.
- For local testing without MySQL, you can change `.env` to use SQLite, but you must have the `pdo_sqlite` extension installed.
