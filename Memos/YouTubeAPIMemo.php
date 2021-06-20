<?php


/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://anytimesnotes.com/archives/2736
|--------------------------------------------------------------------------
|
| Laravelでyoutube apiを呼び出し、動画リストを取得する方法
|
|
*/



/********************************************
 * 「Google APIs Client」ライブラリを導入する
 *
 * youtube apiを呼び出すために「Google APIs Client」ライブラリを導入します。
 *
 * ※サーバーにcomposerをインストールした上で以下のコマンドを実行
 * ******************************************/

composer require google/apiclient:"^2.7"

/********************************************
 * 動画の一覧を検索する
 *
 *
 * ******************************************/



youtube apiを呼び出すメソッドを作成。


今回利用するのは以下。

「/v3/search」
「v3/videos」

「/v3/search」は公式サイトに以下のようにあります。

/********************************************
 * 「/v3/search」は公式サイト情報
 *
 * https: //developers.google.com/youtube/v3/docs/search?hl=ja
 * ******************************************/

API リクエストで指定したクエリ パラメータに一致する検索結果のコレクションを返します。
デフォルトでは、検索結果のセットでは一致する video、channel、playlist の各リソースが識別されますが、
特定の種類のリソースだけを取得するようにクエリを設定することもできます。





/********************************************
 * 「v3/videos」は公式サイト
 * https://developers.google.com/youtube/v3/docs/videos/list?hl=ja
 *
 * ******************************************/


API リクエストのパラメータに一致する動画のリストを返します。

【重要】
動画の再生数や高評価数を取得することができます。

/********************************************
 * APIで検索取得の流れ
 *
 * ******************************************/

 「/v3/search」で動画を検索
「v3/videos」で詳細情報を取得する


/********************************************
 * 実際の作成方法
 * フォルダ作成
 * ******************************************/
app/Http直下にVenderフォルダを作成

Venderフォルダ直下にCallYoutubeApi.phpファイルを作成

/********************************************
 * CallYoutubeApi.phpファイル 設定
 * ******************************************/

<?php

namespace App\Http\Vender;


//必要な機能を導入
use Google_Client;
use Google_Service_YouTube;

class CallYoutubeApi
{
    // 変数宣言を行う
    // You Tubeの個人APIキーを設定
    private $key = '自身のAPIキー';

    // インスタンスを作って、setDeveloperKeyメソッドを入れる。
    private $client;

    // インスタンス作る。ライブラリのメソッドを活用して設定する。
    private $youtube;

    public function __construct()
    {
        // インスタンスの作成
        $this->client = new Google_Client();

        // 最初に指定したYou Tubeの個人APIキーを入れる
        $this->client->setDeveloperKey($this->key);

        $this->youtube = new Google_Service_YouTube($this->client);
    }

    /**
     * /v3/searchを呼び出す
     *
     * @param string $serachWord
     * @return array
     */
    public function serachList(String $searchWord)
    {
        $r = $this->youtube->search->listSearch('id', array(
          'q' => $searchWord,
          'maxResults' => 10,
          'order' => 'viewCount',
        ));

        return $r->items;
    }

    /**
     * /v3/videosを呼び出す
     *
     * @param string $id
     * @return array
     */
    public function videosList(String $id)
    {
        $r = $this->youtube->videos->listVideos('statistics,snippet', array(
          'id' => $id,
        ));

        return $r->items;
    }
}

/********************************************
 * serachListメソッドとvideosListメソッドをコントローラから呼び出します。
 *
 * 以下のコマンドをターミナル上で実行してください。
 * ******************************************/

php artisan make:controller YoutubeController

/********************************************
 * YouTubeController.php
 * ******************************************/


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Vender\CallYoutubeApi;

class YouTubeController extends Controller
{
    public function index(Request $request)
    {
        // クラスのインスタンスを作成する
        $t = new CallYoutubeApi();

        // class CallYoutubeApiのserachListメソッドを利用して変数に入れる
        $serachList = $t->serachList("検索したいワード");

        foreach ($serachList as $result) {
          $videosList = $t->videosList($result->id->videoId);
          $embed = "https://www.youtube.com/embed/" . $videosList[0]['id'];
          $array[] = array($embed, $videosList[0]['snippet'],$videosList[0]['statistics']);
        }
        // View画面で$arrayの配列の中身を$youtubeという名称で渡します。
        // 最初のyoutubeは.blade.phpファイルの名称
        return view('youtube', ['youtube' => $array]);
    }
}

/********************************************
 * 検索結果を画面上に表示
 *
 * resources/views直下にyoutube.blade.phpファイルを作成
 * ******************************************/

// 配列の中身が0以上なら表示する

@if (count($youtube) > 0)
    <table class="table table-striped">
        <thead>
            <tr>
                <th>image</th>
                <th>title</th>
                <th>viewCount</th>
                <th>likeCount</th>
                <th>dislikeCount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($youtube as $youtube)
            <tr>
                <td>
                    <iframe id="ytplayer" type="text/html" width="320" height="180"
                      src={{ $youtube[0] }}
                      frameborder="0"></iframe>
                </td>
                <td>{{ $youtube[1]['title'] }}</td>
                <td>{{ $youtube[2]['viewCount'] }}</td>
                <td>{{ $youtube[2]['likeCount'] }}</td>
                <td>{{ $youtube[2]['dislikeCount'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
/********************************************
 * routing
 * route/web.phpファイルに以下を追記
 * ******************************************/

Route::get('youtube', 'YoutubeController@index');




ブラウザで~/youtubeにアクセスしてもらえばツイートの一覧が表示されるはずです。

