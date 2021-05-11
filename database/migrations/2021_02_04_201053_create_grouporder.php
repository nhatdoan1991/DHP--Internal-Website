<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrouporder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grouporder', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('refuserid')->nullable();
            $table->foreign('refuserid')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');
            $table->string('groupname');
            $table->date('deliverydate')->nullable();
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
        Schema::dropIfExists('grouporder');
    }
}
