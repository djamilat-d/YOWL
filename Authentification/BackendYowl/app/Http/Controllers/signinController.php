<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Login;
use OpenApi\Attributes as OA;

class signinController extends Controller
{

    #[OA\Post(
        path: '/api/signin',
        summary: 'Connexion des utilisatrs',
        tags: ['Authentification'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'equality@gmail.com'),
                    new OA\Property(property: 'password', type: 'string', example: '12345678')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200,description: 'conexxion reusie'),
            new OA\Response(response: 403,description: 'identifiants incorrects')
        ]
    )]

    public function get(Request $request){

        $data = $request ->validate([
                'email' => 'required',
                'password' => 'required',
            ]);

                $nom_mail=$data['email'];
                $paswd=$data['password'];
                //verification dans la base de données à partir du modèle signUser
                $user = (new Login())->login($nom_mail);
                if($user->isNotEmpty()){

                        foreach($user as $utilisateur){
                        if(Hash::check($paswd,  $utilisateur['password'])){
                            Auth::login($utilisateur);

                            $token = Auth::user()->createToken('API Token')->plainTextToken;
                            //$cookie = cookie('jwt', $token, 60*24);
                            return response()->json([
                                'user' => $utilisateur,
                                'token' => $token,
                                'message'=>'Succes Authentification'
                            ]);

                        }else{return response()->json(['paswd'=>'Username, email or password incorrect']);}

                        }

                    } else {return response()->json([
                        'mail'=>'Username or email incorrect'
                            ]);
                        }

                }

            }


?>
