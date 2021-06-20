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

use App\Models\Band\GuestReservation;
use App\Models\Band\AdminRole;
use App\Models\Band\UserRole;
use Illuminate\Http\Request;

class GuestReservationController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
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

        $guestReservations = GuestReservation::get();

        $query = GuestReservation::query();

        /***************************
         *
         * フォームの選択リストを呼び出す
         * セレクトボックスの表示
         *
         *****************************/


        // $sectionList    = MSection::getSectionList(); //セクション名





        /***************************
         * 追加機能として利用
         * $sum〇〇
         * 特定のカラムの数字を合計して出力
         *****************************/
        // $sumCosts        = $queries->sum('performance_production_cost');

        /***************************
         * 追加機能として利用
         * ページネーションの数を設定する
         * コンフィグファイルでページ数を設定しておく
         *****************************/
        $paginateNum     = config('const.paginate.other'); //ページ設定

        /***************************
         * 追加機能として利用
         * 1 ソート機能を追加
         * 2 ソートした内容をページネーションの数値で設定した表示数に区切る
         *****************************/


        // $queriesList = $queriesList->paginate($paginateNum); //ページネーション用



        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/

        $requestData = compact(
            // 認証情報
            'user',
            'guestReservations',

            // 表示リスト
            // 'sectionList',
            // リクエスト情報

        );
        // dd($requestData);
        return view('MemberManagement.GuestReservations.index', $requestData);
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
        /***************************
         * リダイレクト
         *****************************/
        //create.blade.phpに転送
        return view('MemberManagement.GuestReservations.create');
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
        /***************************
         * バリデーションセット
         *
         * バリデーションルールは別ファイルを作ってもOK
         *****************************/

        // バリデーションをかける情報をセット
        $inputs = $request->all();


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

        // $event = Event::create($request->all());

        // $event = Event::create();
        $guestReservation =  GuestReservation::create($request->all());

        /***************************
         * オブジェクトにビューからもらったFormのリクエストデータを入れる
         * 左がオブジェクトの配列
         * 右がリクエストの配列
         *****************************/
        $guestReservation->user_id = $request->user_id;
        $guestReservation->event_id = $request->event_id;




        /***************************
         * 保存
         * オブジェクトの配列をsaveメソッドで保存する
         *****************************/

        $guestReservation->save();


        /***************************
         * 追加機能として利用
         * ページネーションの数を設定する
         * コンフィグファイルでページ数を設定しておく。
         *
         * Bootstrap方式を使うpsgenate()方法も記述
         *
         *****************************/
        $paginateNum     = GuestReservation::paginate(config('const.paginate.other')); //ページ設定


        /***************************
         *
         *  データベースから必要な情報を取得
         *  一覧の全表示のために必要
         *
         *****************************/

        $guestReservations = GuestReservation::get();



        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/

        $requestData = compact(
            'guestReservations',
            'guestReservation',
        );

        /***************************
         * 一覧画面に遷移します。
         *****************************/

        return view('MemberManagement.GuestReservations.index', $requestData);
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
     * @param  \App\Models\GuestReservation  $guestReservation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /***************************
         * レコードを検索
         *****************************/

        $guestReservation = GuestReservation::find($id);

        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/


        $requestData = compact(
            'guestReservation',
        );


        /***************************
         * idを取得して、DBから検索して検索結果をビューに渡す
         * 個別の情報が表示される
         *****************************/

        return view('MemberManagement.GuestReservation.edit', $requestData);
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
     * @param  \App\Models\GuestReservation  $guestReservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        /***************************
         * レコードを検索
         *****************************/

        $guestReservation = GuestReservation::find($id);

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

        // $guestReservation->name = $request->name;

        /***************************
         * 保存
         * オブジェクトの配列をsaveメソッドで保存する
         *****************************/

        $guestReservation->fill($inputs)->update();


        /***************************
         * 保存方式2つめ
         * オブジェクトの配列をupdateメソッドで保存する
         *****************************/
        // GuestReservation::where('id', $id)->update($request->all());
        /***************************
         * セーブしたら最初のページに返してあげる
         * redirect()メソッドを利用する
         *****************************/

        //リダイレクト
        return redirect()->to('/core/guestreservation');
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
     * @param  \App\Models\GuestReservation  $guestReservation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /***************************
         * 削除対象レコードを検索
         *****************************/
        $guestReservation = GuestReservation::find($id);
        /***************************
         * 削除方式1
         *
         *****************************/
        $guestReservation->delete();

        /***************************
         * 削除方式2
         *合体版
         *****************************/
        // GuestReservation::where('id', $id)->delete();
        /***************************
         * セーブしたら最初のページに返してあげる
         * redirect()メソッドを利用する
         *****************************/

        //リダイレクト
        return redirect()->to('/core/guestReservation');
    }
}