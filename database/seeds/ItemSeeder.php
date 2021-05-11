<?php

use Illuminate\Database\Seeder;
use \App\Models\Item;
use Illuminate\Support\Facades\DB;
//use Faker\Generator as Faker;
use App\Models\User;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $typesOfPies = array('Apple','Pecan','Apple Crisp',
        'Banna Cream','Blackberry','Buttermilk',
        'Cantelope','Cherry','Chestnut',
        'Chiffon','Key Lime','Lemon','Peach' );
        $faker = Faker\Factory::create();
        
        
    }
}
