<?php

namespace App\Http\Controllers;

use App\Models\cr;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Embed\Embed;
use Illuminate\Support\Facades\Auth;
use App\Models\Url;


class commentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $comments= Comment::all();
        return response()->json($comments);
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


        $request->validate([
            'url' => 'required|string',
            'contenue'  => 'required|string'
        ]);

        DB::transaction(function () use ($request) {
        $user = Auth::user();


        //return response()->json($request->url);
        $embed = new Embed();
        $info = $embed->get($request->url);
        //return response()->json($info->title);

        $id_url = (new Comment())->show($info->title);

        //return response()->json($id_url);
        switch(true){
            case ($id_url->isNotEmpty()):
                $comment = Comment::create([
                    'contenue'  => $request->contenue,
                    'url_id'    => $id_url[0]->id,
                    'user_id'   => $user->id
                ]);
                break;
            default :
                $url= Url::create([
                    'url' => $request->url,
                    'titre' => $info->title
                ]);

                $comment = Comment::create([
                    'contenue'  => $request->contenue,
                    'url_id'    => $url->id,
                    'user_id'   => $user->id
                ]);
                break;
        }
        /*/if(!empty($id_url)){
            $comment = Comment::create([
            'contenue'  => $request->contenue,
            'url_id'    => $id_url[0]->id,
            'user_id'   => $user->id
        ]);
        } else {
            
            $url= Url::create([
            'url' => $request->url,
            'titre' => $info->title
        ]);

            $comment = Comment::create([
            'contenue'  => $request->contenue,
            'url_id'    => $url->id,
            'user_id'   => $user->id
        ]);
        }*/

        
        
        return response()->json([
                'title'       => $info->title,
                'description' => $info->description,
                'image'       => $info->image,
                //'code'        => $info->code->html, 
                'provider'    => $info->providerName,
                'author'      => $info->authorName,
                'comment'     => $comment
            ]);
        });

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $comment = Comment::find($id);
        
        if(!(isset($comment))){
            return response()->json(['message'=> "Ce commentaire n'existe pas!"]);
        }
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }
        return response()->json($comment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $comment = Comment::find($id);
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }
        $data = $request->validate([
            'url' => 'required|string',
            'contenue'  => 'required|string'
        ]);
        $filter = array_filter($data, function($value){
                    return !is_null($value) && $value !== '';
                });
        $comment->fill($filter);
        if($comment->isDirty()){
            $comment->save();
            return response()->json(['message'=>"Modification effectuée avec succès"]);
        } else {return response()->json(['message'=>"Aucune modification"]);}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if(!(isset($comment))){
            return response()->json(['message'=>"Ce commentaire n'existe pas!"]);
        }
        $comment->delete();
        return response()->json(['message'=> "Commentaire suprimé avec succes"]);
    
    }
}
