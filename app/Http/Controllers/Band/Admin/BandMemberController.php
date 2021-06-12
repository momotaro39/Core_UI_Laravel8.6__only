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



use App\Models\User;
use App\Models\Band\AdminRole;
use App\Models\Band\BandMembers;
use App\Models\Band\UserRole;
use Illuminate\Http\Request;

class BandMemberController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | 一覧表示の情報を表示
    |--------------------------------------------------------------------------
    |
    | リクエストでビュー画面から得た情報を取得
    | index(Request $request)で検索一覧のデータを取得できるように設定する
    |
    */


    /**
     * バンドメンバー一覧表示
     * スーパーバイザーは全店舗の顧客を見られる
     * 以外は自分が所属する顧客のみ表示をするようにする
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request = null)
    {


        /***************************
         *
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
        $adminRoles = AdminRole::get(); // 管理役割一覧を取得
        $userRoles = UserRole::get(); // 利用役割一覧を取得

        $bandMembers = BandMembers::get();


        /***************************
         *
         * 検索するための情報は２つ用意する
         * リクエストデータの取得
         *
         *****************************/

        $conditions      = $request->all();
        $originalRequest = $request;

        /***************************
         *
         *  順番を入れ替えるために必要
         *  順番のカラム設定・順番の昇降順
         *
         *****************************/

        $orderby         = $request->input('orderby') ?: 'created_at';
        $sort            = $request->input('sort') ?: 'desc';


        /***************************
         *
         * フォームの選択リストを呼び出す
         * セレクトボックスの表示
         *
         *****************************/


        // $sectionList    = MSection::getSectionList(); //セクション名


        /***************************
         * データベースとフォームの情報を一致するものを取得
         *  モデル名::searchByConditions($conditions);
         *
         * $queriesはデータベースから取得した情報の総称
         * これがデータベースからの取得の始まり
         * 以降が加工のための変数を追加
         *****************************/

        $queries =  BandMembers::searchByConditions($conditions); //検索

        /***************************
         * 追加機能として利用
         * データベースと一致した数を取得して、数を計上する。
         *
         *****************************/
        $listCount       = $queries->count(); //件数取得

        /***************************
         * 追加機能として利用
         * $sum〇〇
         * 特定のカラムの数字を合計して出力
         *****************************/
        // $sumCosts        = $queries->sum('performance_production_cost');

        /***************************
         * 追加機能として利用
         * ページネーションの数を設定する
         * コンフィグファイルでページ数を設定しておく。
         *
         * Bootstrap方式を使うpsgenate()方法も記述
         *
         *****************************/
        $paginateNum     = config('const.paginate.other'); //ページ設定

        $paginateNum     = config('const.paginate.other'); //ページ設定

        $paginateNum     = config('const.paginate.other'); //ページ設定
        // $paginations = 〇〇::paginate(config('const.paginate.other'));

        /***************************
         * 追加機能として利用
         * 1 ソート機能を追加
         * 2 ソートした内容をページネーションの数値で設定した表示数に区切る
         *****************************/

        $queriesList = $this->setOrderBy($queries, $orderby, $sort);


        // $queriesList = $queriesList->paginate($paginateNum); //ページネーション用

        /***************************
         *
         * バンドメンバー情報は管理者と代表者であれば 全バンドのメンバー情報を閲覧できますが、
         * 代表の場合は自分が所属するバンドのメンバー情報しか閲覧できません。
         *
         *****************************/

        if (auth()->user()->isAdministrators()) {
            $bandMembers = BandMembers::paginate();
        } else {
            $bandMembers = BandMembers::where('band_id', auth()->user()['band_id'])->paginate();
        }


        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/

        $requestData = compact(
            // 認証情報
            'user',
            'bandMembers',
            // 表示リスト
            // 'sectionList',
            // リクエスト情報
            'conditions',
            // 加工情報
            'queriesList',
        );

        return view('MemberManagement.members.index', $requestData);
    }



    /*
    |--------------------------------------------------------------------------
    | 新規投稿画面を表示
    |--------------------------------------------------------------------------
    |
    | HTTP動詞：	GET
    | URL ：	/articles/create
    | アクション ：	create
    | 役割 ：	新規投稿画面
    |
    */

    /**
     * 新しいリソースを作成するためのフォームを表示します。
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('MemberManagement.members.create');
    }

    /**
     * Store a newly created resource in storage.
     * 新規登録のためのバリデーションをここで設定することができます。
     * * store(Request $request)
     * ストア関数 ユーザーが入力した情報の入っている Requestを受けます。
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //   * request()->validate の部分
        // バリデーションは入力が正しいか確認する仕組みです。
        // バリデーション関数にバリデーションルールを連想配列として渡します。
        // バリデーションに失敗すると新規顧客画面に戻ります。
        $attribute = request()->validate([
            'name'         => ['required', 'min:3', 'max:32'],
            'band_id'      => ['required', 'Numeric', 'Between:1,3'],
            'post'         => ['required',],
            'address'      => ['required',],
            'email'        => ['required', 'E-Mail'],
            'birth'        => ['required', 'Date'],
            'phone'        => ['required',],
            'claimer_flag' => ['required', 'Numeric', 'Between:0,1'],
        ]);


        // 顧客情報をデータベースに新規登録します。
        $members = BandMembers::create($attribute);

        // 顧客一覧画面に遷移します。
        return redirect('/members');
    }

    /*
    |--------------------------------------------------------------------------
    | 個別ページ表示の情報を表示
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
     * @param  \App\Models\BandMembers  $bandMembers
     * @return \Illuminate\Http\Response
     */

    // Laravelが該当する情報をデータベースから取得し bandMembers変数に入れます。
    public function show(BandMembers $bandMembers)
    {

        if (auth()->user()->isAdministrators()) {
            $bandMembers = BandMembers::all()->first();
            // dd($bandMembers);
        } else {
            $this->authorize('view', $bandMembers);
        }


        // // Policy.phpのview関数を呼んで管理者でなければ ユーザーの所属するバンドメンバーしか見られないように制御します。
        // $this->authorize('view', $bandMembers);

        return view('MemberManagement.members.show', compact('bandMembers'));
    }


    /*
    |--------------------------------------------------------------------------
    | 更新画面を表示
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
     * 指定したリソースを編集するためのフォームを表示します。
     * @param  \App\Models\BandMembers  $bandMembers
     * @return \Illuminate\Http\Response
     */
    public function edit(BandMembers $bandMembers)
    {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | 更新処理
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BandMembers  $bandMembers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BandMembers $bandMembers)
    {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | 削除処理
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
     * 指定されたリソースをストレージから削除します。
     * @param  \App\Models\BandMembers  $bandMembers
     * @return \Illuminate\Http\Response
     */
    public function destroy(BandMembers $bandMembers)
    {
        //
    }
}
