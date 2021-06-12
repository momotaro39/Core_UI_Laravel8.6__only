<?php

// 参照先はこちら https://qiita.com/ntm718/items/95eee03f5294d0c351b0

/*
    |--------------------------------------------------------------------------
    |
    | ■ Routing
    |
    |--------------------------------------------------------------------------
    |
    | Web.phpの内容を変更します。
    |
    */

ルーティング
これをまずまとめずにルーティングすると、一般的にこのようになるではないでしょうか。
今回はルート名(->nameの部分)については、viewと合わせています。

web.php

Route::get('/main/list','MainController@list')->name('main.list');
Route::get('/main/edit','MainController@edit')->name('main.edit');
Route::get('/main/search','MainController@search')->name('main.search');

これを、/main部分が共通なので,Route::prefixを使いまとめることができます。

prefixメソッドはグループ内の各ルートに対して、指定されたURIのプレフィックスを指定するために使用します。

web.php
Route::prefix('main')->group(function () {
    Route::get('list','MainController@list')->name('main.list');
    Route::get('edit','MainController@edit')->name('main.edit');
    Route::get('search','MainController@search')->name('main.search');
});

これとは別にルート名でまとめると下記のようになります


web.php
Route::name('main.')->group(function () {
    Route::get('/main/list','MainController@list')->name('list');
    Route::get('/main/edit','MainController@edit')->name('edit');
    Route::get('/main/search','MainController@search')->name('search');
});


ただ、
URIでもまとめたくて、
ルート名についてもまとめたい時や、
ここにmiddlewareが加わってそれでもまとめたい時があります。


その場合 Route::groupが使えます。


ルートグループは多くのルートで共通なミドルウェアや名前空間のようなルート属性をルートごとに定義するのではなく、一括して適用するための手法です。
Route::groupメソッドの最初の引数には、共通の属性を配列で指定します。

下記のように、一つずつでも、複数でも使えるので、共通でまとめられる様なら積極的に使いたいところです。



web.php
//複数(URI、ルート名)でのグループ化  カンマを使って複数を入れることができる
Route::group(['prefix' => 'main', 'as' => 'main.'], function () {
    Route::get('list','MainController@list')->name('list');
    Route::get('edit','MainController@edit')->name('edit');
    Route::get('search','MainController@search')->name('search');
});


//URIでのグループ化  /main部分が共通 スラッシュは不要
Route::group(['prefix' => 'main'], function () {
    Route::get('list','MainController@list')->name('main.list');
    Route::get('edit','MainController@edit')->name('main.edit');
    Route::get('search','MainController@search')->name('main.search');
});

//ルート名でのグループ化  ルート名は「name('list')」の部分
Route::group(['as' => 'main.'], function () {
    Route::get('/main/list','MainController@list')->name('list');
    Route::get('/main/edit','MainController@edit')->name('edit');
    Route::get('/main/search','MainController@search')->name('search');
});


//ミドルウェアでのグループ化
Route::group(['middleware' => ['auth']], function () {
    Route::get('/main/list','MainController@list')->name('main.list');
    Route::get('/main/edit','MainController@edit')->name('main.edit');
    Route::get('/main/search','MainController@main')->name('main.search');
});


/*
    |--------------------------------------------------------------------------
    |
    | ■ ミドルウェアについて
    | 参照先 https://blog.capilano-fw.com/?p=3987
    |
    |--------------------------------------------------------------------------
    |
    | ミドルウェアとは、ページにアクセスする直前に実行されるものです。
    | 条件によってリダイレクトしたり別のページを見せるといったことが簡単にできるようになっています。
    | （例えば、ログインしたユーザーしか見られないページもミドルウェアのひとつ）
    |
    */



ミドルウェアの作り方
Laravelにはミドルウェアを作成するコマンドが用意されていますので、これを使います。
例えば、管理者だけが見られるようなミドルウェアを作成する場合は次のようになります。

php artisan make:middleware AdminAuth


app/Http/Middleware/AdminAuth.phpが作成されます。

中身は以下のようになっています。

<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}



/****************************************** */
/*  管理者だけに表示する条件を入力します。
/
/   if文を追加します
/    $next($request);を実行すれば通常通りページが表示される
/****************************************** */

そして、この中に管理者だけが表示できるようコードを追加します。
例としては以下のようになります。

public function handle($request, Closure $next)
{
    if(auth()->check() && auth()->user()->role === 'admin') {

        return $next($request);

    }

    abort(403, '管理者権限がありません。');
}

つまり、return $next($request);を実行すれば通常通りページが表示されるということになります。


/****************************************** */
/*  リダイレクトするとき
/
/   returnする場所をリダイレクトメソッドを活用します。
/
/****************************************** */

また、条件が合わなかったときにリダイレクトすることもできます。
その場合は次のようにしてください。

public function handle($request, Closure $next)
{
    if(auth()->check() && auth()->user()->role === 'admin') {

        return $next($request);

    }

    return redirect('/');
}

/*
    |--------------------------------------------------------------------------
    |
    | ■ ミドルウェアの設置方法
    | 参照先 https://blog.capilano-fw.com/?p=3987
    |
    |--------------------------------------------------------------------------
    |
    | 特定のページのみ有効にする
    |
    |
    */


ミドルウェアは作成しただけでは有効になりません。
ミドルウェアを有効にするには、app/Http/Kernel.phpに登録をする必要があります。

そして、ある特定のページだけでミドルウェアを有効にしたい場合
このファイル内の$routeMiddlewareに登録することになります。


/****************************************** */
/*  特定のページを一つだけ設定する場合
/
/   app/Http/Kernel.phpに登録
/  ルートの設定を行う。
/
/****************************************** */

protected $routeMiddleware = [

    // 省略

    'admin_auth' => \App\Http\Middleware\AdminAuth::class
];


そして、登録が完了したら、次は実際のルートに適用させます。

Route::get('admin', 'AdminController@index')->middleware('admin_auth');


これでhttp://******/adminにはAdminAuthミドルウェアが適用されることになります。



この方法ではページが増えていくごとに毎回middleware()を書かなければいけませんので少しめんどうですよね。
その場合には次のようにgroup()を使って一気にミドルウェアを指定することができます。


/****************************************** */
/*  一括でミドルウェアを設定する場合
/
/
/  ルートの設定を行う。
/
/****************************************** */

Route::group(['middleware' => 'admin_auth'], function(){

    // この中は、全てミドルウェアが適用されます。
    Route::get('middleware_test', 'HomeController@middleware_test');

});

/****************************************** */
/*  一括でミドルウェアを設定する場合
/
/
/  コントローラーで設定する方法
/****************************************** */

さらに、実はミドルウェアはコントローラーにも設置することができます。
その場合は以下のように__construct()内で指定します。

class HomeController extends Controller
{
    public function __construct() {

        $this->middleware('admin_auth');

        // もしくは、「特定のメソッドだけ」か「特定のメソッド以外」
        $this->middleware('admin_auth')->only(['index', 'create']);
        $this->middleware('admin_auth')->except(['edit', 'destroy']);

    }

/****************************************** */
/* 全てのページで有効にする
/
/
/  app/Http/Kernel.php内の$middlewareGroupsに作成したミドルウェアを登録
/****************************************** */





protected $middlewareGroups = [
    'web' => [

        // 省略

        \App\Http\Middleware\AdminAuth::class,
    ],

これで全てのページにミドルウェア適用されることになります。

/****************************************** */
/* 特定のIPアドレスだけ許可する
/
/
/  ある特定のIPアドレスだけからしかページを見ることができなくなるミドルウェアです。
/****************************************** */



<?php

namespace App\Http\Middleware;

use Closure;

class IpAddress
{
    public function handle($request, Closure $next)
    {
        if($request->ip() !== '111.111.111.111') {

            return redirect('/');

        }

        return $next($request);
    }
}

/****************************************** */
/* 特定の範囲のIPアドレスだけ許可する
/
/
/ IPアドレスを範囲で指定したいときはこっちを利用
/****************************************** */


<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class IpAddressRange
{
    public function handle($request, Closure $next)
    {
        if(!Str::startsWith($request->ip(), '111.111.111.')) {

            return redirect('/');

        }

        return $next($request);
    }
}


/****************************************** */
/* ユーザータイプで許可／拒否
/
/
/  usersテーブルにrole（つまりユーザータイプ）のデータが入っていて、これがadminもしくはsuper_adminの場合だけページ閲覧できるようになるミドルウェアです。
/****************************************** */



<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        if(auth()->check()) {

            $role = auth()->user()->role;

            if(in_array($role, ['admin', 'super_admin'])) {

                return $next($request);

            }

        }

        abort(403, '管理者権限がありません。');
    }
}