<?php


/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://qiita.com/sympe/items/9297f41d5f7a9d91aa11
|--------------------------------------------------------------------------
|
| LaravelでRoute::resourceを使うときに気をつけること
|
| Laravelでルーティングを行う際に便利な、Route::resourceを使用した際の注意点を書いておきます。
|
|
*/



/*****************************************
 * 環境
 * Laravel 5.4
 * ルーティングのRoute::resource指定
 * Laravelでは、以下のようにルーティングにRoute::resouceを指定することで、CRUDルーティングを一度に行うことができます。
 * 以下が公式のドキュメントに載っていたルーティングの例と対応表になります。
 *
 *****************************************/







/routes/web.php

Route::resource('photos', 'PhotoController');

/*****************************************
 * 以下のartisanコマンドによって、
 * コントローラとメソッドを自動生成してくれます。
 *
 *****************************************/


$ php artisan make:controller PhotoController --resource


/app/Http/Controllers/PhotoController.php



//コマンド入力で自動的に作成される
namespace App\Http\Controllers;


class PhotoController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}

/*****************************************
 * 全部のメソッドは使わない場合
 * Route::resouceはCRUDのルーティングを一度に行えるのでとても便利なのですが、
 * これらのメソッドを全て使うことはあまりないと思います。
 *
 * 空のメソッドを用意しておいてもいいですが、できれば使わないメソッドは消しておきたい。
 *
 *****************************************/

例えば、以下のようなルーティングをしたとします。

/routes/web.php

Route::resource('hoge', 'HogeController');


今回show、updateメソッドを使わないとして、以下のようにメソッド自体消したとします。

/app/Http/Controllers/HogeController.php


//show、updateは削除する

namespace App\Http\Controllers;
class HogeController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    //showを削除

    public function edit($id)
    {
        //
    }

    // updateを削除

    public function destroy($id)
    {
        //
    }
}
/*****************************************
 * ブラウザのURL入力欄に直書きすると例外が出てしまいます。
 * showメソッドが無いと言われています。
 * showメソッドを呼んだ覚えはないのですが、
 * 先程のresoucesを使ったときのルーティング一覧表を見ると、
 * /hoge/{任意の文字列} を直書き(GET)した場合はshowが呼ばれてしまいます。
 *
 *****************************************/





/*****************************************
 * ルーティングを制限する
 * よってshowのルーティングを削除する必要があります。
 * resourceを使ったときのルーティングを制限する方法は2つあります。
 *
 *****************************************/


/routes/web.php




// show、updateのルーティングを削除

/*****************************************
 * メソッド定義
 *
 *****************************************/

// onlyを使う方法 ホワイトリスト方式
Route::resource('hoge', 'HogeController', ['only' => ['index', 'create', 'edit', 'store', 'destroy']]);

// exceptを使う方法 ブラックリスト方式
Route::resource('hoge', 'HogeController', ['except' => ['show', 'update']]);



/*****************************************
 * ホワイトリストの方がソースも読みやすいので、
 * onlyを使うことをおすすめします。
 *
 * 以上で、resoucesを使ったときにURLを直書きされた場合でも、
 * 例外(Whoops)が出ないようになりました。
 *
 *****************************************/




/*****************************************
 * 備考: どうしてもWhoopsを出したくない場合
 * showメソッドを呼ばないようにして、これでもう大丈夫かと思いきや、先程とは違うエラー画面が発生しました。
 *
 * これは、GETリクエスト(URL直書き)を許可していないURLにGETでアクセスしているため405エラーが出ています。
 * この例外が出るのは意図している動きなので、拾うことにしました。
 *
 * Laravelの例外は、以下のように/app/Exceptions/Handler.phpに記述して拾います。
 *
 *****************************************/



/app/Exceptions/Handler.php

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function render($request, Exception $excpetion)
    {
        if ($exceotion instanceof MethodNotAllowedHttpException) {
             //ここに処理を書く
        }
        return parent::render($request, $exception);
    }
}



これでエラー画面はでなくなりました。
※この方法はコメントでもいただいたとおり、
MethodNotAllowedHttpExceptionを全て握り潰すので注意してください。



/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://qiita.com/sympe/items/9297f41d5f7a9d91aa11
|--------------------------------------------------------------------------
|
|
*/



/*****************************************
 * メソッド定義
 *
 *****************************************/

/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://qiita.com/sympe/items/9297f41d5f7a9d91aa11
|--------------------------------------------------------------------------
|
|
*/



/*****************************************
 * メソッド定義
 *
 *****************************************/

/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://qiita.com/sympe/items/9297f41d5f7a9d91aa11
|--------------------------------------------------------------------------
|
|
*/



/*****************************************
 * メソッド定義
 *
 *****************************************/