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
        App\User::truncate();

        factory(App\User::class)->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'activated' => 1
        ]);

        factory(App\User::class)->create([
            'name' => 'Foo',
            'email' => 'foo@example.com',
            'activated' => 1
        ]);

        factory(App\User::class, 5)->create();
    }
}
