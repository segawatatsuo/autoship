<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('order_number')->nullable()->comment('受注番号');
            $table->string('item_number')->nullable()->comment('商品番号');
            $table->string('item_name')->nullable()->comment('商品名');
            $table->integer('price')->nullable()->comment('価格');
            $table->integer('amount')->nullable()->comment('単価');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
