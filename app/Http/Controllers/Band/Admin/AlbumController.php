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

//コントローラーの場所を変えたときには必要になる
use App\Http\Controllers\Controller;

//認証機能を使うときに必要
use Illuminate\Support\Facades\Auth;

use App\Models\Band\Album;
use App\Models\Band\AdminRole;
use App\Models\Band\UserRole;
use Illuminate\Http\Request;

class AlbumController extends Controller
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
        $albumDates = Album::get();




        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/

        $requestData = compact(
            // 認証情報
            'user',
            'albumDates',
            // 表示リスト
            // 'sectionList',
            // リクエスト情報
            'conditions',
            // 加工情報
            'queriesList',
        );
        return view('MemberManagement.albums.index', $requestData);


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
        //
    }

    /*
    |--------------------------------------------------------------------------
    | 新規投稿処理
    |--------------------------------------------------------------------------
    |
    | HTTP動詞：	POST
    | URL ：	/articles
    | アクション ：	store
    | 役割 ：	新規投稿処理
    |
    | 新規登録のためのバリデーションをここで設定することができます。
    | store(Request $request)ストア関数 ユーザーが入力した情報の入っているRequestを受けます。
    |
    |
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
        //
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
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        //
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
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
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
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
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
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        //
    }


    /*
    |--------------------------------------------------------------------------
    | ソートを設定します。
    |--------------------------------------------------------------------------
    |
    | join()で連結させる外部キーとテーブルを設定します。
    | orderBy()で優先されるカラム名を指定して、ソートをかけていきます。
    | ※ソートの順番はindexメソッドに入力しています。
    | ※変更する場合はここでソートの変数をセットするのも可能
    |
    |
    |
    */

    /**
     * ソートを設定します
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $orderBy
     * @param string $sort
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function setOrderBy($query, $orderBy, $sort)
    {
        switch ($orderBy) {

            case 'created_at':
            default:
                $query = $query->orderBy($orderBy, $sort);
                break;
        }
        return $query;
    }


}
