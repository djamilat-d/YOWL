<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Url;
use App\Models\likes;

class Comment extends Model
{
    //
    use HasFactory;

    protected $fillable = ['user_id','url_id','contenue'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function url(){
        return $this->belongsTo(Url::class);
    }
    public function likes(){
        return $this->hasMany(Like::class);
    }
}
