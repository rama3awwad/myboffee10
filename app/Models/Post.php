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
        'user_name'
       // 'likes_count'
    ];

   /* public static function create($input, array $array)
    {
    }*/

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favoritePosts()
    {
        return $this->belongsToMany(FavoritePost::class, 'favorite_posts');
    }

}
