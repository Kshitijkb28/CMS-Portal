<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index(Request $request)
    {
        $query = Post::query()
            ->with(['author:id,name,email', 'category:id,name,slug'])
            ->latest();

        if ($search = $request->query('search')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->when($status === 'published', fn ($builder) => $builder->where('is_published', true))
                ->when($status === 'draft', fn ($builder) => $builder->where('is_published', false));
        }

        $posts = $query->paginate($request->integer('per_page', 10));

        return PostResource::collection($posts);
    }

    public function store(PostRequest $request)
    {
        $data = $this->preparePayload($request);

        $post = $request->user()->posts()->create($data);

        return PostResource::make($post->load(['author:id,name,email', 'category:id,name,slug']));
    }

    public function show(Post $post)
    {
        $post->load(['author:id,name,email', 'category:id,name,slug']);

        return PostResource::make($post);
    }

    public function update(PostRequest $request, Post $post)
    {
        $data = $this->preparePayload($request, $post);

        $post->update($data);

        return PostResource::make($post->load(['author:id,name,email', 'category:id,name,slug']));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }

    public function publish(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validate([
            'is_published' => ['required', 'boolean'],
        ]);

        $post->fill([
            'is_published' => $data['is_published'],
            'published_at' => $data['is_published'] ? ($post->published_at ?? now()) : null,
        ])->save();

        return PostResource::make($post->fresh(['author:id,name,email', 'category:id,name,slug']));
    }

    protected function preparePayload(PostRequest $request, ?Post $post = null): array
    {
        $data = $request->validated();

        $shouldPublish = $request->boolean('is_published');
        $data['is_published'] = $shouldPublish;

        if ($shouldPublish && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if (! $shouldPublish) {
            $data['published_at'] = null;
        }

        if (array_key_exists('category_id', $data) && empty($data['category_id'])) {
            $data['category_id'] = null;
        }

        return $data;
    }
}
