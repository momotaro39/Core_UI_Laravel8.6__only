<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFestivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('festivals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name'); //名称
            $table->string('name_kana'); //カタカナ
            $table->timestamp('created_at')->nullable(); //開催日
            $table->timestamp('created_at')->nullable(); //受付開始日
            $table->timestamp('created_at')->nullable(); //受付終了日
            $table->timestamp('created_at')->nullable(); //参加登録バンド上限日
            $table->timestamp('created_at')->nullable(); //参加人数
            $table->timestamp('created_at')->nullable(); //お客さん参加料
            $table->timestamp('created_at')->nullable(); //バンド参加費
            $table->timestamp('updated_at')->nullable(); //更新日
            $table->timestamp('deleted_at')->nullable(); //削除日
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('festivals');
    }
}