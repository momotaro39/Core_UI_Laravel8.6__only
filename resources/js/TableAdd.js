/*****************************************
 *      jQuery部分。
 ****************************************** */

//*********************************************//
//***************table-management**************//
//*********************************************//
$(function() {
    // 下で作ったメソッドを呼び出しておく
    // これでどの場所でもドラッグアンドドロップができる。

    p_tableDnD(); // ドラッグアンドドロップ制御
    p_tableShadow(); // ドラッグアンドドロップ時に影を付ける

    // プラスボタンクリック時
    $(".PlusBtn").on("click", function() {
        // １番下へスクロール
        // 使うところまで移動するの親切になるっぽい
        setTimeout(function() {
            window.scroll(0, $(document).height());
        }, 0);

        // 複製を作る
        // クラス名称の属性を作る
        // 行の最初の部分を複製（ tr:first-child").clone(true)）して追加（.appendTo(".table-management tbody");）する。
        $(".table-management tbody tr:first-child")
            .clone(true)
            .appendTo(".table-management tbody");

        // クラス名のテーブルボディの最後の行を指定「tr:last-child」の属性の(テーブルデータ td)の inputの中身を空白にする
        // 値が入ってることもあるので、値ごとコピーしないようにvalを空にする。
        $(".table-management tbody tr:last-child td input").val("");

        // 他のプラグインを再読み込み
        // いろいろ処理した後にもう一度もとの状態に戻す
        // selectedの部分を再度読み込んでるっぽい。それで作り直される
        $(".table-management tbody tr:last-child td select option").atter(
            "selected",
            false
        );
        // ドラッグアンドドロップ制御
        p_tableDnD();
        // ドラッグアンドドロップ時に影を付ける
        p_tableShadow();
    });

    // マイナスボタンクリック時
    $(".MinusBtn").on("click", function() {
        // 行が2行以上あればクリックされた列を削除
        // .lengthで行をカウントする
        if ($(".table-management tbody tr").length >= 2) {
            // 親の要素（parents('tr')）を削除「.remove()」
            $(this)
                .parents("tr")
                .remove();

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
    function p_tableDnD() {
        $(".table-management").tableDnD({
            dragHandle: ".handle"
        });
    }
    // ドラッグアンドドロップ時に影を付ける
    // ボタンを押したとき（.mousedown）に利用
    function p_tableShadow() {
        $(".table-management tbody tr .handle").mousedown(function() {
            // 領域内で押した時
            // 親のtrの行に対して、CSSを変更する
            $(this)
                .parents("tr")
                .css("box-shadow", "2px 3px 6px 2px #9E9E9E");
            return false;
        });
        // アスタリスクがついたところでクリックを話したとき
        $(" * ").mouseup(function() {
            // 指定のクラス名と属性の部分のCSSを変更する。
            // box-shadowに変更した状態をなしにする
            $(".table-management tbody tr").css("box-shadow", "none");
        });
    }
});
