<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::all();

//        $users->each(function ($user) {
//            $user->articles()->save(
//                factory(App\Article::class)->make()
//            );
//        });

        App\Article::truncate();
        factory(App\Article::class, 50)->create();
    }
}
