<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\GuestReservation;
use Illuminate\Support\Facades\DB;

class GuestReservationsTableSeeder extends Seeder
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
        GuestReservation::flushEventListeners();
        DB::table('guest_reservations')->truncate();

        // 代入する項目の変数を作ります。
        $guestReservations = [
            [
                'event_id' => 1,
                'user_id'  => 1,
            ],


        ];

        // それぞれ変数の分だけデータを作成します
        foreach ($guestReservations as $guestReservation) {
            GuestReservation::create([
                'event_id'          => $guestReservation['event_id'],
                'user_id'           => $guestReservation['user_id'],
                'create_user_id'    => 1,
                'update_user_id'    => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ]);
        }
    }
}