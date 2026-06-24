<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $userConnecter= $request->user();
        if($userConnecter->role !== 'admin'){
            return response()->json(['message'=>"impossible,vous n'ave pas acces"]);
        }
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
            'birth_year'=>'nullable|date',
            'phone'=>'nullable|string|min:10',
            'photo_profil'=> 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data= [
            'name' => $request->name,
            'email' => $request->email,
            'password'=>bcrypt($request->password) ,
            'birth_year'=> $request->birth_year,
            'phone'=> $request->phone,
        ];
        if($request->hasFile('photo_profil')){
            $path =$request->file('photo_profil')->store('icons', 'public');
            $data['photo_profil'] = $path;
        }
        $user = User::create($data);
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,$id)
    {
        $userConnecter= $request->user();
        if($userConnecter->id !== $id && $userConnecter->role !== 'admin'){
            return response()->json(['message'=>"impossible,vous n'ave pas acces"]);
        }

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

        $user= User::find($id);
        if(!(isset($user))){
            return response()->json(['message'=> "utilisateur n'existe pas!"]);
        }

        $userConnecter= $request->user();
        if($userConnecter->id !== $id && $userConnecter->role !== 'admin'){
            return response()->json(['message'=>"impossible,vous n'ave pas acces"]);
        }


        $data= $request->only(['name','email','birth_year','phone']);
        if($request->hasFile('photo_profil')){
            if($user->photo_profil){
                Storage::disk('public')->delete($user->photo_profil);
            }
            $path= $request->file('photo_profil')->store('avatars','public');
            $data['photo_profil']=$path;
        }
        $user->update($data);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        $userConnecter= $request->user();
        if($userConnecter->id !== $id && $userConnecter->role !== 'admin'){
            return response()->json(['message'=>"impossible,vous n'ave pas acces"]);
        }

        $user = User::find($id);
        if(!(isset($user))){
            return response()->json(['message'=> "utilisateur n'existe pas!"]);
        }
        $user->delete();
        return response()->json(['message'=> "utilisateur suprime"]);
    }
}
