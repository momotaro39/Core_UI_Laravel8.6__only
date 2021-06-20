<?php


/*
|--------------------------------------------------------------------------
| LINEのMessaging APIを使ってLINE公式アカウントからメッセージを送る  参照先 https://codeeee.net/posts/laravel-line-messaging-api
|--------------------------------------------------------------------------
|
| LINE公式アカウントにメッセージを送られた際Laravelから返答
| LINE公式アカウントから特定のユーザーにメッセージをLaravelから送信
|
*/



/********************************************
 * メソッド定義
 * ******************************************/


LINE側の準備
LINE Developersにアカウントを作る
LINE Developersにアクセスしてログインしてください。



新規で作成する場合は自身の「LINEアカウントでログイン」
すでにLINE@等でビジネスアカウントを持っている場合は「ビジネスアカウントでログイン」
を選択して進めてください。

プロバイダ選択
ビジネスアカウントでログインした場合、すでにプロバイダが存在すればそちらを選択し、なければ 「新規プロバイダー作成」 してください。

プロバイダ新規登録

プロバイダの名前を入力するとプロバイダが作成されます。

Messaging APIチャンネルを作成
次に、対象のもしくは新規で作成したプロバイダを選択してMessaging APIチャンネルを作成してください。

Messaging APIチャンネルを作成

「Create a Messaging API channel」から必要項目を入力して進めてください。

アクセストークンを発行する
作成されたらMessaging APIタブに移動しチャンネルクセストークンを発行します。

チャンネルアクセストークン



発行されたトークンはメモしておいてください。

LINE公式アカウントのMessagingAPIを有効にする
次にアカウントリストから連携するサービスを選択してください。



右側の歯車の設定からMessagingAPIを選択し、「利用する」をクリック



APIの有効化



有効化するとChannel secretが作成されるのでメモしておいてください。



Webhook URLは後ほど入力するので一旦は空の状態で問題ありません。

Webhookをオンにする
応答モードは botにして、Webhookをオンにしてください。

応答設定

Laravel側の準備
envファイルにChannel secretとチャネルアクセストークンを記述
Laravelのenvファイルを開き、先ほど取得したChannel secretとチャネルアクセストークンを記述します。

LINE_CHANNEL_SECRET=XXXXOOOOO
LINE_ACCESS_TOKEN=XXXXOOOOOXXXXOOOOOXXXXOOOOO=


LINEのパッケージをインストール
LINEの公式がPHP用のパッケージを用意してくれているのでそちをインストールします。



公式パッケージ

$ composer require linecorp/line-bot-sdk
ちなみにその他の言語のパッケージについてはこちらを確認してください。

実装
今回は

メッセージを送られた際の返答
特定のユーザーにメッセージを送信
の２つの方法について説明していきます
LINEのwebhook受取用のルーティングを用意
メッセージを送られた時などユーザーが何かしらLINEからの操作を行った時の通知を受け取るための
ルーティングとLaravel側からメッセージを送る際のルーティングを用意します。



/************************ LINE *************************/
// line webhook受取用
Route::post('/line/callback',    'LineApiController@postWebhook');
// line メッセージ送信用
Route::get('/line/message/send', 'LineApiController@sendMessage');


今回は https://{ドメイン}/line/callback を受け口にしておきます。



前提にも記述していますがSSLが有効なURLは用意しておいてください 。

コントローラーを用意
今回は、LineApiControllerという名前で作成していきます。

$ php artisan make:controller LineApiController


LineApiControllerファイルを開き、まずコンストラクタでenvに記述したChannel secretとチャネルアクセストークンを取得してきます。

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class LineApiController extends Controller
{

    protected $access_token;
    protected $channel_secret;

    public function __construct()
    {
        // :point_down: アクセストークン
        $this->access_token = env('LINE_ACCESS_TOKEN');
        // :point_down: チャンネルシークレット
        $this->channel_secret = env('LINE_CHANNEL_SECRET');
    }

    // Webhook受取処理
    public function postWebhook(Request $request) {
        // ここに処理を書いていく
    }

    // メッセージ送信用
    public function sendMessage(Request $request) {
        // ここに処理を書いていく
    }
}


まずは、メッセージを送られた際の返答を行うためにpostWebhookを記述していきます

ユーザーの操作を特定する
LINEからのアクセスがあった際に、最初にどういった操作が行われたのか判定する必要があります。

public function postWebhook(Request $request) {
    $input = $request->all();
    // ユーザーがどういう操作を行った処理なのかを取得
    $type  = $input['events'][0]['type'];

    // タイプごとに分岐
    switch ($type) {
        // メッセージ受信
        case 'message':
            // メッセージ受信
            break;

        // 友だち追加 or ブロック解除
        case 'follow':
            Log::info("ユーザーが追加されました。");
            break;

        // グループ・トークルーム参加
        case 'join':
            Log::info("グループ・トークルームに追加されました。");
            break;

        // グループ・トークルーム退出
        case 'leave':
            Log::info("グループ・トークルームから退出させられました。");
            break;

        // ブロック
        case 'unfollow':
            Log::info("ユーザーにブロックされました。");
            break;

        default:
            Log::info("the type is" . $type);
            break;
    }

    return;
}



caseでいくつかサンプルをおきましたが、その他受け取れるアクション一覧はこちらで確認できます。



今回は、メッセージ受信した際の返答なので $type = 'message';の中を記述します。
メッセージを送られた際の返答
以下の様に記述してください。

// Webhook受取処理
public function postWebhook(Request $request) {
    $input = $request->all();
    // ユーザーがどういう操作を行った処理なのかを取得
    $type  = $input['events'][0]['type'];

    // タイプごとに分岐
    switch ($type) {
        // メッセージ受信
        case 'message':
            // 返答に必要なトークンを取得
            $reply_token = $input['events'][0]['replyToken'];
            // テスト投稿の場合
            if ($reply_token == '00000000000000000000000000000000') {
                Log::info('Succeeded');
                return;
            }
            // Lineに送信する準備
            $http_client = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($this->access_token);
            $bot         = new \LINE\LINEBot($http_client, ['channelSecret' => $this->channel_secret]);
            // LINEの投稿処理
            $message_data = "メッセージありがとうございます。ただいま準備中です";
            $response     = $bot->replyText($reply_token, $message_data);

            // Succeeded
            if ($response->isSucceeded()) {
                Log::info('返信成功');
                break;
            }
            // Failed
            Log::error($response->getRawBody());
            break;
            break;

        // 友だち追加 or ブロック解除
        case 'follow':
            // ユーザー固有のIDを取得
            $mid = $request['events'][0]['source']['userId'];
            // ユーザー固有のIDはどこかに保存しておいてください。メッセージ送信の際に必要です。
            LineUser::updateOrCreate(['line_id' => $mid]);
            Log::info("ユーザーを追加しました。 user_id = " . $mid);
            break;

        // グループ・トークルーム参加
        case 'join':
            Log::info("グループ・トークルームに追加されました。");
            break;

        // グループ・トークルーム退出
        case 'leave':
            Log::info("グループ・トークルームから退出させられました。");
            break;

        // ブロック
        case 'unfollow':
            Log::info("ユーザーにブロックされました。");
            break;

        default:
            Log::info("the type is" . $type);
            break;
    }

    return;
}


やっていることは 返答に必要なトークンを取得してそのトークンを載せて送信。すごくシンプルですね。



また、友達追加された際にユーザー固有のID取得できるのでこれはDB等で保存しておいてください。



これで、Laravelで受信側の準備ができました。

Webhook URLを設定する
再度アカウントリストから連携するサービスを選択し、先ほど空白でにしていたWebhook URLの項目にに先ほど準備したhttps://{ドメイン}/line/callbackを記述してください。

Webhook URLの検証
設定したURLに正しくデータが送信されているかテストするには、LINE Developersのチャンネルトークンを作成したページよりWebhook設定から行います。



Webhook URLの検証



https://{ドメイン}/line/callback このURLを入力し、更新を押すと検証ボタンが出てくるので検証ボタンを押すことでエラーもしくは成功のステータスが返されます。

メッセージ返信のテスト
作成した公式アカウントを友達登録し、実際にメッセージを送信してみましょう。

LINEメッセージ返信



返信されましたね。



次は、特定のユーザーにメッセージを送信する

特定のユーザーにメッセージを送信
先ほど友達登録の際に取得した、ユーザー固有のID（UXXXXXXXXXXXX）を使用します。


public function postWebhook(Request $request) {
    $input = $request->all();
    // ユーザーがどういう操作を行った処理なのかを取得
    $type  = $input['events'][0]['type'];

    // タイプごとに分岐
    switch ($type) {

        /* 省略 */

        // 友だち追加 or ブロック解除
        case 'follow':
            // ユーザー固有のIDを取得
            $mid = $request['events'][0]['source']['userId']; // UXXXXXXXXXXXX
            // ユーザー固有のIDはどこかに保存しておいてください。メッセージ送信の際に必要です。
            LineUser::updateOrCreate(['line_id' => $mid]);
            Log::info("ユーザーが追加されました。");
            break;

        /* 省略 */

        default:
            Log::info("the type is" . $type);
            break;
    }

    return;
}


送信する用のsendMessage内は以下の様に記述します。

// メッセージ送信用
public function sendMessage(Request $request) {
    // Lineに送信する準備
    $http_client = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($this->access_token);
    $bot         = new \LINE\LINEBot($http_client, ['channelSecret' => $this->channel_secret]);

    $line_user_id = "UXXXXXXXXXXXX";
    $message = "お知らせ LINEテスト";
    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
    $response    = $bot->pushMessage($line_user_id, $textMessageBuilder);

    // 配信成功・失敗
    if ($response->isSucceeded()) {
        Log::info('Line 送信完了');
    } else {
        Log::error('投稿失敗: ' . $response->getRawBody());
    }
}


api等でhttps://{ドメイン}/line/message/send を叩いてみてください。

LINEメッセージ送信

問題なく送信されました。
