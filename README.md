PROJET YOWL

YOWL est une application web permettant aux utilisateurs de comenter n'inporte quel contenu trouver sur internet. Chaque commentaire est partager par toute la communaute YOWL.

Equipes

Anini Kouakou | Frontend Vue js
Djamilat Diarrassouba | CRUD Users, API
Guy Kone | Authentification ,CRUD & User Commentaire


Fonctionnalites 

-Backend : Laravel (PHP)
-Frontend : Vue.js
-Base de donées : MYSQL
-Authentification : Laravel Sanctum
-Documentation API : Swagger

Pour le BACKEND :

Installation:
-PHP 8.2.4
-Composer
-Node.js
-Mysql

repo du projet : git@github.com:EpitechCodingAcademyPromo2026/C-DEV-160-ABJ-1-2-yowl-1.git

installer les dependance avec: npm install
configuration du fichier .env pour la Bd

on va generer la cle de l'application en faisant 
 php artisan key:generate
ensuite lancer les migrations avec:
 php artisan migrate
et enfin lancer le serveur en faisantphp artisan serve

Pour le FRONTEND:

nous allons ,
installer les dependance avec: npm install
demarrer le serveur 
npm run dev

Pour l'Authentification :
L'API utilise Laravel Sanctum pour acceder aux routes proteges :

-creer un compte via `POST /api/signup`
-Se connecter via  `POST /api/signin`
-Utiliser le token recu dans le header de chaque requete(Authorization: Bearer {token})

les Routes Principales :

.......


Docker :

docker-compose up -d