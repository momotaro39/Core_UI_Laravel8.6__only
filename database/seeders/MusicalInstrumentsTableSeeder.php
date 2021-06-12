<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\MusicalInstrument;
use Illuminate\Support\Facades\DB;

class MusicalInstrumentsTableSeeder extends Seeder
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
        MusicalInstrument::flushEventListeners();
        DB::table('musical_instruments')->truncate();


        $musicalInstruments = [
            [
                'name' => 'ギター',
                'memo' => '備考1',
            ],
            [
                'name' => 'ドラム',
                'memo' => '備考あれば',
            ],
            [
                'name' => 'キーボード',
                'memo' => '備考3',
            ],
            [
                'name' => 'ベース',
                'memo' => '備考4',
            ],
            [
                'name' => 'ボーカル',
                'memo' => '備考5',
            ],
        ];

        foreach ($musicalInstruments as $musicalInstrument) {
            MusicalInstrument::create([
                'name'           => $musicalInstrument['name'],
                'memo'           => $musicalInstrument['memo'],
                'create_user_id' => 1,
                'update_user_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
