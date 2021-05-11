<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orderid');
            $table->foreign('orderid')->references('id')->on('order')->onDelete('cascade')->onUpdate('cascade');
            $table->string('itemname');
            $table->float('itemprice')->nullable();
            $table->unsignedInteger('itemquantity');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item');
    }
}
