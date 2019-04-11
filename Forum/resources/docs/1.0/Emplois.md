# Projet Emplois

- [Installation](#section-1)
- [Installation de Spatie](#section-2)
- [Utilisation de Spatie](#section-2)

<a name="section-1"></a>

## Installation

Tout d'abord, on crée un nouveau projet avec `laravel new PROJECTNAME` dans le dossier racine de notre ancien projet.

Ensuite, on modifie le fichier Homestead.yaml :

``````yaml
folders:
    - map: D:/Ynov/Projet/Php/Forum
      to: /home/vagrant/code/Forum
    - map: D:/Ynov/Projet/Php/Emplois
      to: /home/vagrant/code/Emplois

sites:
    - map: gui.test
      to: /home/vagrant/code/Forum/public
    - map: gui.job
      to: /home/vagrant/code/Emplois/public

databases:
    - Forum
    - Emplois
``````

Pour connecter le projet à la BDD, on modifie le .env:

``````
DB_CONNECTION=mysql
DB_HOST=192.168.10.10
DB_PORT=3306
DB_DATABASE=Emplois
DB_USERNAME=homestead
DB_PASSWORD=secret
``````

Suivit d'un `php artisan migrate`.

> {warning} Attention !! Ne pas oublier de modifier le fichier host !

<a name="section-2"></a>

## Installation de Spatie

Nous allons voir la gestion des rôles.

Pour cela nous allons utiliser [Spatie](<https://github.com/spatie/laravel-permission#installation>).

Il faut récupérer les paquets de Spatie.

```
composer require spatie/laravel-permission
```

Ensuite, il faut ajouter 

```php
'providers' => [
    // ...
    Spatie\Permission\PermissionServiceProvider::class,
];
```

dans le fichier config/app.php.

```php
'providers' => [

    /*
     * Laravel Framework Service Providers...
     */
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    Illuminate\Bus\BusServiceProvider::class,
    Illuminate\Cache\CacheServiceProvider::class,
    Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
    Illuminate\Cookie\CookieServiceProvider::class,
    Illuminate\Database\DatabaseServiceProvider::class,
    Illuminate\Encryption\EncryptionServiceProvider::class,
    Illuminate\Filesystem\FilesystemServiceProvider::class,
    Illuminate\Foundation\Providers\FoundationServiceProvider::class,
    Illuminate\Hashing\HashServiceProvider::class,
    Illuminate\Mail\MailServiceProvider::class,
    Illuminate\Notifications\NotificationServiceProvider::class,
    Illuminate\Pagination\PaginationServiceProvider::class,
    Illuminate\Pipeline\PipelineServiceProvider::class,
    Illuminate\Queue\QueueServiceProvider::class,
    Illuminate\Redis\RedisServiceProvider::class,
    Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
    Illuminate\Session\SessionServiceProvider::class,
    Illuminate\Translation\TranslationServiceProvider::class,
    Illuminate\Validation\ValidationServiceProvider::class,
    Illuminate\View\ViewServiceProvider::class,
```

On publie ensuite la migration dans le CLI avec 

```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
```

On publie le fichier config dans le CLI avec 

```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
```

<a name="section-3"></a>

## Utilisation de Spatie

[Doc de L'usage](<https://github.com/spatie/laravel-permission#usage>)

En premier lieu, ajouter dans le modèle User (App/User.php) le code ci-dessous.

```
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```

Ensuite, on modifie `database/seeds/DatabaseSeeder.php`:

```php
<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersRolesSeeders::class);
    }
}
```

et on créé la classe `UsersRolesSeeder.php` où l'on ajoute:

```php
<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\Company;
use App\Job;

class UsersRolesSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Role::create(['name' => 'Student']);
        Role::create(['name' => 'Company']);
        Permission::create(['name' => 'CreateJob']);
        Permission::create(['name' => 'ApplyJob']);

        factory(User::class, 50)->create()->each(function ($user) {
            $user->companies()->saveMany(factory(App\Company::class, 1)->make());
            $user->assignRole('Company');
            $user->givePermissionTo('CreateJob');
        });

        $myCompanies = Company::all();
        foreach ($myCompanies as $company){
            factory(Job::class, 1)->create([
                'company_id' => $company->id,
            ]);
        }

        factory(User::class, 50)->create()->each(function ($user) {
            $user->assignRole('Student');
            $user->givePermissionTo('ApplyJob');
        });
    }
}
```

On Créé et modifie la classe Job, Company et User.

``php artisan make:model MODELNAME``



`User.php`

```php

<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function applies(){
        return $this->hasMany('App\Job', 'user_id');
    }

    public function companies(){
        return $this->hasMany('App\Company', 'user_id');
    }
}
```

`Job.php`

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public function owner(){
        return $this->belongTo('App\Company', 'user_id');
    }
}
```

`Company.php`

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function owner(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function jobs(){
        return $this->hasMany('App\Job', 'user_id');
    }
}
```

Ensuite les migrations:

``php artisan make:migration create_NAME_table``



`CreateJobsTable`

```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->string('titre');
            $table->text('contenu');
            $table->integer('company_id');
            $table->bigIncrements('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
```



`CreateCompaniesTable`

```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Company_name');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
```



``CreateUsersTable`

```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
```

Les factories :

`CompanyFactory`

```php
<?php

use Faker\Generator as Faker;
use App\Company;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'Company_name' => $faker->domainName,
    ];
});
```

``JobFactory`

```php
<?php

use Faker\Generator as Faker;
use App\Job;
$factory->define(Job::class, function (Faker $faker) {
    return [
        'titre' => $faker->sentence,
        'contenu' => $faker->paragraph,
    ];
});
```

`UserFactory`

```php
<?php

use Faker\Generator as Faker;
use App\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
```

