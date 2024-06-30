<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{

    protected $fillable =[
        'user_id',
        'books',
        'levels',
    ];
    use HasFactory;

    /*public function users()
    {
        return $this->hasMany(User::class);
    }*/
    public function users()
    {
        return $this->belongsTo(User::class);
    }


}
