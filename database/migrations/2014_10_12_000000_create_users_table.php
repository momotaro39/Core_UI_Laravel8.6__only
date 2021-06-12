<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_role_id')->default(\App\Models\User::ADMIN_ROLE_REPRESENTATIVE);
            $table->unsignedInteger('user_role_id')->default(\App\Models\band\UserRole::MEMBER_ID);
            $table->unsignedInteger('band_id')->default(\App\Models\band\Band::BAND_ID_EXAMPLE);
            $table->unsignedInteger('musical_instrument_id')->default(\App\Models\band\MusicalInstrument::MUSICALINSTRUMENT_ID_EXAMPLE);
            $table->string('menuroles');
            $table->string('name', 100)->nullable()->comment('氏名');
            $table->string('email', 100)->comment('ログインID(メールアドレス)');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255)->comment('ログインパスワード');
            $table->tinyInteger('type_flag')->nullable()->comment('種類フラグ・管理人・バンドメンバー・顧客');
            $table->string('post')->nullable()->comment('郵便番号');
            $table->string('address')->nullable()->comment('住所');
            $table->date('birth')->nullable()->comment('誕生日');
            $table->string('phone')->nullable()->comment('電話番号');
            $table->string('memo')->nullable();
            $table->unsignedTinyInteger('status')->default(\App\Models\User::USE_STATUS_ENABLE)->comment('ステータス');
            $table->timestamp('logined_at')->nullable()->comment('ログイン日');
            $table->bigInteger('create_user_id')->nullable()->comment('作成者');
            $table->timestamp('created_at')->comment('作成日');
            $table->bigInteger('update_user_id')->nullable()->comment('更新者');
            $table->timestamp('updated_at')->comment('更新日');
            $table->bigInteger('delete_user_id')->nullable()->comment('削除者');
            $table->timestamp('deleted_at')->nullable()->comment('削除日');
            $table->rememberToken();
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}