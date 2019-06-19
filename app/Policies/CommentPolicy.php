<?php

namespace App\Policies;

use App\Models\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Comment $comment)
    {
        return $user->isAdmin() || $user->id === $comment->user_id;
    }

    public function delete(User $user, Comment $comment)
    {
        return $user->isAdmin() || $user->id === $comment->user_id;
    }



}
