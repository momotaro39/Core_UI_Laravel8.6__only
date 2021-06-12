<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\BandMembers;
use Illuminate\Support\Facades\DB;

class BandMembersTableSeeder extends Seeder
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

        BandMembers::flushEventListeners();
        // テーブル名を入れて一掃します。
        DB::table('band_members')->truncate();

        $bandMembers = [

            [
                'band_id' => '1',
                'user_id' => '1',
                'musical_instrument_id' => '1',
            ],
            [
                'band_id' => '2',
                'user_id' => '2',
                'musical_instrument_id' => '2',
            ],
            [
                'band_id' => '3',
                'user_id' => '3',
                'musical_instrument_id' => '3',
            ],
        ];

        foreach ($bandMembers as $bandMember) {
            BandMembers::create([
                'band_id'               => $bandMember['band_id'],
                'user_id'               => $bandMember['user_id'],
                'musical_instrument_id' => $bandMember['musical_instrument_id'],
                'post'                  => '522-0304',
                'address'               => '住所は栗東',
                'email'                 => '1@1.com',
                'birth'                 => '2010/5/5',
                'phone'                 =>  1111222,
                'create_user_id'        => 1,
                'update_user_id'        => 1,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ]);
        }

        // その他にもバンドメンバー作っておきます。
        // class BandMembersFactoryをfactoryフォルダに作っておく
        // \App\Models\band\BandMembers::factory()->count(20)->create();
        BandMembers::factory()->count(20)->create();
    }
}
