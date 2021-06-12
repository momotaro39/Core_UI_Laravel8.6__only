<?php

// コントローラーの基本

    /*
    |--------------------------------------------------------------------------
    |
    | 必要な機能をインポートする
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */
// モデルのネームスペースを設定する
namespace App\Http\Controllers;

    /*
    |--------------------------------------------------------------------------
    |
    | 必要な機能をインポートする
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */
// 使うモデルを設定
use App\Company;

// CSVを使うときはインポート・エクスポートするときは準備する
use App\Libs\RepresentativeDownload;
// フロント側から情報を入力するときは使用する
use Illuminate\Http\Request;


/*
    |--------------------------------------------------------------------------
    |
    | 必要な機能をインポートする
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */

// 参照情報
// Requestクラスのメソッド
// https://qiita.com/kuzira_vimmer/items/54d9bfd88f66208c1709








/*
    |--------------------------------------------------------------------------
    |
    | 【Laravel】Controller内のメソッドがひとつの場合の書き方　__invoke
    |
    |--------------------------------------------------------------------------
    |
    | メソッドがひとつのみの場合、
    | "__invoke"というメソッドを使います。
    | 日本語の意味は「呼び出す」です。
    */

class RepresentativeController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    |
    | CSV関係の処理
    |
    |--------------------------------------------------------------------------
    |
    | 1 ヘッダーカラムの設定
    | 2 エクスポート設定
    |
    */
    /**
     * csvダウンロードのheader
     * 項目をここで入力する
     * @var array
     */
    public static $headers = [
        'メールアドレス',
        'パスワード',
        '代表者都道府県名',
        '学校名',
        'チーム名',
        '代表者漢字氏名（姓）',
        '代表者漢字氏名（名）',
        '代表者かな氏名（姓）',

    ];


    /**
     * 代表者一覧をcsvでエクスポートします
     *
     * @param Request $request
     * @return void
     */
    public function csvExport(Request $request)
    {
        // 初期変数などの設定
        // フロント側で入力された情報を変数をセットします
        $conditions = $request->all();
        // モデルにある情報を検索にかけて、一致するものを変数にセットします。
        $query = Company::searchByConditions(($conditions));

        // ファイルの名前を設定します。 「概要_日付_拡張子」でセット
        $filename = 'representative_' . date('Ymd') . '.csv';
        // Libにセットしたライブラリのダウンロードメソッドを利用。モデルと一致した情報をデータベースから取得した変数、コントローラ内に設定した項目情報（$headers）,ファイルの名前の命名方法）
        return RepresentativeDownload::download($query, self::$headers, $filename, false);
    }


    /*
    |--------------------------------------------------------------------------
    |
    | 認証機能の設定
    |
    |--------------------------------------------------------------------------
    |
    | Web.phpで設定しているときは全てのコントローラーに設定する。
    |
    |
    */
    /**
     * 認証を設定
     *
     */
    public function __construct()
    {
        $this->middleware('auth:administrators');
    }

    /*
    |--------------------------------------------------------------------------
    |
    | 画面の設定一覧
    |
    |--------------------------------------------------------------------------
    |
    | 一覧画面
    | 編集画面
    |
    */


    /**
     * 代表者エントリー 一覧画面
     * @return view
     */
    public function index(Request $request)
    {
        // メンバー一覧の取得準備
        // 検索データ
        $conditions = $request->all(); //フロント側で取得した情報を検索用に利用するために変数にセット
        $originalRequest = $request; //全ての情報をプレーンの状態でフロントに渡すために変数にセット

        // どのカラムをベースにして順番を変更するか
        // ※ Requestクラスのメソッドで調べてね
        // input() 入力値を個別に取得する
        $orderby = $request->input('orderby') ?: 'id';
        // 昇順（1→10）か降順（10→1）かを設定する。ascかdesc
        $sort = $request->input('sort') ?: 'desc';

        // 検索、ページネーションの変数をセット
        $personals = Personal::searchByConditions($conditions); //モデルの検索メソッドに挿入
        $listCount = $personals->count(); //変数に入っている件数を取得するメソッドを使用
        $paginateNum = config('const.paginate.company'); //ページに表示する内容を設定ファイルの数字に合わせる

        //ソート
        // コントローラー内に設定されているsetOrderByメソッド（情報、カラム、順番）を使って、全てをgetメソッドで取得する
        $personalList = $this->setOrderBy($personals, $orderby, $sort)->get();

        // ページの番号を表示するための変数をセットする
        // ページネートメソッド（表示数）を使って、まとまりを作る
        $list = $personals->paginate($paginateNum); //ページネーション用

        //リクエストデータセット コンパクトメソッドを使って、変数に渡して、リターンをすっきり表示する
        // ※コンパクトの時は変数の時の＄は使用せず表示する
        $requestData = compact('conditions', 'personalList', 'listCount', 'list', 'originalRequest', 'paginateNum');
        return view('admin.member.index', $requestData);
    }

    /**
     * 代表者エントリー 編集画面
     * 詳細はidを持っていない状態からスタートする。
     *
     * @return view
     */
    public function detail($id = null)
    {
        // 変数の準備をします
        // idがtrueかfalseを変数にセットします
        $isNew = is_null($id);
        // モデルの消去日があるかをないかを目印に存在を確認して、検索をかけます。
        $company = Company::where('deleted_at', null)->find($id);

        // idがデータベースにない場合は、404を返します。
        if (is_null($company)) {
            abort(404);
        }

        // 存在する場合は、データベースから情報を取得して、ビュー画面に返します
        $requestData = compact('company');
        return view('admin.representative.detail', $requestData);
    }



    /*
    |--------------------------------------------------------------------------
    |
    | ソートを設定します。
    |
    |--------------------------------------------------------------------------
    |
    |  setOrderBy(データベースに一致した情報, カラム名, 昇順か降順)
    | このメソッドはこのコントローラー内で使用するためprivateの設定
    | $queryはrequestからモデルのデータを取得した名称を総称して命名
    | $orderByはどのカラムをベースにして順番を変更するか。画面によって変更できるようにしている。
    | ※特に(id)にセットされることが多い
    |
    */

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $orderBy
     * @param string $sort
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function setOrderBy($query, $orderBy, $sort)
    {
        switch ($orderBy) {
                // 他のモデルとリレーションをしている時はcaseを利用する
                // ログインIDのカラムをソートの項目にした場合の処理
            case 'login_id':
                // データベースの情報を一致させる
                // ※最初にリレーションを貼っておくこと
                // 詳細に順番を変えたい場合はorderByメソッドをチェーンで繋げていく
                // リレーションを貼った情報はドット記法で繋げていくことが可能
                // 必ずbreakをつけること
                $query = $query->leftJoin('users', 'companies.user_id', '=', 'users.id')
                    ->orderBy('users.login_id', $sort);
                break;

            case 'password':
                $query = $query->leftJoin('users', 'companies.user_id', '=', 'users.id')
                    ->orderBy('users.login_password', $sort);
                break;

            default:
                // 基本的には最初にセットされている内容（switch ($orderBy)）でソートをかける
                $query = $query->orderBy($orderBy, $sort);
                break;
        }
        // ケースごとに変えた情報を返します。
        return $query;
    }
}
