<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Login extends Model
{
    public function login($name_email){
        $users=User::select('id', 'name', 'password',)->where('email',$name_email)->orWhere('name',$name_email)->get();
        
        return $users;
    }
}
