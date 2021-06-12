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
    | 2 Fillable
    | 3 searchableFields
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
    |
    */


namespace App\Models\band;


/*
    |--------------------------------------------------------------------------
    |
    | 必要な機能をインポートする
    |
    |--------------------------------------------------------------------------
    |【大分類】
    | 初期設定
    | 認証系
    | 暗号化系
    | DB記入系
    | その他のLib
    |
    */
// 認証系を使うときはインポートする
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

//Laravel8の初期設定のuse文
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//リレーションディレクトリの指定（RDB）
use App\Models\band\Band;

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
 * @property string $name
 * @property int $ticket
 * @property string $hall_id
 * @property string $event_date
 * @property integer $create_user_id
 * @property string $created_at
 * @property integer $update_user_id
 * @property string $updated_at
 * @property integer $delete_user_id
 * @property string $deleted_at
 */
class Event extends Model
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


    use HasFactory;

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
     *
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

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function guest_reservations()
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
    |   protected $guarded = []; 変更してはNGなカラムを指定
    */

    /**
     * 変更して良いカラムを設定する
     * @var array
     */
    protected $fillable = [
        'name',
        'ticket',
        'hall_id',
        'event_date',
        'create_user_id',
        'created_at',
        'update_user_id',
        'updated_at',
        'delete_user_id',
        'deleted_at'
    ];


    /*
    |--------------------------------------------------------------------------
    | 検索機能で利用するカラムを指定します。
    |--------------------------------------------------------------------------
    |
    |   検索に必要なものをモデルからピックアップ
    |   $searchableFields は検索処理機能に使用
    |  ビュー画面の検索フォームで指定したnameタグから情報をコピーする。
    */
    /**
     * 検索項目定義
     * @var array
     */
    public static $searchableFields = [
        'name',
    ];



    /*
    |--------------------------------------------------------------------------
    | パスワードの設定  アクセサとミューテタ
    |--------------------------------------------------------------------------
    |
    | 1  パスワードの取得 （アクセサ）
    | 2  Crypt::encryptメソッドを利用して暗号化 と保存（ミューテタ）
    | 3  暗号化したデータを取得（アクセサ）
    |
    | ※パスワードが必要なユーザーモデルのみ搭載
    |
    */





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

        self::creating(function ($album) {
            return $album->onCreatingHandler();
        });

        self::updating(function ($album) {
            return $album->onUpdatingHandler();
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
    | 検索機能の名称設定と範囲の選択(オリジナル機能)
    |--------------------------------------------------------------------------
    |
    | 基本の使い方
    | self::select('テーブル名')
    | ->with(['モデル名']);
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
    | $conditionsはフロント側で取得した情報を検索用に利用するために変数にセットされています。
    | ※基本的にフロント側のrequestデータを取得して使うことが多いよう。
    | ※特に「index」の一覧に使うときにセットされていることが多いです。
    | 検索の情報を取得するのはモデルに書きます。
    | 順番を変更するメソッドはコントローラに記載
    | フロント側で検索する情報だけ「case」でカラムを設定していく。全てを記入する必要はない。
    |
    |
    |
    */


    /**
     *
     * @param array $conditions 検索条件
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function searchByConditions($conditions)
    {


        /**********************
         * 変数をセットします。
         * self::select('companies.*')で自身のモデルの情報を取得します
         * withメソッドでリレーション先のモデルを指定します。
         *  ※ リレーションが跨る時はドット記法で繋げていきます
         *  ※※ RDBが繋がっていることを確認しましょう。
         ***********************/

        $query = self::select('companies.*')->with(['user', 'company.user']);


        /**********************
         * リクエストデータ（フロント側で入力したデータ）を回していきます。
         ***********************/


        foreach ($conditions as $key => $value) {

            /**********************
             * 第一引数には、検索する値を渡し、第二引数には、検索対象の配列を渡します。
             * 第二引数に渡した配列の中から、第一引数の値が存在するか確認し、検索した結果存在した場合は、boolean型のtrueが返却され、存在しなかった場合は、boolean型のfalseが返却されます。
             ***********************/

            /**********************
             *  in_arrayメソッド（検索する値（リクエストの名前）を渡し、第二引数には、検索対象の配列（このモデルに存在する$searchableFieldsの変数））を渡します。
             * issetメソッド 変数がセットされているか、NULLではないかを確認するにはisset関数を使用します。
             * なので、リクエストが来ている場合はデータベースから検索処理を行う
             ***********************/
            if (!in_array($key, self::$searchableFields) || !isset($value)) {
                continue;
            }

            /**********************
             * リクエストデータの名前の一覧を順番に取得していきます
             ***********************/

            switch ($key) {

                    /**********************
                 *自身のモデルで加工する必要がない場合はカラム名のみ
                 ***********************/
                case 'name':
                case 'sei_kana':
                case 'mei_kana':

                    /**********************
                     * $queryはリレーションも含めた全データなので、whereメソッドで絞りをかけていく。
                     *  前方一致・後方一致を「like」と「％」で設定する
                     ***********************/

                    $query = $query->Where($key, 'LIKE', '%' . $value . '%');
                    break;

                case 'created_at_from': //検索作成日(from)

                    /**********************
                     * リレーションで繋げたモデルとカラム名をドット記法でつなげる
                     ***********************/

                    $key = "companies.created_at";

                    /**********************
                     * リクエストデータ（日付）の数より大きいか（未来である日付を取得）
                     ***********************/
                    $query = $query->where($key, '>=', $value);
                    break;

                case 'created_at_to': //検索作成日(to)

                    /**********************
                     *  リレーションで繋げたモデルとカラム名をドット記法でつなげる
                     ***********************/

                    $key = "companies.created_at";

                    /**********************
                     * リクエストデータ（日付）の数より小さいか（過去である日付を検索）
                     ***********************/
                    $query = $query->where($key, '<=', $value);
                    break;

                    /**********************
                     * デフォルトは全ての情報を取得する
                     ***********************/

                default:
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


}
