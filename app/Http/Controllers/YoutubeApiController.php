<?php

namespace App\Http\Controllers;

//コントローラーの場所を変えたときには必要になる
// use App\Http\Controllers\Controller;

//  GoogleApiを使用する時に必要
use Google_Client;
// YouTubeを利用するときに必要
use Google_Service_YouTube;


use Illuminate\Http\Request;

class YoutubeApiController extends Controller
{
    const MAX_SNIPPETS_COUNT = 50;
    const DEFAULT_ORDER_TYPE = 'viewCount';


    public function getListByChannelId(string $channelId)
    {
        // Googleへの接続情報のインスタンスを作成と設定
        $client = new Google_Client();
        $client->setDeveloperKey(env('GOOGLE_API_KEY'));

        // 接続情報のインスタンスを用いてYoutubeのデータへアクセス可能なインスタンスを生成
        $youtube = new Google_Service_YouTube($client);

        // 必要情報を引数に持たせ、listSearchで検索して動画一覧を取得
        $items = $youtube->search->listSearch('snippet', [
            'channelId'  => $channelId,
            'order'      => self::DEFAULT_ORDER_TYPE,
            'maxResults' => self::MAX_SNIPPETS_COUNT,
        ]);

        // 連想配列だと扱いづらいのでcollection化して処理
        $snippets = collect($items->getItems())->pluck('snippet')->all();
        return view('youtube/index')->with(['snippets' => $snippets]);
    }
}