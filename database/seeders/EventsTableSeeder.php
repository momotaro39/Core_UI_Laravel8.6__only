<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\Event;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * シーダー作成方法
     * winpty docker-compose exec app bash    //1 仮想環境に入る
     * php artisan db:seed --class=ReservationDetailsTableSeeder //2 実行する
     * @return void
     */
    public function run()
    {

        Event::flushEventListeners();
        // テーブル名を入れて一掃します。
        DB::table('events')->truncate();

        $events = [

            [
                'name'               => 'イベント名1',
                'ticket'             => 200,
                'hall_id'            => 1,
                'event_date'         => '2021-06-02 12:30:30',
                'event_opening_time' => '2021-06-02 13:00:00',
                'event_start_time'   => '2021-06-02 14:00:00',
                'event_end_time'     => '2021-06-02 18:00:00',
                'reservation_start'  => '2021-05-20 12:00:00',
                'reservation_end'    => '2021-05-30 12:00:00',
            ],
            [
                'name'               => 'イベント名2',
                'ticket'             => 200,
                'hall_id'            => 1,
                'event_date'         => '2021-06-02 12:30:30',
                'event_opening_time' => '2021-06-02 13:00:00',
                'event_start_time'   => '2021-06-02 14:00:00',
                'event_end_time'     => '2021-06-02 18:00:00',
                'reservation_start'  => '2021-05-20 12:00:00',
                'reservation_end'    => '2021-05-30 12:00:00',
            ],
            [
                'name'               => 'イベント名3',
                'ticket'             => 200,
                'hall_id'            => 1,
                'event_date'         => '2021-06-02 12:30:30',
                'event_opening_time' => '2021-06-02 13:00:00',
                'event_start_time'   => '2021-06-02 14:00:00',
                'event_end_time'     => '2021-06-02 18:00:00',
                'reservation_start'  => '2021-05-20 12:00:00',
                'reservation_end'    => '2021-05-30 12:00:00',
            ],
        ];
        // それぞれ変数の分だけデータを作成します
        foreach ($events as $event) {
            Event::create([
                'name'               => $event['name'],
                'ticket'             => $event['ticket'],
                'hall_id'            => $event['hall_id'],
                // 'event_date'         => date('Y-m-d H:i:s'),
                // 'event_opening_time' => date('Y-m-d H:i:s'),
                // 'event_start_time'   => date('Y-m-d H:i:s'),
                // 'event_end_time'     => date('Y-m-d H:i:s'),
                // 'reservation_start'  => date('Y-m-d H:i:s'),
                // 'reservation_end'    => date('Y-m-d H:i:s'),
                'event_date'         => $event['event_date'],
                'event_opening_time' => $event['event_opening_time'],
                'event_start_time'   => $event['event_start_time'],
                'event_end_time'     => $event['event_end_time'],
                'reservation_start'  => $event['reservation_start'],
                'reservation_end'    => $event['reservation_end'],
                'create_user_id'     => 1,
                'update_user_id'     => 1,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
