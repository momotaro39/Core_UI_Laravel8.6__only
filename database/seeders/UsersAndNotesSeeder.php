<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\RoleHierarchy;

class UsersAndNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期値設定
        $numberOfUsers = 3;
        $numberOfNotes = 5;
        // 空の配列作成
        $usersIds = array();
        $statusIds = array();

        // フェイカー作成
        $faker = Faker::create();
        /* 役割の作成 */
        // 管理者権限
        $adminRole = Role::create(['name' => 'admin']);
        RoleHierarchy::create([
            'role_id' => $adminRole->id,
            'hierarchy' => 1,
        ]);
        // ユーザー権限
        $userRole = Role::create(['name' => 'user']);
        RoleHierarchy::create([
            'role_id' => $userRole->id,
            'hierarchy' => 2,
        ]);
        // ゲスト権限
        $guestRole = Role::create(['name' => 'guest']);
        RoleHierarchy::create([
            'role_id' => $guestRole->id,
            'hierarchy' => 3,
        ]);

        /*  insert status　ステータス挿入  */
        DB::table('status')->insert([
            'name' => '進行中',
            'class' => 'badge badge-pill badge-primary',
        ]);
        array_push($statusIds, DB::getPdo()->lastInsertId());

        // 停止挿入
        DB::table('status')->insert([
            'name' => '停止',
            'class' => 'badge badge-pill badge-secondary',
        ]);
        array_push($statusIds, DB::getPdo()->lastInsertId());

        // 完成挿入
        DB::table('status')->insert([
            'name' => '完了',
            'class' => 'badge badge-pill badge-success',
        ]);
        array_push($statusIds, DB::getPdo()->lastInsertId());


        DB::table('status')->insert([
            'name' => '期限切れ',
            'class' => 'badge badge-pill badge-warning',
        ]);
        array_push($statusIds, DB::getPdo()->lastInsertId());


        /*  insert users  ユーザーデータ挿入 */
        // 管理者権限ユーザー１名の作成
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(), //現在時刻追加
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'user,admin' //権限追加
        ]);
        $user->assignRole('admin');
        $user->assignRole('user');

        $user = User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'email_verified_at' => now(), //現在時刻追加
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'user,admin' //権限追加
        ]);
        $user->assignRole('admin');
        $user->assignRole('user');

        // 固定ユーザー
        $user = User::create([
            'name' => '荻野',
            'email' => 'test1@test.com',
            'email_verified_at' => now(), //現在時刻追加
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'user,admin' //権限追加
        ]);
        $user->assignRole('user');

        // 固定ユーザー
        $user = User::create([
            'name' => 'サボさん',
            'email' => 'test2@test.com',
            'email_verified_at' => now(), //現在時刻追加
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'user,admin' //権限追加
        ]);
        $user->assignRole('user');

        // 固定ユーザー
        $user = User::create([
            'name' => '齋藤',
            'email' => 'test3@test.com',
            'email_verified_at' => now(), //現在時刻追加
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'menuroles' => 'user,admin' //権限追加
        ]);
        $user->assignRole('user');



        // ユーザー権限メンバーを一気に作る
        for ($i = 0; $i < $numberOfUsers; $i++) {
            $user = User::create([
                'name' => $faker->firstName,
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'menuroles' => 'user'
            ]);
            $user->assignRole('user');

            // 空の配列に、idを追加していく
            array_push($usersIds, $user->id);
        }

        /*  insert notes  ノートを追加*/
        for ($i = 0; $i < $numberOfNotes; $i++) {
            // $noteType = $faker->word();
            $noteType = '文庫';
            if (random_int(0, 1)) {
                // $noteType .= ' ' . $faker->word();

            }

            // ノートにまとめたデータを追加する。
            DB::table('notes')->insert([
                'title'         => $faker->sentence(4, true),
                'content'       => $faker->paragraph(2, true),
                'status_id'     => $statusIds[random_int(0, count($statusIds) - 1)],
                'note_type'     => $noteType,
                'applies_to_date' => $faker->date(),
                'users_id'      => $usersIds[random_int(0, $numberOfUsers - 1)]
            ]);
        }
    }
}