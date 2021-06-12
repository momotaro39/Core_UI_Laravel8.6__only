<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->comment('役割');
            $table->string('memo')->comment('備考');
            $table->timestamps();
        });

        // マイグレーションファイルで直接入力してもOK。シーダーで入力してもOK
        DB::table('admin_roles')->insert(['id' => 1, 'name' => 'admin', 'memo' => '管理者']);
        DB::table('admin_roles')->insert(['id' => 2, 'name' => 'representative', 'memo' => '代表']);
        DB::table('admin_roles')->insert(['id' => 3, 'name' => 'member', 'memo' => 'バンドメンバー']);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_roles');
    }
}
