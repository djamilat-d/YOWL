<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    
    protected $fillable = [   
        'user_id',
        'url_id',
        'contenue'     
    ];

    public function show($titre){
        $id_url=Url::select('id')->where('titre',$titre)->get();
        return $id_url;
    }
   
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function url(): BelongsTo
    {
        return $this->belongsTo(Url::class);
    }

}
