<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {

    $typesOfPies = array('Apple','Pecan','Apple Crisp',
        'Banna Cream','Blackberry','Buttermilk',
        'Cantelope','Cherry','Chestnut',
        'Chiffon','Key Lime','Lemon','Peach' );

    return [
            
        'orderid' => factory(App\Models\Order::class),
	    'itemname' => $faker->randomElement($typesOfPies),
	    'itemquantity' => rand(1,4),       
    ];
});
