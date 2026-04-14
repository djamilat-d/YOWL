<?php

namespace App\Http\Controllers;

ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

use App\Models\rc;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon; 

class signupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
            $data = $request ->validate([
                'nom' => 'required',
                'email' => 'required|email:rfc,strict,dns|disposable_email',
                'birth_year' => 'required',
                'phone' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required',
            ]);

            $username = $data['nom'];
            $email=$data['email'];
            $password=$data['password'];
            $birth_year = $data['birth_year'];
            $phone=$data['phone'];
            $pass_confirm=$data['password_confirmation'];
            $date = Carbon::parse($birth_year)->age;
            if($password == $pass_confirm){
                if($date >= 13 ){
                    $user= User::create(['name' => $username, 'email' => $email, 'password' => $password, 'birth_Year'=> $birth_year, 'phone' => $phone,]);

                if($user){

                    Auth::login($user);
                    return response()->json([
                        'user' => $user,
                        'message'=>'Utilisateur enregistré avec succes'
                    ], 201);
                    exit();
                }
                }else{return response()->json([
                        'age'=>'Vous n\'avez pas l\'age requise'
                    ]);}
                
            }else {return response()->json([
                        'pass_confi'=>'Erreur password confirmation'
                    ]);}
    }


    /**
     * Display the specified resource.
     */
    public function show(rc $rc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(rc $rc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, rc $rc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(rc $rc)
    {
        //
    }
}
