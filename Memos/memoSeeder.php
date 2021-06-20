<?php

/*
    |--------------------------------------------------------------------------
    | データを一括削除しながらダミーデータを作る方法  参照先　https://biz.addisteria.com/seed/#toc9
    |--------------------------------------------------------------------------
    |
    | 一気に削除したい場合は、truncateを使えば、一括削除ができます。
    | モデル名::truncate();
    |
    */


App\Post::truncate();
App\User::truncate();

/*
    |--------------------------------------------------------------------------
    | Seederファイルで設定する  リレーション付きのダミーデータを作る方法
    |--------------------------------------------------------------------------
    |
    | app/database/seeds フォルダのDatabaseSeeder.phpファイルを開きます。
    | ユーザーテーブルのidと、ポストテーブルのuser_idが hasManyで連携しているとき
    | ポストテーブルのuser_idは、ユーザーテーブルのidのどれかが割り振られる
    |
    | モデルクラスのインスタンスを10個作成する
    | factory(App\モデル名::class,10)->create();
    |
    | php artisan db:seed
    |
    */


factory(App\User::class, 10)->create()->each(function ($user) {
    $user->posts()->save(factory(App\Post::class)->make());
});


/*
    |--------------------------------------------------------------------------
    | DatabaseSeeder.phpの設定 Laravel７  参照先 https://rara-world.com/laravel-user-role-seeder-factory/
    |--------------------------------------------------------------------------
    |
    | 設定後はこのコマンドを必ず実行すること
    | php artisan migrate:refresh
    | php artisan db:seed
    |
    */


/**
 *
 * use Illuminate\Database\Seeder;
 * use App\User;  //必ずモデルの場所を書いておくこと
 *
 * class DatabaseSeeder extends Seeder
 * {
 *
 *     public function run()
 *     {
 *         $this->call(RolesTableSeeder::class);
 *         factory('App\User', 10)->create(); //ここが重要
 *     }
 * }
 *
 */

/*
    |--------------------------------------------------------------------------
    | 名前
    |--------------------------------------------------------------------------
    |
    | a
    |
    */

/**********************
 *
 ***********************/

/*
    |--------------------------------------------------------------------------
    | 名前
    |--------------------------------------------------------------------------
    |
    | a
    |
    */

/**********************
 *
 ***********************/

/*
    |--------------------------------------------------------------------------
    | 名前
    |--------------------------------------------------------------------------
    |
    | a
    |
    */

/**********************
 *
 ***********************/

/*
    |--------------------------------------------------------------------------
    | 名前
    |--------------------------------------------------------------------------
    |
    | a
    |
    */

/**********************
 *
 ***********************/
