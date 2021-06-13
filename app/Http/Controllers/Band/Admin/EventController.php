<?php

namespace App\Http\Controllers\Band\Admin;

/*
    |--------------------------------------------------------------------------
    |
    | 追加機能
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */

// ページネーションを使う時に利用
use Illuminate\Pagination\Paginator;

//コントローラーの場所を変えたときには必要になる
use App\Http\Controllers\Controller;

//認証機能を使うときに必要
use Illuminate\Support\Facades\Auth;

use App\Models\Band\Event;
use App\Models\Band\AdminRole;
use App\Models\band\Event as BandEvent;
use App\Models\Band\UserRole;
use Illuminate\Http\Request;

class EventController extends Controller
{



    /*
    |--------------------------------------------------------------------------
    | 一覧表示の情報を表示
    |--------------------------------------------------------------------------
    |
    | リクエストでビュー画面から得た情報を取得
    | index(Request $request)で検索一覧のデータを取得できるように設定する
    |
    | HTTP動詞：	GET
    | URL ：	/articles
    | アクション ：	index
    | 役割 ：	一覧表示
    |
    */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /***************************
         * ログインユーザーを取得してデータを利用するときに使用
         * use文に追加しておくこと
         * use Illuminate\Support\Facades\Auth;
         *****************************/

        $user = Auth::user();

        /***************************
         *
         *  データベースから必要な情報を取得
         *
         *
         *****************************/

        $events = Event::get();



        /***************************
         * 追加機能として利用
         * ページネーションの数を設定する
         * コンフィグファイルでページ数を設定しておく。
         *
         * Bootstrap方式を使うpsgenate()方法も記述
         *
         *****************************/
        $paginateNum     = config('const.paginate.other'); //ページ設定
        $paginations = Event::paginate(config('const.paginate.other'));

        /******************************************
         * 条件（queryメソッドを使った書き方）
         * 例えば、集計関数とページネーションの両方を取りたい場合はこっちがおススメ。
         * $query = モデル名::query();
         * $query->where('pref', '大阪府');
         *
         * $result_count = $query->count(); // 集計関数
         * $users = $query->paginate(12);   // ページネーション
         ****************************************** */

        $query = Event::query();

        //全件取得
        $eventPages = $query->get();


        //ページネーション
        $eventPages = $query->orderBy('id', 'desc')->paginate($paginateNum);


        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/

        $requestData = compact(
            // 認証情報
            'user',
            'events',
            'eventPages',

        );

        return view('MemberManagement.events.index', $requestData);
    }



    /*
    |--------------------------------------------------------------------------
    | 新規投稿画面を表示（create）
    |--------------------------------------------------------------------------
    | create : 入力画面の生成とstoreへのデータの送信。
    | 基本的に、view('MemberManagement.members.create'); viewに処理を転送しているだけ。
    | ※ディレクトリの構造を書くだけ view('MemberManagement.members.create')
    |
    | HTTP動詞：	GET
    | URL ：	/articles/create
    | アクション ：	create
    | 役割 ：	新規投稿画面|
    |
    */

    /**
     * 新しいリソースを作成するためのフォームを表示します。
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /***************************
         * リダイレクト
         *****************************/
        //create.blade.phpに転送
        return view('MemberManagement.events.create');
    }



    /*
    |--------------------------------------------------------------------------
    | 新規投稿画面を表示(store)
    |--------------------------------------------------------------------------
    | store : 情報を受け取り保存（一覧へリダイレクト）。
    | createが投げてきた値を受け取り、DBに保存。
    |
    |
    |
    | HTTP動詞：	POST
    | URL ：	/articles/
    | アクション ：	store
    | 役割 ：	新規投稿画面
    |
    */

    /**
     * Store a newly created resource in storage.
     * 新しく作成したリソースをストレージに保存します。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /***************************
         * バリデーションセット
         *
         * バリデーションルールは別ファイルを作ってもOK
         *****************************/

        // バリデーションをかける情報をセット
        $inputs = $request->all();

        //バリデーションルールの明記
        $rules = [
            // 'name' => 'required',
            // 'email' => 'required|email|unique:users',
        ];

        //バリデーションルールにかかった時のメッセージ
        $messages = [
            // 'name.required' => '名前は必須です。',
            // 'email.required' => 'emailは必須です。',
            // 'email.email' => 'emailの形式で入力して下さい。',
            // 'email.unique' => 'このemailは既に登録されています。',
        ];



        /***************************
         * バリデーションの実体はValidator::make()
         * 評価対象
         * 評価ルール
         * エラーメッセージ（オプション）を渡す。
         *
         *
         *****************************/

        $validation = \Validator::make($inputs, $rules, $messages);

        /***************************
         *
         * $validation-.fails()でNGだった場合
         * 呼び出し元のviewにリダイレクト
         * OKなら、登録処理に移ります。
         *
         * エラー内容および、元々の入力値を呼び出して、元のビューに戻す。
         * ビュー側でのエラー表示等に利用することができます。
         *
         *****************************/

        //バリデーションがかかった時のメッセージ処理
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }

        /***************************
         * オブジェクト生成
         * カラム（フィールド）を作る
         * モデル名::create()
         *
         * データベースに新規登録します。
         * バリデーションにかかっていなければ保存することができます。
         *
         *****************************/

        //  バリデーションをかけない場合はこの方法で対応
        // $bandMember = BandMembers::create();

        $event = Event::create();


        /***************************
         * オブジェクトにビューからもらったFormのリクエストデータを入れる
         * 左がオブジェクトの配列
         * 右がリクエストの配列
         *****************************/
        // $event->name = $request->name;
        // $event->email = $request->email;




        /***************************
         * 保存
         * オブジェクトの配列をsaveメソッドで保存する
         *****************************/

        $event->fill()->save();


        /***************************
         * 追加機能として利用
         * ページネーションの数を設定する
         * コンフィグファイルでページ数を設定しておく。
         *
         * Bootstrap方式を使うpsgenate()方法も記述
         *
         *****************************/
        $paginateNum     = Event::paginate(config('const.paginate.other')); //ページ設定



        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/

        $requestData = compact(
            'event',
        );

        /***************************
         * 一覧画面に遷移します。
         *****************************/

        return view('MemberManagement.events.index', $requestData);
    }




    /*
    |--------------------------------------------------------------------------
    | 個別ページの詳細情報を表示(show)
    |--------------------------------------------------------------------------
    |
    | HTTP動詞：	GET
    | URL ：	/articles/{article}
    | アクション ：	show
    | 役割 ：	個別ページ表示
    |
    */

    /**
     * Display the specified resource.
     * 指定したリソースを表示します。
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */



    public function show($id)
    {
        /***************************
         * レコードを検索
         *****************************/

        $event = Event::find($id);

        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/


        $requestData = compact(
            'event',

        );

        /***************************
         * 検索結果をビューに渡す
         *****************************/

        return view('MemberManagement.events.show', $requestData);
    }


    /*
    |--------------------------------------------------------------------------
    | 更新画面を表示(edit)
    |--------------------------------------------------------------------------
    |
    | HTTP動詞：	GET
    | URL ：	/articles/{article}/edit
    | アクション ：	edit
    | 役割 ：	更新画面
    |
    */

    /**
     * Show the form for editing the specified resource.
     * 指定したリソースを編集するためのフォームを表示します。 指定したリソースを編集するためのフォームを表示します。
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /***************************
         * レコードを検索
         *****************************/

        $event = Event::find($id);

        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/


        $requestData = compact(
            'event',

        );


        /***************************
         * idを取得して、DBから検索して検索結果をビューに渡す
         * 個別の情報が表示される
         *****************************/

        return view('MemberManagement.events.edit', $requestData);
    }


    /*
    |--------------------------------------------------------------------------
    | 更新処理(update)
    |--------------------------------------------------------------------------
    |
    | HTTP動詞：	PUT/PATCH
    | URL ：	/articles/{article}
    | アクション ：	update
    | 役割 ：	更新処理
    |
    */


    /**
     * Update the specified resource in storage.
     * ストレージ内の指定されたリソースを更新します。
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        /***************************
         * レコードを検索
         *****************************/

        $event = Event::find($id);

        /***************************
         * 変更した情報を一気に上書きするために
         * リクエスト情報を全て取得する
         *****************************/

        // バリデーションをかけた情報をセット
        $inputs = $request->all();
        /***************************
         * 値を代入
         * find()したデータにリクエストの情報を入力
         * 個別に入れたい場合あはリクエストとレコード情報を一致させて
         * 上書きするものを決める。
         *
         * 面倒なので、まとめて上書きの方法で決定します。
         *****************************/

        // $event->name = $request->name;

        dd($inputs);
        /***************************
         * 保存
         * オブジェクトの配列をsaveメソッドで保存する
         *****************************/

        $event->fill($inputs)->save();

        /***************************
         * セーブしたら最初のページに返してあげる
         * redirect()メソッドを利用する
         *****************************/

        //リダイレクト
        return redirect()->to('/core/events');
    }



    /*
    |--------------------------------------------------------------------------
    | 削除処理(destoroy)
    |--------------------------------------------------------------------------
    |
    | HTTP動詞：	DELETE
    | URL ：	/articles/{article}
    | アクション ：	destroy
    | 役割 ：	削除処理
    |
    */


    /**
     * Remove the specified resource from storage.
     * 指定されたリソースをストレージから削除します。 指定されたリソースをストレージから削除します。
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /***************************
         * 削除対象レコードを検索
         *****************************/
        $event = Event::find($id);
        /***************************
         * 削除
         *
         *****************************/
        $event->delete();

        /***************************
         * セーブしたら最初のページに返してあげる
         * redirect()メソッドを利用する
         *****************************/

        //リダイレクト
        return redirect()->to('/core/events');
    }
}