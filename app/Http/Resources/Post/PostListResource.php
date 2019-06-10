<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;

class PostListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => str_limit($this->description, 100),
            'created_at' => $this->created_at->diffForHumans(),
            'user' => new UserResource($this->user),
            'image' => new ImageResource($this->mainImage),
        ];
    }
}
