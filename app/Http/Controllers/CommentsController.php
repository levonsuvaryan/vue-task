<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CommentRequest;
use App\Http\Resources\Post\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(CommentRequest $request, Post $post, PostService $postService)
    {
        $comment = $postService->writeComment($post, $request);

        $comment->load('user');

        return new CommentResource($comment);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Comment $comment, PostService $postService)
    {
        $this->authorize('delete', $comment);

        $postService->deleteComment($comment);

        return response()->json([
            'message' => trans('crud.deleted', ['item' => 'Comment'])
        ]);
    }

}

