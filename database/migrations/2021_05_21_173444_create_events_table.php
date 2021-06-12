<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('hall_id')->comment('ホールに紐付け');
            $table->string('name')->comment('イベント名');
            $table->integer('ticket')->comment('チケット数');
            $table->timestamp('event_date')->comment('イベント日');
            $table->timestamp('event_opening_time')->comment('イベント開場時間');
            $table->timestamp('event_start_time')->comment('イベント開始時間');
            $table->timestamp('event_end_time')->comment('イベント終了時間');
            $table->timestamp('reservation_start')->comment('予約開始日');
            $table->timestamp('reservation_end')->comment('予約終了日');
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
        Schema::dropIfExists('events');
    }
}
