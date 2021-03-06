<?php


/*
|--------------------------------------------------------------------------
| リダイレクトの書き方メモ  参照先  https://qiita.com/manbolila/items/767e1dae399de16813fb
|--------------------------------------------------------------------------
|
|
| リダイレクトの基本
|
|
*/



/*****************************************
 * リダイレクト定義の書き方について
 *****************************************/



// httpの場合
/*****************************************
 * リダイレクト定義の書き方について
 *****************************************/
return redirect('test/index');                 // http://xxxxx/test/index
return redirect()->to('test/index');           // ↑と同義

/*****************************************
 * リダイレクト定義の書き方について
 *****************************************/
return redirect('test/index', 301);            // ステータスコードを指定する場合（※ デフォルトは、302）

/*****************************************
 * リダイレクト定義の書き方について
 *****************************************/
return redirect('test/index', 301, ['test-header' => 'テスト'] ); // HTTPヘッダー を追加する場合



/*****************************************
 * リダイレクト定義の書き方について
 *****************************************/
// httpsの場合
return redirect('test/index', 302, [], true);  // https://xxxxx/test/index

/*****************************************
 * リダイレクト定義の書き方について
 *****************************************/
// ルート名での指定
return redirect()->route('test.list');

/*****************************************
 * リダイレクト定義の書き方について
 *****************************************/

return redirect()->route('test.show', ['id' => 12]);      // id情報を含むルーティングの場合（例： test/{id} ）
$user = App\User::find(12);                               // ↑と同義

/*****************************************
 * リダイレクト定義の書き方について
 *****************************************/
return redirect()->route('test.show', ['id' => $test]);

/*****************************************
 * リダイレクト定義の書き方について
 *****************************************/
// コントローラ名での指定
return redirect()->action('TestController@index');
return redirect()->action('TestController@show', ['id' => 12]);    // id情報を含む場合



/*****************************************
 * パス、アクションの指定
 *
 *****************************************/


return redirect($to = null, $status = 302, $headers = [], $secure = null);          // パスの指定
return redirect()->to($path, $status = 302, $headers = [], $secure = null);         // 取得インスタンスへのパス指定
return redirect()->route($route, $parameters = [], $status = 302, $headers = []);   // 取得インスタンスへのルート指定
return redirect()->action($action, $parameters = [], $status = 302, $headers = []); // 取得したインスタンスへアクション指定
return redirect()->away($path, $status = 302, $headers = []);                       // 取得したインスタンスへの外部ドメインの指定


// コントローラを使わず、リダイレクト先を指定する場合
Route::redirect($uri, $destination, $status = 301);

/*****************************************
 * データも一緒にリダイレクト
 * セッションデータと一緒にリダイレクト（※フラッシュメッセージなどに使う）
 *
 *****************************************/


return redirect('home')->with('result', '完了');
return redirect('home')->with([       // 複数データを格納する場合は、配列で！
    'result_1'=>'成功-1', 'result_2'=>'成功-2', 'result_3'=>'成功-3'
]);


/*****************************************
 * ビューで取得
 *****************************************/

{{ session('result') }}

/*****************************************
 * 直前ページへのリダイレクト
 * 基本
 *****************************************/


public function back() {
    return back();
}

/*****************************************
 * データも一緒に、直前ページに戻す場合
 * withInputはよく使うので覚えましょう
 *****************************************/


public function back() {
    return back()->with('result', 'ok！');

// 送信データがセッション内に格納される
    return back()->withInput();
}
/*****************************************
 * ビューで取得
 * コントローラーで渡された内容を表示するので書き方を覚えておきましょう
 *
 *****************************************/
//
{{ session('result') }}

/*****************************************
 * 情報をコントローラから渡す。
 * ビューの使い方も覚えておく
 * oldなのであれば表示するという書き方
 *****************************************/

// 例
public function back(Request $request) {
    return back()->withInput($request->only(['email']));
}
// ビューで取得
<textarea name="message">{{ old('message') }}</textarea>

/*
|--------------------------------------------------------------------------
| 基本の設定
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
| 基本の設定
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
| 基本の設定  参照先 https://techacademy.jp/magazine/18787
|--------------------------------------------------------------------------
|
|
*/



/*****************************************
 * メソッド定義
 *
 *****************************************/

リダイレクトとは
簡単に言うとURL転送です。リクエストされたURLとは別のURLに直ちに再リクエストさせます。

Webアプリケーションでは次のようなシーンで使われます。

ユーザー登録に成功したら、homeページにリダイレクト
未認証ユーザーが要認証ページにアクセスしようとしたら、ログインページにリダイレクト
認証に成功したら、アクセスしようとしていたページまたはhomeページにリダイレクト
認証済ユーザーがログインページにアクセスしようとしたら、homeページにリダイレクト
フォーム処理でバリデーションエラーが発生したら、フォームのページにリダイレクト
リソースの作成/更新/削除が成功したら、リソースの一覧ページにリダイレクト
多くの場合、ユーザーにはただのページ遷移に見えますが、普通のページ表示と異なる点は、一旦レスポンスが返るためブラウザからのリクエストが2回になることです。

リダイレクトの動作原理は次のようなものです。

Webサーバーがリダイレクトという特別なレスポンスを返す
ブラウザはそのレスポンスを受信すると直ちに移動先のURLにリクエストする
リダイレクトのレスポンスには以下のような情報が含まれ、ブラウザはその情報をもとにリダイレクトと判断します。

– 300番台のHTTPステータスコード（302, 303, …）
– Locationヘッダで示される移動先のURL



Laravelのリダイレクトの書き方
アクションから、RedirectResponseのインスタンスを生成して返します。
RedirectResponseのインスタンスを生成するには様々な方法があります。

// redirect関数にパスを指定する方法
return redirect($to = null, $status = 302, $headers = [], $secure = null);

// redirect関数で取得したリダイレクタインスタンスにパスを指定する方法
return redirect()->to($path, $status = 302, $headers = [], $secure = null);

// redirect関数で取得したリダイレクタインスタンスにルートを指定する方法
return redirect()->route($route, $parameters = [], $status = 302, $headers = []);

// redirect関数で取得したリダイレクタインスタンスにアクションを指定する方法
return redirect()->action($action, $parameters = [], $status = 302, $headers = []);

// redirect関数で取得したリダイレクタインスタンスに外部ドメインを指定する方法
return redirect()->away($path, $status = 302, $headers = []);

// コントローラを使用せずにリダイレクトするルートを定義する方法
Route::redirect($uri, $destination, $status = 301);


[PR] PHPプログラミングで挫折しない学習方法を動画で公開中

実際にLaravelでリダイレクトさせてみよう
Composerのcreate-projectコマンドでLaravelのプロジェクトを作成すると、welcomeビューを表示するルートが定義されています。

routes/web.php
Route::get('/', function () {
    return view('welcome');
});
次のような新しいルートを追加して、上記の ‘/’ にリダイレクトさせてみましょう。

Route::get('/test-redirect', function () {
    // redirect関数にパスを指定する方法
    return redirect('/');
});
ブラウザで ‘/test-redirect’ というパスにアクセスすると、 ‘/’ にリダイレクトされwelcomeページが表示されることを確認できます。


/*
|--------------------------------------------------------------------------
| 基本の設定
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
| 基本の設定
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
| 基本の設定
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
| 基本の設定
|--------------------------------------------------------------------------
|
|
*/



/*****************************************
 * メソッド定義
 *
 *****************************************/