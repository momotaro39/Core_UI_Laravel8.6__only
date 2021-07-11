<?php

namespace App\Http\Controllers;

// Maiableクラスを利用
use App\Mail\SendTestMail;

use Mail;

class MailSendController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | send(view_name, data, callback);
    |--------------------------------------------------------------------------
    |
    | view_nameは、メールの本文の内容を記述するビューの名前です。htmlタグが使えます。
    | dataはビューに渡す変数です。
    | callbackはメッセージインスタンスを受け取るクロージャ()です。メッセージインスタンスはto, from, cc, subjectなどのメソッドを持っています。
    |
    */

    /**
     * メール機能確認用
     */
    public function index()
    {
        // 配列を作成しておきます。
        $data = [];


        // viewディレクトリに作成 'emails.welcome
        Mail::send('emails.welcome', $data, function ($message) {
            $message->to('atesaki@example.com', '送り先の名称')
                ->subject('件名テスト。');
        });
    }


    /**
     * メールを実際に送ります。
     */
    public function send()
    {

        $data = [];

        Mail::send('emails.test', $data, function ($message) {
            $message->to('XXXXX@XXXXX.co.jp', '送り先の名称')
                ->from('XXXXX@XXXXX.co.jp', '送り主の名称')
                ->subject('件名を入力');
        });
    }

    /**
     * Maiableクラスで設定して送ります。
     */
    public function MaiableSend()
    {

        $to = [
            [
                'email' => 'XXXXX@XXXXX.jp',
                'name' => 'Test',
            ]
        ];

        Mail::to($to)->send(new SendTestMail());
    }
}