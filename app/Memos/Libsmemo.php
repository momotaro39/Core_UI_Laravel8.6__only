<?php



/*
    |--------------------------------------------------------------------------
    |
    | 基本設定 APIの共通処理
    |
    |--------------------------------------------------------------------------
    | BaseAPIとして共通処理を設定する
    |
    */

namespace App\Libs\Api;

use GuzzleHttp\Client;

class BaseApi
{
    /****************************************** */
    /*  変数定義
    /****************************************** */


    /****************************************** */
    /*  メソッド定義
    /****************************************** */
    /**
     * API実行メイン処理
     *
     * @param [array] $request
     * @param [string] $url
     * @param [string] $sendType
     * @return array
     */
    public static function execApi($request, $url, $sendType)
    {
        //送信詳細
        try {
            if ($sendType == config('const.api_type.post')) {
                $res = self::sendApiByPost($request, $url);
            } else {
                $res = self::sendApiByGet($request, $url);
            }

            return $response = [
                'status'         => true,
                'data'           => json_decode($res, true),
            ];

            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            \Log::error($e->getResponse()->getBody());
            return $response = [
                'status'         => false,
                'result_code'    => $e->getCode(),
                'result_message' => $e->getResponse()->getReasonPhrase(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            \Log::error($e->getResponse()->getBody());
            return $response = [
                'status'         => false,
                'result_code'    => $e->getCode(),
                'result_message' => $e->getResponse()->getReasonPhrase(),
            ];
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $response = [
                'status'         => false,
                'result_code'    => $e->getCode(),
                'result_message' => $e->getMessage(),
            ];
        }
    }


    /**
     * API実行メイン処理(リクエスト配列空で作成)
     * ※オーバーロード関数
     * @param [string] $url
     * @param [string] $sendType
     * @return array
     */
    public static function execApiWithoutRequest($url, $sendType)
    {
        return Self::execApi([], $url, $sendType);
    }


    /**
     * API送信処理(POST)
     *
     * @param [array] $request
     * @param [string] $url
     * @return void
     */
    public static function sendApiByPost($request, $url)
    {
        //クライアント宣言
        $client = new Client();

        //ヘッダーの作成
        $header = [
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'Connection'    => 'keep-alive'
        ];

        //POST処理とエラーキャッチ処理
        $res = $client->request('POST', $url, [
            'headers' => $header,
            'body'    => json_encode($request),
        ]);
        return (string) $res->getBody();
    }

    /**
     * API送信処理(GET)
     *
     * @param [array] $request
     * @param [string] $url
     * @return void
     */
    public static function sendApiByGet($request, $url)
    {
        //クライアント宣言
        $client = new Client();

        //ヘッダーの作成
        $header = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'Connection'   => 'keep-alive',
            'Authorization' => config('const.git_hub.api_token')
        ];

        //URLにGetパラメータ付与
        if (!empty($request)) {
            $url = $url . '?' . http_build_query($request);
        }
        //dd($url);
        $res = $client->request('GET', $url, [
            'headers' => $header,
        ]);

        return (string) $res->getBody();
    }
}


/*
    |--------------------------------------------------------------------------
    |
    |  GitHubApi
    |
    |--------------------------------------------------------------------------
    |
    | BaseAPIを継承してセットする
    | gitHub連携APIクラス
    | ※送信するリクエストデータは呼び出し元で作成する事
    |
    */

namespace App\Libs\Api;

class GitHubApi extends BaseApi
{
    /****************************************** */
    /*  メソッド定義
    /****************************************** */
    /**
     * gitHub API issueリスト取得
     * 指定のプロジェクト内のissueを取得する(openのみ)
     * @param [type] $request     ： getパラメータ
     * @param [type] $projectName ： プロジェクト名称
     * @return array
     */
    public static function getIssueList($request, $projectName)
    {
        //APIエンドポイントの作成
        $apiUrl = config('const.git_hub.api_url') . '/repos/' . config('const.git_hub.repo_name') . '/' . $projectName . '/issues';

        //APIタイプセット
        $type = config('const.api_type.get');

        //実行メソッド呼び出し
        $responseParam = parent::execApi($request, $apiUrl, $type);

        return $responseParam;
    }
}





    /*
    |--------------------------------------------------------------------------
    |
    | CSVとExcelのダウンロード設定 基本の共通設定
    |
    |--------------------------------------------------------------------------
    |
    | Maatwebsiteを事前にインストールしておく。
    | EXCELダウンロードは複数のファイルに分割しても、連続で記入しても良い。
    | 使うときにインポートする場所が変わります。
    |
    */

namespace App\Libs;

use Maatwebsite\Excel\Facades\Excel;
use app\Exports\CustomerExport;
use app\Exports\ProjectExport;
use app\Exports\HourExport;

class BaseDownloadHelper
{

    /**
     * CSV/TSVダウンロード
     * @param array $list
     * @param array $header
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public static function download($list, $header, $filename, $tsvFlg)
    {
        if (count($header) > 0) {
            array_unshift($list, $header);
        }

        $delimiter = $tsvFlg == false ? ',' : "\t";

        $stream = fopen('php://temp', 'r+b');
        foreach ($list as $row) {
            fputcsv($stream, $row, $delimiter);
        }
        rewind($stream);

        $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
        $csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');

        $headers = array(
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        );

        return \Response::make($csv, 200, $headers);
    }

    /**
     * EXCELダウンロード
     * @param type $query
     * @param type $fileName
     * @param type $writerType
     * @param type $headers
     * @return type
     */
    public static function excelDownload($query, $fileName, $writerType, $headers)
    {
        return Excel::download(new CustomerExport($query), $fileName, $writerType, $headers);
    }


    /*
    |--------------------------------------------------------------------------
    |
    | EXCELのダウンロードを別ファイルとして設定する
    | BaseDownloadHelperを利用する
    |--------------------------------------------------------------------------
    |
    | 上に記載されているBaseを継承してセットを行う
    |
    |
    */


    namespace App\Libs;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;

class CustomerDownload extends BaseDownloadHelper
{
    /**
     * EXCELダウンロード
     * @param type $query
     * @param type $fileName
     * @param type $writerType
     * @param type $headers
     * @return type
     */
    public static function excelDownload($query, $fileName, $writerType, $headers)
    {
        return Excel::download(new CustomerExport($query), $fileName, $writerType, $headers);
    }
}