<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status')->nullable();
            $table->foreign('status')->references('id')->on('orderstatus')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('deliverystatus')->nullable();
            $table->foreign('deliverystatus')->references('id')->on('deliverystatus')->onDelete('cascade')->onUpdate('cascade');
            $table->date('deliverydate')->format('m-d-Y')->nullable();
            $table->unsignedBigInteger('grouporderid')->nullable();
            $table->foreign('grouporderid')->references('id')->on('grouporder')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('index')->nullable();
            $table->string('reportnote')->nullable();
            $table->string('deliveryinstruction')->nullable();
            $table->unsignedBigInteger('customerid');
            $table->foreign('customerid')->references('id')->on('customer')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('order');
    }
}
