<?php

namespace App;

use App\Providers\EventServiceProvider;

// 【1対多】のリレーションを定義するメソッドがあります。

// hasManyメソッド　【1対多】のリレーションを定義する
// →【1】側で定義する（Authorモデル）
// belongsToメソッド　【1対多】の逆向きのリレーションを定義する
// →【多】側で定義する（Bookモデル）


// Public function 接続先の名称を単数か複数で設定()
// {
//   //「user_id」以外の外部キーにしたい
//   // さらにusers.id 以外のidカラムにしたい
//   return $this->hasOne('App\User', '子のモデルの外部キー', '親のモデルの id キー');
// }
  // 「１対多」の「多」側 → メソッド名は複数形
//   public function posts()
//   {
//     return $this->hasMany('App\Post');
//   }

  // 「１対１」→ メソッド名は単数形
//   Public function user()
//   {
//     return $this->belongsTo('App\User');
//   }
// }


public function company()
{
    return $this->hasOne(Company::class, '相手（子）', '自分のid');
    return $this->hasOne(Company::class, 'user_id', 'id');
}






// User_table


    /*
    |--------------------------------------------------------------------------
    | クエリビルダで変更可能なカラムを指定します。
    | $fillable = [];がホワイトリスト方式
    | $guarded = [];がブラックリスト方式
    |--------------------------------------------------------------------------
    |  どちらかを使用します。
    |  変更するのが多い場合はguard、変更するカラムが少ない場合はfillableを使用
    |   protected $fillable = [];
    |   protected $guarded = [];
    */

    /**
     * The "type" of the auto-incrementing ID.
     * 主キーの自動採番と型の設定
     * @var string
     */
    protected $keyType = 'integer';



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

Public function bands()
{
  return $this->belongsTo(Band::class);
}

Public function bands()
{
  return $this->belongsTo(Admin_role::class);
}

public function guest_resevations()
{
return $this->hasOne(Guest_resevation::class, 'user_id', 'id');
}



    /*
    |--------------------------------------------------------------------------
    | 検索機能で利用するカラムを指定します。
    |--------------------------------------------------------------------------
    |
    |   検索に必要なものをモデルからピックアップ
    |   $searchableFields は検索処理機能に使用
    |
    */
 /**
     * 検索項目定義
     * @var array
     */
    public static $searchableFields = [
        'user_id',
        'name',
        'name_kana',
        'code',
        'code2',

    ];


    /**
     * リレーションでソフトデリート（論理削除の場合）でも表示するリレーションの取得の方法
     * function モデル名（$bool = false)） 初期設定はfalseに設定
     * 使う時はtrueに設定する設定をコントローラなどで設定
     * $bool：trueで削除済み受検者も取得
     * @param boolean
     * @return void
     */
    public function personals($bool = false)
    {
        if ($bool) {
            return $this->hasMany(Personal::class, 'company_id', 'id');
        } else {
            return $this->hasMany(Personal::class, 'company_id', 'id')->where('deleted_at', null);
        }
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
    | 新規と更新の判定と新規登録の挙動と更新の挙動設定
    |--------------------------------------------------------------------------
    |
    |  テーブルに「created_at」「create_user_id」がある場合は必要
    |
    */
/**
 * 新規か更新か判定して必要な項目を追加します.
 * creatingメソッドとupdatingメソッドはEloquentフォルダに初期状態で入っているものを利用する
 *
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
     * 新規の場合
     * 作成者、作成日
     * 更新者、更新日追加
     * @return boolean
     */
    private function onCreatingHandler()
    {
        // 認証を通ったユーザーの場合、作成者と更新者のIDを自動入力
        if (Auth::user()) {
            $this->create_user_id = Auth::user()->id;
            $this->update_user_id = Auth::user()->id;
        }
        // 認証を通っていないユーザーでも作った日付と更新日の日付を挿入
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        return true;
    }

    /**
     * 更新の場合
     * 更新者、更新日追加
     * @return boolean
     */
    private function onUpdatingHandler()
    {
        // 更新者のIDをモデルに入っているユーザーのIDとともに記録する
        $this->update_user_id = Auth::user()->id;
        $this->updated_at = date('Y-m-d H:i:s');
        return true;
    }

    /**
     * 住所都道府県名を返します
     * DispはDisplayの略で表示用という意味
     * ※最初にconfigファイルに都道府県を1〜47まで設定しておきます。
     * 配列の中の番号をモデルのデータベースに持たせておく
     * このモデルの中に都道府県の番号を持たせておく。
     * 都道府県番号があれば、都道府県名で表示するようにセットする。
     * @return string
     */
    public function getDispStaffPrefAttribute()
    {
        return isset($this->pref_code) ? config('const.prefecture')[$this->pref_code] : null;
    }




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

    /**
     * メールアドレスを取得します
     * （ログインIdがメールアドレスになります）
     *三項演算子を利用する。情報があれば表示して、情報がない場合は空白を返す
     * @return string
     */
    public function getMailAddressAttribute()
    {
        return isset($this->user) ? $this->user->login_id : null;
    }

    /**
     * 代表者氏名を返します
     * モデルにある $fillableの情報を確認してセットする
     * このモデル内にあるデータ（姓と名）をつけて、新しい情報（名前（姓＋名））を作成する
     * @return string
     */
    public function getRepresentativeNameAttribute()
    {
        return $this->sei . '　' . $this->mei;
    }

    /**
     * 作成日（エントリー日）を返します
     * 作成日があれば、dateメソッド(showCreatedAtFormattedmaメソッドのフォーマット,モデルの文字列の作成時間を変更して取得)を使用し、ない場合は空白を表示する
     * @param string $format
     * @return date
     */
    public function showCreatedAtFormatted($format = 'Y/m/d')
    {
        return isset($this->created_at) ? date($format, strtotime($this->created_at)) : '';
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
     *「名前とIDだけ取得します」
     * @return void
     */
    public static function getCreateUserList()
    {
        return self::all()->pluck('name', 'id');
    }

/**
 * Sectionメンバー一覧を取得します。
 * Controllerで次のように書いておく
 * $personnel = User::getSectionMemberList($project->section_id);
 * プロジェクトに紐づくセクションIDを取得して、変数にセットする
 * セクションIDがセットされている項目の名前とIDを取得する
 * @param [type] $sectionId
 * @return void
 */
    public static function getSectionMemberList($sectionId)
    {
        return self::where('section_id', $sectionId)->pluck('name', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | 検索機能の名称設定と範囲の選択  オリジナル機能
    |--------------------------------------------------------------------------
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

                default:
                // デフォルトは全ての情報を取得する
                    $query = $query->Where($key, $value);
                    break;
            }
        }
        return $query;
    }




// Admin_roles
public function user()
{
return $this->hasOne(User::class, 'admin_role_id', 'id');
}

// Labeles

public function band()
{
return $this->hasMany(Band::class, ',label_id', 'id');
}


// Event

  // 「１対１」→ メソッド名は単数形
  Public function hall()
  {
    return $this->belongsTo(Hall::class);
  }

  public function guest_resevations()
  {
  return $this->hasOne(Guest_resevation::class, 'event_id', 'id');
  }

  public function Proceed()
  {
  return $this->hasOne(Proceed::class, 'event_id', 'id');
  }

  public function entry()
  {
  return $this->hasOne(Entry::class, 'event_id', 'id');
  }

  public function performance_list()
  {
  return $this->hasOne(PerformanceList::class, 'event_id', 'id');
  }

// ticket

public function Proceeds()
{
return $this->hasMany(Proceed::class, ',ticket_rank_id', 'id');
}

// guest_reserve


  Public function user()
  {
    return $this->belongsTo(User::class);
  }


  Public function event()
  {
    return $this->belongsTo(Event::class);
  }



// Proceed

Public function event()
{
  return $this->belongsTo(Event::class);
}

Public function ticket_list()
{
  return $this->belongsTo(TicketList::class);
}


// performance_list

Public function event()
{
  return $this->belongsTo(Event::class);
}

Public function band()
{
  return $this->belongsTo(Band::class);
}

Public function music()
{
  return $this->belongsTo(Music::class);
}

// entrys

Public function event()
{
  return $this->belongsTo(Event::class);
}

Public function band()
{
  return $this->belongsTo(Band::class);
}

// halls
public function events()
{
return $this->hasMany(Events::class, 'hall_id', 'id');
}

// music
Public function album()
{
  return $this->belongsTo(Album::class);
}

public function band()
{
return $this->belongsTo(Band::class);
}

public function performance_list()
{
return $this->hasOne(PerformanceList::class, 'music_id', 'id');
}



// album

Public function band()
{
  return $this->belongsTo(Band::class);
}

public function musics()
{
return $this->hasMany(Music::class, 'album_id', 'id');
}

// bandgoods
  // 「１対１」→ メソッド名は単数形
  Public function band()
  {
    return $this->belongsTo(Band::class);
  }


    Public function goods_type()
    {
      return $this->belongsTo(GoodsType::class);
    }

// goodstype
public function band_goods()
{
return $this->hasMany(Band_goods::class, 'goods_type_id', 'id');
}

// musical
public function band_member()
{
return $this->hasMany(Band_member::class, 'mudical_instrument_id', 'id');
}
// bandmember

  // 「１対１」→ メソッド名は単数形
  Public function band()
  {
    return $this->belongsTo(Band::class);
  }

  // 「１対１」→ メソッド名は単数形
  Public function musical_instrument()
  {
    return $this->belongsTo(BandMember::class);
  }


// bandmemberlog

// band
public function users()
{
return $this->hasMany(User::class, 'band_id', 'id');
}

Public function lebels()
{
  return $this->belongsTo(Label::class);
}

public function albums()
{
return $this->hasMany(Album::class, 'band_id', 'id');
}

public function musics()
{
return $this->hasMany(Music::class, 'band_id', 'id');
}


public function band_members()
{
return $this->hasMany(BandMember::class, 'band_id', 'id');
}

public function band_goods()
{
return $this->hasMany(BandGoods::class, 'band_id', 'id');
}

public function performance_list()
{
return $this->hasOne(PerfomancList::class, 'band_id', 'id');
}

public function entry()
{
return $this->hasOne(Entry::class, 'band_id', 'id');
}


// コントローラーの基本

// モデルのネームスペースを設定する
namespace App\Http\Controllers;

// 使うモデルを設定
use App\Company;

// CSVを使うときはインポート・エクスポートするときは準備する
use App\Libs\RepresentativeDownload;
// フロント側から情報を入力するときは使用する
use Illuminate\Http\Request;


// 参照情報
// Requestクラスのメソッド
// https://qiita.com/kuzira_vimmer/items/54d9bfd88f66208c1709


class RepresentativeController extends Controller
{
    /**
     * csvダウンロードのheader
     * 項目をここで入力する
     * @var array
     */
    public static $headers = [
        'メールアドレス', 'パスワード', '代表者都道府県名', '学校名',
        'チーム名', '代表者漢字氏名（姓）', '代表者漢字氏名（名）', '代表者かな氏名（姓）',
        '代表者かな氏名（名）', '代表者自宅郵便番号', '代表者自宅住所（都道府県名）', '代表者自宅住所（市区郡）',
        '代表者自宅住所（町村番地）', '代表者自宅住所（ビル・号室）', '勤務先電話番号', '携帯電話',
        'アンケート_オンライン筆記競技', 'アンケート_特別体験プログラム', 'エントリー年月日'
    ];

    /**
     * 認証を設定
     * Web.phpで設定しているときは全てのコントローラーに設定する。
     */
    public function __construct()
    {
        $this->middleware('auth:administrators');
    }

    /**
     * 代表者エントリー 一覧画面　
     * @return view
     */
    public function index(Request $request)
    {
        // メンバー一覧の取得準備
        // 検索データ
        $conditions = $request->all();  //フロント側で取得した情報を検索用に利用するために変数にセット
        $originalRequest = $request;  //全ての情報をプレーンの状態でフロントに渡すために変数にセット

        // どのカラムをベースにして順番を変更するか
        // ※ Requestクラスのメソッドで調べてね
        // input() 入力値を個別に取得する
        $orderby      = $request->input('orderby') ?: 'id';
        // 昇順（1→10）か降順（10→1）かを設定する。ascかdesc
        $sort         = $request->input('sort') ?: 'desc';

        // 検索、ページネーションの変数をセット
        $personals        = Personal::searchByConditions($conditions); //モデルの検索メソッドに挿入
        $listCount        = $personals->count(); //変数に入っている件数を取得するメソッドを使用
        $paginateNum = config('const.paginate.company');//ページに表示する内容を設定ファイルの数字に合わせる

        //ソート
        // コントローラー内に設定されているsetOrderByメソッド（情報、カラム、順番）を使って、全てをgetメソッドで取得する
        $personalList = $this->setOrderBy($personals, $orderby, $sort)->get();

// ページの番号を表示するための変数をセットする
// ページネートメソッド（表示数）を使って、まとまりを作る
        $list = $personals->paginate($paginateNum); //ページネーション用

        //リクエストデータセット コンパクトメソッドを使って、変数に渡して、リターンをすっきり表示する
        // ※コンパクトの時は変数の時の＄は使用せず表示する
        $requestData = compact( 'conditions', 'personalList', 'listCount', 'list', 'originalRequest', 'paginateNum' );
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

    /**
     * ソートを設定します。
     * setOrderBy(データベースに一致した情報, カラム名, 昇順か降順)
     * このメソッドはこのコントローラー内で使用するためprivateの設定
     * $queryはrequestからモデルのデータを取得した名称を総称して命名
     * $orderByはどのカラムをベースにして順番を変更するか。画面によって変更できるようにしている。
     * ※特に(id)にセットされることが多い
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



// seederの例

// 参照先
// flushEventListenersについて
// https://qiita.com/pinekta/items/86ccec485c8e1d199487

// オブザーバについて
// https://ryuzan03.hatenablog.com/entry/2019/09/28/190000
// オブザーバとは？
// モデルを監視し、実行されるイベントに対応するメソッドを呼んでくれます。
// イベントフックが多い場合に、イベントフックをまとめてオブザーバ(observer)に定義することができる。
// ※コントローラの肥大化を防ぐことができます。

// シリアライズして任意の型を暗号化
// 参照先  https://qiita.com/saya1001kirinn/items/2495bf4889c3d97c3e10
// use Illuminate\Support\Facades\Crypt;

// オブザーバーを無効にする
Model::flushEventListeners();
// オブザーバーを無効にしたあとに有効にするには
// Model::observe(ModelObserver::class)


// 暗号化は、 .env で設定された APP_KEY=base64:XXXXXX をシークレットとして使用します。
/*
Base64エンコードされた暗号化結果が出力される
*/
$encrypted= Crypt::encrypt(['foo' => 'bar']);
// 'login_password' => Crypt::encrypt('test'),


/*
/ 複合化された（暗号化した情報を元に戻した）結果が出力される
*/
$decrypted = Crypt::decrypt($encrypted);



/*
// all() toArray() input() 入力値を連想配列として取得する
// 3つはどれも入力値を連想配列として取得することができます。
// またその際、HTTP動詞は考慮しません。

// $request->all();
// ⬇︎ toArrayは内部でall()を呼んでそのまま返してる
// $request->toArray();
// $request->input();
*/


// ■サービスコンテナとは

// 「クラスをインスタンス化してくれるマシーン」
// $classA = new ClassA();
// ⬇︎ サービスコンテナを使うと・・・
// $classA = app()->make(ClassA::class);
// このapp()関数でサービスコンテナを取得し、そのサービスコンテナのmake()メソッドでインスタンス化しています。



/*
参照先 https://qiita.com/yukachin0414/items/b561afcad793de9ca2f4
■Collectionのtakeメソッド
takeメソッドで指定した件数だけデータ取得する
Collectionの中から特定の件数だけを取得することができます。
$collection = collect(['赤', '青', '黄', '緑', '白',]);
$chunk = $collection->take(3);

後ろから指定した数の値を取得することができます。
$chunk = $collection->take(-2);
*/

// 参照先 https://www.ritolab.com/entry/93
// latest() メソッドを使うと、降順で取得します。

/*
参照先 https://www.ritolab.com/entry/93
TRUNCATE
delete()メソッドは単純にレコードを削除するだけなので、もし全レコードを削除しても、自動増分IDはリセットされません。
もしテーブル内を全て削除し、オートインクリメントもリセットしたい場合は、truncate()メソッドを使います。

*/
/*
Laravel クエリビルダ記法まとめ
参照先 https://www.ritolab.com/entry/93
※ 主要な内容だけ説明としてメモしておきます
クエリビルダを使用する為の基本書式
基本的な書式を以下に示します。

use Illuminate\Support\Facades\DB; // DB ファサードを use する


public function getDate()
{
    // テーブルを指定
    $users = DB::table('users');
}

DB ファサードを use します。
DB ファサードの table メソッドを使い、引数に取得したいテーブル名を渡します。
今回は例として users テーブルから情報を取得する為のクエリビルダを記述していきますが、users テーブルのクエリビルダインスタンスを格納した変数 $users に対してクエリを記述し、結果を取得していきます。

irst()
結果データの最初の1件のみを取得します。結果が何件であっても、1件のみを取得します。

$data = $users->first();
結果データはStdClassオブジェクトで取得されます。取り出すにはオブジェクトのプロパティにアクセスします。

echo $data->name;
value()
value()メソッドで指定した1つのカラムのみを取得します。

$data = $users->value('email');
ただしこのメソッドは条件に限らず1件のみを取得するので、where句などと併用して狙った1件を取得する際などに使います。

echo $data; // test01@test.com
pluck()
pluck()メソッドで指定カラム1つだけをコレクションで取得できます。

$data = $users->pluck('email');

toSql()
toSql() メソッドで、発行しようとしているSQL文を確認できます。

$data = $users->toSql();


分割処理
レコードを取得し処理を行おうとした際に、対象レコードが数万件レベルであった場合は一度に取得する事は難しいかもしれません。

そんな時は chunk() メソッドを使えば、取得条件に対して分割処理を行う事ができます。

$users->orderBy('id')->chunk(100, function ($users) {
    foreach ($users as $user) {
        //
    }
});
上記の例では、usersテーブルに対し100件ずつレコードを取得し、クロージャの中でそれらを処理する事ができます。

例えば、users の全体件数は 10,000 件だけど、100 件ずつ取り出して処理を行ったり。リソースを節約しながら処理を行っていくことができます。

１点気を付ける事は、chunk() メソッドを使用する際は必ず orderBy() でデータの並び順を指定する必要があります。


count()
count()メソッドで、レコードの件数を取得できます。



SQL文を直接記述する
クエリビルダの中で、直接SQL文を記述する事もできます。

DB::rawメソッドを使ってSQL文を記述していきます。

$data = $users->select(DB::raw('COUNT(*) AS user_count'))->get();
rawメソッド
DB::rawは最も基本的なSQL文挿入メソッドです。更に各セクション毎に用意されているrawメソッドを使う事によって、記述を短縮できます。

selectRaw
selectRawメソッドは select(DB::raw(...)) 式を置き換えます。

$data = $users->selectRaw('COUNT(*) AS user_count')->get();


日時比較
日時の比較を行う際にも、Laravelのクエリビルダには専用のメソッドが用意されています。

whereDate / orWhereDate
日付比較にはwhereDate()メソッドを使います。

$data = $users->whereDate('updated_at', '2018-03-21')->get();

orderBy
orderBy()メソッドを使うと、指定したカラムで結果データをソートします。

$data = $users->orderBy('id', 'desc')->get();
第一引数にカラム名、第二引数に昇順(asc)、もしくは降順(desc)のどちらかを指定します。


グループ化（GROUP BY）と絞り込み（HAVING）
集計を行う際に GROUP BY や HAVING を使いたい場面もよくあると思いますが、その場合はgroupBy()メソッド、そしてhaving()メソッドを使います。

$data = $users
    ->selectRaw('role, sum(role) AS role_cnt')
    ->groupBy('role')
    ->having('role', '>', 5)
    ->get();

// => SELECT role, sum(role) AS role_cnt FROM `users` GROUP BY `role` HAVING `role` > 5
もちろん、groupBy()メソッドに複数のカラムを指定する事も可能です。

$data = $users
    ->selectRaw('`role`, sum(role) AS role_cnt')
    ->groupBy('role', 'gender')
    ->having('role', '>', 5)
    ->get();

// => SELECT `role`, sum(role) AS role_cnt FROM `users` GROUP BY `role`, `gender` HAVING `role` > 5

LIMIT
取得レコード数を制限するには、limit()メソッドを使います。

$data = $users->limit(3)->get();
OFFSET
結果レコードの取得開始位置（何件目から取得するか）を指定するには、offset()メソッドを使います。

$data = $users
    ->offset(2)
    ->limit(3)
    ->get();
OFFSETは得てしてLIMIT節と組み合わせて使う事が多いですが、上記の例の場合では「2件目から3件取得する」という意味になります。

判定式によって検索条件を変更する
「リクエストであのパラメータが来た場合は、検索条件にこれを追加する」
のような流れは良くあるパターンです。

Laravelのクエリビルダには、そのパターンでのSQL文組み立てにも対応しています。

when()メソッドを使い、第一引数に判定式の結果（trueもしくはfale）、第二引数にクロージャを指定し、trueであった場合の処理をクロージャに定義します。

$user_id = 8;

$data = $users
    ->when($user_id, function ($query) use ($user_id) {
        return $query->where('id', $user_id);
    })
    ->get();
上記例の場合、
「$user_idがtrue（すなわち、値が入っている）の場合は、idカラムをその値で指定する」
という条件が追加されます。

INSERT
テーブルへデータを登録（レコードを挿入）するには、insert()メソッドを使います。

$data = $users
    ->insert(
        [
            'name' => 'test11',
            'email' => 'test11@test.com',
            'password' => '123456',
            'role' => 5
        ]
    );
1つの配列に1件のデータを格納（プロパティ名をカラム名にする）しています。

*/