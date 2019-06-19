<?php

namespace App\Services;

use App\Http\Requests\Post\CommentRequest;
use App\Http\Requests\Post\ImageUploadRequest;
use App\Http\Requests\Post\PostCreateRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostService
{
    private $imageService;

    public function __construct()
    {
        $this->imageService = new ImageService('post');
    }

    public function create(PostCreateRequest $request): Post
    {
        $file = $request->file('image');
        $storedImagePath = $this->imageService->store($file);

        return DB::transaction(function () use ($request, $storedImagePath) {
            $data = $request->only(['title', 'description']);

            $post = Post::make($data);
            $post->user()->associate($request->user());
            $post->save();

            $post->addMainImage($request->user()->id, $storedImagePath);

            return $post;
        });
    }

    public function update(Post $post, PostUpdateRequest $request): void
    {
        $data = $request->only('title', 'description');
        $post->update($data);
    }

    public function delete(Post $post): void
    {
        $deletableImagePaths = $post->getImagePaths();

        DB::transaction(function () use ($post) {
            $post->comments()->delete();
            $post->images()->delete();
            $post->delete();
        });

        $this->imageService->unlinkMany($deletableImagePaths);
    }

    public function writeComment(Post $post, CommentRequest $request): Comment
    {
        return $post->writeComment(
            $request->user()->id,
            $request->input('comment')
        );
    }

    public function deleteComment(Comment $comment): void
    {
        $comment->delete();
    }

    public function uploadImage(Post $post, ImageUploadRequest $request): Image
    {
        $file = $request->file('image');

        $path = $this->imageService->store($file);

        return $post->addSecondaryImage($request->user()->id, $path);
    }

    public function deleteImage(Image $image): void
    {
        $path = $image->path;
        $image->remove();
        $this->imageService->unlink($path);
    }

    public function setImageMain(Image $image): void
    {
        DB::transaction(function () use ($image) {
            $image->setMain();
        });
    }

}