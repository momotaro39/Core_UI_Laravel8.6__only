<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AuthInformationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 一旦テーブル削除
        // DB::table('auth_information')->delete();

        // faker使う
        $faker = Faker\Factory::create('ja_JP');

        // レコード100人分出力
        for ($i = 0; $i < 100; $i++) {
            \App\Models\AuthInformation::create([
                'email' => $faker->email(),
                'password' => $faker->password(),
            ]);
        }
    }
}