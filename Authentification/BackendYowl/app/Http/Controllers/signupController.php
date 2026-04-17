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
use Illuminate\Support\Facades\Validator;

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
                'name' => 'required',
                'email' => 'required|email:rfc,strict,dns|disposable_email',
                'birth_year' => 'required|date',
                'phone' => 'required',
                'password' => 'required|min:8',
                'password_confirmation' => 'required',
                'photo_profil'=> 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);

            
            $username = $data['name'];
            $email=$data['email'];
            $password=$data['password'];
            $birth_year = $data['birth_year'];
            $phone=$data['phone'];
            $pass_confirm=$data['password_confirmation'];



            if(User::where('email', $email)->exists()){
                return response()->json(['email'=>'L\'email existe deja']);
            }
            

            $date = Carbon::parse($birth_year)->age;
            if($password == $pass_confirm){
                if($date >= 13 ){
                    /*if($request->hasFile('photo_profil')){
                        $path =$request->file('photo_profil')->store('icons', 'public');
                        $data['photo_profil'] = $path;
                    }
                    $user = User::create($data);*/
                    if(isset($data['photo_profil']) && !$data['photo_profil']){
                        $path = $request->file('photo_profil')->store('images', 'public');
                        $user= User::create(['name' => $username, 'email' => $email, 'password' => $password, 'birth_year'=> $birth_year, 'phone' => $phone,'photo_profil'=>$path]);
                    }else{
                        $user= User::create(['name' => $username, 'email' => $email, 'password' => $password, 'birth_year'=> $birth_year, 'phone' => $phone,]);//'photo_profil'=>$path]);
                    }                    

                if($user){

                    Auth::login($user);
                    $token = Auth::user()->createToken('API Token')->plainTextToken;
                    return response()->json([
                        'user' => $user,
                        'token' => $token,
                        'succes'=>'Utilisateur enregistré avec succes'
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
