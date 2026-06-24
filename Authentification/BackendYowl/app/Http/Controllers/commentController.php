<?php

namespace App\Http\Controllers;

use App\Models\cr;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Embed\Embed;
use Illuminate\Support\Facades\Auth;
use App\Models\Url;
use OpenApi\Attributes as OA;



class commentController extends Controller
{


    // #[OA\Get(
    //     path: '/api/comments',
    //     summary: 'Liste tous les utilisatrs',
    //     security: [['sanctum' => []]],
    //     tags: ['Comments'],
    //     responses: [
    //         new OA\Response(response: 200,description: 'Liste des commentaires')
    //     ]
    // )]

    /**
     * Display a listing of the resource.
     * ici on va essayer d'aller chercher les reponses des commenteaire parent
     */
    public function index()
    {
        //$comments= Comment::with('user','url')->get()->paginate(5);
        $comments= Comment::with('replies','url')->whereNull('parent_id')->paginate(500000);
        return response()->json($comments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    // #[OA\Post(
    //     path: '/api/add-comment',
    //     summary: 'Ajouter des commentaires',
    //     security: [['sanctum' => []]],
    //     tags: ['Comments'],
    //     requestBody: new OA\RequestBody(
    //         required: true,
    //         content: new OA\JsonContent(
    //             properties: [
    //                 new OA\Property(property: 'url', type:'string', example: 'https://equality.com'),
    //                 new OA\Property(property: 'contenue', type:'string', example: 'Meilleur Groupe '),
    //             ])
    //     ),
    //     responses: [
    //         new OA\Response(response: 200,description: 'commentaire cree avec successss'),
    //         new OA\Response(response: 422,description: 'donnees non valides')
    //     ]
    // )]

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //


        $request->validate([
            'url' => 'required_without:parent_id|nullable|string',
            'parent_id'=> 'nullable|exists:comments,id',
            'contenue'  => 'required|string'
        ]);

        //on va gerer le cas ou ces un sous-commentaire et le cas ou il est le commentre parent
        //si ces un sosu commentre on va recuperer l'url du commentaire parent
        $user = Auth::user();

        if($request->parent_id){
            $comment = Comment::create([
                'contenue'  => $request->contenue,
                'user_id'   => $user->id,
                'parent_id'=> $request->parent_id,
                'url_id'    =>Comment::find($request->parent_id)->url_id,
            ]);
            return response()->json($comment);
        }

        // DB::transaction(function () use ($request,$user) {


        //return response()->json($request->url);
        $embed = new \Embed\Embed();
        $info = $embed->get($request->url);
        // $info = \Embed\Embed::create(request->url);
        //return response()->json($info->title);

        $id_url = (new Comment())->show($info->title);

        //return response()->json($id_url);
        switch(true){
            case ($id_url->isNotEmpty()):
                $comment = Comment::create([
                    'contenue'  => $request->contenue,
                    'url_id'    => $id_url[0]->id,
                    'user_id'   => $user->id,
                    'parent_id'=> null,
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
                    'user_id'   => $user->id,
                    'parent_id'=>null,
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
        // });

    }


    // #[OA\Get(
    //     path: '/api/comment-modifier/{id}',
    //     summary: 'afficherer un commentaire',
    //     security: [['sanctum' => []]],
    //     tags: ['Comments'],
    //     parameters:[new OA\Parameter(name: 'id', in: 'path',required: true, schema: new OA\Schema(type:'integer'))],
    //     responses: [
    //         new OA\Response(response: 200, description: 'commentaire trouvé'),
    //         new OA\Response(response: 404, description: 'commentaire non trouvé'),
    //         new OA\Response(response: 403,description: 'Acces refuse,juste les admins!!!')
    //     ]
    // )]

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
    public function edit($id)
    {
        //

    }


    // #[OA\Patch(
    //     path: '/api/comment-update/{id}',
    //     summary: 'Modifier un commentaire',
    //     security: [['sanctum' => []]],
    //     tags: ['Comments'],
    //     parameters:[new OA\Parameter(name: 'id', in: 'path',required: true, schema: new OA\Schema(type:'integer'))],
    //     requestBody: new OA\RequestBody(
    //         required: true,
    //         content: new OA\JsonContent(
    //             properties: [
    //                 new OA\Property(property: 'contenue', type:'string', example: 'equality modifier'),
    //             ])
    //     ),
    //     responses: [
    //         new OA\Response(response: 200,description: 'commentaire modifier avec successss'),
    //         new OA\Response(response: 403,description: 'Acces refuse,juste les admins!!!')
    //     ]
    // )]


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



    // #[OA\Delete(
    //     path: '/api/comment-supprimer/{id}',
    //     summary: 'Supprimer un commentaire',
    //     security: [['sanctum' => []]],
    //     tags: ['Comments'],
    //     parameters:[new OA\Parameter(name: 'id', in: 'path',required: true, schema: new OA\Schema(type:'integer'))],
    //     responses: [
    //         new OA\Response(response: 200, description: 'commentaire supprimé'),
    //         new OA\Response(response: 404, description: 'commentaire non trouvé'),
    //         new OA\Response(response: 403,description: 'Acces refuse,juste les admins!!!')
    //     ]
    // )]

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if(!(isset($comment))){
            return response()->json(['message'=>"Ce commentaire n'existe pas!"]);
        }
        if(Auth::id() !== $comment->user_id){
            return response()->json(['message'=>"vous n'avez pas acces"]);
        }
        $comment->delete();
        return response()->json(['message'=> "Commentaire suprimé avec succes"]);

    }
}
