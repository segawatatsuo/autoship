<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('order_number')->nullable()->comment('受注番号');
            $table->string('email')->nullable()->comment('メール');
            $table->string('name')->nullable()->comment('名前');
            $table->string('kana')->nullable()->comment('カナ');
            $table->string('tel')->nullable()->comment('電話');
            $table->string('postal')->nullable()->comment('郵便番号');
            $table->string('prefecture')->nullable()->comment('都道府県');
            $table->string('city')->nullable()->comment('市区町村');
            $table->string('street')->nullable()->comment('番地');
            $table->string('interval')->nullable()->comment('お届け間隔');
            $table->string('week')->nullable()->comment('お届け第何週');
            $table->string('youbi')->nullable()->comment('お届け曜日');
            $table->string('message')->nullable()->comment('要望');
            $table->integer('subtotal')->nullable()->comment('税抜計');
            $table->integer('shipping')->nullable()->comment('送料');
            $table->integer('tax')->nullable()->comment('税');
            $table->integer('total')->nullable()->comment('合計');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
