<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Page::class, 'page');
    }

    public function index(Request $request)
    {
        $query = Page::query()->latest();

        if ($search = $request->query('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = $request->query('status')) {
            $query->when($status === 'published', fn ($builder) => $builder->where('is_published', true))
                ->when($status === 'draft', fn ($builder) => $builder->where('is_published', false));
        }

        $pages = $query->paginate($request->integer('per_page', 10));

        return PageResource::collection($pages);
    }

    public function store(PageRequest $request)
    {
        $page = Page::create($this->preparePayload($request));

        return PageResource::make($page);
    }

    public function show(Page $page)
    {
        return PageResource::make($page);
    }

    public function update(PageRequest $request, Page $page)
    {
        $page->update($this->preparePayload($request, $page));

        return PageResource::make($page);
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return response()->noContent();
    }

    protected function preparePayload(PageRequest $request, ?Page $page = null): array
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

        return $data;
    }
}
