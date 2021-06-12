

イントロダクション
すべてのリクエスト処理ロジックをルートファイルのクロージャとして定義する代わりに、「コントローラ」クラスを使用してこの動作を整理することを推奨します。
コントローラにより、関係するリクエスト処理ロジックを単一のクラスにグループ化できます。
たとえば、UserControllerクラスは、ユーザーの表示、作成、更新、削除など、ユーザーに関連するすべての受信リクエストを処理するでしょう。
コントローラはデフォルトで、app/Http/Controllersディレクトリに保存します。

    /****************************************** */
    /*
    /****************************************** */


コントローラを書く


 基本のコントローラ
基本的なコントローラの一例を見てみましょう。
コントローラは、Laravelに含まれている基本コントローラクラス、App\Http\Controllers\Controllerを拡張することに注意してください::

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * 指定ユーザーのプロファイルを表示
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return view('user.profile', [
            'user' => User::findOrFail($id)
        ]);
    }
}



/*
    |--------------------------------------------------------------------------
    |
    | コントローラメソッドのルートは、次のように定義できます。
    | 参照先 https://readouble.com/laravel/8.x/ja/controllers.html
    |--------------------------------------------------------------------------
    |
    | この記述が基本になります
    |
    | 受信リクエストが指定したルートURIに一致すると、
    | App\Http\Controllers\UserControllerクラスのshowメソッドが呼び出され、ルートパラメータがメソッドに渡されます。
    |
    */

use App\Http\Controllers\UserController;

Route::get('/user/{id}', [UserController::class, 'show']);



/******************************************
 *  Tip!!
 * コントローラは基本クラスを拡張する必要はありません。
 * ただし、middlewareやauthorizeメソッドなどの便利な機能にはアクセスできません。
 ******************************************/







/*
    |--------------------------------------------------------------------------
    |
    | シングルアクションコントローラ
    | ひとつのみのメソッドを使うときに利用します
    |
    |--------------------------------------------------------------------------
    |
    | コントローラのアクションがとくに複雑な場合は、コントローラクラス全体をその単一のアクション専用にするのが便利です。
    | これを利用するには、コントローラ内で単一の__invokeメソッドを定義します。
    |
    */





<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProvisionServer extends Controller
{
    /**
     * 新しいWebサーバをプロビジョニング
     * シングルアクションコントローラの設置
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        // ...
    }
}




    /******************************************
     *  ここ重要
     *  シングルアクションコントローラのルートを登録する場合、コントローラ方式を指定する必要はありません。
     *  代わりに、コントローラの名前をルーターに渡すだけです。
    ******************************************/




use App\Http\Controllers\ProvisionServer;

Route::post('/server', ProvisionServer::class);





/******************************************
 * 重要コマンド
 * Artisanコマンドで--invokableオプションを指定すると、__invokeメソッドを含んだコントローラを生成できます。
 ******************************************/

php artisan make:controller ProvisionServer --invokable





/******************************************
 * Tip!!
 *  stubのリソース公開を使用し、コントローラのスタブをカスタマイズできます。
 ******************************************/


/*
    |--------------------------------------------------------------------------
    |
    | コントローラミドルウェア
    |
    |--------------------------------------------------------------------------
    |
    | ミドルウェアはルートファイルの中で、コントローラのルートに対して指定します。
    |
    */



Route::get('profile', [UserController::class, 'show'])->middleware('auth');


/******************************************
 *  コントローラのコンストラクター内でミドルウェアを指定できると便利な場合があります。
 * コントローラのコンストラクタ内でmiddlewareメソッドを使用して、コントローラのアクションにミドルウェアを割り当てられます。
 ******************************************/



class UserController extends Controller
{
    /**
     * 新しいUserControllerインスタンスの生成
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('log')->only('index');
        $this->middleware('subscribed')->except('store');
    }
}

/*
    |--------------------------------------------------------------------------
    |
    | クロージャを使用したミドルウェアの登録もできます。
    |
    |--------------------------------------------------------------------------
    |
    | これにより、ミドルウェアクラス全体を定義せずに、単一のコントローラ用のインラインミドルウェアを便利に定義できます。
    |
    */



$this->middleware(function ($request, $next) {
    return $next($request);
});


/*
    |--------------------------------------------------------------------------
    |
    | リソースコントローラ
    |
    |--------------------------------------------------------------------------
    | ★★よく使うので覚えておきましょう★★
    | リソースルートという名称になるので覚えておくこと
    |
    | 通常、アプリケーション内の各リソースに対して同じ一連のアクションを実行します。
    | アクションとは「を作成、読み取り、更新、または削除」
    | 共通（コモンケース）されているアクションのことを頭文字をとってCRUDと呼ぶ
    |
    |
    |
    */


/******************************************
 *  重要コマンド
 * １行のコードでコントローラに割り当てます。
 *  app/Http/Controllers/PhotoController.phpにコントローラを生成します。
 *  Artisanコマンドへ--resourceオプションを指定すると、こうしたアクションを処理するコントローラをすばやく作成できます。
 ******************************************/

php artisan make:controller PhotoController --resource


/******************************************
 *  コントローラを指すリソースルートを登録
 *
 *  一つのルート宣言（resouce()）で、リソースに対するさまざまなアクションを処理するための複数のルートを定義
 * ※生成したコントローラには、これらのアクションごとにスタブしたメソッドがすでに含まれています。
 ******************************************/


use App\Http\Controllers\PhotoController;

Route::resource('photos', PhotoController::class);


/******************************************
 *  重要コマンド
 * Artisanコマンドを実行すると、いつでもアプリケーションのルートの概要をすばやく確認できます。
 ******************************************/

php artisan route:list

/******************************************
 *  配列をresourcesメソッドに渡すことで、一度に多くのリソースコントローラを登録することもできます。
 * 使いやすいので覚えておきましょう
 ******************************************/


Route::resources([
    'photos' => PhotoController::class,
    'posts' => PostController::class,
]);

/*
    |--------------------------------------------------------------------------
    |
    | リソースコントローラにより処理されるアクション
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    */



動詞	URI	アクション	ルート名
GET	/photos	index	photos.index
GET	/photos/create	create	photos.create
POST	/photos	store	photos.store
GET	/photos/{photo}	show	photos.show
GET	/photos/{photo}/edit	edit	photos.edit
PUT/PATCH	/photos/{photo}	update	photos.update
DELETE	/photos/{photo}	destroy	photos.destroy


/*
    |--------------------------------------------------------------------------
    |
    | 見つからないモデルの動作のカスタマイズ
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    */




暗黙的にバインドしたリソースモデルが見つからない場合、通常404のHTTPレスポンスが生成されます。
ただし、リソースルートを定義するときにmissingメソッドを呼び出すことでこの動作をカスタマイズすることができます。
missingメソッドは、暗黙的にバインドされたモデルがリソースのルートに対して見つからない場合に呼び出すクロージャを引数に取ります。



use App\Http\Controllers\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

Route::resource('photos', PhotoController::class)
        ->missing(function (Request $request) {
            return Redirect::route('photos.index');
        });


/*
    |--------------------------------------------------------------------------
    |
    | リソースモデルの指定
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    */



ルートモデル結合を使用していて、リソースコントローラのメソッドでモデルインスタンスをタイプヒントしたい場合

コントローラを生成するときのオプションに--modelを使用します。

/******************************************
 *  重要コマンド
 ******************************************/


php artisan make:controller PhotoController --resource --model=Photo


/*
    |--------------------------------------------------------------------------
    |
    | 部分的なリソースルート
    |
    |--------------------------------------------------------------------------
    |
    | リソースルートの宣言時に、デフォルトアクション全部を指定する代わりに、
    | ルートで処理するアクションの一部を指定可能です。
    |
    */


use App\Http\Controllers\PhotoController;

Route::resource('photos', PhotoController::class)->only([
    'index', 'show'
]);

Route::resource('photos', PhotoController::class)->except([
    'create', 'store', 'update', 'destroy'
]);

/*
    |--------------------------------------------------------------------------
    |
    | APIリソースルート
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */


APIに使用するリソースルートを宣言する場合、createやeditのようなHTMLテンプレートを提供するルートを除外したいことがよく起こります。
そのため、これらの２ルートを自動的に除外する、apiResourceメソッドが使用できます。

use App\Http\Controllers\PhotoController;

Route::apiResource('photos', PhotoController::class);


/******************************************
 *  Tip!!
 *  apiResourcesメソッドに配列として渡すことで、一度に複数のAPIリソースコントローラを登録できます。
 ******************************************/


use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PostController;

Route::apiResources([
    'photos' => PhotoController::class,
    'posts' => PostController::class,
]);


/******************************************
 *  Tip!!
 *  createやeditメソッドを含まないAPIリソースコントローラを素早く生成する
 * make:controllerコマンドを実行するときの最後に追加する
 * --apiスイッチを使用
 ******************************************/

/******************************************
 *  重要コマンド
 ******************************************/


php artisan make:controller PhotoController --api

/*
    |--------------------------------------------------------------------------
    |
    |  ネストしたリソース
    | ドット記法で設定する
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */



ネストしたリソースへのルートを定義したい場合もあるでしょう。
たとえば、写真リソースは、写真へ投稿された複数のコメントを持っているかもしれません。
リソースコントローラをネストするには、ルート宣言で「ドット」表記を使用します。

use App\Http\Controllers\PhotoCommentController;

Route::resource('photos.comments', PhotoCommentController::class);
このルートにより次のようなURLでアクセスする、ネストしたリソースが定義できます。

/photos/{photo}/comments/{comment}


ネストしたリソースのスコープ
Laravelの暗黙的なモデル結合機能は、リソース解決する子モデルが親モデルに属することを確認するように、ネストした結合を自動的にスコープできます。
ネストしたリソースを定義するときにscopedメソッドを使用することにより、自動スコープを有効にしたり、子リソースを取得するフィールドをLaravelに指示したりできます。
この実現方法の詳細は、リソースルートのスコープに関するドキュメントを参照してください。




/*
    |--------------------------------------------------------------------------
    |
    |  リソースルートの命名
    |
    |--------------------------------------------------------------------------
    |
    | すべてのリソースコントローラアクションにはデフォルトのルート名があります。
    | ただし、names配列に指定したいルート名を渡すことで、この名前を上書きできます。
    |
    */



use App\Http\Controllers\PhotoController;

Route::resource('photos', PhotoController::class)->names([
    'create' => 'photos.build'
]);



    /*
    |--------------------------------------------------------------------------
    |
    | リソースコントローラへのルート追加
    |
    |--------------------------------------------------------------------------
    | リソースルートのデフォルトセットを超えてリソースコントローラにルートを追加する必要がある場合
    | ★★   Route::resourceメソッドを呼び出す前にそれらのルートを定義する
    |
    | getとresourceを共存させる
    */


use App\Http\Controller\PhotoController;


/******************************************
 *  Tip!!
 *  コントローラの責務を限定することを思い出してください。
 * 典型的なリソースアクションから外れたメソッドが繰り返して必要になっているようであれば、
 * コントローラを２つに分け、小さなコントローラにすることを考えましょう。
 ******************************************/

Route::get('/photos/popular', [PhotoController::class, 'popular']);
Route::resource('photos', PhotoController::class);








    /*
    |--------------------------------------------------------------------------
    |
    |
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */

