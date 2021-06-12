<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\BandGoods;
use Illuminate\Support\Facades\DB;

class BandGoodsTableSeeder extends Seeder
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
        BandGoods::flushEventListeners();
        DB::table('band_goods')->truncate();

        // 代入する項目の変数を作ります。
        $bandGoods = [
            [
                'band_id'       => 1,
                'goods_type_id' => 1,
                'name'          => 'オリジナルタオル',
                'stock'          => 5000,
            ],


        ];

        // それぞれ変数の分だけデータを作成します
        foreach ($bandGoods as $bandGood) {
            BandGoods::create([
                'band_id'         => $bandGood['band_id'],
                'goods_type_id'   => $bandGood['goods_type_id'],
                'name'            => $bandGood['name'],
                'stock'            => $bandGood['stock'],
                'create_user_id'  => 1,
                'update_user_id'  => 1,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
