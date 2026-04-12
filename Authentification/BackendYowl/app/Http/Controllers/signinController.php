<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Login;

class signinController extends Controller
{
    public function get(Request $request){

        $data = $request ->validate([
                'nom_email' => 'required',
                'password' => 'required',
            ]);

                $nom_mail=$data['nom_email'];
                $paswd=$data['password'];
                //verification dans la base de données à partir du modèle signUser
                $user = (new Login())->login($nom_mail);
                if(!empty($user)){

                        foreach($user as $utilisateur){
                        if(Hash::check($paswd,  $utilisateur['password'])){
                            Auth::login($utilisateur);

                            return response()->json([
                                'user' => $utilisateur,
                                'message'=>'Succes Authentification'
                            ], 200);
                            exit();
                        }
                        }

                    }

                }
                //return back()->withErrors(['name_email'=>'Invalid username or email'])->withInput();
            }


?>
