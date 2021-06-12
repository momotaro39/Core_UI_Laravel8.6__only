<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\band\UserRole;
use Illuminate\Support\Facades\DB;


class UserRolesTableSeeder extends Seeder
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
        //        DB::table('UserRoles')->insert(['id' => 3, 'name' => 'member', 'memo' => 'バンドメンバー']);
    }
}
