<?php
namespace App\Http\Controllers;
use OpenApi\Attributes as OA;



#[OA\Info(
    title: 'YOWL API',
    version: '1.0.0',
    description: 'Dcumentation de l\'API YOWL - app de commentaire'
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Serveur local'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'apiKey',
    name: 'Authorization',
    in: 'header'
)]
class SwaggerController extends Controller
{
}
