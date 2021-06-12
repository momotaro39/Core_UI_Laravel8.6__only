<?php

    /*
    |--------------------------------------------------------------------------
    | 名前空間の設定
    |--------------------------------------------------------------------------
    |
    | a
    |
    */
namespace Database\Factories;

    /*
    |--------------------------------------------------------------------------
    | a
    |--------------------------------------------------------------------------
    |
    | a
    |
    */
use App\Models\User;
    /*
    |--------------------------------------------------------------------------
    | a
    |--------------------------------------------------------------------------
    |
    | a
    |
    */
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{

    /*
    |--------------------------------------------------------------------------
    | a
    |--------------------------------------------------------------------------
    |
    | a
    |
    */

    /**
     * ファクトリの対応するモデル名
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * モデルのデフォルト状態の定義
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Laravel７ 役割とユーザーをリレーションして設定する方法
    |--------------------------------------------------------------------------
    |
    | use Faker\Generator as Faker;
    | use App\Role;
    |
    |
    |
    |
    */
/***
 * Usersテーブルはランダムにユーザーが登録されるようにします。
 * また、気をつけなければならない点は、「userは必ず１つのroleをもつ」
 * 設定したRolesテーブルのidをランダムでrole_idに格納
 *
 * role_idに対してRole::all()->random()を使って、Roleテーブルが持っているidをランダムに保有させている
 *
 */
    $factory->define(App\User::class, function (Faker $faker) {
        return [
            'name' => $faker->name,
            // ここが重要なポイント
            'role_id' => function() {
                return Role::all()->random();
            }
        ];
    });



    /*
    |--------------------------------------------------------------------------
    | Laravel8の基本 参照先 https://readouble.com/laravel/8.x/ja/database-testing.html
    |--------------------------------------------------------------------------
    |
    | a
    |
    */

    /**********************
     * モデルのインスタンス化
     * makeメソッドを使用して、データベースへ永続化せずにモデルを作成
     ***********************/

    use App\Models\User;

    public function test_models_can_be_instantiated()
    {
        $user = User::factory()->make();

        // テストでモデルを使用する
    }

    /**********************
     * countメソッドを使用して多くのモデルのコレクションを作成できます。
     * count(3)- モデルのコレクションの数を指定
     ***********************/
    $users = User::factory()->count(3)->make();

    /*
    |--------------------------------------------------------------------------
    | 連続データ  参照先 https://readouble.com/laravel/8.x/ja/database-testing.html
    |--------------------------------------------------------------------------
    |
    | シーケンスライブラリを活用する
    | 全部で何人かを指定する。
    | stateの状態編を定義する
    | 作成メソッドを利用
    |
    */

    /**********************
     * モデルを生成するごとに、特定のモデル属性の値を変更したい場合があります。
     * これは、状態変換を連続データとして定義することで実現できます。
     * たとえば、作成されたユーザーごとに、adminカラムの値をYとNの間で交互に変更したいとしましょう。
     * この例では、admin値がYのユーザーが５人作成され、admin値がNのユーザーが５人作成されます。
     ***********************/

    use App\Models\User;
    use Illuminate\Database\Eloquent\Factories\Sequence;

    $users = User::factory()
                    ->count(10)
                    ->state(new Sequence(
                        ['admin' => 'Y'],
                        ['admin' => 'N'],
                    ))
                    ->create();


    /*
    |--------------------------------------------------------------------------
    | リレーションのファクトリ 参照先 https://readouble.com/laravel/8.x/ja/database-testing.html
    |--------------------------------------------------------------------------
    | Has Manyリレーション
    | 準備
    | アプリケーションにApp\Models\UserモデルとApp\Models\Postモデルを準備
    | UserモデルがPostとのhasManyリレーションを定義する。
    |
    | ファクトリが提供するhasメソッドを使用して、３つの投稿を持つユーザーを作成できます。
    | hasメソッドはファクトリインスタンスを引数に取ります。
    |
    */

    use App\Models\Post;
    use App\Models\User;

    $user = User::factory()
                ->has(Post::factory()->count(3))
                ->create();


                // ほぼ上と同じだが、操作するリレーション名を明示的に指定できます。
                $user = User::factory()
                ->has(Post::factory()->count(3), 'posts')
                ->create();

    /*
    |--------------------------------------------------------------------------
    | リレーションのファクトリ 参照先 https://readouble.com/laravel/8.x/ja/database-testing.html
    |--------------------------------------------------------------------------
    | Belongs Toリレーション
    | hasManyの逆の関係
    | forメソッドを使用して、ファクトリが作成したモデルの属する親モデルを定義できます。
    | たとえば、１人のユーザーに属する３つのApp\Models\Postモデルインスタンスを作成できます。
    |
    */

    use App\Models\Post;
    use App\Models\User;

    $posts = Post::factory()
                ->count(3)
                ->for(User::factory()->state([
                    'name' => 'Jessica Archer',
                ]))
                ->create();

     /**********************
     * 親モデルが変数でセットされている場合
     * モデルインスタンスをforメソッドに渡すことができます。
     ***********************/


// 親モデルのセット
    $user = User::factory()->create();

    // forメソッドで対応
    $posts = Post::factory()
                ->count(3)
                ->for($user)
                ->create();


    /*
    |--------------------------------------------------------------------------
    | ファクトリ内でのリレーション定義 参照先 https://readouble.com/laravel/8.x/ja/database-testing.html
    |--------------------------------------------------------------------------
    | モデルファクトリ内でリレーションを定義するには、リレーションの外部キーへ新しいファクトリインスタンスを割り当てます。
    | これは通常、belongsToやmorphToリレーションなどの「逆」関係で行います。
    | たとえば、投稿を作成時に新しいユーザーを作成する場合
    |
    |
    */

    use App\Models\User;

    /**
     * モデルのデフォルト状態の定義
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->title(),
        ];
    }



    /**********************
     * リレーションのカラムがそれを定義するファクトリに依存している場合
     * 属性にクロージャを割り当てることができます。
     * クロージャは、ファクトリの評価済み属性配列を受け取ります。
     ***********************/

/**
 * モデルのデフォルト状態の定義
 *
 * @return array
 */
public function definition()
{
    return [
        'user_id' => User::factory(),
        'user_type' => function (array $attributes) {
            return User::find($attributes['user_id'])->type;
        },
    ];
}

    /*
    |--------------------------------------------------------------------------
    | Laravel7でのリレーション付きのダミーデータを作る方法 参照先 https://biz.addisteria.com/seed/#toc9
    |--------------------------------------------------------------------------
    |
    | ポストテーブルとユーザーテーブルのダミーデータを同時に作ることができます。
    |
    */

    $factory->define(Post::class, function (Faker $faker) {
        return [
            'user_id' => factory(App\User::class),
            'title' => $faker->sentence(7,11),
            'body' => $faker->sentence(7,11),
        ];

}



use App\Models\User;

public function test_models_can_be_persisted()
{
    // App\Models\Userインスタンスを一つ作成
    $user = User::factory()->create();

    // App\Models\Userインスタンスを３つ作成
    $users = User::factory()->count(3)->create();

    // テストでモデルを使用する…
}