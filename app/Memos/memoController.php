<?php


   /*
    |--------------------------------------------------------------------------
    | 名前空間の指定
    |--------------------------------------------------------------------------
    |
    | a
    |
    */

namespace App\Http\Controllers;


   /*
    |--------------------------------------------------------------------------
    | 既存機能の追加
    |--------------------------------------------------------------------------
    |
    | a
    |
    */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HourRequest;

   /*
    |--------------------------------------------------------------------------
    | モデルの追加
    |--------------------------------------------------------------------------
    |
    | リレーションで設置した情報を取得する内容を記述
    |
    */
use App\Project;
use App\ProductionCost;
use App\MSection;
use App\MCustomer;
use App\User;
use App\Task;
use App\MTeam;



   /*
    |--------------------------------------------------------------------------
    | 追加でライブラリが必要だったら追加
    |--------------------------------------------------------------------------
    |
    | a
    |
    */

// Excel
use Maatwebsite\Excel\Excel;
use App\Libs\HourDownload;

class HourController extends Controller
{


   /*
    |--------------------------------------------------------------------------
    | 初期値を設定
    |--------------------------------------------------------------------------
    |
    | Excel（エクセル）関係の定数を設置
    |
    */

    /**
     * Optional Writer Type
     * 出力の拡張子
     */
    private $writerType = Excel::XLSX;

    /**
     * Export Headers
     * EXCELヘッダーセルの名称
     * 該当マイグレーションファイルのコメントを活用して整理
     */
    public static $headers = [
        '日付',
        '部署名',
        'チーム名',
        'ユーザー名',
        'プロジェクト名',
        'タスク名',
        '実績工数(h)',
        '備考'
    ];


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
     * 工数入力
     * @return type
     */
    public function index(Request $request)
    {

        /***************************
         *
         *  ログインユーザーを取得してデータを利用するときに使用
         *
         *****************************/

        $user = Auth::user();


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


        $sectionList    = MSection::getSectionList(); //セクション名
        $teamList       = MTeam::getTeamList(); //チーム名
        $customerList   = MCustomer::getCustomerList(); //顧客名
        $createUserList = User::getCreateUserList(); //作成者

        /***************************
         * データベースとフォームの情報を一致するものを取得
         * これがデータベースからの取得の始まり
         * 以降が加工のための変数を追加
         *****************************/

        $queries =  ProductionCost::searchByConditions($conditions); //検索

        /***************************
         * 追加機能として利用
         * データベースと一致した数を取得して、数を計上する。
         *
         *****************************/
        $listCount       = $queries->count(); //件数取得

        /***************************
         * 追加機能として利用
         * 特定のカラムの数字を合計して出力
         *****************************/
        $sumCosts        = $queries->sum('performance_production_cost');

        /***************************
         * 追加機能として利用
         * ページネーションの数を設定する
         * コンフィグファイルでページ数を設定しておく。
         *
         * Bootstrap方式を使うpsgenate()方法も記述
         *
         *****************************/
        $paginateNum     = config('const.paginate.hour'); //ページ設定

        /***************************
         * 追加機能として利用
         * 1 ソート機能を追加
         * 2 ソートした内容をページネーションの数値で設定した表示数に区切る
         *****************************/

        $queriesList = $this->setOrderBy($queries, $orderby, $sort);
        $queriesList = $queriesList->paginate($paginateNum); //ページネーション用


        /***************************
         * 追加機能として利用
         * return view('MemberManagement.members.index', compact('bandMembers', 'roles'));
         * まとめてセットするほうが整理しやすいのでい以下のように記述
         * 変数の順番にセットして見やすくすること
         *****************************/

        $requestData = compact(
            // 認証情報
            'user',
            // 表示リスト
            'sectionList',
            'teamList',
            'listCount',
            'createUserList',
            // リクエスト情報
            'conditions',
            // 加工情報
            'queriesList',
            'sumCosts'
        );
        return view('hour.index', $requestData);
    }


   /*
    |--------------------------------------------------------------------------
    | 詳細画面の設定
    |--------------------------------------------------------------------------
    |
    | idをnullで初期設定しておく
    |
    |
    |
    */

    /**
     * 詳細
     * @return type
     */
    public function detail($id = null , Request $request)
    {
        /***************************
         * 変数を宣言する
         * idがある場合とない場合で処理を分岐する
        *****************************/


        $productionCost      = ProductionCost::find($id);

        /***************************
         * 一覧表示で表示するリストを各モデルから取得する
        *****************************/

        $projectList         = Project::getSelfProjectList();
        $performanceCostList = ProductionCost::getSelectCost();

　       /***************************
         * インスタンスを作成する
         * 変数 ＝ new モデル名（）
         *
         * idがない場合はインスタンスと配列を作成する
         * idが存在する場合はリレーションから必要情報を取得する
         *
        *****************************/
        if(!isset($productionCost)) {
            $productionCost      = new ProductionCost();
            $taskList            = [];
            $sectionsProjectList = [];
        } else {
            $taskList = Task::where('project_id', $productionCost->project_id)
                            ->pluck('name', 'id');
            $sectionsProjectList = Project::getProjectListByProductionCostId($productionCost->user->section_id);
        }

        /***************************
         * ビュー画面に渡す情報をまとめる
        *****************************/

        return view('hour.edit', compact(
            'id',
            'projectList',
            'sectionsProjectList',
            'taskList',
            'performanceCostList',
            'productionCost'
        ));
    }


   /*
    |--------------------------------------------------------------------------
    | 更新処理
    |--------------------------------------------------------------------------
    |
    | 更新する場合はRequestの名称を作成したファイル名に変更する。
    | バリデーションを独自に設定している場合はその対応が必要
    |
    |
    */

    /**
     * 更新処理
     * @return type
     */
    public function update(MPartnerRequest $request)
    {
        /***************************
         * 変数を宣言する
         * idがある場合とない場合で処理を分岐する
        *****************************/

        $mPartner = MPartner::find($request->id);


        /***************************
         * インスタンスを作成する
         * 変数 ＝ new モデル名（）
         *
         * idが存在しない場合はインスタンスを作成する
         *
        *****************************/
        if (!$mPartner) {
            $mPartner = new MPartner();

        }

        /***************************
         * 新規作成したインスタンス or idがあった場合は上書き
         *
         * fill($request->all()) リクエスト情報を全て代入
         * save() 保存処理
        *****************************/

        $mPartner->fill($request->all())->save();

        /***************************
         * [Laravel]レスポンスをJSON形式で返す方法
         * https://qiita.com/qiita-kurara/items/089db7349e33b9402b42
         *
         *
         *
         * response()->json() でreturnすると・・HP関数によりJSONへ変換される
         * {
         * 'apple':'red',
         * 'peach':'pink'
         * }
        *****************************/

        return response()->json([
            'fail'         => false,
            'redirect_url' => url('/master/user/')
        ]);
    }

   /*
    |--------------------------------------------------------------------------
    | ベーシックな削除処理
    |--------------------------------------------------------------------------
    |
    |
    |
    */

     /**
     * 削除処理
     * @return type
     */
    public function delete($id)
    {
        /***************************
         * 変数を宣言する
         * idが存在するのでモデル名を指定して取得する
        *****************************/

        $productionCost = ProductionCost::find($id);

        /***************************
         * 削除対象が存在する場合のみ
         * ユーザーIDを取得して記録する
         * 削除時間を記録
         * 変更したカラムを全て保存
         * リダイレクトするページを設定する
        *****************************/

        if (isset($productionCost)) {
            $productionCost->delete_user_id = Auth::user()->id;
            $productionCost->deleted_at     = date('Y-m-d H:i:s');
            $productionCost->save();
        }
        return redirect('/master/project');
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
                // 第二キー以降は常にセクション > チーム > 氏名 > プロジェクト > タスク
            case 'regist_date':
            case 'created_at':
                $query = $query->join('users', 'production_costs.user_id', '=', 'users.id')
                    ->join('projects', 'production_costs.project_id', '=', 'projects.id')
                    ->join('tasks', 'production_costs.task_id', '=', 'tasks.id')
                    ->orderBy('created_at', $sort)
                    ->orderBy('users.section_id', $sort)
                    ->orderBy('users.team_id', $sort)
                    ->orderBy('users.name', $sort)
                    ->orderBy('project_id')
                    ->orderBy('task_id');
                break;
            case 'section_id':
                $query = $query->join('users', 'production_costs.user_id', '=', 'users.id')
                    ->join('projects', 'production_costs.project_id', '=', 'projects.id')
                    ->join('tasks', 'production_costs.task_id', '=', 'tasks.id')
                    ->orderBy('users.section_id', $sort)
                    ->orderBy('regist_date', $sort)
                    ->orderBy('users.team_id', $sort)
                    ->orderBy('users.name', $sort)
                    ->orderBy('projects.name', $sort)
                    ->orderBy('tasks.name', $sort);

                break;
            case 'team_id':
                $query = $query->join('users', 'production_costs.user_id', '=', 'users.id')
                    ->join('projects', 'production_costs.project_id', '=', 'projects.id')
                    ->join('tasks', 'production_costs.task_id', '=', 'tasks.id')
                    ->orderBy('users.team_id', $sort)
                    ->orderBy('regist_date', $sort)
                    ->orderBy('users.section_id', $sort)
                    ->orderBy('users.name', $sort)
                    ->orderBy('projects.name', $sort)
                    ->orderBy('tasks.name', $sort);
                break;
            case 'user_id':
                $query = $query->join('users', 'production_costs.user_id', '=', 'users.id')
                    ->join('projects', 'production_costs.project_id', '=', 'projects.id')
                    ->join('tasks', 'production_costs.task_id', '=', 'tasks.id')
                    ->orderBy('users.name', $sort)
                    ->orderBy('regist_date', $sort)
                    ->orderBy('users.section_id', $sort)
                    ->orderBy('users.team_id', $sort)
                    ->orderBy('projects.name', $sort)
                    ->orderBy('tasks.name', $sort);
                break;
            case 'project_id':
                $query = $query->join('projects', 'production_costs.project_id', '=', 'projects.id')
                    ->join('users', 'production_costs.user_id', '=', 'users.id')
                    ->join('tasks', 'production_costs.task_id', '=', 'tasks.id')
                    ->orderBy('projects.name', $sort)
                    ->orderBy('regist_date', $sort)
                    ->orderBy('tasks.name', $sort)
                    ->orderBy('users.section_id', $sort)
                    ->orderBy('users.team_id', $sort)
                    ->orderBy('users.name', $sort);
                break;
            case 'task_id':
                $query = $query->join('tasks', 'production_costs.task_id', '=', 'tasks.id')
                    ->join('projects', 'production_costs.project_id', '=', 'projects.id')
                    ->join('users', 'production_costs.user_id', '=', 'users.id')
                    ->orderBy('tasks.name', $sort)
                    ->orderBy('regist_date', $sort)
                    ->orderBy('users.section_id', $sort)
                    ->orderBy('users.team_id', $sort)
                    ->orderBy('users.name', $sort)
                    ->orderBy('projects.name', $sort);
                break;
            case 'performance_production_cost':
                $query = $query->join('users', 'production_costs.user_id', '=', 'users.id')
                    ->join('tasks', 'production_costs.task_id', '=', 'tasks.id')
                    ->join('projects', 'production_costs.project_id', '=', 'projects.id')
                    ->orderBy('performance_production_cost', $sort)
                    ->orderBy('regist_date', $sort)
                    ->orderBy('users.section_id', $sort)
                    ->orderBy('users.team_id', $sort)
                    ->orderBy('users.name', $sort)
                    ->orderBy('projects.name', $sort)
                    ->orderBy('tasks.name', $sort);
                break;
            default:
                $query = $query->orderBy($orderBy, $sort);
                break;
        }
        return $query;
    }


   /*
    |--------------------------------------------------------------------------
    | 追加機能の設定
    |--------------------------------------------------------------------------
    |
    | Laravel Excelの設定を行います
    |
    */

    /**
     * EXCELエクスポート
     *
     * @param Request $request
     * @return void
     */
    public function excelExport(Request $request)
    {
        //Requestデータを取得
        $conditions = $request->all();

        //Requestデータで検索機能をかける
        $query = ProductionCost::searchByConditions($conditions);

        //整形 （検索機能をかけたデータ、順序ののカラム、ソートの昇順及び・降順）
        $query      = $this->setOrderBy($query, 'regist_date', 'desc');
        // ファイルの名称を組み立てる
        $fileName   = '工数一覧_' . date('Ymd') . '.xlsx';

        return HourDownload::excelHourDownload($query, $fileName, $this->writerType, self::$headers);
    }


     /*
    |--------------------------------------------------------------------------
    | モデルの挿入と更新
    |--------------------------------------------------------------------------
    |
    | データベースへ新しいレコードを挿入する順番
    | １ 新しいモデルインスタンスをインスタンス化
    | ２ モデルに属性をセットする
    | ３ モデルインスタンスでsaveメソッドを呼び出します。
    |
    | saveメソッドを呼び出すと、レコードがデータベースに挿入されます。
    | モデルのcreated_atおよびupdated_atタイムスタンプは、saveメソッドが呼び出されたときに自動的に設定される
    |
    */

    /**********************
     *
     ***********************/

    /**
     * 新しいフライトをデータベースに保存
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // リクエストのバリデーション処理…
        // １ 新しいモデルインスタンスをインスタンス化
        $flight = new Flight;

        // ２ モデルに属性をセットする
        // 左が作成したインスタンス ＝ リクエストからきた「name」情報を入れる
        // 左が作成したインスタンス ＝ リレーションを貼ったモデルのカラム名の「name」情報を入れることもできる
        $flight->name = $request->name;

        // ３ モデルインスタンス（ここでは$flightにシングルアローを入れる）でsaveメソッドを呼び出します。
        $flight->save();
    }


}