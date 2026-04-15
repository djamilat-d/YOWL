<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Url extends Model
{
    use HasFactory;

    
    protected $fillable = [   
        'url',
        'titre'     
    ];

    public function show($titre){
        $id=Url::select('id')->where('name',$titre)->get();
        return $id;
    }
   
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

}
