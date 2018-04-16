<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Profile::class, function (Faker $faker) {
    // 随机取一个月以内的时间
    $time = $faker->dateTimeThisMonth();

    // 随机取过去30年的时间
    $birthday = $faker->dateTimeBetween('-30 years');
    return [
        'name' => $faker->name,
        'birthday' => $birthday,
        'ethnic' => '汉族',
        'address' => $faker->streetAddress,
        'desc' => $faker->sentence(),
        'created_at' => $time,
        'updated_at' => $time,
    ];
});
