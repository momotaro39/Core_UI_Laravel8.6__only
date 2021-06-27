<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\Ticket;
use Illuminate\Support\Facades\DB;

class TicketsTableSeeder extends Seeder
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
        Ticket::flushEventListeners();
        DB::table('tickets')->truncate();

        // 代入する項目の変数を作ります。
        $tickets = [
            [
                'event_id' => 1,
                'rank' => 'S席',
                'sheet' => 'アリーナ',
                'unit_price' => 100,
                'sold_number' => 200,
                'rank' => 'S席',
                'memo' => 'オフィシャル2021プレミア',
            ],
            [
                'event_id' => 1,
                'rank' => 'A席',
                'sheet' => 'アリーナ',
                'unit_price' => 100,
                'sold_number' => 200,
                'memo' => 'オフィシャル2021プレミア',
            ],
            [
                'event_id' => 1,
                'rank' => 'S席',
                'sheet' => 'ホール',
                'unit_price' => 100,
                'sold_number' => 200,
                'memo' => 'オフィシャル2021プレミア',
            ],

        ];

        // それぞれ変数の分だけデータを作成します
        foreach ($tickets as $ticket) {
            Ticket::create([


                'event_id'           => $ticket['event_id'],
                'rank'           => $ticket['rank'],
                'sheet'           => $ticket['sheet'],
                'unit_price'           => $ticket['unit_price'],
                'sold_number'           => $ticket['sold_number'],
                'rank'           => $ticket['rank'],
                'memo'           => $ticket['memo'],
                'create_user_id' => 1,
                'update_user_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }
    }
}