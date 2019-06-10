<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\ImageUploadRequest;
use App\Http\Requests\Post\PostCreateRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\ImageResource;
use App\Http\Resources\Post\PostListResource;
use App\Http\Resources\Post\PostShowResource;
use App\Models\Image;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    const PER_PAGE = 10;

    private $service;

    public function __construct(PostService $postService)
    {
        $this->service = $postService;
    }

    public function index(Request $request)
    {
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
        $this->checkAccessToPost($post);

        $post->load('mainImage', 'user');

        return new PostShowResource($post);
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        $this->checkAccessToPost($post);

        $this->service->update($post, $request);

        return response()->json([
            'message' => trans('crud.edited', ['item' => 'Post'])
        ]);
    }

    public function destroy(Post $post)
    {
        $this->checkAccessToPost($post);

        $this->service->delete($post);

        return response()->json([
            'message' => trans('crud.deleted', ['item' => 'Post'])
        ]);
    }

    // IMAGES

    public function images(Post $post)
    {
        $this->checkAccessToPost($post);

        $images = Image::forPost($post)->get();

        return ImageResource::collection($images);
    }

    public function uploadImage(ImageUploadRequest $request, Post $post)
    {
        $this->checkAccessToPost($post);

        $image = $this->service->uploadImage($post, $request);

        return new ImageResource($image);
    }

    public function deleteImage(Image $image)
    {
        $this->checkAccessToImage($image);

        $this->service->deleteImage($image);

        return response()->json([
            'message' => trans('crud.deleted', ['item' => 'Image'])
        ]);
    }

    public function setImageMain(Image $image)
    {
        $this->checkAccessToImage($image);

        $this->service->setImageMain($image);

        return response()->json(['message' => trans('crud.image.main')]);
    }

    // ACCESS

    private function checkAccessToPost(Post $post)
    {
        Gate::authorize('manage-post', $post);
    }

    private function checkAccessToImage(Image $image)
    {
        Gate::authorize('manage-image', $image);
    }

}
