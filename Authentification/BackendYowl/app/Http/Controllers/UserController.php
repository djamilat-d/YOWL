<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use OpenApi\Attributes as OA;

class UserController extends Controller
{

    #[OA\Get(
        path: '/api/users',
        summary: 'Liste tous les utilisatrs',
        security: [['sanctum' => []]],
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des users'
            ),
            new OA\Response(
                response: 403,
                description: 'Acces refuse,juste les admins!!!'
            )
        ]
    )]


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $userConnecter= $request->user();
        if($userConnecter->role !== 'admin'){
            return response()->json(['message'=>"impossible,vous n'ave pas acces"]);
        }
        $users= User::all();
        return response()->json($users);
    }


    #[OA\Post(
        path: '/api/users/add',
        summary: 'Inscriptionn utilisateurs',
        security: [['sanctum' => []]],
        tags: ['Users'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type:'string', example: 'equality'),
                    new OA\Property(property: 'email', type:'string', example: 'equality@gmail.com'),
                    new OA\Property(property: 'password', type: 'string', example: '12345678'),
                    new OA\Property(property: 'password_confirmation', type: 'string', example: '12345678'),
                    new OA\Property(property: 'birth_year', type: 'string', example: '2026-09-28'),
                    new OA\Property(property: 'phone', type: 'string', example: '07 48 74 37 66')
                ])
        ),
        responses: [
            new OA\Response(response: 201,description: 'Utilisateur cree avec successss'),
            new OA\Response(response: 422,description: 'donnees non valides')
        ]
    )]


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $pass = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password'=> 'required|min:8',
            'password_confirmation' => 'required',
            'birth_year'=>'required|date',
            'phone'=>'nullable|string|min:10',
            'photo_profil'=> 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data= [
            'name' => $request->name,
            'email' => $request->email,
            'password'=>$request->password,
            'birth_year'=> $request->birth_year,
            'phone'=> $request->phone,
        ];

        if(User::where('email', $data['email'])->exists()){
                return response()->json(['email'=>'L\'email existe deja']);
            }

        $date = Carbon::parse($data['birth_year'])->age;
        if($data['password'] == $pass['password_confirmation']){
                if($date >= 13 ){
                    /*if($request->hasFile('photo_profil')){
                        $path =$request->file('photo_profil')->store('images', 'public');
                        $data['photo_profil'] = $path;
                    }
                    $user = User::create($data);


                    return response()->json($user);*/
                    if(isset($data['photo_profil']) && !$data['photo_profil']){
                        $path = $request->file('photo_profil')->store('images', 'public');
                        $user= User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password'], 'birth_year'=> $data['birth_year'], 'phone' => $data['phone'],'photo_profil'=>$path]);
                    }else{
                        $user= User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password'], 'birth_year'=> $data['birth_year'], 'phone' => $data['phone']]);//'photo_profil'=>$path]);
                    }
                }else{return response()->json([
                        'age'=>'Vous n\'avez pas l\'age requise'
                    ]);}
        }else {return response()->json([
                        'pass_confi'=>'Erreur password confirmation'
                    ]);}

    }



    #[OA\Get(
        path: '/api/users/{id}',
        summary: 'Afficher un utilisatrs',
        security: [['sanctum' => []]],
        tags: ['Users'],
        parameters:[new OA\Parameter(name: 'id', in: 'path',required: true, schema: new OA\Schema(type:'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'users trouvé'),
            new OA\Response(response: 404, description: 'users non trouvé'),
            new OA\Response(response: 403,description: 'Acces refuse,juste les admins!!!')
        ]
    )]

    /**
     * Display the specified resource.
     */
    public function show(Request $request,$id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(['message'=> "utilisateur n'existe pas !"]);
        }
        $userConnecter= $request->user();
        switch(true){
            case Auth::id() == $user->id :
                return response()->json($user);
                break;
            case $userConnecter && $userConnecter->role == 'admin':
                return response()->json($user);
                break;
            default:
                return response()->json(['message'=> "Impossible vous n'avez pas acces!"]);
                break;
        }

        /*if (Auth::id() !== $user->id) {
            abort(403);
        }
        if(!(isset($user))){
            return response()->json(['message'=> "utilisateur n'existe pas!"]);
        }

        $userConnecter= $request->user();
        if($userConnecter->id !== $id && $userConnecter->role !== 'admin'){
            return response()->json(['message'=>"impossible,vous n'ave pas acces"]);
        }

        return response()->json($user);*/
    }


    #[OA\Patch(
        path: '/api/users/update/{id}',
        summary: 'Modifier un utilisateurs',
        security: [['sanctum' => []]],
        tags: ['Users'],
        parameters:[new OA\Parameter(name: 'id', in: 'path',required: true, schema: new OA\Schema(type:'integer'))],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type:'string', example: 'equality'),
                    new OA\Property(property: 'email', type:'string', example: 'equality@gmail.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '0748743766')
                ])
        ),
        responses: [
            new OA\Response(response: 200,description: 'Utilisateur modifier avec successss'),
            new OA\Response(response: 404, description: 'users non trouvé'),
            new OA\Response(response: 403,description: 'Acces refuse,juste les admins!!!')
        ]
    )]


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $user= User::find($id);
        $userConnecter= $request->user();

        if(!$user){
            return response()->json(['message'=> "utilisateur n'existe pas!"]);
        }
        switch(true){
            case Auth::id() == $user->id :
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
                break;
            case $userConnecter && $userConnecter->role == 'admin':
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
                break;
            default:
                return response()->json(['message'=> "Impossible vous n'avez pas acces!"]);
                break;
        }
    }


    #[OA\Delete(
        path: '/api/users/supprimer/{id}',
        summary: 'Supprimer un utilisatrs',
        security: [['sanctum' => []]],
        tags: ['Users'],
        parameters:[new OA\Parameter(name: 'id', in: 'path',required: true, schema: new OA\Schema(type:'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'users supprimé'),
            new OA\Response(response: 404, description: 'users non trouvé'),
            new OA\Response(response: 403,description: 'Acces refuse,juste les admins!!!')
        ]
    )]


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {

        $user = User::find($id);
        $userConnecter= $request->user();

        if(!$user){
            return response()->json(['message'=> "utilisateur n'existe pas!"]);
        }
        switch(true){
            case Auth::id() == $user->id :
                    $user->delete();
                    return response()->json(['message'=> "utilisateur suprime"]);
                break;
            case $userConnecter && $userConnecter->role == 'admin':
                    $user->delete();
                    return response()->json(['message'=> "utilisateur suprime"]);
                break;
            default:
                return response()->json(['message'=> "Impossible vous n'avez pas acces!"]);
                break;
        }
    }
}
