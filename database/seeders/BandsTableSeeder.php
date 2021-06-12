<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\Band;
use Illuminate\Support\Facades\DB;

class BandsTableSeeder extends Seeder
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
        Band::flushEventListeners();
        DB::table('bands')->truncate();

        // 代入する項目の変数を作ります。
        $bandNames = [
            [
                'name' => 'レッドチェッペリン'
            ],
            [
                'name' => 'バンプオブチキン'
            ],
            [
                'name' => '10-FEET'
            ],
            [
                'name' => '灯火'
            ],
            [
                'name' => 'ときたバンド'
            ],
        ];

        // それぞれ変数の分だけデータを作成します
        foreach ($bandNames as $bandName) {
            Band::create([
                'name'           => $bandName['name'],
                'label_id'       => 1,
                'create_user_id' => 1,
                'update_user_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }

    }
}
