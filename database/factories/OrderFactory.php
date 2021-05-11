<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    
    // random delivery instructions 2 NULL to create more chance of no instruction
    $deliveryInstruction = array("Please Ring Doorbell","Knock on door",NULL,
        NULL,"Set on Patio"
    );
    
    return [
        
        // Run a single seeder...
        'status'=> $faker->numberBetween(1,1),
        //'grouporderid' => ,
        'deliverydate' => $faker->dateTimeBetween('now', '+15 days'),
        'deliveryinstruction' => $faker->randomElement($deliveryInstruction),
        'customerid' => factory(App\Models\Customer::class),
    ];
});

