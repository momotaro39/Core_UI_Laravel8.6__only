<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\PerformanceList;
use Illuminate\Support\Facades\DB;

class PerformanceListsTableSeeder extends Seeder
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
        PerformanceList::flushEventListeners();
        DB::table('performance_lists')->truncate();

        // 代入する項目の変数を作ります。
        $performanceLists = [
            [
                'event_id' => 1,
                'band_id'  => 1,
                'music_id' => 1,
                'performance_order' => 1,
            ],
            [
                'event_id' => 1,
                'band_id'  => 2,
                'music_id' => 1,
                'performance_order' => 2,
            ],
            [
                'event_id' => 1,
                'band_id'  => 3,
                'music_id' => 1,
                'performance_order' => 3,
            ],

        ];

        // それぞれ変数の分だけデータを作成します
        foreach ($performanceLists as $performanceList) {
            PerformanceList::create([
                'event_id'           => $performanceList['event_id'],
                'band_id'            => $performanceList['band_id'],
                'music_id'           => $performanceList['music_id'],
                'performance_order'  => $performanceList['music_id'],
                'create_user_id'     => 1,
                'update_user_id'     => 1,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
