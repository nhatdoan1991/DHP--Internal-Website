<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orderid');
            $table->foreign('orderid')->references('id')->on('order')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('userid');
            $table->foreign('userid')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');
            $table->string('message');
            $table->boolean('isOperator');
            $table->boolean('isRead');
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
        Schema::dropIfExists('message');
    }
}
