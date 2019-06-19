<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostCreateRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\PostListResource;
use App\Http\Resources\Post\PostShowResource;
use App\Http\Resources\Post\PostViewResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    const PER_PAGE = 10;

    private $service;

    public function __construct(PostService $postService)
    {
        $this->service = $postService;
    }

    public function blogIndex()
    {
        $posts = Post::with('mainImage', 'user')
            ->orderByDesc('id')->paginate(self::PER_PAGE);

        return PostListResource::collection($posts);
    }

    public function blogView(Post $post)
    {
        $post->load(['images', 'user', 'comments' => function ($query) {
            $query->with('user')->orderByDesc('id');
        }]);

        return new PostViewResource($post);
    }

    public function index(Request $request)
    {
        $this->authorize('manage', Post::class);

        $query = Post::with('mainImage', 'user');

        if (! $request->user()->isAdmin()) {
            $query->forUser($request->user());
        }
        
        $posts = $query->orderByDesc('id')->paginate(self::PER_PAGE);

        return PostListResource::collection($posts);
    }

    public function store(PostCreateRequest $request)
    {
        $post = $this->service->create($request);

        return response()->json([
            'id' => $post->id,
            'message' => trans('crud.stored', ['item' => 'Post'])
        ], 201);
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        $post->load('mainImage', 'user');

        return new PostShowResource($post);
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        $this->service->update($post, $request);

        return response()->json([
            'message' => trans('crud.edited', ['item' => 'Post'])
        ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $this->service->delete($post);

        return response()->json([
            'message' => trans('crud.deleted', ['item' => 'Post'])
        ]);
    }


}
