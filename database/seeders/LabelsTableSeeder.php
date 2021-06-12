<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\Label;
use Illuminate\Support\Facades\DB;

class LabelsTableSeeder extends Seeder
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
        Label::flushEventListeners();
        DB::table('labels')->truncate();


        $labels = [
            [
                'name' => 'エイベックス'
            ],
            [
                'name' => 'ポニーキャニオン'
            ],
        ];

        foreach ($labels as $label) {
            Label::create([
                'name'           => $label['name'],
                'address'        => 121,
                'post'           => 5223232,
                'phone'          => 03005522,
                'create_user_id' => 1,
                'update_user_id' => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }

        echo 'LabelsTable insert end.. ';

    }
}
