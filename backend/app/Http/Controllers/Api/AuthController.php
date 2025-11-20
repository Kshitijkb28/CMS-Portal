<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        $user = Auth::user();

        if (! $user->is_admin) {
            Auth::logout();

            return response()->json([
                'message' => 'You are not authorized to access the admin panel.',
            ], Response::HTTP_FORBIDDEN);
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => new UserResource($user),
            'stats' => [
                'posts' => Post::count(),
                'published_posts' => Post::where('is_published', true)->count(),
                'pages' => Page::count(),
                'drafts' => Post::where('is_published', false)->count() + Page::where('is_published', false)->count(),
            ],
        ]);
    }
}
