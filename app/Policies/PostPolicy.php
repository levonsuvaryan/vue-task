<?php

namespace App\Policies;

use App\Models\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function manage(User $user)
    {
        return $user->isAdmin() || $user->isUser();
    }
    
    public function create(User $user)
    {
        return $user->isAdmin() || $user->isUser();
    }  
    
    public function view(User $user, Post $post)
    {
        return $user->isAdmin() || $user->id === $post->user_id;
    }

    public function update(User $user, Post $post)
    {
        return $user->isAdmin() || $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post)
    {
        return $user->isAdmin() || $user->id === $post->user_id;
    }

    public function uploadImage(User $user, Post $post)
    {
        return $user->isAdmin() || $user->id === $post->user_id;
    }

}
