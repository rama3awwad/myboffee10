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

    public function users()
    {
        return $this->belongsToMany(User::class, 'shelves')
            ->withPivot('status', 'progress')
            ->withTimestamps();
    }



    use HasFactory;
}
