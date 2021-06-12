<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBandMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('band_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('band_id')->comment('所属バンド');
            $table->unsignedInteger('user_id')->comment('名前');
            $table->unsignedInteger('musical_instrument_id')->comment('担当楽器');
            $table->string('post')->comment('郵便番号');
            $table->string('address')->comment('住所');
            $table->string('email')->comment('メールアドレス');
            $table->date('birth')->comment('誕生日');
            $table->string('phone')->comment('電話番号');
            $table->boolean('claimer_flag')->default(false)->comment('クレーマーフラグ');
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
        Schema::dropIfExists('band_members');
    }
}
