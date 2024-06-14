<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    protected $fillable = [
        'title',
        'file',
        'cover',
        'author_name',
        'points',
        'description',
        'total_pages',
        'type_id'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function shelves()
    {
        return $this->belongsToMany(User::class, 'shelves')->withTimestamps();
    }


    public function reviews()
    {
        return $this->belongsToMany(Reviwe::class, 'book_id');
    }

    public function notes()
    {
        return $this->belongsToMany(User::class, 'notes')->withTimeStamps();
    }

    public function reports()
    {
        return $this->belongsToMany(User::class,'reports')->withTimeStamps();
    }

    public function levels()
    {
        return $this->belongsToMany(User::class,'reports')->withTimeStamps();
    }

    public function favoriteBooks()
    {
        return $this->belongsToMany(FavoriteBook::class, 'favorite_books');
    }

    use HasFactory;
}
