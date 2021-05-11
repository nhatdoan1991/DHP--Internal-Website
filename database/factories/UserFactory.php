<?php

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

$factory->define(App\Models\User::class, function (Faker $faker) {
    //line is to define gender to use with faker's firstName
    $gender = $faker -> randomElement($array = array('male','female','mixed'));
    $role = $faker -> randomElement($array = array('operator','driver'));
    return [
        'role' =>  $role, 
        'firstname' => $faker->firstname($gender),
        'lastname' => $faker->lastname,
        'email' => $faker->unique()->safeEmail,
        'phonenumber' => $faker->phoneNumber,
        'password' => Hash::make('test'),
        
    ];

});

