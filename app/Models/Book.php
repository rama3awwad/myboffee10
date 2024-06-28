<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    protected $fillable = [
        'title_en',
        'title_ar',
        'file',
        'cover',
        'author_name_en',
        'author_name_ar',
        'points',
        'description_en',
        'description_ar',
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
        return $this->belongsToMany(User::class, 'notes');
    }

    public function reports()
    {
        return $this->belongsToMany(User::class,'reports')->withTimeStamps();
    }

  /*  public function levels()
    {
        return $this->belongsToMany(User::class,'levels')->withTimeStamps();
    }*/

    public function favoriteBooks()
    {
        return $this->belongsToMany(FavoriteBook::class, 'favorite_books');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }


    use HasFactory;
}
