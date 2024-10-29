<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'publication_id', // Assurez-vous que publication_id est ici
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

