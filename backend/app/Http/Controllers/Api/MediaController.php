<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaUploadRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Media::class, 'media');
    }

    public function index(Request $request)
    {
        $media = Media::query()
            ->with('user:id,name')
            ->latest()
            ->paginate($request->integer('per_page', 12));

        return MediaResource::collection($media);
    }

    public function store(MediaUploadRequest $request)
    {
        $file = $request->file('file');
        $disk = 'public';
        $path = $file->storePublicly('uploads', $disk);

        $media = Media::create([
            'original_name' => $file->getClientOriginalName(),
            'file_name' => basename($path),
            'path' => $path,
            'disk' => $disk,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'url' => Storage::disk($disk)->url($path),
            'user_id' => $request->user()->id,
        ]);

        return MediaResource::make($media->load('user'));
    }

    public function show(Media $media)
    {
        return MediaResource::make($media->load('user'));
    }

    public function destroy(Media $media)
    {
        Storage::disk($media->disk)->delete($media->path);

        $media->delete();

        return response()->noContent();
    }
}
