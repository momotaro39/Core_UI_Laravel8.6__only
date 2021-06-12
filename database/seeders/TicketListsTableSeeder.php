<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\TicketList;
use Illuminate\Support\Facades\DB;

class TicketListsTableSeeder extends Seeder
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
        TicketList::flushEventListeners();
        DB::table('ticket_lists')->truncate();

        // 代入する項目の変数を作ります。
        $ticketLists = [
            [
                'rank' => 'S席',
                'memo' => 'オフィシャル2021プレミア',
            ],
            [
                'rank' => 'A席',
                'memo' => 'オフィシャル2021プレミア',
            ],
            [
                'rank' => 'B席',
                'memo' => 'オフィシャル2021プレミア',
            ],

        ];

        // それぞれ変数の分だけデータを作成します
        foreach ($ticketLists as $ticketList) {
            TicketList::create([
                'rank'           => $ticketList['rank'],
                'memo'           => $ticketList['memo'],
                'create_user_id' => 1,
                'update_user_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
