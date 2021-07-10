<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Example;

class CreateBandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('バンド名');
            $table->unsignedInteger('label_id')->default(\App\Models\band\Label::LABEL_ID_EXAMPLE)->comment('Labelと紐付け');
            $table->bigInteger('create_user_id')->nullable()->comment('作成者');
            $table->timestamp('created_at')->nullable()->comment('作成日');
            $table->bigInteger('update_user_id')->nullable()->comment('更新者');
            $table->timestamp('updated_at')->nullable()->comment('更新日');
            $table->bigInteger('delete_user_id')->nullable()->comment('削除者');
            $table->timestamp('deleted_at')->nullable()->comment('削除日');
        });
        // DB::table('bands')->insert(['id' => \App\Models\band::BAND_ID_EXAMPLE, 'name' => 'バンドメンバー名 見本',]);
        // DB::table('bands')->insert(['id' => 2, 'name' => 'アコースティック 灯火']);
        // DB::table('bands')->insert(['id' => 3, 'name' => 'バンド レッド・ホット・チリ・ペッパー']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bands');
    }
}