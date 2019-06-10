<?php

namespace App;

use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public static function roleList(): array
    {
        return [
            self::ROLE_USER => 'User',
            self::ROLE_ADMIN => 'Admin'
        ];
    }

    public function roleName(): string
    {
        return self::roleList()[$this->role] ?? '';
    }

    public function roleTypeWithName(): array
    {
        return [
            'type' => $this->role,
            'name' => $this->roleName()
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function setAdmin(): void
    {
        if ($this->isAdmin()) {
            throw new \LogicException('User is already Admin.');
        }

        $this->update(['role' => self::ROLE_ADMIN]);
    }

    public function setUser(): void
    {
        if ($this->isUser()) {
            throw new \LogicException('User is already User.');
        }

        $this->update(['role' => self::ROLE_USER]);
    }
    
    public function getStatistics(): array
    {
        return [
            ['name' => 'Posts', 'value' => $this->posts()->count()],
            ['name' => 'Images', 'value' => $this->images()->count()],
            ['name' => 'Comments', 'value' => $this->comments()->count()]
        ];
    }

}
