<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\Hall;
use Illuminate\Support\Facades\DB;


class HallTableSeeder extends Seeder
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


        Hall::flushEventListeners();
        // テーブル名を入れて一掃します。
        DB::table('halls')->truncate();


        $halls = [

            [
                'name' => '〇〇体育館',
                'seating_capacity_arena' => 100,
                'seating_capacity_hall' => 200,
            ],
            [
                'name' => 'Zepp東京',
                'seating_capacity_arena' => 100,
                'seating_capacity_hall' => 200,
            ],

        ];

        foreach ($halls as $hall) {
            Hall::create([
                'name'                   => $hall['name'],
                'post'                   => 1,
                'address'                => 1,
                'seating_capacity_arena' => $hall['seating_capacity_arena'],
                'seating_capacity_hall'  => $hall['seating_capacity_hall'],
                'img'                    => 1,
                'latitude'               => 1,
                'longitude'              => 1,
                'station'                => "代々木公園",
                'phone'                  =>  '1111222',
                'create_user_id'         => 1,
                'update_user_id'         => 1,


            ]);
        }

        // その他にもバンドメンバー作っておきます。
        // \App\Models\band\Hall::factory()->count(20)->create();



    }
}
