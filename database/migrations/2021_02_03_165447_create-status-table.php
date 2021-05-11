<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  create pivot table for order status
            Schema::create('orderstatus', function (Blueprint $table) {
            $table->id();
            $table->string('statusname');
            $table->timestamps();
        });
        
         //Insert the value of status into this array. 0=list ect.
         $orderStatus = array( 'list', 'queue' , 'delivery' , 'archive');
        
         foreach($orderStatus as $item=>$values){
            DB::table('orderstatus')->insert([
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
        Schema::dropIfExists('orderstatus');
    }
}
