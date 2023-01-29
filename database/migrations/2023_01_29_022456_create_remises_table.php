<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remises', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('X-TRANID')->nullable()->comment('トランザクションID');
            $table->string('X-S_TORIHIKI_NO')->nullable()->comment('請求番号');
            $table->string('X-AMOUNT')->nullable()->comment('金額');
            $table->string('X-TAX')->nullable()->comment('税送料');
            $table->string('X-TOTAL')->nullable()->comment('合計金額');
            $table->string('X-REFAPPROVED')->nullable()->comment('承認番号');
            $table->string('X-REFFORWARDED')->nullable()->comment('仕向先コード');
            $table->string('X-ERRCODE')->nullable()->comment('エラーコード');
            $table->string('X-ERRINFO')->nullable()->comment('エラー詳細コード');
            $table->string('X-ERRLEVEL')->nullable()->comment('エラーレベル');
            $table->string('X-R_CODE')->nullable()->comment('戻りコード');
            $table->string('REC_TYPE')->nullable()->comment('戻り区分');
            $table->string('X-REFGATEWAYNO')->nullable()->comment('ゲートウェイ番号');
            $table->string('X-PAYQUICKID')->nullable()->comment('ペイクイックID');
            $table->string('X-PARTOFCARD')->nullable()->comment('カード番号');
            $table->string('X-EXPIRE')->nullable()->comment('有効期限');
            $table->string('X-NAME')->nullable()->comment('名義人');
            $table->string('X-AC_MEMBERID')->nullable()->comment('メンバーID');
            $table->string('X-AC_S_KAIIN_NO')->nullable()->comment('加盟店会員番号');
            $table->string('X-AC_AMOUNT')->nullable()->comment('金額（継続）');
            $table->string('X-AC_TOTAL')->nullable()->comment('合計金額（継続）');
            $table->string('YYYYMMDD')->nullable()->comment('次回課金日');
            $table->string('X-AC_INTERVAL')->nullable()->comment('決済間隔');
            $table->string('X-CARDBRAND')->nullable()->comment('カードブランド');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remises');
    }
}
