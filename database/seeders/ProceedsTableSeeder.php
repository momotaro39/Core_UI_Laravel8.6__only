<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\Proceed;
// use App\Proceed as AppProceed;
use Illuminate\Support\Facades\DB;

class ProceedsTableSeeder extends Seeder
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

        Proceed::flushEventListeners();
        DB::table('proceeds')->truncate();

        // 代入する項目の変数を作ります。
        $proceeds = [
            [
                'event_id'            => 1,
                'ticket_rank_id'      => 1,
                'unit_price'          => 3000,
                'ticket_total_number' =>500,
            ],

        ];

        // それぞれ変数の分だけデータを作成します
        foreach ($proceeds as $proceed) {
            Proceed::create([
                'event_id'            => $proceed['event_id'],
                'ticket_rank_id'      => $proceed['ticket_rank_id'],
                'unit_price'          => $proceed['unit_price'],
                'ticket_total_number' => $proceed['ticket_total_number'],
                'create_user_id'      => 1,
                'update_user_id'      => 1,
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
