<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        App\User::create([
//            'name'     => sprintf('%s %s', str_random(3), str_random(4)),
//            'email'    => str_random(10) . '@example.com',
//            'password' => bcrypt('password'),
//        ]);

        factory(App\User::class, 5)->create();
    }
}
