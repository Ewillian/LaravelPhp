<?php

use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        factory(App\User::class, 50)->create()->each(function ($user) {
            $user->posts()->saveMany(factory(App\Post::class, 3)->make());
        });
    }
}
