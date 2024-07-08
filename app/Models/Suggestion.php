<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'body',
        'author_name',
    ];

    use HasFactory;
}
