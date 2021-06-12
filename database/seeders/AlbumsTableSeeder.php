<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\Album;
use Illuminate\Support\Facades\DB;

class AlbumsTableSeeder extends Seeder
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
        Album::flushEventListeners();
        // テーブル名を入れて一掃します。
        DB::table('albums')->truncate();

        $albums = [
            [
                'name'    => 'アルバム１',
                'band_id' => '1',
            ],
            [
                'name'    => 'アルバム2',
                'band_id' => '2',
            ],

        ];

        // それぞれ変数の分だけデータを作成します
        foreach ($albums as $album) {
            Album::create([
                'name'               => $album['name'],
                'band_id'            => $album['band_id'],
                'release_date'       => '2020/2/3',
                'create_user_id'     => 1,
                'update_user_id'     => 1,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);
        }

    }
}
