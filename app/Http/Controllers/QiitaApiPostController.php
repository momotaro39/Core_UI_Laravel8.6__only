<?php

namespace App\Http\Controllers;

// gazelleを利用するときは明記
use GuzzleHttp\Client;

use Illuminate\Http\Request;

class QiitaApiPostController extends Controller
{
    public function index()
    {

        // 変数をセットしておく
        // ここを自由にセットすることによって色々な情報を取得できる
        $tag_id = "laravel";

        // responceの変数をセット
        $url = "https://qiita.com/api/v2/tags/" . $tag_id . "/items?page=1&per_page=20";
        $method = "GET";

        //接続
        // use GuzzleHttp\Client;を使用していると通信が行われる
        // APIに対してGETメソッドでHTTP通信を行う。

        $client = new Client();

        // 必要な情報おを取得しresponce変数に入れる
        // $response = $client->request("GET", [アクセスしたいURL]);
        $response = $client->request($method, $url);



        // APIとHTTPを行い受け取ったデータに対して、getBody()メソッドを使用してメッセージの本文を取得。

        $posts = $response->getBody();
        // APIで取得したデータはJSON形式のため、json_decode()関数を利用してJSON文字列を配列に変換します。
        $posts = json_decode($posts, true);

        return view('QiitaApi.index', ['posts' => $posts]);
    }

    /**
     * 投稿するためのコントローラ
     */
    public function send(Request $request)
    {
        // 基本的な変数をセットする
        // tokunは独自に取得するので、envファイルなどに記載
        $url = 'https://qiita.com/api/v2/items';
        $method = "POST";
        $token = [取得したQiitaのアクセストークン];
        // env("API_TOKEN")


        // 値を含めてリクエストを送りたい場合は、渡す値を連想配列にする
        $data = array(
            "title" => $request->title,
            "body" => $request->body,
            "private" => $request->private === 'private' ? true : false,
            "tags" => [
                [
                    "name" => $request->tag,
                ]
            ],
        );

        // APIに対してPOSTメソッドでHTTP通信を行う
        $client = new Client();

        // オプションの変数をセットする

        $options = [
            // 値をJSONで送りたいので、オプションとして渡す配列のキーを 'json' 、値を先ほどの$dataにする。
            'json' => $data,
            // Qiitaに記事を投稿する場合は、アクセストークンをヘッダーに含める必要がある
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ]
        ];

        // Qiita APIはレスポンスで自分が投稿した記事の詳細情報を返すので、そこから記事のURLを取得
        // $response = $client->request("POST", [アクセスしたいURL], [オプション(配列)]);
        $response = $client->request($method, $url, $options);

        // 本文を取得
        $post = $response->getBody();
        $post = json_decode($post, true);

        //レスポンスから新規記事のURLを取得
        $new_post_url = $post['url'];

        return redirect('/function/qiita/create')->with('flash_message', '<a href=' . $new_post_url . '>記事</a>を投稿しました');
    }
}