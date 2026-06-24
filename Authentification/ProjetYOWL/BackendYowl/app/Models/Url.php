<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Url extends Model
{
    //
    use HasFactory;

    protected $fillable = ['url',
                           'titre'];
    public function comments(){
        return $this->hasMany(\App\Models\Comment::class);
    }
}
