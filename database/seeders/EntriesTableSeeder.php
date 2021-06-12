<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\Entry;
use Illuminate\Support\Facades\DB;

class EntriesTableSeeder extends Seeder
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

        Entry::flushEventListeners();
        // テーブル名を入れて一掃します。
        DB::table('entries')->truncate();

        $entries = [

            [
                'band_id'  => '1',
                'event_id' => '1',
            ],
            [
                'band_id'  => '2',
                'event_id' => '1',
            ],
            [
                'band_id'  => '3',
                'event_id' => '1',
            ],
        ];
        // それぞれ変数の分だけデータを作成します
        foreach ($entries as $entry) {
            Entry::create([
                'band_id'        => $entry['band_id'],
                'event_id'       => $entry['event_id'],
                'create_user_id' => 1,
                'update_user_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
