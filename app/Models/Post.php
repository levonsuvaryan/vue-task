<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function mainImage()
    {
        return $this->hasOne(Image::class)
            ->where('status', Image::STATUS_MAIN);
    }

    public function alreadyHasMainImage(): bool
    {
        return $this->images()
                ->where('status', Image::STATUS_MAIN)
                ->count() > 0;
    }

    public function addMainImage(int $userId, string $path): Image
    {
        if ($this->alreadyHasMainImage()) {
            throw new \LogicException('Post can contain only one main image.');
        }

        return $this->images()->create([
            'path' => $path,
            'user_id' => $userId,
            'status' => Image::STATUS_MAIN
        ]);
    }

    public function addSecondaryImage(int $userId, string $path): Image
    {
        return $this->images()->create([
            'path' => $path,
            'user_id' => $userId,
            'status' => Image::STATUS_SECONDARY
        ]);
    }

    public function getImagePaths(): array
    {
        return $this->images()->pluck('path')->toArray();
    }
    
    public function writeComment(int $userId, string $body): Comment
    {
        return $this->comments()->create([
            'body' => $body,
            'user_id' => $userId
        ]);
    }

    public function scopeForUser(Builder $query, User $user)
    {
        $query->where('user_id', $user->id);
    }
    
}
