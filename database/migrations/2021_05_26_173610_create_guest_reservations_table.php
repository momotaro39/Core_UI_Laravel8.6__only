<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('event_id')->comment('イベントに紐付け');
            $table->unsignedInteger('user_id')->comment('ユーザーに紐付け');
            $table->unsignedInteger('ticket_id')->comment('チケットに紐付け');
            $table->bigInteger('create_user_id')->nullable()->comment('作成者');
            $table->timestamp('created_at')->nullable()->comment('作成日');
            $table->bigInteger('update_user_id')->nullable()->comment('更新者');
            $table->timestamp('updated_at')->nullable()->comment('更新日');
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
        Schema::dropIfExists('guest_reservations');
    }
}