<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CommentRequest;
use App\Http\Resources\Post\CommentResource;
use App\Http\Resources\Post\PostListResource;
use App\Http\Resources\Post\PostViewResource;
use App\Models\Post;
use App\Services\PostService;

class BlogController extends Controller
{
    const PER_PAGE = 5;

    public function index()
    {
        $posts = Post::with('mainImage', 'user')
            ->orderByDesc('id')->paginate(self::PER_PAGE);

        return PostListResource::collection($posts);
    }

    public function view(Post $post)
    {
        $post->load(['images', 'user', 'comments' => function ($query) {
            $query->with('user')->orderByDesc('id');
        }]);

        return new PostViewResource($post);
    }

    public function writeComment(CommentRequest $request, Post $post, PostService $postService)
    {
        $comment = $postService->writeComment($post, $request);

        $comment->load('user');

        return new CommentResource($comment);
    }
}
