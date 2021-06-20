<?php


/*
|--------------------------------------------------------------------------
| 予備知識整理
|--------------------------------------------------------------------------
|
| リソースでコントローラーを設定した状態でスタートします。
|
|  参照先  https://qiita.com/zaburo/items/9fefa3f6834b2e79b734
|
|
*/
    /******************************************
     * Collection インスタンス https://readouble.com/laravel/8.x/ja/collections.html
     *    get()メソッドが返すのは配列ではありません。
     * dd() メソッドで表示される通り Collection インスタンスです。
    **************************************** */



        /******************************************
     * メソッド定義
    **************************************** */

        /******************************************
     * メソッド定義
    **************************************** */

        /******************************************
     * メソッド定義
    **************************************** */

        /******************************************
     * メソッド定義
    **************************************** */


/*
|--------------------------------------------------------------------------
| モデルの設定
|--------------------------------------------------------------------------
|
| リソースでコントローラーを設定した状態でスタートします。
|
|  参照先  https://qiita.com/zaburo/items/9fefa3f6834b2e79b734
|
|
*/

use App\User;

    /****************************************** */
    /*  メソッド定義
    /****************************************** */



    /*
|--------------------------------------------------------------------------
| index
|--------------------------------------------------------------------------
|
| クエリービルダーまとめ
| https://laraweb.net/practice/4756/
|
|
*/


    /*****************************************
     * 一覧だけなら、users=User::all();とかでもいいのですが、ここではusers=User::all();とかでもいいのですが、ここではqueryオブジェクトを生成して対応しています（その理由は検索機能の実装等で便利だからですが、ここでは触れません）。
     * また、せっかくなので、orderByで最新登録が先頭に来るようにしているのと、10行毎にページ処理をしています。
     * また、取得したデータはcompact()等で返してもいいのですが、わかりやすく-with()を利用しています。
     *
     * viwe('viwe名')-with('viewでの変数名','実データ'); という形式になります。
    /****************************************** */

public function index()
{

    /******************************************
     * 条件（queryメソッドを使った書き方）
     * 例えば、集計関数とページネーションの両方を取りたい場合はこっちがおススメ。
     * $query = User::query();
     * $query->where('pref', '大阪府');
     *
     * $result_count = $query->count(); // 集計関数
     * $users = $query->paginate(12);   // ページネーション
    ****************************************** */

    $query = User::query();

    //全件取得
//$users = $query->get();


    //$users = $query->get();
    //ページネーション
    $users = $query->orderBy('id','desc')->paginate(10);
    return view('users.index')->with('users',$users);
}








    /*
|--------------------------------------------------------------------------
| 新規登録、詳細、編集、削除ボタンをつける
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */


    データの一覧という意味では上記までのコードでいいのですが、この後必要となる新規登録、詳細、編集、削除ボタンを用意しておきましょう。
    index.blade.phpを更に編集し、下記のようにします。
    ポイントとしては、

    idはvalue等ではなくurlに含んで送る（POSTの場合も）。
    削除(destroy)は、リンク（GET）ではなくPOSTで処理する。idはurlで送る。
    削除ボタンにbtn-destroyというクラスを追加。

    また、restfulとかならhref="/users/{{$user->id}}/edit"とする方がいいのですけど、まあ、わかりやすさ重視で。


    @extends('layout')

    @section('content')

        <h1>一覧表示</h1>

        <div class="row">
            <div class="col-sm-12">
                <a href="/users/create" class="btn btn-primary" style="margin:20px;">新規登録</a>
            </div>
        </div>

        <!-- table -->
        <table class="table table-striped">

        <!-- loop -->
        @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td><a href="/users/show/{{$user->id}}" class="btn btn-primary btn-sm">詳細</a></td>
                <td><a href="/users/edit/{{$user->id}}" class="btn btn-primary btn-sm">編集</a></td>
                <td>
                    <form method="post" action="/users/destroy/{{$user->id}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="submit" value="削除" class="btn btn-danger btn-sm btn-destroy">
                    </form>
                </td>
            </tr>
        @endforeach
        </table>

        <!-- page control -->
        {!! $users->render() !!}

    @stop



    /*
|--------------------------------------------------------------------------
| 基本の設定
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */





    /*
|--------------------------------------------------------------------------
| .新規登録
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */


    新規登録を作成します。
    新規登録では2つのルートと機能を設定します。

    create : 入力画面の生成とstoreへのデータの送信。
    store : 情報を受け取り保存（一覧へリダイレクト）。

    ルート
    ルートは、createとstoreを設定します。

    storeの方はpostになります。

    Route::get('users/create','UsersController@create');

    Route::post('users/store','UsersController@store');

    コントローラー
    まず、create。

        public function create()
        {
            //createに転送
            return view('users.create');
        }
    基本的に、users.create viewに処理を転送しているだけ。


    そして、store。
    createが投げてきた値を受け取り、DBに保存。そして、一覧表示へリダイレクトしているだけ。

        public function store(Request $request)
        {
            //userオブジェクト生成
            $user = User::create();

            //値の登録
            $user->name = $request->name;
            $user->email = $request->email;

            //保存
            $user->save();

            //一覧にリダイレクト
            return redirect()->to('/users');
        }
    ビュー
    users以下に、create.blade.phpを作成し、下記のように記述。

    @extends('layout')

    @section('content')

        <h1>新規作成</h1>

        <div class="row">
            <div class="col-sm-12">
                <a href="/users" class="btn btn-primary" style="margin:20px;">一覧に戻る</a>
            </div>
        </div>

        <!-- form -->
        <form method="post" action="/users/store">

            <div class="form-group">
                <label>名前</label>
                <input type="text" name="name" value="" class="form-control">
            </div>

            <div class="form-group">
                <label>E-Mail</label>
                <input type="text" name="email" value="" class="form-control">
            </div>

            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <input type="submit" value="登録" class="btn btn-primary">

        </form>

    @stop


    post先(action)はstore(/users/store)。
    methodはpost。

    hiddenでLaravelでpostするときに原則必要となるcsrf_tokenを送っている。






    /*
|--------------------------------------------------------------------------
| 編集
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */

    各レコードの編集機能を作ります。
    基本は、新規作成の応用です。
    新規の時と同様、2つのルートと機能（メソッド）を作ります。

    edit : 編集画面を表示し、updateにデータを送る。
    update : editから送られたデータを受け取り更新する。

    ルート
    ルートとしては、editとupdateを追加。

    Route::get('users/edit/{id}','UsersController@edit');

    Route::post('users/update/{id}','UsersController@update');

    それぞれ、編集対象となるidを{id}として受け取ります。
    また、updateはpostとなります。

    コントローラー

    edit。

        public function edit($id)
        {
            //レコードを検索
            $user = User::find($id);
            //検索結果をビューに渡す
            return view('users.edit')->with('user',$user);
        }
    受け取ったidを元に、レコードを検索し、その情報をviewに返します。


    update。
    受け取ったidを元にレコードを検索、更新し、一覧へリダイレクトさせています。

        public function update(Request $request, $id)
        {
            //レコードを検索
            $user = User::find($id);
            //値を代入
            $user->name = $request->name;
            $user->email = $request->email;

            //保存（更新）
            $user->save();

            //リダイレクト
            return redirect()->to('/users');
        }

    ビュー
    基本的に構成はcreateと同じなので、コピーしてedit.blade.phpとし、必要な箇所を編集します。

    @extends('layout')

    @section('content')

        <h1>情報編集</h1>

        <div class="row">
            <div class="col-sm-12">
                <a href="/users" class="btn btn-primary" style="margin:20px;">一覧に戻る</a>
            </div>
        </div>

        <!-- form -->
        <form method="post" action="/users/update/{{$user->id}}">

            <div class="form-group">
                <label>名前</label>
                <input type="text" name="name" value="{{$user->name}}" class="form-control">
            </div>

            <div class="form-group">
                <label>E-Mail</label>
                <input type="text" name="email" value="{{$user->email}}" class="form-control">
            </div>

            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <input type="submit" value="更新" class="btn btn-primary">

        </form>

    @stop
    createとの主な違いは、

    action先が/user/update/{id}になる（idはpostせずurlに含める）。
    name,emailともに初期値として、既存値をvalue={{$user->name}}などとして表示。
    submitとのvalueを「更新」に。



    /*
|--------------------------------------------------------------------------
| 詳細表示
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */


    各レコードの詳細表示画面を作ってみます。

    ルート
    ルートはshowを定義します。

    Route::get('users/show/{id}','UsersController@show');
    詳細表示対象となるidを{id}として受け取ります。

    コントローラー
    show()メソッドを定義します。
    idでレコードを検索し、その結果をそのままviweに返します。

        public function show($id)
        {
            //レコードを検索
            $user = User::find($id);
            //検索結果をビューに渡す
            return view('users.show')->with('user',$user);
        }
    ビュー
    show.blade.phpを作成し、編集します。
    お好みで表示すればいいのですが、ここはテーブルにしました。


    @extends('layout')

    @section('content')

        <h1>詳細表示</h1>

        <div class="row">
            <div class="col-sm-12">
                <a href="/users" class="btn btn-primary" style="margin:20px;">一覧に戻る</a>
            </div>
        </div>

        <!-- table -->
        <table class="table table-striped">
            <tr><td>ID</td><td>{{$user->id}}</tr>
            <tr><td>名前</td><td>{{$user->name}}</tr>
            <tr><td>E-Mail</td><td>{{$user->email}}</tr>
        </table>

    @stop



    /*
|--------------------------------------------------------------------------
| 削除
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */

    削除も全く難しくありません。強いて言うなら、一覧のところでformで削除処理を書くところくらいでしょうか。

    ルート
    destroyを定義します。受け付けるメソッドはpostとします。

    Route::post('users/destroy/{id}','UsersController@destroy');
    コントローラー
    対象となるレコードを取得し、削除。その後、一覧へリダイレクトしています。

        public function destroy($id)
        {
            //削除対象レコードを検索
            $user = User::find($id);
            //削除
            $user->delete();
            //リダイレクト
            return redirect()->to('/users');
        }
    ビュー
    ビューはありません。が、この仕様だと、何の警告もなく削除されるのでcomfirmくらい入れておきます。
    index.blade.phpを編集します（下記抜粋）。

    削除ボタンには既にbtn-destroy classが設定されているので、@section('script')を設け、下記コードを追加します。


    @section('script')
    $(function(){
        $(".btn-destroy").click(function(){
            if(confirm("本当に削除しますか？")){
                //そのままsubmit（削除）
            }else{
                //cancel
                return false;
            }
        });
    });
    @stop



    /*
|--------------------------------------------------------------------------
| バリデーションをつける
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */

    従来のバリデーションと、RequestFormを使ったバリデーションが使えます。

    ここでは新規保存(create/store）を例に、従来型のバリデーションを実装してみます。また、評価だけでなく、エラーメッセージの表示についても実装してみます。

    従来型のValidation
    コントローラー
    バリデーションは保存前に行うのでstore()で行うことになります。
    store()メソッドは次のようになります。

        public function store(Request $request)
        {
            //バリデーション

            //評価対象
            $inputs = $request->all();

            //ルール
            $rules = [
                'name'=>'required',
                'email'=>'required|email|unique:users',
            ];

            $messages = [
                'name.required'=>'名前は必須です。',
                'email.required'=>'emailは必須です。',
                'email.email'=>'emailの形式で入力して下さい。',
                'email.unique'=>'このemailは既に登録されています。',
            ];

            $validation = \Validator::make($inputs,$rules,$messages);

            //エラー次の処理
            if($validation->fails())
            {
                return redirect()->back()->withErrors($validation->errors())->withInput();
            }

            //バリデーションOKなら、今まで通り。


            //userオブジェクト生成
            $user = User::create();

            //値の登録
            $user->name = $request->name;
            $user->email = $request->email;

            //保存
            $user->save();

            //一覧にリダイレクト
            return redirect()->to('/users');
        }
    バリデーションの実体はValidator::make()になります。ここに、評価対象、評価ルール、エラーメッセージ（オプション）を渡し、評価結果を得ます。$validation-.fails()でNGだった場合は、呼び出し元のviewにリダイレクトし、OKなら、登録処理に移ります。

    なお、エラー時に、リダイレクト文を、

    return redirect()->back()->withErrors($validation->errors())->withInput();
    とすることで、エラー内容および、元々の入力値を呼び出し元のビューに戻すことができ、ビュー側でのエラー表示等に利用することができます。

    ビュー
    エラーメッセージ等の表示は、リクエスト元であるcreate(view)に記述します。
    create.blade.phpを下記のようにします。

    @extends('layout')

    @section('content')

        <h1>新規作成</h1>

        <div class="row">
            <div class="col-sm-12">
                <a href="/users" class="btn btn-primary" style="margin:20px;">一覧に戻る</a>
            </div>
        </div>

        <!-- form -->
        <form method="post" action="/users/store">

            <!-- エラーがあるかどうかを判断して、has-errorクラスを追加 -->
            <div class="form-group @if(!empty($errors->first('name'))) has-error @endif">
                <label>名前</label>
                <input type="text" name="name" value="{{Input::old('name')}}" class="form-control">
                <!-- (最初の）エラーメッセージ表示 -->
                <span class="help-block">{{$errors->first('name')}}</span>
            </div>

            <!-- エラーがあるかどうかを判断して、has-errorクラスを追加 -->
            <div class="form-group @if(!empty($errors->first('email'))) has-error @endif">
                <label>E-Mail</label>
                <input type="text" name="email" value="{{Input::old('email')}}" class="form-control">
                <!-- (最初の）エラーメッセージ表示 -->
                <span class="help-block">{{$errors->first('email')}}</span>
            </div>

            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <input type="submit" value="登録" class="btn btn-primary">

        </form>

    @stop
    色々追加されていますが、nameを例に説明すると、ポイントは、

    {{$errors->first('name')}}でのエラーメッセージ表示。
    value="{{Input::old('name')}}"での入力値保存。
    <div class="form-group @if(!empty($errors->first('name'))) has-error @endif">でのhas-errorクラスの追加（赤くする処理）
    の３つです。