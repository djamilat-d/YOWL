<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $urls =Url::withCount('comments')->get();
        return response()->json($urls);
        //pour savoir combien de commentaire chaq URL a reçu
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //ici on va creer un nouvelle url
        $request->validate([
            'url'=>'required|url|unique:urls,url',
            'titre'=>'nullable|string',
        ]);

        $url= Url::create([
            'url' =>$request->url,
            'titre'=>$request->titre,
            'user_id'=>$request->user()->id,
        ]);
        return response()->json($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //ici on va recuperer une url grce a son id avectous ses commtre
        $url= Url::with('comments')->find($id);

        if(!$url){
            return response()->json(['message'=>"pas d'url trouvée"]);
        }
        return response()->json($url);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $url= Url::find($id);
        if(!$url){
            return response()->json(['message'=>"pas d'url trouvée"]);
        }
        $url->update($request->only(['url','titre']));
        return response()->json($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        $url = Url::find($id);
         if(!$url){
            return response()->json(['message'=>"pas d'url trouvée"]);
        }
        $userConnecter= $request->user();

        if($userConnecter->id === $url->user_id || $userConnecter->role === 'admin'){
            $url->delete();
            return response()->json(['message'=>"url supprimee avec succes"]);
        }
        return response()->json(['message'=>"vous n'etes pas autoriserrrr"]);
    }
}
