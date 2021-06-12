<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBandGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('band_goods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('商品名');
            $table->string('band_id')->comment('バンドに紐付け');
            $table->string('goods_type_id')->comment('商品タイプに紐付け');
            $table->biginteger('stock')->comment('在庫数');
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
        Schema::dropIfExists('band_goods');
    }
}
