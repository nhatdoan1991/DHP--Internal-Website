<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    //fake address database
    $sacMetroAddress = array(
        array("1831 S St #100", "Sacramento", 95811), array("1517 Broadway", "Sacramento", 95818),
        array("4960 Freeport Blvd", "Sacramento", 95822), array("2057 Arena Blvd #150", "Sacramento", 95834),
        array("5209 North Ave", "Carmichael", 95608), array("6701 63rd St", "Sacramento", 95828),
        array("5240 Fruitridge Rd", "Sacramento", 95820), array("6656 Florin Rd", "Sacramento", 95828),
        array("4400 47th Ave", "Sacramento", 95823), array("5715 Stockton Blvd", "Sacramento", 95824),
        array("5924 S Land Park Dr", "Sacramento", 95822), array("3010 Florin Rd", "Sacramento", 95822),
        array("2250 John Still Dr", "Sacramento", 95832), array("7591 24th St", "Sacramento", 95822),
        array("388 Florin Rd", "Sacramento", 95831), array("277 Brewster Ave", "Sacramento", 95831),
        array("1100 Howe Ave", "Sacramento", 95825), array("2025 Morse Ave", "Sacramento", 95825),
        array("4315 Arden Way", "Sacramento", 95864), array("4540 American River Dr", "Sacramento", 95864),
        array("6456 Fair Oaks Blvd", "Carmichael", 95608), array("3333 Marconi Ave", "Sacramento", 95821),
        array("2885 Norwood Ave", "Sacramento", 95815), array("3298 Northgate Blvd", "Sacramento", 95834),
    );
    $metro = $faker -> randomElement($sacMetroAddress);

    //line is to define gender to use with faker's firstName
    $gender = $faker -> randomElement($array = array('male','female','mixed'));
    return [
        'firstname' => $faker->firstname($gender),
        'lastname' => $faker->lastname,
        'email' => $faker->unique()->safeEmail,
        'phonenumber' => $faker->tollFreePhoneNumber,
        'address' => $metro[0],
        'city' => $metro[1],
        'zipcode' => $metro[2],
        'state' => 'CA',
        
    ];
});
