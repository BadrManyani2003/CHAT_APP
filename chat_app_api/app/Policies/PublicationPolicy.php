<?php

namespace App\Policies;

use App\Models\Publication;
use App\Models\User;

class PublicationPolicy
{
    public function update(User $user, Publication $publication)
    {
        return $user->id === $publication->user_id;
    }

    public function delete(User $user, Publication $publication)
    {
        return $user->id === $publication->user_id;
    }
}
