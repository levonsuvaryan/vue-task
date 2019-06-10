<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    const STATUS_MAIN = 'main';
    const STATUS_SECONDARY = 'secondary';

    protected $table = 'post_images';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isMain(): bool
    {
        return $this->status === self::STATUS_MAIN;
    }

    public function isSecondary(): bool
    {
        return $this->status === self::STATUS_SECONDARY;
    }

    public function setMain(): void
    {
        if ($this->isMain()) {
            throw new \LogicException('The image is already set main.');
        }

        $oldMainImage = self::where('post_id', $this->post_id)
            ->where('status', self::STATUS_MAIN)->first();

        $this->update(['status' => self::STATUS_MAIN]);
        $oldMainImage->update(['status' => self::STATUS_SECONDARY]);
    }
    
    public function remove(): void
    {
        if ($this->isMain()) {
            throw new \LogicException('Unable to delete main image.');
        }

        $this->delete();
    }

    public function scopeForPost(Builder $query, Post $post)
    {
        $query->where('post_id', $post->id);
    }

}
