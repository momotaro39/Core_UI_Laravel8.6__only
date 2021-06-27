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

        // 下の書き方でも良い
        // $user = auth()->user(); //アクセスしているユーザ情報を取得
        /***************************
         *
         *  データベースから必要な情報を取得
         *
         *
         *****************************/

        $bandMembers = BandMembers::get();




        /***************************
         *
         * フォームの選択リストを呼び出す
         * セレクトボックスの表示
         *
         *****************************/


        // $sectionList    = MSection::getSectionList(); //セクション名


        /***************************
         * 追加機能として利用
         * ページネーションの数を設定する
         * コンフィグファイルでページ数を設定しておく
         *****************************/
        $paginateNum     = config('const.paginate.other'); //ページ設定


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

        );

        return view('MemberManagement.members.index', $requestData);
    }



    /*
    |--------------------------------------------------------------------------
    |  新規投稿画面を表示（create）
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


        /***************************
         * 保存
         * オブジェクトの配列をsaveメソッドで保存する
         *****************************/

        $members->save();


        /***************************
         * 追加機能として利用
         * ページネーションの数を設定する
         * コンフィグファイルでページ数を設定しておく。
         *
         * Bootstrap方式を使うpsgenate()方法も記述
         *
         *****************************/
        $paginateNum     = BandMembers::paginate(config('const.paginate.other')); //ページ設定


        /***************************
         *
         *  データベースから必要な情報を取得
         *  一覧の全表示のために必要
         *
         *****************************/

        $bandMembers = BandMembers::get();



        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/

        $requestData = compact(
            'bandMembers',
            'bandMember',
        );


        /***************************
         * 一覧画面に遷移します。
         *****************************/

        return view('MemberManagement.members.index', $requestData);
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
    public function show(BandMembers $bandMember, $id)
    {

        /***************************
         * レコードを検索
         *****************************/

        $bandMember = BandMembers::find($id);


        /***************************
         * 管理者のみ表示することができる
         * 一旦外します
         *****************************/

        // if (auth()->user()->isAdministrators()) {
        //     $bandMember = BandMembers::all()->first();
        //     // dd($bandMembers);
        // } else {
        //     $this->authorize('view', $bandMember);
        // }


        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/


        $requestData = compact(

            'bandMember',

        );

        /***************************
         * 検索結果をビューに渡す
         *****************************/
        // // Policy.phpのview関数を呼んで管理者でなければ ユーザーの所属するバンドメンバーしか見られないように制御します。
        // $this->authorize('view', $bandMembers);

        return view('MemberManagement.members.show', $requestData);
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
    public function edit($id)
    {
        /***************************
         * レコードを検索
         *****************************/

        $bandMember = BandMembers::find($id);

        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'AdminRoles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/


        $requestData = compact(
            'bandMember',

        );


        /***************************
         * idを取得して、DBから検索して検索結果をビューに渡す
         * 個別の情報が表示される
         *****************************/

        return view('MemberManagement.members.edit', $requestData);
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
    public function update(Request $request, $id)
    {

        /***************************
         * レコードを検索
         *****************************/

        $bandMember = BandMembers::find($id);

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

        /***************************
         * 保存
         * オブジェクトの配列をsaveメソッドで保存する
         *****************************/

        $bandMember->fill($inputs)->save();


        /***************************
         * 保存方式2つめ
         * オブジェクトの配列をupdateメソッドで保存する
         *****************************/
        // Event::where('id', $id)->update($request->all());
        /***************************
         * セーブしたら最初のページに返してあげる
         * redirect()メソッドを利用する
         *****************************/

        //リダイレクト
        return redirect()->to('/core/members');
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
    public function destroy(BandMembers $bandMembers, $id)
    {
        /***************************
         * 削除対象レコードを検索
         *****************************/
        $bandMember = BandMembers::find($id);
        /***************************
         * 削除方式1
         *
         *****************************/
        $bandMember->delete();

        /***************************
         * 削除方式2
         *合体版
         *****************************/
        BandMembers::where('id', $id)->delete();
        /***************************
         * セーブしたら最初のページに返してあげる
         * redirect()メソッドを利用する
         *****************************/

        //リダイレクト
        return redirect()->to('/core/members');
    }
}