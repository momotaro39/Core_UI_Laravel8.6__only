<?php

namespace App\Mail;


// 自動的に挿入されます
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }



    /*
    |--------------------------------------------------------------------------
    | メールの内容を設定
    |--------------------------------------------------------------------------
    |
    | $this->view(' ')HTMLメールに設定できる
    | $this->text(' ')テキストメールに設定できる
    |
    | ヘッダーのcontent-typeの設定は以下になる
    | テキストメールはtext/plain
    | HTMLメールだとcontent-typeはtext/html
    |
    | マルチパートでテキストとHTMLメールの両方を送る子tも可能
    |     return $this->text('emails.test_text')->view('emails.test')
    |
    |
    |
    */
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.test')
            ->from('test@example.com', 'MailTestUser')
            ->subject('件名を挿入します');
    }
}