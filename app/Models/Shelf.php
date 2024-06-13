<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Shelf extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'progress'
    ];


   /* public function books()
    {
        return $this->hasMany(Book::class, 'book_id');
    }


    public function users()
    {
        return $this->hasMany(User::class);
    }*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }


    use HasFactory;

}
