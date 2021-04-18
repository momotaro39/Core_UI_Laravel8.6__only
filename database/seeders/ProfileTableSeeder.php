<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //一旦削除
        // DB::table('profiles')->delete();

        // faker使う
        $faker = Faker\Factory::create('ja_JP');

        // レコード100人分出力
        for ($i = 0; $i < 100; $i++) {
            \App\Models\Profile::create([
                'authinformation_id' => $i + 601,
                'name' => $faker->name(),
                'address' => $faker->address(),
                'birthdate' => $faker->dateTimeBetween('-80 years', '-20years')->format('Y-m-d'),
                'tel' => $faker->phoneNumber(),
                'msg' => $faker->text()
            ]);
        }
    }
}