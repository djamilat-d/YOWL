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

        $url= Url::create([
            'url' => $request->url,
            'titre' => $info->title
        ]);

        $comment = Comment::create([
            'contenue'  => $request->contenue,
            'url_id' => $url->id,
            'user_id' => $user->id
        ]);
        
        return response()->json($comment);
        });

    }

    /**
     * Display the specified resource.
     */
    public function show(cr $cr)
    {
        //
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
    public function update(Request $request, cr $cr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cr $cr)
    {
        //
    }
}
