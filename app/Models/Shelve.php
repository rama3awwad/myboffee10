<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Shelve extends Model
{
    protected $fillable = ['user_id', 'book_id', 'status', 'progress'];

    use HasFactory;
}
