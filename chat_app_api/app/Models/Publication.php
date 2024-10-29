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
        return $this->hasMany(Like::class, 'publication_id'); // Assurez-vous que 'publication_id' est correct
    }

    public function dislikes()
    {
        return $this->hasMany(Dislike::class, 'publication_id'); // Assurez-vous que 'publication_id' est correct
    }

    public function commentaires()
    {
        return $this->hasMany(Comment::class, 'publication_id'); // Changer 'post_id' à 'publication_id'
    }
}
