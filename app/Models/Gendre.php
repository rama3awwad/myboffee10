<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gendre extends Model
{

    protected $fillable = ['name'];


    public function users()
    {
        return $this->hasMany(User::class, 'gendre_id');
    }

    use HasFactory;

}
