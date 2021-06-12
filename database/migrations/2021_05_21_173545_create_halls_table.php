<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('halls', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('名称');
            $table->string('post')->comment('郵便番号');
            $table->string('address')->comment('住所');
            $table->string('seating_capacity_arena')->comment('アリーナ席収容人数');
            $table->string('seating_capacity_hall')->comment('ホール席収容人数');
            $table->string('img')->comment('画像 地図用データ');
            $table->string('latitude')->nullable()->comment('緯度 地図用データ');
            $table->string('longitude')->nullable()->comment('経度 地図用データ');
            $table->string('station')->comment('最寄り駅');
            $table->string('phone')->comment('電話番号');
            $table->bigInteger('create_user_id')->nullable()->comment('作成者');
            $table->timestamp('created_at')->comment('作成日');
            $table->bigInteger('update_user_id')->nullable()->comment('更新者');
            $table->timestamp('updated_at')->comment('更新日');
            $table->bigInteger('delete_user_id')->nullable()->comment('削除者');
            $table->timestamp('deleted_at')->nullable()->comment('削除日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('halls');
    }
}
