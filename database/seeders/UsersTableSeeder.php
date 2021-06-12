<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// 暗号化するためのライブラリを使用
use Illuminate\Support\Facades\Crypt;

// functionで一気に入れる場合はuse文を使って入力します
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
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

        echo 'UsersTable ... ';


        //モデルのすべてのイベントリスナーを削除
        User::flushEventListeners();
        //テーブル内のデータを削除
        DB::table('users')->truncate();


        $users = [
            [
                'name'          => '管理者',
                'admin_role_id' => 1,
                'user_role_id'  => 1,
                'email'         => '1@example.com',
            ],
            [
                'name'          => '管理者admin',
                'admin_role_id' => 1,
                'user_role_id'  => 1,
                'email'         => 'admin@admin.com',
            ],
            [
                'name'          => '代表者',
                'admin_role_id' => 2,
                'user_role_id'  => 1,
                'email'         => '2@example.com',
            ],
            [
                'name'          => 'バンドメンバー',
                'admin_role_id' => 3,
                'user_role_id'  => 1,
                'email'         => '3@example.com',
            ],
            [
                'name'          => 'ゲスト',
                'admin_role_id' => 1,
                'user_role_id'  => 1,
                'email'         => '4@example.com',
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'name'                  => $user['name'],
                'email'                 => $user['email'],
                'email_verified_at'     => 1,
                // 'password'              => Crypt::encrypt('password'),
                'password'              => bcrypt('password'),
                // 'password'              => 'password',
                'admin_role_id'               => $user['admin_role_id'],
                'user_role_id'               => $user['user_role_id'],
                'band_id'               => 1,
                'musical_instrument_id' => 1,
                'type_flag'             => 1,
                'post'                  => '522-3823',
                'address'               => 151,
                'birth'                 => '2005/5/12',
                'phone'                 => '0905853323',
                'memo'                  => 1,
                'status'                => 1,
                'create_user_id'        => 1,
                'update_user_id'        => 1,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ]);
        }
    }
}