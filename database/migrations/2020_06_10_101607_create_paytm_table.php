<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaytmTable extends Migration
{
    public function up()
    {
        Schema::create('paytm', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('mobile');
            $table->string('email');
            $table->tinyInteger('status')->default(0);
            $table->integer('fee');
            $table->string('order_id');
            $table->string('transaction_id')->default(0);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('paytm');
    }
}
