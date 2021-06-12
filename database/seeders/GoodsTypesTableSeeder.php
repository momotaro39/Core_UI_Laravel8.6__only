<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\GoodsType;
use Illuminate\Support\Facades\DB;

class GoodsTypesTableSeeder extends Seeder
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
        GoodsType::flushEventListeners();
        DB::table('goods_types')->truncate();

        // 代入する項目の変数を作ります。
        $goodTypes = [
            [
                'type_name' => '人形',
                'memo'      => 'オフィシャル2021',
            ],
            [
                'type_name' => 'うちわ',
                'memo'      => 'オフィシャル2022'
            ],
            [
                'type_name' => 'タオル',
                'memo'      => 'オフィシャル2023'
            ],
        ];

        // それぞれ変数の分だけデータを作成します
        foreach ($goodTypes as $goodType) {
            GoodsType::create([
                'type_name'      => $goodType['type_name'],
                'memo'           => $goodType['memo'],
                'create_user_id' => 1,
                'update_user_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
