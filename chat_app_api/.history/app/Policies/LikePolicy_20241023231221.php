<?php

namespace App\Policies;

use App\Models\Like;
use App\Models\User;

class LikePolicy
{
    public function delete(User $user, Like $like)
    {
        return $user->id === $like->user_id;
    }
}
