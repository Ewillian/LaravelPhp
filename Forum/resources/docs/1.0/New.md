# Installation

------

- [Les bases](#section-1)
- [Installation BDD](#section-2)
- [Création d'utilisateur](#section-3)
- [Installation telescope](#section-4)
- [Création 100 d'utilisateurs](#section-5)
- [Post Model](#section-6)
- [Post Factory](#section-7)
- [Post Seeders](#section-8)
- [Post Show](#section-9)
- [Outils Utiles](#section-100)

<a name="section-1"></a>

## Les bases

L'installation de base (pour windows) nécéssite:

- [Xampp](https://www.apachefriends.org/fr/index.html)

  - Base de donnée SQL

- [Php 7](https://windows.php.net/download/)

- [PhpStorm](https://www.jetbrains.com/phpstorm/)

  - Normalement installe [composer](https://kgaut.net/blog/2017/installer-composer-sous-windows.html)

  

<a name="section-2"></a>

## Installation BDD

Après avoir créée la bdd sur phpmyadmin, on la lie avec phpstorm et on modifie les lignes suivantes :

```php
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=Forum
  DB_USERNAME=root
  DB_PASSWORD=
```

<a name="section-3"></a>

## Création d'utilisateur

Pour créer un utilisateur, il faut d'abord utiliser ``php artisan make:auth`` dans le CLI

Ensuite il est possible de créer un utilisateur sur l'onglet ``register``

<a name="section-4"></a>

## Installation Telescope

Dans le CLI taper les commandes suivante:

```CLI
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
php artisan telescope:publish
```

<a name="section-5"></a>

## Création de 100 utilisateurs

``php artisan make:seeder UsersTableSeeder``

Après avoir utilisé la commande ci-dessus, dans database/seeds/UsersTableSeeder.php on modifie le fichier ``UsersTableSeeder``

```php
    public function run()
    {
        factory(App\User::class, 100)->create();
    }
```

Ensuite ajouter dans database/seeds/DatabaseSeeder.php

```php
    public function run()
    {
        $this->call(UsersTableSeeder::classs);
    }
```

Pour finir on utilise la commande ``php artisan db:seed``.



## Post Model

Tout d'abord, la création de model se fait avec la commande ``php artisan make:model Post`` dans le CLI.



> {warning} Attention !! Pas créée dans la BDD ni fait les fichier de migration !!

  Donc pour cela, nous allons faire `` php artisan make:migration create_posts_table``dans le CLI pour créer dans la BDD.

Ensuite nous allons dans database/migrations/.....create_post_table (le fichier créée dans la commande précédente) ajouter les lignes de création des colonnes.

```php
public function up()
{
    Schema::create('posts', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('titre');
        $table->text('contenu');
        $table->unsignedBigInteger('user_id');
        $table->foreign('user_id')->references('id')->on('users');
        $table->timestamps();
    });
}
```

Pour finir, on exécute dans le CLI ``php artisan migrate`` pour migrer le 'model' de table que l'on vient de définir



<a name="section-7"></a>

## Post Factory

Pour créer la factory on utilise dans le CLI `` php artisan make:factory PostFactory``.

Ensuite le fichier factory précédement créée, doit ressembler à:

```php


use Faker\Generator as Faker;
use App\Post;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'titre' => $faker->sentence,
        'contenu' => $faker->paragraph,
    ];
```

<a name="section-8"></a>

## Post Seeders

Aves la commande CLI ``php artisan make:seeder PostTableSeeder`` nous allons créer le seeder.

Dans database/seeds/PostTableSeeder.php, on ajoute:

```php
public function run()
{
    factory(App\User::class, 50)->create()->each(function ($user) {
        $user->posts()->save(factory(App\Post::class)->make());
    });
}
```

Ainsi que dans database/seeds/DatabaseSeeder.php

```php
public function run()
{
    //$this->call(UsersTableSeeder::class);
    $this->call(PostTableSeeder::class);
}
```

> {danger.fa-exclamation-triangle} On n'oublie pas de commenter le premier callback !! Sinon il va recréer 100 utilisateurs en plus !!!

Dans app/Providers/User.php on ajoute:

```php
public function posts(){
    return $this->hasMany('App\Post', 'user_id');
}
```

Quant à app/Providers/Post.php on ajoute:

```php
public function owner(){
    return $this->belongsTo('App\User', 'user_id');
}
```



Pour finir on utilise la commande ``php artisan db:seed``.



<a name="section-9"></a>

## Post Show

Dans Database/Seeds/PostTableSeeders.php on ajoute :

```php
    public function run()
    {
        factory(App\User::class, 50)->create()->each(function ($user) {
            $user->posts()->saveMany(factory(App\Post::class, 3)->make());
        });
    }
```

Autrement dit par utilisateurs créé, on ajoute 3 Posts.

Dans le HomeController.php, on envois un objet Posts à GET par la page.

```php
use Illuminate\Support\Facades\Auth;

public function index()
    {
        //Récupération des posts ici
        $user = Auth::user();
        $postsUser = $user->posts;
        return view('home', ["posts"=> $postsUser]);
    }
```

Dans User.php

```php
public function posts()
    {
        return $this->hasMany('App\Post', 'user_id');
    }
```

Et enfin dans Home.blade.php, on modifie la page pour afficher les Posts. 

![HomeBlade](D:\Ynov\Projet\Php\Forum\resources\docs\1.0\Images\HomeBlade.png)

Pour mettre à jour la BDD, on effectue la commande `php artisan migrate:reset`.

On effectue un `php artisan migrate` et un `php artisan db:seeds`.



<a name="section-100"></a>

## Outils Utiles

### Dans CLI

`` php artisan list`` == affiche les commandes (équivalent au help).

`` php artisan make --help`` == help du make de artisan.

``php artisant [option] --help`` == help d'une commande

### Liens

[Columns Type](https://laravel.com/docs/5.8/migrations#columns)