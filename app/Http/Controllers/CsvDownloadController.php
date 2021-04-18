<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsvDownloadController extends Controller
{
    public function list()
    {
        $query = \App\Models\AuthInformation::query();
        $query->Join('profiles', 'auth_information.id', '=', 'profiles.authinformation_id'); //内部結合
        $query->orderBy('auth_information.id');
        $lists = $query->paginate(10);

        $hash = array(
            'lists' => $lists,
        );

        return view('csv.practice1')->with($hash);
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        $query = \App\Models\AuthInformation::query();
        $query->Join('profiles', 'auth_information.id', '=', 'profiles.authinformation_id'); //内部結合
        $query->where('profiles.name', 'LIKE', "%$q%");
        $query->orderBy('auth_information.id');
        $lists = $query->paginate(10);

        $hash = array(
            'lists' => $lists,
        );

        return view('csv.practice1')->with($hash);
    }

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

    public function download1()
    {
        // 出力項目定義
        $csvlist = $this->csvcolmns(); //auth_information + profiles

        // ファイル名
        $filename = "auth_info_profiles_" . date('Ymd') . ".csv";

        // 仮ファイルOpen
        $stream = fopen('php://temp', 'r+b');

        // *** ヘッダ行 ***
        $output = array();

        foreach ($csvlist as $key => $value) {
            $output[] = $value;
        }

        // CSVファイルを出力
        fputcsv($stream, $output);

        // *** データ行 ***
        $blocksize = 100; // QUERYする単位
        for ($i = 0; true; $i++) {
            $query = \App\Models\AuthInformation::query();
            $query->Join('profiles', 'auth_information.id', '=', 'profiles.authinformation_id'); //内部結合
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
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        );

        return \Response::make($csv, 200, $headers);
    }

    public function practice2()
    {
        return view('csv.practice2');
    }


    public function upload_regist(Request $request)
    {
        if ($request->hasFile('csv') && $request->file('csv')->isValid()) {
            // CSV ファイル保存
            $tmpname = uniqid("CSVUP_") . "." . $request->file('csv')->guessExtension(); //TMPファイル名
            $request->file('csv')->move(public_path() . "/csv/tmp", $tmpname);
            $tmppath = public_path() . "/csv/tmp/" . $tmpname;

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
            $interpreter->addObserver(function (array $row) use (&$datalist) {
                // 各列のデータを取得
                $datalist[] = $row;
            });

            // CSVデータをパース
            $lexer_in->parse($tmppath, $interpreter);

            // TMPファイル削除
            unlink($tmppath);

            // 処理
            foreach ($datalist as $row) {
                // 各データ取り出し
                $csv_user = $this->get_csv_user($row);

                // DBへの登録
                $this->regist_user_csv($csv_user);
            }
            return redirect('/csv/practice2')->with('flashmessage', 'CSVのデータを読み込みました。');
        }
        return redirect('/csv/practice2')->with('flashmessage', 'CSVの送信エラーが発生しましたので、送信を中止しました。');
    }


    private function get_csv_user($row)
    {
        $user = array(
            'name' => $row[0],
            'email' => $row[1],
            'tel' => $row[2],
        );
        return $user;
    }

    private function regist_user_csv($user)
    {
        $newuser = new CsvUser;
        foreach ($user as $key => $value) {
            $newuser->$key = $value;
        }
        $newuser->save();
    }
}