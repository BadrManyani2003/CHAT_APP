<?php

namespace App\Policies;

use App\Models\Dislike;
use App\Models\User;

class DislikePolicy
{
    public function delete(User $user, Dislike $dislike)
    {
        return $user->id === $dislike->user_id;
    }
}
