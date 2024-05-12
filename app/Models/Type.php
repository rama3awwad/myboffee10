<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{

    protected $fillable = ['name'];

    //one to many between types and books
    public function books()
    {
        return $this ->hasMany(Book::class, 'type_id');
    }

    use HasFactory;
}
