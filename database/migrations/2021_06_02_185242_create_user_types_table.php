<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


// ここでデータを複数insertするなら必要
use Illuminate\Support\Facades\DB;



class CreateUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->comment('役割');
            $table->string('memo')->comment('備考');
            $table->timestamps();
        });


        DB::table('user_types')->insert(['id' => 1, 'name' => 'バンド代表者', 'memo' => 'バンドの代表者で利用']);
        DB::table('user_types')->insert(['id' => 2, 'name' => 'バンドメンバー', 'memo' => 'バンドメンバーとして利用']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_types');
    }
}
