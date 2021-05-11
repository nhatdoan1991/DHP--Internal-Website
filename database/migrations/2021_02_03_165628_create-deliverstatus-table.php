<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliverstatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create table for status of delivery orders
        Schema::create('deliverystatus', function (Blueprint $table) {
            $table->id();
            $table->string('statusname');
            $table->timestamps();
        });

         //Insert the value of status into this array. 0=list ect.
         $status = array( 'inkitchen', 'indelivery' , 'unfulfilled' , 'completed');
         foreach($status as $item=>$values){
             DB::table('deliverystatus')->insert([
                 'statusname' => $values,    
             ]);
            }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('deliverystatus');
    }
}
