<?php



/*
|--------------------------------------------------------------------------
| 予備知識  疑似フォームメソッド  参照先 https://readouble.com/laravel/8.x/ja/routing.html
|--------------------------------------------------------------------------
|
| HTMLフォームは、PUT、PATCH、DELETEアクションをサポートしていません。
|
| 重要なこと
| HTMLフォームから呼び出されるPUT、PATCH、またはDELETEルートを定義する場合
| フォームに非表示の_methodフィールドを追加する必要があります。
|
|
*/


/******************************************
 *  _methodフィールドで送信された値は、
 * HTTPリクエストメソッドとして使用します。
 *
 **************************************** */


<form action="/example" method="POST">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>


/******************************************
 * @method Bladeディレクティブ
 *
 * 省略形が存在します。
 *
 * @method Bladeディレクティブを使用して
 * _method入力フィールドを生成できます。
 *
 *
 **************************************** */



<form action="/example" method="POST">
    @method('PUT')
    @csrf
</form>


/*
|--------------------------------------------------------------------------
| メイン  参照先 https://qiita.com/namizatork/items/c2d32054335e0a31c829
|--------------------------------------------------------------------------
|
| Laravelでresource使用してる時のroute()
| nameの紹介
|
*/


/******************************************
 *  ルーティング
 * 基本
 **************************************** */




Route::resource('user', 'UserController');


/******************************************
 *  ルーティング
 * 制限する方法が2種類
 * ブラックリスト方式とホワイトリスト方式
 **************************************** */

web.php


Route::resource('user', 'UserController', ['only' => ['index', 'show']]);


/******************************************
 *  Blade
 * index（一覧画面）
 **************************************** */


<a href="{{ route("user.index") }}">一覧画面へのリンク</a>


/******************************************
 *  Blade
 * create（作成画面）
 **************************************** */


<a href="{{ route("user.create") }}">作成画面へのリンク</a>



/******************************************
 *  Blade
 * store（作成処理）
 *
 * method="post"は必ず必要
 * @method Bladeディレクティブを使用
 * ※@csrf
 **************************************** */



<form action="{{ route("user.store") }}" action="post">
    @csrf


    </form>

/******************************************
 *  Blade
 * show（詳細画面）
 **************************************** */




<a href="{{ route("user.show", $user_id) }}">詳細画面へのリンク</a>

/******************************************
 *  Blade
 * edit（編集画面）
 **************************************** */



<a href="{{ route("user.edit", $user_id) }}">詳細画面へのリンク</a>

/******************************************
 *  Blade
 * update（更新処理）
 *
 * 情報を送るので必ずフォームを入れる
 * method="post"は必ず必要
 * 疑似フォームも理解しておくこと
 *  * @method Bladeディレクティブを使用
 * ※@csrf @method('PUT')
 **************************************** */


<form action="{{ route("user.update", $user_id) }}" method="post">
    @csrf
    @method('PUT')

/******************************************
 *  Blade
 * delete（削除処理）
 *
 * 情報を送るので必ずフォームを入れる
 * method="post"は必ず必要
 * 疑似フォームも理解しておくこと
 *  * @method Bladeディレクティブを使用
 * ※@csrf @method('DELETE')
 **************************************** */



<form action="{{ route("user.destroy", $user_id) }}" method="post">
    @csrf
    @method('DELETE')


/******************************************
 * Controller
 *
 **************************************** */



<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function create()
    {
        return view('user.create');
    }

    // store（作成処理）なのでクリエイトからのRequestをもらう
    public function store(Request $request)
    {
        // store（作成処理）なのでクリエイトからのRequestをもらう書き方
        User::create($request->all());
    }

    // show（詳細画面）で
    public function show($id)
    {
        return view('user.show', ['user_id' => $id ]);
    }

    public function edit($id)
    {
        return view('user.edit', [
            'user_id' => $id
        ]);
    }

    public function update(Request $request, $id)
    {
        User::where('id', $id)->update($request->all());
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();
    }
}


    /******************************************
     *
     *
    **************************************** */




    /******************************************
     *
     *
    **************************************** */




    /******************************************
     *
     *
    **************************************** */




    /******************************************
     *
     *
    **************************************** */




    /******************************************
     *
     *
    **************************************** */




    /******************************************
     *
     *
    **************************************** */



