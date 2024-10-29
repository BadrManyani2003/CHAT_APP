<?php

namespace App\Providers;

use App\Models\Publication;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Dislike;
use App\Policies\PublicationPolicy;
use App\Policies\CommentPolicy;
use App\Policies\LikePolicy;
use App\Policies\DislikePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Publication::class => PublicationPolicy::class,
        Comment::class => CommentPolicy::class,
        Like::class => LikePolicy::class,
        Dislike::class => DislikePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
