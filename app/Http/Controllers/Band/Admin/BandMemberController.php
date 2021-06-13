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
    public function index()
    {


        /***************************
         * ログインユーザーを取得してデータを利用するときに使用
         *
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
        );

        return view('MemberManagement.members.index', $requestData);
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
        return view('MemberManagement.members.create');
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
     * 新規登録のためのバリデーションをここで設定することができます。
     * * store(Request $request)
     * ストア関数 ユーザーが入力した情報の入っている Requestを受けます。
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
            'email' => 'required|email|unique:users',
        ];

        //バリデーションルールにかかった時のメッセージ
        $messages = [
            // 'name.required' => '名前は必須です。',
            'email.required' => 'emailは必須です。',
            'email.email' => 'emailの形式で入力して下さい。',
            'email.unique' => 'このemailは既に登録されています。',
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
         * requestの情報にバリデーションをかけて変数（$attribute）に入れる
         *
         * $attribute はフィールドの名称を入れます。
         * 別にもできるが今回はこの方法も明記
         *
         * request()->validate の部分
         * バリデーションは入力が正しいか確認する仕組みです。
         * バリデーション関数にバリデーションルールを連想配列として渡します。
         * バリデーションに失敗すると新規顧客画面に戻ります。
         *****************************/


        $attribute = request()->validate([
            // 'name'         => ['required', 'min:3', 'max:32'],
            'band_id'      => ['required', 'Numeric', 'Between:1,3'],
            'post'         => ['required',],
            'address'      => ['required',],
            'email'        => ['required', 'E-Mail'],
            'birth'        => ['required', 'Date'],
            'phone'        => ['required',],
            'claimer_flag' => ['required', 'Numeric', 'Between:0,1'],
        ]);


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

        $bandMember = BandMembers::create($attribute);


        /***************************
         * ログインユーザーを取得してデータを利用するときに使用
         * use文に追加しておくこと
         * use Illuminate\Support\Facades\Auth;
         *****************************/

        $user = Auth::user();




        /***************************
         *
         *  順番を入れ替えるために必要
         *  順番のカラム設定・順番の昇降順
         *
         *****************************/

        $orderby         = $request->input('orderby') ?: 'created_at';
        $sort            = $request->input('sort') ?: 'desc';




        /***************************
         * オブジェクトにビューからもらったFormのリクエストデータを入れる
         * 左がオブジェクトの配列
         * 右がリクエストの配列
         *****************************/
        // $user->name = $request->name;
        // $user->email = $request->email;



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
         * コンフィグファイルでページ数を設定しておく。
         *
         * Bootstrap方式を使うpsgenate()方法も記述
         *
         *****************************/
        $paginateNum     = config('const.paginate.other'); //ページ設定

        // $paginations = 〇〇::paginate(config('const.paginate.other'));




        /***************************
         * 保存
         * オブジェクトの配列をsaveメソッドで保存する
         *****************************/

        $bandMember->save();


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

        /***************************
         * 一覧画面に遷移します。
         *****************************/



        // return redirect('/members');
        return view('MemberManagement.members.index', $requestData);
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
     * @param  \App\Models\BandMembers  $bandMembers
     * @return \Illuminate\Http\Response
     */

    // Laravelが該当する情報をデータベースから取得し bandMembers変数に入れます。
    public function show(BandMembers $bandMembers, $id)
    {
        /***************************
         * レコードを検索
         *****************************/

        $bandMember = BandMembers::find($id);

        /***************************
         * 追加機能として利用 (所属のみ閲覧可能)
         * Policy.phpのview関数を呼んで管理者でなければ ユーザーの所属するバンドメンバーしか見られないように制御します。
         *
         * $this->authorize('view', $bandMembers);
         *
         *****************************/

        if (auth()->user()->isAdministrators()) {
            $bandMembers = BandMembers::all()->first();
            // dd($bandMembers);
        } else {
            $this->authorize('view', $bandMembers);
        }


        $requestData = compact(
            'bandMember',
            'bandMembers',
        );

        /***************************
         * 検索結果をビューに渡す
         *****************************/

        // return view('MemberManagement.members.show')->with('user',$user);

        return view('MemberManagement.members.show', $requestData);
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
         * 検索結果をビューに渡す
         *****************************/

        return view('MemberManagement.members.edit')->with('bandMember', $bandMember);
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
         * 値を代入
         * find()したデータにリクエストの情報を入力
         *****************************/

        $bandMember->name = $request->name;
        $bandMember->email = $request->email;

        /***************************
         * 保存（更新）
         * 上書きを行う
         *****************************/

        $bandMember->save();

        /***************************
         * セーブしたら最初のページに返してあげる
         * redirect()メソッドを利用する
         *****************************/

        //リダイレクト
        return redirect()->to('/core/members');
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
     * 指定されたリソースをストレージから削除します。
     * @param  \App\Models\BandMembers  $bandMembers
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /***************************
         * 削除対象レコードを検索
         *****************************/
        $bandMember = BandMembers::find($id);
        /***************************
         * 削除
         *
         *****************************/

        $bandMember->delete();

        /***************************
         * リダイレクト
         *****************************/

        return redirect()->to('/core/members');
    }
}