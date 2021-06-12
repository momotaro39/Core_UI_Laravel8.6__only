<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\Music;
use Illuminate\Support\Facades\DB;

class MusicsTableSeeder extends Seeder
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

        Music::flushEventListeners();
        // テーブル名を入れて一掃します。
        DB::table('musics')->truncate();

        $musics = [

            [
                'album_id' => 1,
                'band_id' => 1,
                'name' => '曲名1',
                'music_time' => '3:53',
                'album_music_number' => 3,
            ],
            [
                'album_id' => 1,
                'band_id' => 1,
                'name' => '曲名2',
                'music_time' => '3:10',
                'album_music_number' => 5,
            ],

        ];
        // それぞれ変数の分だけデータを作成します
        foreach ($musics as $music) {
            Music::create([
                'album_id'           => $music['album_id'],
                'band_id'            => $music['band_id'],
                'name'               => $music['name'],
                'music_time'         => $music['music_time'],
                'album_music_number' => $music['album_music_number'],
                'create_user_id'     => 1,
                'update_user_id'     => 1,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
