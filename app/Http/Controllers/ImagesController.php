<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\ImageUploadRequest;
use App\Http\Resources\Post\ImageResource;
use App\Models\Image;
use App\Models\Post;
use App\Services\PostService;

class ImagesController extends Controller
{
    private $service;

    public function __construct(PostService $postService)
    {
        $this->service = $postService;
    }

    public function index(Post $post)
    {
        $this->authorize('view', $post);

        $images = Image::forPost($post)->get();

        return ImageResource::collection($images);
    }

    public function upload(ImageUploadRequest $request, Post $post)
    {
        $image = $this->service->uploadImage($post, $request);

        return new ImageResource($image);
    }

    public function delete(Image $image)
    {
        $this->authorize('delete', $image);

        $this->service->deleteImage($image);

        return response()->json([
            'message' => trans('crud.deleted', ['item' => 'Image'])
        ]);
    }

    public function setMain(Image $image)
    {
        $this->authorize('update', $image);

        $this->service->setImageMain($image);

        return response()->json(['message' => trans('crud.image.main')]);
    }


}
