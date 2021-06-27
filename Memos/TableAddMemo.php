<?php


/*
|--------------------------------------------------------------------------
| 【HTML＆CSS&jQuery】テーブルの行の追加、削除、移動機能(並び替え)  参照先   https://patoblog.com/table-management/
|--------------------------------------------------------------------------
|
| 仕様
|プラスボタンを押したら行が追加される
| マイナスボタンを押したら行が削除される
| 左の三本線をドラッグアンドドロップしたら行の移動ができる
|
|
*/





/*
|--------------------------------------------------------------------------
| 基本の設定
|--------------------------------------------------------------------------
|
|
*/



    /*****************************************
     * HTML部分
     * HTML部分ではテーブルを作成します。
****************************************** */




    <html lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

        <!-- BootstrapのCSS読み込み -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- jQuery読み込み -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

        <!-- BootstrapのJS読み込み -->
        <script src="js/bootstrap.min.js"></script>

        <!-- tablednd読み込み -->
        <script type="text/javascript" src="comn/jquery.tablednd.0.7.min.js"></script>


        <title>自分のタイトル</title>
    </head>


    // クラス名を入れておくこと
    <table class="table tablesorter-bootstrap table-management">
        <thead>
            <tr class="nodrop nodrag">
                <th style="width: 5%;"></th>
                <th style="width: 20%;">name</th>
                <th style="width: 20%;">type</th>
                <th style="width: 10%;">public</th>
                <th style="width: 40%;">description</th>
                <th style="width: 5%;"></th>
            </tr>
        </thead>
        <tbody>
            <tr>

                <!-- 三本線の画像 -->
                <td class="handle"><img src="img/handle.png"></td>
                <td>name</td>
                <td>type</td>
                <td>public</td>
                <td>description</td>

                <!-- マイナスボタンの画像 -->
                <td class="MinusBtn"><img src="img/minus.png"></td>
            </tr>
        </tbody>
    </table>

    <!-- プラスボタンの画像 -->
    <img class="PlusBtn" src="img/plus.png">
    好きな三本線の画像とマイナスボタンの画像とプラスボタンの画像を用意します。


        /*****************************************
     *     CSS部分
     *     CSS部分です。
****************************************** */

    .handle{
        cursor: move;
        position: relative;
    }
    .handle img{
        min-width: 20px;
        width: 20px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateY(-50%) translateX(-50%);
        -webkit-transform: translateY(-50%) translateX(-50%);
    }
    .PlusBtn{
        display: block;
        margin: 10px auto;
        width: 25px;
        cursor: pointer;
    }
    .MinusBtn{
        cursor: pointer;
        position: relative;
    }
    .MinusBtn img{
        min-width: 20px;
        width: 20px;
        position: absolute;
        top: 50%; /*親要素を起点に上から50%*/
        left: 50%;  /*親要素を起点に左から50%*/
        transform: translateY(-50%) translateX(-50%); /*要素の大きさの半分ずつを戻す*/
        -webkit-transform: translateY(-50%) translateX(-50%);
    }


        /*****************************************
     *      jQuery部分。
****************************************** */



    //*********************************************//
    //***************table-management**************//
    //*********************************************//
    $(function(){
        // 下で作ったメソッドを呼び出しておく
        // これでどの場所でもドラッグアンドドロップができる。

        p_tableDnD();  // ドラッグアンドドロップ制御
        p_tableShadow();   // ドラッグアンドドロップ時に影を付ける

        // プラスボタンクリック時
        $(".PlusBtn").on('click',function(){

            // １番下へスクロール
            // 使うところまで移動するの親切になるっぽい
            setTimeout(function() {
                window.scroll(0,$(document).height());
            },0);

            // 複製を作る
            // クラス名称の属性を作る
            // 行の最初の部分を複製（ tr:first-child").clone(true)）して追加（.appendTo(".table-management tbody");）する。
            $(".table-management tbody tr:first-child").clone(true).appendTo(".table-management tbody");

        // クラス名のテーブルボディの最後の行を指定「tr:last-child」の属性の(テーブルデータ td)の inputの中身を空白にする
        // 値が入ってることもあるので、値ごとコピーしないようにvalを空にする。
            $(".table-management tbody tr:last-child td input").val("");


        // 他のプラグインを再読み込み
        // いろいろ処理した後にもう一度もとの状態に戻す
        // selectedの部分を再度読み込んでるっぽい。それで作り直される
            $(".table-management tbody tr:last-child td select option").atter("selected",false);
        // ドラッグアンドドロップ制御
            p_tableDnD();
        // ドラッグアンドドロップ時に影を付ける
            p_tableShadow();
        });

        // マイナスボタンクリック時
        $(".MinusBtn").on('click',function(){

            // 行が2行以上あればクリックされた列を削除
            // .lengthで行をカウントする
            if ($(".table-management tbody tr").length >= 2) {

                // 親の要素（parents('tr')）を削除「.remove()」
                $(this).parents('tr').remove();

            // 他のプラグインを再読み込み
            // いろいろ処理した後にもう一度もとの状態に戻す
            // ドラッグアンドドロップ制御
            p_tableDnD();
            // ドラッグアンドドロップ時に影を付ける
            p_tableShadow();

            }
        });

    // ドラッグアンドドロップ制御の基本的なメソッドを定義
    // ボタンを押したときに利用
        function p_tableDnD(){
            $(".table-management").tableDnD({
                dragHandle: ".handle"
            });

        }
    // ドラッグアンドドロップ時に影を付ける
        // ボタンを押したとき（.mousedown）に利用
        function p_tableShadow(){
            $(".table-management tbody tr .handle").mousedown(function(){

                // 領域内で押した時
                // 親のtrの行に対して、CSSを変更する
                $(this).parents('tr').css('box-shadow','2px 3px 6px 2px #9E9E9E');
                return false;
            })
            // アスタリスクがついたところでクリックを話したとき
            $(" * ").mouseup(function(){
                // 指定のクラス名と属性の部分のCSSを変更する。
                // box-shadowに変更した状態をなしにする
                $(".table-management tbody tr").css('box-shadow','none');
            });
        }
    });



/*
|--------------------------------------------------------------------------
| 基本の設定
|--------------------------------------------------------------------------
|
|
*/

プラスボタンを押した時に、一番最初のtr要素をコピーして一番下に貼り付けます。
その際、一番下へスクロールさせる。
値が入ってる場合
値ごとコピーしないようにvalを空にする。
他のプラグインを再読み込みしたりしてます。


マイナスボタンを押した時は、押された要素の親のtrを削除します。
もし、最後の一行だった場合削除できないようにしています。

次にプラスボタンを押した時、行をコピペできなくなります。
どうしても最後の一行を消す機能が欲しい方は、tr要素をどこかで定義しておかなければなりません。


ドラッグアンドドロップした時は、影を付けるようにしています。
領域外で話した時でも解除されるようにワイルドカードを使って、影を外しています。

    /*****************************************
    *  メソッド定義
    /****************************************** */


/*
|--------------------------------------------------------------------------
| 基本の設定
|--------------------------------------------------------------------------
|
|
*/



    /*****************************************
    *  メソッド定義
    /****************************************** */