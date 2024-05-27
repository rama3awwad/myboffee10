<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoritePost extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'likes_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

   /* public function post1()
    {
        return $this->belongsToMany(Post::class, 'likes_count');
    }*/
    use HasFactory;
}
