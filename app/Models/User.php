<?php



/*
    |--------------------------------------------------------------------------
    |
    | モデル記載の統一（保守性維持）
    |
    |--------------------------------------------------------------------------
    |
    | 共通変数定義は先頭にまとめる。
    | 0 use SoftDeletes; (新規・更新処理導入)
    | 1 自動インクリメントIDの「タイプ」設定
    | 2 Fillable  変更して良いカラムを選択します
    | 3 searchableFields   検索に使用するカラムを指定します
    | 4 リレーション（RDS）
    | 5 boot処理
    | 6 Handler処理
    | 7 アクセサ処理（getExampleAttribute）とミューテタ処理（setExampleAttribute）
    | 8 その他 追加メソッド（検索処理・CSV..etc）
    | 9 static処理（static function getExampleAttribute()）
    |  主キーで使う「ID」の型を指定する
    |
    */

/*
    |--------------------------------------------------------------------------
    |
    | モデルのある場所を指定します
    |
    |--------------------------------------------------------------------------
    |
    |  Laravel7とLaravel8ではフォルダの階層が違うので注意する
    |
    | Laravel7  namespace App;
    | Laravel8  App\Models;
    |
    */



namespace App\Models;


/*
    |--------------------------------------------------------------------------
    |
    | 必要な機能をインポートする
    |
    |--------------------------------------------------------------------------
    |【大分類】
    | 認証系
    | 暗号化系
    | DB記入系
    | その他のLib
    |
    */


// 認証系を使うときはインポートする
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

// パスワードを暗号化するためにインポートする
use Illuminate\Support\Facades\Crypt;

// DBに直接書くときにインポートする
use Illuminate\Support\Facades\DB;
// Libで使うときに利用する
use App\Libs\Stamp;


// 論理削除を使うときは導入する
use Illuminate\Database\Eloquent\SoftDeletes;


use Spatie\Permission\Traits\HasRoles;



// Laravel8の場合は最初に導入される
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


//リレーションディレクトリの指定（RDB）
use App\Models\band\Band;
use App\Models\band\BandMembers;
use App\Models\band\BandMembersLog;
use App\Models\band\AdminRole;
use App\Models\band\Entry;
use App\Models\band\UserType;

/*
    |--------------------------------------------------------------------------
    |
    | プロパティの「型」を定義する
    |
    |--------------------------------------------------------------------------
    |
    |  型 カラム名
    |  integer 数値
    |  string 文字列  ER図ではvarcharを利用
    |  boolean 真偽
    */


/**
 * @property integer $id
 * @property integer $type
 * @property string $login_id
 * @property string $password
 * @property boolean $status

 */


class User extends Authenticatable
{





    /*
    |--------------------------------------------------------------------------
    |
    |  classで利用する機能追加
    |
    |--------------------------------------------------------------------------
    |
    | 利用する機能を明記する
    |
    */
    // Laravel8から導入されています
    use HasFactory, Notifiable;

    // 論理削除を設定します
    use \Illuminate\Database\Eloquent\SoftDeletes;
    // use分をクラスの前に書いたらこの書き方でもOK
    // use SoftDeletes;



    use HasRoles;
    use HasFactory;




    /*
    |--------------------------------------------------------------------------
    |
    | 自動採番の型を定義する
    |
    |--------------------------------------------------------------------------
    |
    |  主キーで使う「ID」の型を指定する
    |
    */

    /**
     * The "type" of the auto-incrementing ID.
     * 主キーの型を決めます
     * @var string
     */
    protected $keyType = 'integer';

    /*
    |--------------------------------------------------------------------------
    |
    | 利用するテーブルを明示的に表示
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    protected $table = 'users';


    /*
    |--------------------------------------------------------------------------
    |
    | 型の指定ができる
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    protected $dates = [
        'deleted_at'
    ];

    protected $attributes = [
        'menuroles' => 'user',
    ];



    /*
    |--------------------------------------------------------------------------
    |
    | 定数の設定
    |
    |--------------------------------------------------------------------------
    |
    |  大文字で記入するルール
    |
    */
    /**
     * 定数を決めます
     *
     * @return void
     */
    const ADMIN_ROLE_ADMIN = 1; // 管理者
    const ADMIN_ROLE_REPRESENTATIVE = 2; // バンド代表者


    /**
     * 定数を決めます
     * ステータス
     * @return void
     */
    const USE_STATUS_ENABLE = 1; // 有効
    const USE_STATUS_DISABLE = 2; // 無効

    /*
    |--------------------------------------------------------------------------
    | クエリビルダで変更可能なカラムを指定します。
    | $fillable = [];がホワイトリスト方式
    | $guarded = [];がブラックリスト方式
    |--------------------------------------------------------------------------
    |  どちらかを使用します。
    |  変更するのが多い場合はguard、
    |  変更するカラムが少ない場合はfillableを使用
    |   protected $fillable = [];  変更して良いカラムを指定
    |   protected $guarded  = [];  変更してはNGなカラムを指定
    */


    /**
     * 変更してよいカラムを決めます。
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];



    /**
     * 隠すパラメータを指定します。
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     * 日付の型変換を行う（キャスト）
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | 検索機能で利用するカラムを指定します。
    |--------------------------------------------------------------------------
    |
    |   検索に必要なものをモデルからピックアップ
    |   $searchableFields は検索処理機能に使用
    |   ビュー画面の検索フォームで指定したnameタグから情報をコピーする。
    */

    /**
     * 検索項目定義
     * @var varchar
     */
    public static $searchableFields = [
        'name', //氏名
        'login_id', //ログインID
    ];



    /**
     * リレーションでソフトデリート（論理削除の場合）でも表示するリレーションの取得の方法
     * function モデル名（$bool = false)） 初期設定はfalseに設定
     * 使う時はtrueに設定する設定をコントローラなどで設定
     * $bool：trueで削除済み受検者も取得
     * @param boolean
     * @return void
     */
    // public function personals($bool = false)
    // {
    //     if ($bool) {
    //         return $this->hasMany(Personal::class, 'company_id', 'id');
    //     } else {
    //         return $this->hasMany(Personal::class, 'company_id', 'id')->where('deleted_at', null);
    //     }
    // }

    /*
    |--------------------------------------------------------------------------
    | 主従関係を明確にリレーションを張る
    |--------------------------------------------------------------------------
    |
    | 主  〇〇管理者 1 or 多 (hasMany hasOne)
    | 従  〇〇〇〇 1 or 多(belongsTo belongsToMany)
    | *(相手先モデル名（アッパーキャメル）::クラス名,相手テーブルの外部キー,ローカルキー)
    |
    |※命名規則
    |  function名は、ローワーキャメルケースで記入。
    |※※hasManyの場合は複数形、hasOneの場合は単数形が理想
    |
    |
    */

    /**
     * ここからリレーションを張ります。
     * 役割・バンド・バンドメンバー
     *
     * @return void
     */

    public function adminRole()
    {
        return $this->belongsTo(AdminRole::class);
    }
    public function userRole()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    public function bandMembersLog()
    {
        return $this->hasMany(BandMembersLog::class);
    }

    public function bandMember()
    {
        return $this->hasOne(BandMembers::class, ',user_id', 'id');
    }


    /*
    |--------------------------------------------------------------------------
    | 管理権限を使って、画面に表示する内容を設定します
    |--------------------------------------------------------------------------
    |
    | 定数は管理モデルに設定しています。
    |
    */

    /**
     *
     * ※管理者であればtrueを返す
     * これで表示非表示の項目を変更できます。
     * @return bool
     */
    public function isAdministrators(): bool
    {
        return $this['AdminRole_id'] === AdminRole::ADMIN_ID;
    }

    public static function enumAdministrators()
    {
        return User::where('AdminRole_id', '=', AdminRole::ADMIN_ID)->get();
    }


    /*
    |--------------------------------------------------------------------------
    | 新規と更新の判定
    | 新規登録の挙動と更新の挙動設定
    |--------------------------------------------------------------------------
    | 新規
    |  テーブルに「created_at」「create_user_id」「updated_at」「update_user_id」の更新
    |
    | 更新
    |  テーブルに「updated_at」「update_user_id」の更新
    |
    */
    /**
     *
     * 新規か更新か判定して必要な項目を追加します。
     *  * creatingメソッドとupdatingメソッドはEloquentフォルダに初期状態で入っているものを利用する
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($user) {
            return $user->onCreatingHandler();
        });

        self::updating(function ($user) {
            return $user->onUpdatingHandler();
        });
    }

    /**
     * 新規の場合のメソッドを追加
     * 2つ同時に設定します
     * 作成者、作成日の追加
     * 更新者、更新日の追加
     * @return boolean
     */
    private function onCreatingHandler()
    {
        if (Auth::check()) {
            $this->create_user_id = Auth::user()->id;
            $this->update_user_id = Auth::user()->id;
        }
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        return true;
    }

    /**
     * 更新の場合のメソッドを追加
     *
     * 更新者、更新日追加
     * @return boolean
     */
    private function onUpdatingHandler()
    {
        if (Auth::check()) {
            $this->update_user_id = Auth::user()->id;
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | パスワードの設定  アクセサとミューテタ
    |--------------------------------------------------------------------------
    |
    | 1  パスワードの取得 （アクセサ）
    | 2  Crypt::encryptメソッドを利用して暗号化 と保存（ミューテタ）
    | 3  暗号化したデータを取得（アクセサ）
    |
    |※パスワードが必要なユーザーモデルのみ搭載
    |

    */
    /**
     * ログインパスワードを返します
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * 平文パスワードを暗号化してpasswordにセットします
     * @param string $plainLoginPassword 平文パスワード
     */
    public function setPlainPasswordAttribute($plainLoginPassword)
    {
        $encryptPw = Crypt::encrypt($plainLoginPassword);
        $this->password = $encryptPw;
    }

    /**
     * 復号されたパスワードを返します
     * @return string
     */
    public function getPlainPasswordAttribute()
    {
        return isset($this->password) ? Crypt::decrypt($this->password) : null;
    }

    /**
     * チーム名を返します
     * @return string
     */
    public function getTeamNameAttribute()
    {
        return isset($this->team_id) ? config('const.team_name')[$this->team_id] : null;
    }

    /*
    |--------------------------------------------------------------------------
    | セレクトボックスの一覧表示作成
    |--------------------------------------------------------------------------
    |
    |  マスタの情報を一覧で表示できるように設定します。
    | コントローラーにuse文を追加して機能させます。
    | $変数 = モデル名::メソッド名();
    |
    | $sectionList = MSection::getSectionList();  //見本
    |
    */

    /**
     * 担当者一覧を配列で返します
     *
     * @return void
     */
    public static function getCreateUserList()
    {
        return self::all()->pluck('name', 'id');
    }

    /**
     * バンドメンバー一覧を取得します。
     *
     * @param [type] $sectionId
     * @return void
     */
    public static function getBandMemberList($bandId)
    {
        return self::where('band_id', $bandId)->pluck('name', 'id');
    }


    /**
     * チーム名を返します
     * @return string
     */
    // public function getTeamNameAttribute()
    // {
    //     return isset($this->team_id) ? config('const.team_name')[$this->team_id] : null;
    // }



    /*
    |--------------------------------------------------------------------------
    | 検索機能の名称設定と範囲の選択(オリジナル機能)
    |--------------------------------------------------------------------------
    |
    |基本の使い方
    |self::select('テーブル名')
    |->with(['モデル名']);
    |
    | 自分のモデルから取得する場合に利用
    | $query = $query->Where($key, $value);
    |
    | 他のモデルから取得する場合に使用
    | $query->whereHas
    |
    | $query = $query->where(DB::raw("{$key}"), 'LIKE', '%' . $value . '%');//自分のモデルから複数取得する場合に利用
    | ※caseも複数必要
    |
    |
    |
    |
    */


    /**
     * 検索一覧画面でのでの検索処理
     * $conditionsはフロント側で取得した情報を検索用に利用するために変数にセットされています。
     * ※基本的にフロント側のrequestデータを取得して使うことが多いよう。
     * ※特に「index」の一覧に使うときにセットされていることが多いです。
     * 検索の情報を取得するのはモデルに書きます。
     * 順番を変更するメソッドはコントローラに記載
     * フロント側で検索する情報だけ「case」でカラムを設定していく。全てを記入する必要はない。
     *
     * @param array $conditions 検索条件
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function searchByConditions($conditions)
    {
        // 変数をセットします。
        // self::select('companies.*')で自身のモデルの情報を取得します
        // withメソッドでリレーション先のモデルを指定します。
        // ※ リレーションが跨る時はドット記法で繋げていきます
        // ※※ RDBが繋がっていることを確認しましょう。
        $query = self::select('companies.*')->with(['user', 'company.user']);

        // リクエストデータ（フロント側で入力したデータ）を回していきます。

        foreach ($conditions as $key => $value) {
            // 第一引数には、検索する値を渡し、第二引数には、検索対象の配列を渡します。
            // 第二引数に渡した配列の中から、第一引数の値が存在するか確認し、検索した結果存在した場合は、boolean型のtrueが返却され、存在しなかった場合は、boolean型のfalseが返却されます。


            // in_arrayメソッド（検索する値（リクエストの名前）を渡し、第二引数には、検索対象の配列（このモデルに存在する$searchableFieldsの変数））を渡します。
            // issetメソッド 変数がセットされているか、NULLではないかを確認するにはisset関数を使用します。
            // なので、リクエストが来ている場合はデータベースから検索処理を行う
            if (!in_array($key, self::$searchableFields) || !isset($value)) {
                continue;
            }

            // リクエストデータの名前の一覧を順番に取得していきます
            switch ($key) {
                    // 自身のモデルで加工する必要がない場合はカラム名のみ
                case 'name':
                case 'sei_kana':
                case 'mei_kana':
                    // $queryはリレーションも含めた全データなので、whereメソッドで絞りをかけていく。
                    // 前方一致・後方一致を「like」と「％」で設定する
                    $query = $query->Where($key, 'LIKE', '%' . $value . '%');
                    break;
                case 'login_id':
                    $query = $query->where(DB::raw("{$key}"), 'LIKE', '%' . $value . '%');
                    break;
                case 'created_at_from': //検索作成日(from)
                    // リレーションで繋げたモデルとカラム名をドット記法でつなげる
                    $key = "companies.created_at";
                    // リクエストデータ（日付）の数より大きいか（未来である日付を取得）
                    $query = $query->where($key, '>=', $value);
                    break;

                case 'created_at_to': //検索作成日(to)
                    // リレーションで繋げたモデルとカラム名をドット記法でつなげる
                    $key = "companies.created_at";
                    // リクエストデータ（日付）の数より小さいか（過去である日付を検索）
                    $query = $query->where($key, '<=', $value);
                    break;

                case 'task_name': //タスク名
                    $key = 'name';
                    $query = $query->whereHas('task', function ($query) use ($key, $value) {
                        $query->where($key, 'LIKE', '%' . $value . '%');
                    });
                    break;

                case 'regist_date': //登録日 期間
                    $startDate = substr($value, 0, 10);
                    $endDate = substr($value, -10) . ' 23:59:59';
                    $query = $query->where('production_costs.' . $key, '>=', date($startDate))
                        ->where('production_costs.' . $key, '<=', date($endDate));
                    break;
                default:
                    // デフォルトは全ての情報を取得する
                    $query = $query->Where($key, $value);
                    break;
            }
        }
        return $query;
    }

    /*
    ****************************************************
    ここから下はこのモデル限定のオリジナル機能を追加していきます
    ****************************************************
    */
    /*
    |--------------------------------------------------------------------------
    | データベースの保存処理
    |--------------------------------------------------------------------------
    | 使い所
    | ユーザーのパスワード処理などはこちらを参考にする
    |
    */
    /**
     * ユーザーの保存処理
     *
     * @param [type] $request
     * @return void
     */
    public static function saveUser($request)
    {
        // データベースから情報を取得する
        $user = User::find($request->user_id);
        // フロント側から入力されたデータで更新するデータを取得する。
        $fillData    = $request->all();

        // データベースでユーザー情報がなかった場合
        if (!$user) {
            //新規作成時のみ実行 インスタンスを作成する
            $user = new User();

            //ログインIDの入力がない場合、メールアドレスをログインIDとする
            if (!$request->login_id) {
                if (isset($fillData['staff1_mail'])) {
                    $fillData['login_id'] = $fillData['staff1_mail'];
                } elseif (isset($fillData['mail_address'])) {
                    $fillData['login_id'] = $fillData['mail_address'];
                } else {
                    $fillData['login_id'] = Str::random(8);
                }
            }
            //パスワードの入力がない場合、ランダムな8文字のパスワードを作成する
            if (!$request->login_password) {
                $fillData['login_password'] = Str::random(8);
            }
        }

        // フロント側でパスワードの入力がない場合、ランダムな8文字のパスワードを作成する
        if (isset($fillData['login_password'])) {
            // ログインパスワードを複合化する
            $fillData['login_password'] =  Password::encrypt($fillData['login_password']);
        }

        // 上の複合化した情報を保存する処理 fill()でまとめて保存する
        $user->fill($fillData)->save();
        return $user;
    }
    /*
    |--------------------------------------------------------------------------
    | 複数フィールドのデータベースへの保存処理
    |--------------------------------------------------------------------------
    | 使い所
    | フィールドが複数ある場合は次のように連想配列を設定します。
    | 登録画面もJavaScriptを設定します
    |

    */
    /**
     * タスク一覧の保存処理
     * @param array $flow, array $request
     */
    // public static function saveTask($project, $request)
    // {
    //     //既に登録済みのデータ
    //     $tasks = $project->tasks;

    //     //配列を作成
    //     $array = [];
    //     foreach ($request as $requestKey => $requestValue) {
    //         //リクエストキーが複数あり、特定の名称を指定する。
    //         if ($requestKey == 'tasks') {
    //             foreach ($requestValue as $key => $value) {
    //                 //プロセスのIDがセットされていない場合は保存の配列に入れない
    //                 if (isset($value['process_id'])) {
    //                     //View画面のサフィックスネームの「-tasks」からそれぞれのinput配列を抽出
    //                     $array[] = $value;
    //                 }
    //             }
    //         }
    //     }

    //     //詳細の保存
    //     if (isset($array)) {
    //         //新規作成
    //         if ($tasks->count() < 1) {
    //             $project->tasks()->createMany($array);
    //             //更新（更新する項目が1つ以上あるときの処理）
    //         } else {

    //             // 保存する項目の配列を作成
    //             $saveTargetId = [];

    //             foreach ($array as $task) {

    //                 // 更新するタスクの有無をチェックする
    //                 // $updateTargetTask = $tasks->find($task['id']);

    //                 $updateTargetTask = null !== isset($tasks->find($task['id']));



    //                 //既存データがない場合
    //                 if (!isset($updateTargetTask)) {
    //                     // タスクのインスタンスを作成
    //                     $newTask = new Task();
    //                     // プロジェクトidをタスクのインスタンスに挿入
    //                     $task['project_id'] = $project->id;
    //                     // 保存処理
    //                     $newTask->fill($task)->save();

    //                     //既存データがある場合
    //                 } else {

    //                     //更新対象のタスクのIDを格納
    //                     $saveTargetId[] = $task['id'];
    //                     // 更新した項目の保存
    //                     $updateTargetTask->fill($task)->save();
    //                 }
    //             }
    //             //既に存在するタスクのIDを格納
    //             $registedTasks = $tasks->pluck('id')->toArray();
    //             //差分をチェック
    //             $deleteTargetId = array_values(array_diff($registedTasks, $saveTargetId));
    //             if (isset($deleteTargetId)) {
    //                 //削除実行
    //                 Task::whereIn('id', $deleteTargetId)->delete();
    //             }
    //         }
    //     }
    // }


    /*
    |--------------------------------------------------------------------------
    | アクセサの設定
    |--------------------------------------------------------------------------
    | 使い所
    |
    |
    | get〜Attributeといった形式で名付けられたモデルのメソッドがアクセサになります。
    | 「アクセサ」を利用するには、getとAttributeを取り除いたスネークケースで記述
    |
    |  モデルと紐付けた情報から情報を取得
    |  $this-> は、現在記入しているファイル内に記載しているメソッドを使用しています。
    |  $thisの矢印の最後はカラム名を示しています。
    |
    |  isset(Ⅰ) ? Ⅱ : Ⅲ;
    |  Ⅰが存在しているか確認し、Ⅰがある場合はⅡを出力、Ⅰがない場合は、Ⅲを出力
    |
    */

    /**
     * PJに紐づくプロセスを取得して返します
     *
     * @param [type] $project
     * @return array
     */
    public static function getProcessByProject($project)
    {
        //PJに紐づくプロセスを取得
        $processes = self::where(['project_type' => $project->project_type, 'section_id' => $project->section_id]);
        if (isset($project->team_id)) {
            $processes = $processes->where('team_id', $project->team_id);
        }
        return $processes->orderBy('id', 'asc')->pluck('name', 'id');
    }


    /**
     * エクセル出力に必要な情報を取得
     *
     * @param query $sub_query
     * @param string $projectId
     * @return string
     */
    public static function getExcelInfo($sub_query, $projectId)
    {
        $processes = self::select(DB::raw('m_processes.id AS process_id,m_processes.name ,SUM(tasks.estimated_production_costs) AS sum_estimated_production_costs,production_cost.sum_production_cost
        ,process_end_dates.plan_process_end_date,process_end_dates.performance_process_end_date'))
            ->leftJoin('tasks', function ($join) {
                $join->on('tasks.process_id', '=', 'm_processes.id')
                    ->whereNull('tasks.deleted_at');
            })
            ->leftJoin('process_end_dates', function ($join) use ($projectId) {
                $join->on('m_processes.id', '=', 'process_end_dates.process_id')
                    ->where('process_end_dates.project_id', '=', $projectId);
            })
            ->leftJoin(
                self::raw("({$sub_query->toSql()}) AS production_cost"),
                'production_cost.id',
                '=',
                'm_processes.id'
            )
            ->mergeBindings($sub_query->getQuery())
            ->where('tasks.project_id', $projectId)
            ->groupBy('m_processes.id')
            ->groupBy('production_cost.sum_production_cost')
            ->groupBy('process_end_dates.plan_process_end_date')
            ->groupBy('process_end_dates.performance_process_end_date')
            ->orderBy('process_id', 'asc')
            ->get();

        return $processes;
    }

    /*
    |--------------------------------------------------------------------------
    | 0埋め処理
    |--------------------------------------------------------------------------
    | 使い所
    | 1を01で表示する
    |
    | ヘルパ関数を使った方法なので、use文に以下の一行を追加
    | use Illuminate\Support\Str;
    |
    |
    |   Str::of($this->serial_number)->padLeft(2, '0')
    |   Str::of（数字）->padLeft(桁数, 'どの文字で左を埋めるか')
    |   ※Laravel7とLaravel8ではuse文が違うので、公式サイトを確認して合わせる
    |
    |  sprintf("%'.9d\n", 123);スタイルに変更しました。
    |  ※空白がいらない場合は\n削除
    */

    /**
     * 一覧に0埋めをして表示する。
     *
     * @return string
     */
    // public function getDisplayCodeAttribute()
    // {
    //     return sprintf("%'.04d", $this->code);
    // }




    /**
     * 一覧に0埋めをして返します。
     *
     * @return string
     */
    // public function getDisplayIdAttribute()
    // {
    //     return sprintf("%'.04d", $this->id);
    // }

    /**
     * コードを0埋めをして返します。
     *
     * @return string
     */
    // public function getDisplayCodeAttribute()
    // {
    //     return isset($this->code) ? sprintf("%'.04d", $this->code) : null;
    // }

    /**
     * 届出区分を数字から変換
     *
     * constファイルの数字から変換を行う
     * コンフィグのステータスの配列から、該当モデルのテーブルの番号で変換
     *
     * @return void
     */
    // public function getNotificationTypeNameAttribute()
    // {
    //     return config('const.notification_type_name')[$this->notification_type];
    // }

    /*
    |--------------------------------------------------------------------------
    | 計算処理
    |--------------------------------------------------------------------------
    | 使い所
    | number_formatメソッドで数字の型を合わせる
    |
    | sum()メソッドで合計数を表す。引数（カラム名、小数点）
    | sum('estimated_production_costs'), 2)
    |
    |
    */

    /**
     * 受注金額を返します
     *
     * @return void
     */
    // public function getDispOrderAmountAttribute()
    // {
    //     return isset($this->order_amount) ? number_format($this->order_amount) : 0;
    // }

    /**
     * 仕入金額を返します
     *
     * @return void
     */
    // public function getDispPurchaseAmountAttribute()
    // {
    //     return isset($this->purchase_amount) ? number_format($this->purchase_amount) : 0;
    // }

    /**
     * 合計見積工数を返す
     *
     * @param
     * @return void
     */
    // public function getSumEstimatedProductionCostAttribute()
    // {
    //     return isset($this->tasks)  ? number_format($this->tasks->sum('estimated_production_costs'), 2) : null;
    // }



    /**
     * 進捗率を計算して返します
     * 進捗率 = (完了タスクの見積工数 / 全タスクの見積工数) * 100
     *
     * @param [type] $projectId
     * @return void
     */
    // public static function getProgressRate($projectId)
    // {
    //     $project = self::find($projectId);
    //     $totalTaskEstimatedCost = $project->tasks->sum('estimated_production_costs');
    //     if (
    //         $totalTaskEstimatedCost == 0
    //     ) {
    //         //合計見積工数が0の場合はタスクの個数単位での進捗比率を算出
    //         $progressRate = ($project->tasks->where('status', config('const.status.stop'))->count() / $project->tasks->count()) * 100;
    //     } else {
    //         //見積工数が設定されている場合は見積工数に対する完了比率を算出
    //         $totalTaskEstimatedCostComplete = $project->tasks->where('status', config('const.status.stop'))->sum('estimated_production_costs');
    //         $progressRate = ($totalTaskEstimatedCostComplete / $totalTaskEstimatedCost) * 100;
    //     }
    //     return $progressRate != 0 ? number_format($progressRate, 2) : 0;
    // }




    /*
    |--------------------------------------------------------------------------
    | configにある情報の数字を文字に変換していきます
    |--------------------------------------------------------------------------
    | 使い所
    |
    |
    */

    /**
     * ステータスを数字から変換
     *
     * constファイルの数字から変換を行う
     * コンフィグのステータスの配列から、該当モデルのテーブルの番号で変換
     *
     * @return void
     */
    // public function getProjectStatusNameAttribute()
    // {
    //     return config('const.project_status_name')[$this->status];
    // }

    /**
     * プロジェクトに関わるチーム名を数字から変換
     *
     * チームIDからチーム名を表示
     *
     */
    // public function getProjectTeamNameAttribute()
    // {
    //     return isset($this->mTeam)  ? $this->mTeam->name : null;
    // }

    /**
     * 進捗率を返します
     *
     * @return void
     */
    // public function getDispProgressRateAttribute()
    // {
    //     if ($this->project_type == config('const.project_type.estimated')) {
    //         return isset($this->progress_rate) ? number_format($this->progress_rate, 2) : number_format(0, 2);
    //     } else {
    //         return '-';
    //     }
    // }

    /**
     * 実績工数に登録されたセクションに関連するプロジェクトリストを返します
     *
     * @param [type] $productionCostSectionId
     * @return void
     */
    // public static function getProjectListByProductionCostId($productionCostSectionId)
    // {
    //     $projectList = self::where('section_id', $productionCostSectionId)
    //         ->orWhere('section_id', null)
    //         ->get()
    //         ->sortByDesc('code')
    //         ->pluck('name', 'id');
    //     //見積外工数を連結
    //     return $projectList;
    // }
    /*
    |--------------------------------------------------------------------------
    | 都道府県を数字から変換
    |--------------------------------------------------------------------------
    |
    | constファイルの数字から変換を行う
    | コンフィグの県の配列から、該当モデルのテーブルの番号で変換
    |
    |
    */
    /**
     * 住所都道府県名を返します
     * DispはDisplayの略で表示用という意味
     * ※最初にconfigファイルに都道府県を1〜47まで設定しておきます。
     * 配列の中の番号をモデルのデータベースに持たせておく
     * このモデルの中に都道府県の番号を持たせておく。
     * 都道府県番号があれば、都道府県名で表示するようにセットする。
     * @return string
     */
    // public function getDispStaffPrefAttribute()
    // {
    //     return isset($this->pref_code) ? config('const.prefecture')[$this->pref_code] : null;
    // }



    /*
    |--------------------------------------------------------------------------
    | セレクトボックスの一覧表示作成
    |--------------------------------------------------------------------------
    |
    | マスタの情報を一覧で表示できるように設定します。
    | コントローラーにuse文を追加して機能させます。
    | $変数 = モデル名::メソッド名();
    |
    | $sectionList = MSection::getSectionList();  //見本
    |
    */

    /**
     * 顧客名一覧を配列で返します
     *
     * @return void
     */
    // public static function getCustomerList()
    // {
    //     return self::all()->sortByDesc('code')->pluck('name', 'id');
    // }



    /**
     * 担当者一覧を配列で返します
     *「名前とIDだけ取得します」
     * @return void
     */
    // public static function getCreateUserList()
    // {
    //     return self::all()->pluck('name', 'id');
    // }


    /**
     * プロジェクトタイプのリストを返します
     *
     * @param [type] $id
     * @return void
     */
    // public static function getProjectTypeList($id = null)
    // {
    //     $projectTypeSelectList = Config('const.project_type_name');
    //     if (!isset($id)) {
    //         unset($projectTypeSelectList[Config('const.project_type.non_estimated')]);
    //     }
    //     return $projectTypeSelectList;
    // }



    /**
     * ログインユーザーに関係する顧客案件管理番号を0埋めにして一覧として返します。
     *
     * @return string
     */
    // public static function getDisplayManagementNumberList($sectionId)
    // {

    //     //プロジェクトマスタの詳細画面よりsectionIdを取得して、データベースの制約をかけるために準備
    //     $numberingLedgersList =  $sectionId;

    //     $set = self::select('numbering_ledgers.id', DB::raw("CONCAT(lpad(numbering_ledgers.serial_number, 2, '0'),'-',lpad(numbering_ledgers.month, 2, '0'),'-',lpad(m_customers.code, 4, '0'),'-',lpad(numbering_ledgers.management_number, 4, '0'),' | ',numbering_ledgers.project_title) as number")) //顧客管理番号にSQLで変更、別名（number）をつける
    //     ->leftJoin('m_customers', 'numbering_ledgers.customer_id', '=', 'm_customers.id') //テーブル同士の結合
    //     ->where('numbering_ledgers.section_id', $numberingLedgersList) //sectionIdで制約
    //     ->pluck('number', 'id');

    //     //ログインユーザーのセクションに該当するプロジェクトを配列へ格納
    //     $returnArray = [];
    //     foreach ($set as $Key => $Value) {
    //         if (!empty($Value))
    //         $returnArray[$Key] =  $Value;
    //     }
    //     return $returnArray;
    // }

    /**
     * Sectionメンバー一覧を取得します。
     * Controllerで次のように書いておく
     * $personnel = User::getSectionMemberList($project->section_id);
     * プロジェクトに紐づくセクションIDを取得して、変数にセットする
     * セクションIDがセットされている項目の名前とIDを取得する
     * @param [type] $sectionId
     * @return void
     */
    // public static function getSectionMemberList($sectionId)
    // {
    //     return self::where('section_id', $sectionId)->pluck('name', 'id');
    // }

    /*
    |--------------------------------------------------------------------------
    | 時間をフォーマットして取得
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * 開始日を取得
     *
     * @param string $format
     * @param string $nullValue
     * @return string
     */
    // public function showStartDate($format, $nullValue = '')
    // {
    //     return isset($this->start_date) ? date($format, strtotime($this->start_date)) : $nullValue;
    // }

    /**
     * 開始時間を取得($format = 'H:i')
     *
     * @param string $format
     * @param string $nullValue
     * @return string
     */
    // public function showStartTimeEdit($format = 'H:i', $nullValue = '')
    // {
    //     return isset($this->start_date) ? date($format, strtotime($this->start_date)) : $nullValue;
    // }

    /**
     * 開始時間を取得($format = 'Ah時i分')
     *
     * @param string $format
     * @param string $nullValue
     * @return string
     */
    // public function showStartTimeExcel($format = 'Ah時i分', $nullValue = '')
    // {
    //     $val = date('H:i', strtotime($this->start_date)) != '00:00' ? date($format, strtotime($this->start_date)) : $nullValue;
    //     if ($val != $nullValue) {
    //         $val = str_replace('AM', '午前 ', $val);
    //         $val = str_replace('PM', '午後 ', $val);
    //     }
    //     return $val;
    // }

    /**
     * 終了日を取得
     *
     * @param string $format
     * @param string $nullValue
     * @return string
     */
    // public function showEndDate($format, $nullValue = '')
    // {
    //     return isset($this->end_date) ? date($format, strtotime($this->end_date)) : $nullValue;
    // }

    /**
     * 終了時間を取得($format = 'H:i')
     *
     * @param string $format
     * @param string $nullValue
     * @return string
     */
    // public function showEndTimeEdit($format = 'H:i', $nullValue = '')
    // {
    //     return isset($this->end_date) ? date($format, strtotime($this->end_date)) : $nullValue;
    // }

    /**
     * 終了時間を取得($format = 'Ah時i分')
     *
     * @param string $format
     * @param string $nullValue
     * @return string
     */
    // public function showEndTimeExcel($format = 'Ah時i分', $nullValue = '')
    // {
    //     $val = date('H:i', strtotime($this->end_date)) != '00:00' ? date($format, strtotime($this->end_date)) : $nullValue;
    //     if ($val != $nullValue) {
    //         $val = str_replace('AM', '午前 ', $val);
    //         $val = str_replace('PM', '午後 ', $val);
    //     }
    //     return $val;
    // }



}