<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users= User::all();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password'=> 'required|min:8',
            'birth_year'=>'nullable|string',
            'phone'=>'nullable|string|min:10',
            'photo_profil'=> 'nullable|string',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password'=>bcrypt($request->password) ,
            'birth_year'=> $request->birth_year,
            'phone'=> $request->phone,
            'photo_profil'=> $request->photo_profil,
        ]);
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        if(!(isset($user))){
            return response()->json(['message'=> "utilisateur n'existe pas!"]);
        }
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $user= User::find($id);
        if(!(isset($user))){
            return response()->json(['message'=> "utilisateur n'existe pas!"]);
        }
        $user->update($request->all());
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if(!(isset($user))){
            return response()->json(['message'=> "utilisateur n'existe pas!"]);
        }
        $user->delete();
        return response()->json(['message'=> "utilisateur suprime"]);
    }
}
