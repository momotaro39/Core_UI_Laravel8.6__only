<?php


/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://qiita.com/ryouya3948/items/bd1bccef883148f1b281
|--------------------------------------------------------------------------
|
|
*/



    /*****************************************
     *  投稿ボタン作成
     *  ボタンクリック時にモーダルウィンドウを出力させる
    /****************************************** */


/*****************************************
 * ヘッダーに投稿ボタンを追加します。
 * このpost_windowをクリックしたときにJavaScriptの処理が開始されるようにします。
 *
 * ***************************************** */

    header.php

    <ul>
        <li><a href="../post/post_index.php">投稿一覧</a></li>
        <li><a class="post_window" href="#">投稿</a></li>   //ここがモーダルのリンク

        </ul>


 /*****************************************
 * JavaScriptでモーダルウィンドウ出力
 * 先ほどの投稿ボタンがクリックされたときに
 * モーダルウィンドウを出力するよう実装します。
 ***************************************** */


    user_page.js

    $(document).on('click', '.post_window', function() {

        //背景をスクロールできないようにスクロール場所を維持
        scroll_position = $(window).scrollTop();
        $('body').addClass('fixed').css({ 'top': -scroll_position });

        // モーダルウィンドウを開く
        $('.post_process').fadeIn();
        $('.modal').fadeIn();
    });

/*****************************************
 * 解説
 * post_windowをクリックしたときに処理が走る
 *
 ***************************************** */

   $(document).on('click', '.post_window', function() {

   }

/*****************************************
 * 解説
 * モーダルウィンドウが出力された際に背景の画面をスクロールしないように設定。
 * 画面のスクロール位置を取得して、そこで位置を固定。
 ***************************************** */
           scroll_position = $(window).scrollTop();
        $('body').addClass('fixed').css({ 'top': -scroll_position });


/*****************************************
 *  こちらでモーダルウィンドウを出力しています。
 ***************************************** */
            $('.post_process').fadeIn();
        $('.modal').fadeIn();

/*****************************************
 *  モーダルウィンドウ作成
 * モーダルの画面を設定します。
 *
 *
 *  投稿ボタンをクリックするとpost_add_done.phpに処理が遷移するようになっています。
 *  post_add_done.phpはSQL文でpostテーブルにINSERTするようになっています。
 ***************************************** */

    post_process.php

    <div class="modal"></div>
    <div class="post_process">
      <h2 class="post_title">投稿</h2>

    //   actionで遷移先を設定
    // actionの先でInsertの設定が入っている。そのため、コントローラーを確認
      <form method="post" action="../post/post_add_done.php" enctype="multipart/form-data">
      <textarea class="textarea form-control" placeholder="投稿内容を入力ください" name="text"></textarea>
      <div class="post_btn">
      //ここが投稿ボタンになります。
      <button class="btn btn-outline-danger" type="submit" name="post" value="post" id="post">投稿</button>
      <button class="btn btn-outline-primary modal_close" type="button">キャンセル</button>
      </div>
      </form>
    </div>

    /*****************************************
     * 解説
     * モーダルウィンドウが出力された際に背景を灰色にする
     * 細かいことはCSSで設定
     ***************************************** */

    <div class="modal"></div>


/*****************************************
 * 解説
 * モーダルウィンドウ出力時の背景レイアウトCSS
 *
 * こちらも位置(画面全体)、背景色を決めています。
 * z-index: 10;にすることで重なり順序がモーダルウィンドウよりも低く設定しています。
 *
 ***************************************** */

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(152, 152, 152, 0.7);
        width: 100%;
        height: 100%;
        z-index: 10;
    }

    /*****************************************
     * 解説
     * モーダルウィンドウのレイアウト
     *
     *     表示する位置、背景色などを決めています。
     *
     *     z-index: 15;で重なりの順序を高くする
     *     display: none;で普段は表示させないようにさせ、JavaScriptの処理で出力させる
     *
     ***************************************** */

    style.css
    .post_process {
        display: none;
        position: fixed;
        z-index: 15;
        top: 30%;
        left: 50%;
        width: 600px;
        padding: 10px;
        background-color: #121212;
        border-radius: 8px;
        -webkit-transform: translate(-50%, -10%);
        transform: translate(-50%, -10%);
        color: #fff;
        font-size: 1.3rem;
        border: 0.3rem solid #fff;
    }




/*****************************************
 *
 ***************************************** */



    上記を一通り実装すれば、トップの動作画面のように動くと思います。


/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://qiita.com/ryouya3948/items/bd1bccef883148f1b281
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */




/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://qiita.com/ryouya3948/items/bd1bccef883148f1b281
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */




/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://qiita.com/ryouya3948/items/bd1bccef883148f1b281
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */




/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://qiita.com/ryouya3948/items/bd1bccef883148f1b281
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */




/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://qiita.com/ryouya3948/items/bd1bccef883148f1b281
|--------------------------------------------------------------------------
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */