<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'body',
        'likes_count'
    ];

   /* public static function create($input, array $array)
    {
    }*/

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorite_posts()
    {
        return $this->belongsToMany(FavoritePost::class, 'post_id');
    }

   /* public function favorite_post()
    {
        return $this->belongsToMany(FavoritePost::class, 'likes_count');
    }*/
}
