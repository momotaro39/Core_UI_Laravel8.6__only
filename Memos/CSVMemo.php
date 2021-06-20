<?php




/*
|--------------------------------------------------------------------------
| 【Laravel-admin】good/csvを利用したCSVファイルのインポート  参照先  https://sazaijiten.work/csv_import/
|--------------------------------------------------------------------------
|
|
|
|
*/



/********************************************
 * メソッド定義
 * ******************************************/

Goodby/CSVのインストール
LaravelでCSVからテーブルにデータをインポートすることができるライブラリはいくつかありますが、今回はメモリ効率が非常にいいという「goodby/csv」を使用したいと思います。

参考：goodby/csv

composerを使用してインストールを行います。

    "require": {
        "goodby/csv": "*"
    }
1
2
3
    "require": {
        "goodby/csv": "*"
    }
上記の記述を行ったら「composer install」あるいは「composer update」を行います。

updateを行うと他のライブラリなど、新しいバージョンが存在する場合最新版を取得してしまうので、

その場合は「composer.lock」を一度削除してインストールを行います。

composer install
1
composer install
実装内容
一覧画面にインポートボタンを追加し、ボタン押下でCSVのデータをテーブルに挿入できるようにしたいと思います。

※一覧に表示されているデータはファクトリーで作成したダミーデータです。





インポート用のテーブル作成
インポート用にitemsテーブルを作成します。

マイグレーションファイル
Toolクラスを作成
インポート機能を提供するボタンを一覧画面に追加するためにToolクラスを作成します。

一覧画面のカスタムツールの作成に関しては以下のドキュメントを参考にしました。

ajaxSetupでheader情報にcsrfトークンを追加しないとデータをpost出来ないので忘れず追加しましょう。

参考：Custom tools

app/Admin/Extemsion/Tools/CsvImport.phpを以下の内容で作成します。

CsvImport.php

<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class CsvImport extends AbstractTool
{


    /**
     * Set up script for import button.
     */
    protected function script()
    {
        return <<< SCRIPT

// ボタン押下でCSVインポート
$('.csv-import').click(function() {
    var select = document.getElementById('files');
    document.getElementById("files").click();
    select.addEventListener('change',function() {
        var fd = new FormData();
        fd.append( "file", $("input[name='hoge']").prop("files")[0] );
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type : "POST",
            url : "/admin/import",
            data : fd,
            processData : false,
            contentType : false,
        });
    });
});

SCRIPT;
    }

    /**
     * Render Import button.
     *
     * @return string
     */
    public function render()
    {

            Admin::script($this->script());

            return view('csv_upload');
    }
}

<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class CsvImport extends AbstractTool
{


    /**
     * Set up script for import button.
     */
    protected function script()
    {
        return <<< SCRIPT

// ボタン押下でCSVインポート
$('.csv-import').click(function() {
    var select = document.getElementById('files');
    document.getElementById("files").click();
    select.addEventListener('change',function() {
        var fd = new FormData();
        fd.append( "file", $("input[name='hoge']").prop("files")[0] );
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type : "POST",
            url : "/admin/import",
            data : fd,
            processData : false,
            contentType : false,
        });
    });
});

SCRIPT;
    }

    /**
     * Render Import button.
     *
     * @return string
     */
    public function render()
    {

            Admin::script($this->script());

            return view('csv_upload');
    }
}
resource/views配下にビューを作成し、ボタン用のHTMLを記述します。

csv_upload.blade.php

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="#" class="btn btn-sm btn-twitter csv-import"><i class="fa fa-upload"></i><span class="hidden-xs"> CSVインポート</span></a>
    <input type="file" id="files" name="hoge" style="display: none">
</div>


<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="#" class="btn btn-sm btn-twitter csv-import"><i class="fa fa-upload"></i><span class="hidden-xs"> CSVインポート</span></a>
    <input type="file" id="files" name="hoge" style="display: none">
</div>


Controller
コントローラのgrid内で、作成したToolクラスを読み込みます。

csvImport関数内でgoodby/csvを使用しCSVファイルをパースし、配列に代入後テーブルにインサートしています。

「$interpreter->unstrict();」を記述するすることで、CSVファイルの列の一貫性がない場合も無視して実行します。

CSVファイルの列の一貫性が必要な場合はコメントアウト等すれば、列の一貫性がない場合エラーとなります。

また、LexerConfigでCSVファイルの区切り文字や囲い文字、文字コードなどを指定することができます。

参考：Documentation

TestUserCpntroller.php

<?php

namespace App\Admin\Controllers;

use App\Admin\Models\TestUser;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Admin\Extensions\Tools\CsvImport;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;
use Illuminate\Http\Request;
use App\Admin\Models\Item;

class TestUserController extends Controller
{

～中略～

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TestUser);

        $grid->id('Id');
        $grid->name('Name');
        $grid->age('Age');
        $grid->email('Email');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');
        $grid->tools(function ($tools) {
            $tools->append(new CsvImport());
        });

        return $grid;
    }

～中略～


    public function csvImport(Request $request)
    {
        $file = $request->file('file');
        $config = new LexerConfig();
        $lexer = new Lexer($config);

        $interpreter = new Interpreter();
        $rows = array();
        // 行の一貫性は無視
        $interpreter->unstrict();
        $interpreter->addObserver(function (array $row) use (&$rows) {
            $rows[] = $row;
        });


        // CSVデータをパース
        $lexer->parse($file, $interpreter);
        $data = array();

        // CSVのデータを配列化
        foreach ($rows as $key => $value) {

            $arr = array();

            foreach ($value as $k => $v) {

                switch ($k) {

                    case 0:
                        $arr['item_name'] = $v;
                        break;

                    case 1:
                        $arr['quantity'] = $v;
                        break;

                    case 2:
                        $arr['price'] = $v;
                        break;

                    default:
                        break;
                }
            }
            $data[] = $arr;
        }
        Item::insert($data);
        return response()->json(
            [
                'data' => '成功'
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}


<?php

namespace App\Admin\Controllers;

use App\Admin\Models\TestUser;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Admin\Extensions\Tools\CsvImport;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;
use Illuminate\Http\Request;
use App\Admin\Models\Item;

class TestUserController extends Controller
{

～中略～

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TestUser);

        $grid->id('Id');
        $grid->name('Name');
        $grid->age('Age');
        $grid->email('Email');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');
        $grid->tools(function ($tools) {
            $tools->append(new CsvImport());
        });

        return $grid;
    }

～中略～


    public function csvImport(Request $request)
    {
        $file = $request->file('file');
        $config = new LexerConfig();
        $lexer = new Lexer($config);

        $interpreter = new Interpreter();
        $rows = array();
        // 行の一貫性は無視
        $interpreter->unstrict();
        $interpreter->addObserver(function (array $row) use (&$rows) {
            $rows[] = $row;
        });


        // CSVデータをパース
        $lexer->parse($file, $interpreter);
        $data = array();

        // CSVのデータを配列化
        foreach ($rows as $key => $value) {

            $arr = array();

            foreach ($value as $k => $v) {

                switch ($k) {

                    case 0:
                        $arr['item_name'] = $v;
                        break;

                    case 1:
                        $arr['quantity'] = $v;
                        break;

                    case 2:
                        $arr['price'] = $v;
                        break;

                    default:
                        break;
                }
            }
            $data[] = $arr;
        }
        Item::insert($data);
        return response()->json(
            [
                'data' => '成功'
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}
まとめ
今回、一覧画面のボタン追加にToolクラスを使用しました。

このToolクラスですが、コントローラで読み込むだけで、表示画面のHTMLにJavascriptを使用して様々な処理を実行できるので使い道はたくさんありそうです！


/*
|--------------------------------------------------------------------------
| 基本の設定  参照先
|--------------------------------------------------------------------------
|
|
|
|
*/



/********************************************
 * メソッド定義
 * ******************************************/


/*
|--------------------------------------------------------------------------
| CSVエキスポートの作成（イントロ）  参照先  https://laraweb.net/tutorial/5760/
|--------------------------------------------------------------------------
|
|
|
|
*/

DBの設計
「auth_information」テーブル と 「profiles」テーブル を内部結合してCSVダウンロードさせます。



「auth_information」テーブル
認証用の情報が入っている「auth_information」テーブルの構成です。

項目	カラム名
ID	id
メールアドレス（※ログインID）	email
パスワード	password
リメンバートークン	remember_token
作成日	created_at
更新日	updated_at


「profiles」テーブル
ユーザーの情報が入っている「profiles」テーブル

項目	カラム名
ID	id
認証用ID（※auth_informationのidと紐づけ）	authinformation_id
名前	name
住所	address
生年月日	birthdate
電話番号	tel
メッセージ	msg
作成日	created_at
更新日	updated_at


事前知識
response::make
下記のように書くことでCSVをダウンロードすることができます。


return response()->make($csv, 200, $headers);

PHP


第一引数の $csv は出力するデータです。

第三引数のヘッダーはCSVダウンロードする場合に、以下のように記述します。


$headers = array(
    'Content-Type' => 'text/csv',
    'Content-Disposition' => 'attachment; filename="demo.csv"',
);

PHP


fputcsv
PHPで配列からCSVファイルに出力するのに便利な関数として、「fputcsv関数」があります。

以下のように使います。


fputcsv($file_handler, $array);

PHP


$file_handler とは fopen 関数で正常にオープンされた、有効なファイルポインタを指します。

PHPで一時的なファイルポインタを扱う場合、以下のように記述します。


// メモリ上に領域を確保し、2MB を超えたら自動削除される一時ファイルを作る
$file_handler = fopen('php://temp', 'r+b');

PHP


fputcsv関数の第二引数である $array はCSVファイルへ書き込む配列を指定します。

fputcsv関数の返り値は、書き込まれたバイト数をint型で返します。

ポイント
メモリが解放される・一時ファイルが削除されるタイミングは？
・fclose($fp) のようにしてファイルポインタを閉じたとき。
・スクリプトが終了したとき。


今回はイントロまで

以上です。


なお、PHP のファイルシステム関数についてもっと理解を深めたい方は 独習PHP 第3版 をお勧めします。

ポイント
ファイルシステム関数の掲載ページの箇所
5.5.1 テキストファイルへの書き込み
5.5.2 ファイルを開く - fopen/fclose関数
5.5.3 fopen 関数でのエラー処理 - エラー制御演算子
5.5.4 ファイルへの書き込み - fwrite関数
5.5.5 ファイルのロック - flock関数
5.5.6 タブ区切りテキストの読み込み - fgetcsv関数
5.5.7 タブ区切りテキストの読み込み(別解) - fgets/file関数
5.5.8 ファイルシステム関数の設定パラメータ

/********************************************
 * メソッド定義
 * ******************************************/


/*
|--------------------------------------------------------------------------
| CSVインポート機能の作成  参照先  https://laraweb.net/tutorial/5906/
|--------------------------------------------------------------------------
|
|
|
|
*/



/********************************************
 * メソッド定義
 * ******************************************/
テーブル作成
artisanコマンドでスケルトンを作成。


php artisan make:migration create_csv_users_table --create=csv_users

Bash


自動生成されたスケルトンに中身を入れていきます。


public function up()
{
    Schema::create('csv_users', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name')->comment('名前');
        $table->string('email')->comment('メールアドレス');
        $table->string('tel',20)->nullable()->comment('電話番号');
        $table->timestamps();
    });
}

PHP


作成したマイグレーションファイルを実行。


php artisan migrate

Bash



モデル作成
モデルのスケルトンを作成。


php artisan make:model Models/CsvUser

Bash


中身を入れてきます。


class CsvUser extends Model
{
    //ブラックリスト方式
    protected $guarded = ['id'];
}

PHP


Bladeファイル作成
フォーム
Bootstrap4のファイルを選択するコンポーネントを使いました。ファイル名はpractice2.blade.php。


<h1>Laravel で CSV インポート 演習</h1>
    <p>CSVファイルを csv_users テーブルに登録します。</p>
    <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <label class="col-1 text-right" for="form-file-1">File:</label>
            <div class="col-11">
                <div class="custom-file">
                    <input type="file" name="csv" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile" data-browse="参照">ファイル選択...</label>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success btn-block">送信</button>
    </form>

Markup


ファイルを選択すると、コントロール部分にファイル名を表示するためには以下のjQueryも必要です。末尾に記述します。


：
<script>
    // ファイルを選択すると、コントロール部分にファイル名を表示
    $('.custom-file-input').on('change',function(){
        $(this).next('.custom-file-label').html($(this)[0].files[0].name);
    })
</script>
</body>
</html>

Markup


フラッシュメッセージ
CSVインポートが完了したらフラッシュメッセージを表示させます。


@if(Session::has('flashmessage'))
    <script>
        $(window).on('load',function(){
            $('#myModal').modal('show');
        });
    </script>

    <!-- モーダルウィンドウの中身 -->
    <div class="modal fade" id="myModal" tabindex="-1"
         role="dialog" aria-labelledby="label1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                     {{ session('flashmessage') }}
                </div>
                <div class="modal-footer text-center">
                </div>
            </div>
        </div>
    </div>
@endif

Markup


PHPライブラリ「Goodby CSV」ライブラリをインストール

composer require goodby/csv

Bash


ルーティング＆コントローラ作成
ルーティング
コントローラは前回作成したCSVダウンロードのコントローラに追記しました。

厳密にはCSVアップロードになるので、気になるかたは新しいコントローラを作成してください。


Route::get('csv/practice2', 'CsvDownloadController@practice2'); //表示
Route::post('csv/practice2', 'CsvDownloadController@upload_regist'); //登録

PHP


コントローラ(表示)

public function practice2()
{
    return view('csv.practice2');
}

PHP


コントローラ(CSVインポート処理)
この記事のメインになるところですね。


public function upload_regist(Request $rq)
{
    if($rq->hasFile('csv') && $rq->file('csv')->isValid()) {
        // CSV ファイル保存
        $tmpname = uniqid("CSVUP_").".".$rq->file('csv')->guessExtension(); //TMPファイル名
        $rq->file('csv')->move(public_path()."/csv/tmp",$tmpname);
        $tmppath = public_path()."/csv/tmp/".$tmpname;

        // Goodby CSVの設定
        $config_in = new LexerConfig();
        $config_in
            ->setFromCharset("SJIS-win")
            ->setToCharset("UTF-8") // CharasetをUTF-8に変換
            ->setIgnoreHeaderLine(true) //CSVのヘッダーを無視
        ;
        $lexer_in = new Lexer($config_in);

        $datalist = array();

        $interpreter = new Interpreter();
        $interpreter->addObserver(function (array $row) use (&$datalist){
           // 各列のデータを取得
           $datalist[] = $row;
        });

        // CSVデータをパース
        $lexer_in->parse($tmppath,$interpreter);

        // TMPファイル削除
        unlink($tmppath);

        // 処理
        foreach($datalist as $row){
            // 各データ取り出し
            $csv_user = $this->get_csv_user($row);

            // DBへの登録
            $this->regist_user_csv($csv_user);
        }
        return redirect('/csv/practice2')->with('flashmessage','CSVのデータを読み込みました。');
    }
    return redirect('/csv/practice2')->with('flashmessage','CSVの送信エラーが発生しましたので、送信を中止しました。');
}

PHP


解説
インポートの流れは以下のようになります。

１．フォームデータからCSVファイルを受け取る
２．Goodby/CSV の config設定
３．Goodby/CSV ライブラリでCSVデータをパース
４．get_csv_userメソッドでkeyとCSVデータを配列にセット
５．regist_user_csvメソッドでDBに保存
コントローラ(get_csv_user)

private function get_csv_user($row)
{
    $user = array(
        'name' => $row[0],
        'email' => $row[1],
        'tel' => $row[2],
    );
    return $user;
}

PHP


コントローラ(regist_user_csv)

private function regist_user_csv($user)
{
    $newuser = new CsvUser;
    foreach($user as $key => $value){
        $newuser->$key = $value;
    }
    $newuser->save();
}

PHP


以上です。

/*
|--------------------------------------------------------------------------
| CSVエキスポートの作成（ステップ３ : CSVエキスポート機能の作成）  参照先  https://laraweb.net/tutorial/5814/
|--------------------------------------------------------------------------
|
|
|
|
*/



/********************************************
 * メソッド定義
 * ******************************************/
PHPでのCSV生成方法について
PHPでのCSV生成方法は主に４つやり方があります。

１．変数に出力テキストを追加していく方式
２．いったんサーバ内にファイル保存する方式
３．メモリ内に一時保存する方式
４．ブラウザに直接返却する方式


１．変数に出力テキストを追加していく方式
入門書やブログなどで紹介されている方法です。

しかし、$datasが100件なら問題ありませんが、数千件になるようであればCPU使用率を振り切るかメモリを使いきって落ちたりします。


：
$buf = '';
$datas = array(
array(…),
：
);
foreach($datas as $data){
$buf .= '"'.implode(‘”,”‘, $data).'"'.PHP_EOL;
}
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=hoge.csv');
print $buf;


PHP


２．いったんサーバ内にファイル保存する方式
サーバローカルにfopenしたファイルに対して、１行づつCSVを追記していきます。

追記が全部終わったところでreadfileして返却するという方式です。


：
$tmp_file = 'tmp.csv';
$fp = fopen($tmp_file,'a');
$datas = array(
array(…),
：
);
foreach($datas as $data){
fputcsv($fp, $data, ',','"');
}
fclose($fp);
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=hoge.csv');
readfile($tmp_file);
unlink($tmp_file);

PHP


３．メモリ内に一時保存する方式
php://memoryやphp://tempなどのストリームラッパーを使用して、データをメモリに保存していくという方法です。

今回の演習ではこの方法でCSVをダウンロードします。

ポイント
php://memoy と php://temp の違い
php://memory は全てをメモリ上に保持します。
対して php://temp は一定以上の容量に達した場合、自動的にテンポラリファイルに逃がされます。
php://memory 一択のように思えますが、結果データの容量が読めない場合は php://temp の方が安全です。


：
$fp = fopen('php://temp/maxmemory:5242880','a');
$datas = array(
array(…),
：
);
foreach($datas as $data){
fputcsv($fp, $data, ',','"');
}
header(“Content-Type: application/octet-stream”);
header(“Content-Disposition: attachment; filename=hoge.csv”);
rewind($fp);
print stream_get_contents($fp);
fclose($fp);

PHP


４．ブラウザに直接返却する方式
ファイル保存の必要が別途必要でない場合は、php://output で直接ブラウザに返却する方法が最短の手段になります。


：
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=hoge.csv");
$fp = fopen('php://output','w');
$datas = array(
array(…),
：
);
foreach($datas as $data){
fputcsv($fp, $data, ',','"');
}
fclose($fp);


PHP




CSVダウンロード機能の作成手順
ビュー
ルーティング
CSV出力するカラムの定義（コントローラ）
CSV出力（コントローラ）
ビュー
CSVダウンロードボタンです。URLヘルパーを使ってリンク先を記述します。


：
<p><a class="btn btn-primary" href="{{url('/csv/download1')}}" target="_blank"> CSV download その1</a></p>
：

PHP




ルーティング
上記のリンク先に対応するコントローラを記述します。


Route::get('csv/download1', 'CsvDownloadController@download1'); //ダウンロード

PHP




CSV出力するカラムの定義（コントローラ）

private function csvcolmns()
{
    $csvlist = array(
        'email' => 'email',
        'password' => 'password',
        'name' => '名前',
        'address' => '住所',
        'birthdate' => '生年月日',
        'msg' => 'メッセージ',
    );
    return $csvlist;
}

PHP


解説
CSVに出力するカラムを設定しておきます。このメソッドは後で呼び出します。

取り出すときは foreach を使います。

key はデータベースのカラム名です。

value はカラム名を説明する文言です。CSV出力ファイルではヘッダとして出力する部分です。



CSV出力（コントローラ）

public function download1()
{
    // 出力項目定義
    $csvlist = $this->csvcolmns(); //auth_information + profiles

    // ファイル名
    $filename = "auth_info_profiles_".date('Ymd').".csv";

    // 仮ファイルOpen
    $stream = fopen('php://temp', 'r+b');

    // *** ヘッダ行 ***
    $output = array();

    foreach($csvlist as $key => $value){
        $output[] = $value;
    }

    // CSVファイルを出力
    fputcsv($stream, $output);

    // *** データ行 ***
    $blocksize = 100; // QUERYする単位
    for($i=0 ; true ; $i++) {
        $query = \App\Models\AuthInformation::query();
        $query->Join('profiles','auth_information.id','=','profiles.authinformation_id'); //内部結合
        $query->orderBy('auth_information.id');
        $query->skip($i * $blocksize); // 取得開始位置
        $query->take($blocksize); // 取得件数を指定
        $lists = $query->get();

        //デバッグ
        //$list_sql = $query->toSql();
        //\Log::debug('$list_sql="' . $list_sql . '"');

        // レコードあるか？
        if ($lists->count() == 0) {
            break;
        }

        foreach ($lists as $list) {
            $output = array();
            foreach ($csvlist as $key => $value) {
                $output[] = str_replace(array("\r\n", "\r", "\n"), '', $list->$key);
            }
            // CSVファイルを出力
            fputcsv($stream, $output);
        }
    }

    // ポインタの先頭へ
    rewind($stream);

    // 改行変換
    $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));

    // 文字コード変換
    $csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');

    // header
    $headers = array(
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="'.$filename.'"',
    );

    return \Response::make($csv, 200, $headers);
}

PHP


解説
コントローラで定義したメソッドです。このメソッドでデータベースのレコードを抽出してCSVをダウンロードします。

最初にカラム名を出力して、次にその値を出力します。

Microsoft Excel が想定している日本語CSVのデフォルトエンコードは『Shift_JIS』です。

なので、最後にmb_convert_encodeing() でエンコードしてあげます。


演習「CSVダウンロードの作成」に関しては以上です。


