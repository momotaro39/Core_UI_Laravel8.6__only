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

use App\Models\band\AdminRole;
use App\Models\Band\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
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

        $userRoles = UserRole::get(); // 利用役割一覧を取得

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

        $queries =  UserRole::searchByConditions($conditions); //検索

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
         * コンフィグファイルでページ数を設定しておく
         *****************************/
        $paginateNum     = config('const.paginate.other'); //ページ設定

        /***************************
         * 追加機能として利用
         * 1 ソート機能を追加
         * 2 ソートした内容をページネーションの数値で設定した表示数に区切る
         *****************************/

        $queriesList = $this->setOrderBy($queries, $orderby, $sort);


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
            'userRoles',
            // 表示リスト
            // 'sectionList',
            // リクエスト情報
            'conditions',
            // 加工情報
            'queriesList',
        );

        return view('MemberManagement.UserRoles.index', $requestData);
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
