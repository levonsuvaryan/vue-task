<?php

namespace App\Policies;

use App\Models\Image;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Image $image)
    {
        return $user->isAdmin() || $user->id === $image->user_id;
    }

    public function delete(User $user, Image $image)
    {
        return $user->isAdmin() || $user->id === $image->user_id;
    }

}
