<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $activated = $faker->randomElement([0, 1]);

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
        'activated' => $activated,
        'confirm_code' => $activated ? null : str_random(60),
    ];
});

$factory->define(App\Article::class, function (Faker\Generator $faker) {
    $date = $faker->dateTimeThisMonth;
    $userId = App\User::pluck('id')->toArray();

    return [
        'title' => $faker->sentence(),
        'content' => $faker->paragraph(),
        'user_id' => $faker->randomElement($userId),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});

$factory->define(App\Attachment::class, function (Faker\Generator $faker) {
    return [
        'filename' => sprintf("%s.%s",
            str_random(),
            $faker->randomElement(config('project.mimes'))
        )
    ];
});
