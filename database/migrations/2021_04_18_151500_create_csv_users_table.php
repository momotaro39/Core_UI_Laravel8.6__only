<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsvUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->comment('名前');
                $table->string('email')->comment('メールアドレス');
                $table->string('tel',20)->nullable()->comment('電話番号');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('csv_users');
    }
}
