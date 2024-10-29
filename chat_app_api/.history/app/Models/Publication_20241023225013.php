<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'img', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
{
    return $this->hasMany(Like::class);
}

public function dislikes()
{
    return $this->hasMany(Dislike::class);
}

public function commentaires()
{
    return $this->hasMany(Commentaire::class, 'postid');
}

}

