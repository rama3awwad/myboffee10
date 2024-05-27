<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'my_points',
        'age',
        'image',
        'gendre_id'
    ];

    public function gendre()
    {
        return $this->belongsTo(Gendre::class, 'gendre_id');
    }

    public function Suggestions()
    {
        return $this->hasMany(Suggestion::class, 'user_id');
    }

    public function shelves()
    {
        return $this->belongsToMany(Book::class, 'shelves')->withTimestamps();
    }

    public function notes()
    {
        return $this->belongsToMany(Book::class, 'notes')->withTimeStamps();
    }

    public function reports()
    {
        return $this->belongsToMany(Book::class,'reports')->withTimeStamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function favoritePosts()
    {
        return $this->belongsToMany(FavoritePost::class, 'post_id');
    }

    public function reviews()
    {
        return $this->belongsToMany(Reviwe::class, 'user_id');
    }

    public function favoriteBooks()
    {
        return $this->belongsToMany(Book::class, 'favorite_books')->withTimestamps();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
