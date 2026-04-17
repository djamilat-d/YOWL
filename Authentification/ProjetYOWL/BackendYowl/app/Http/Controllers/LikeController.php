<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $likes= Like::with('user','comment')->get();
        return response()->json($likes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'=>'required|exists:users,id',
            'comment_id'=>'required|exists:comments,id',
        ]);

        $likeExist = Like::where('user_id',$request->user()->id)->where('comment_id',$request->comment_id)->first();
        if($likeExist){
            $likeExist->delete();
            return response()->json(['message'=>'le like a ete retirer']);
        }

        $like= Like::create([
            'user_id'=>$request->user()->id,
            'comment_id'=>$request->comment_id,
        ]);
        return response()->json($like);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $like=Like::find($id);
        if(!$like){
            return response()->json(['message'=>'aucun like trouvee']);
        }

        if($like->user_id !== request()->user()->id){
            return response()->json(['message'=>"impossible de supprimee"]);
        }
        $like->delete();
        return response()->json(['message'=>'like supprimer avec suucces']);
    }
}
