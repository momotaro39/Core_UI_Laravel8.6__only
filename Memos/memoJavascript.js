

/*
    |--------------------------------------------------------------------------
    |  基礎知識をここにメモしていきます
    |--------------------------------------------------------------------------
    |
    |
    */
/**********************
 * .val()
 * 参照先 あ
 ***********************/

/**********************
 * event.preventDefault();
 * 参照先 あ
 ***********************/

/**********************
 * .attr("href")
 * 参照先 あ
 ***********************/

/**********************
 * .find(".modal-title")
 * 参照先 あ
 ***********************/

/**********************
 * .removeClass("is-invalid");
 * 参照先 あ
 ***********************/

/**********************
 * .remove();
 * 参照先 あ
 ***********************/

/**********************
 *  control.replace(/\./g, "-");
 * 参照先 あ
 ***********************/

/**********************
 *  ajaxTableLoad($(this).attr("href"));
 * 参照先 あ
 ***********************/

/**********************
 * $('input[name="' + control + '"]').addClass("is-invalid");
 * 参照先 あ
 ***********************/

/**********************
 * $("#error-file-original")
 * 参照先 あ
 ***********************/

/**********************
 *  .clone()
 * 参照先 あ
 ***********************/

/**********************
 *  .attr("id", "error-" + altControl)
 * 参照先 あ
 ***********************/

/**********************
 *  .appendTo("#error-expense-file");
 * 参照先 あ
 ***********************/

/**********************
 * .html(data.errors[control])
 * 参照先 あ
 ***********************/

/**********************
 *  .after('<br class="error-br">');
 * 参照先 あ
 ***********************/

/**********************
 * $("#" + content).html(data);
 * 参照先 あ
 ***********************/

/**********************
 *  $(".loading").hide();
 * 参照先 あ
 ***********************/

/**********************
 * .find("textarea, :text, select")
 * 参照先 あ
 ***********************/

/**********************
 * .end()
 * 参照先 あ
 ***********************/

/**********************
 * .find(":checked")
 * 参照先 あ
 ***********************/

/**********************
 * .prop("checked", false);
 * 参照先 あ
 ***********************/

/**********************
 * .attr("content");
 * 参照先 あ
 ***********************/

/**********************
 * .parent()
 * 参照先 あ
 ***********************/

/**********************
 * .prop("checked", false);
 * 参照先 あ
 ***********************/


/*
    |--------------------------------------------------------------------------
    |  参照先 https://getbootstrap.jp/docs/4.3/components/modal/
    |--------------------------------------------------------------------------
    |
    |
    */

$("#exampleModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var recipient = button.data("whatever"); // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    modal.find(".modal-title").text("New message to " + recipient);
    modal.find(".modal-body input").val(recipient);
});

/*
    |--------------------------------------------------------------------------
    | 編集画面 郵便番号の入力を確認後、住所の上書きの確認
    |--------------------------------------------------------------------------
    |
    |
    */


$(document).on("click", "#zip_btn", function () {
    //郵便番号が空欄
    if ($("#zip").val() == "" || $("#zip").val() == undefined) {
        Swal.fire("郵便番号がセットされていません", "", "error");
    } else {
        //フォームにテキストが入っているかをtrueかfalseで返す
        var value = $("#address").val();
        // フォームにテキストが入っている場合の対応
        if (value) {
            Swal.fire({
                title: "住所を上書きしてよろしいですか？",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "OK",
            }).then((result) => {
                // OKを押した場合
                if (result.value) {
                    //（zip_codeを入力、、県の数字を入力される、住所も入力する。）
                    AjaxZip3.zip2addr("zip_code", "", "pref_code", "address");
                    //正しくない郵便番号
                    setTimeout(function () {
                        if (
                            $("#address").val() == "" ||
                            $("#address").val() == undefined
                        ) {
                            Swal.fire(
                                "郵便番号が正しくありません",
                                "",
                                "error"
                            );
                        }
                    }, 4000);
                }
            });
        } else {
            //（zip_codeを入力、、県の数字を入力される、住所も入力する。）
            AjaxZip3.zip2addr("zip_code", "", "pref_code", "address");
            //正しくない郵便番号
            setTimeout(function () {
                if (
                    $("#address").val() == "" ||
                    $("#address").val() == undefined
                ) {
                    Swal.fire("郵便番号が正しくありません", "", "error");
                }
            }, 4000);
        }
    }
});

/*
    |--------------------------------------------------------------------------
    |
    |--------------------------------------------------------------------------
    | $(document).on('click', '【セレクタ】', function (){
    | // 何かの処理
    |});
    |
    | JavaScriptでdocumentオブジェクトを活用することで、HTML要素へ簡単にアクセスする
    |
    |
    */
$(document).on("click", "a.page-link", function (event) {
    event.preventDefault();
    ajaxTableLoad($(this).attr("href"));
});

/*
    |--------------------------------------------------------------------------
    | モーダル画面のsubmit処理
    |--------------------------------------------------------------------------
    | $(document).on('click', '【セレクタ】', function (){
    | // 何かの処理
    |});
    |
    | JavaScriptでdocumentオブジェクトを活用することで、HTML要素へ簡単にアクセスする
    |
    |
    */


/**********************
 * summitをクリックした時
 *
 ***********************/
$(document).on("submit", "form#frm", function (event) {
    event.preventDefault();
    /**********************
     * 変数の設定
     ***********************/
    var form = $(this);
    var data = new FormData($(this)[0]);
    var url = form.attr("action");
    $.ajax({
        /**********************
         * Ajaxを利用するときの基本情報
         ***********************/
        type: form.attr("method"),
        url: url,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        /**********************
         * 成功したと時の出力情報
         ***********************/
        success: function (data) {
            console.log(data);
            $(".is-invalid").removeClass("is-invalid");
            $(".error-br").remove();

            /**********************
             * 変数の設定
             ***********************/
            if (data.fail) {
                /**********************
                 * 配列の作成
                 ***********************/
                var array = [];

                /**********************
                 * 配列の作成
                 *
                 ***********************/
                for (var control in data.errors) {
                    var altControl = control.replace(/\./g, "-");
                    /**********************
                     * 配列の作成
                     * addClass()
                     ***********************/
                    $('input[name="' + control + '"]').addClass("is-invalid");
                    $('select[name="' + control + '"]').addClass("is-invalid");
                    $('textarea[name="' + control + '"]').addClass(
                        "is-invalid"
                    );
                    $("#" + altControl).addClass("is-invalid");
                    if (array.indexOf(data.errors[control][0]) == -1) {
                        //メッセージが重複せず存在しない
                        array.push(data.errors[control][0]);
                        if (altControl.match(/file/)) {
                            console.log("file");
                            $("#error-file-original")
                                .clone()
                                .attr("id", "error-" + altControl)
                                .appendTo("#error-expense-file");
                            $("#error-" + altControl)
                                .html(data.errors[control])
                                .after('<br class="error-br">');
                        }

                        $("#error-" + altControl).html(data.errors[control]);
                    }
                }
            } else {
                $("#modalForm").modal("hide");
                locationUrl = location.href;
                ajaxTableLoad(locationUrl, "result");
                Swal.fire("保存が完了しました", "", "success");
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
    });
    return false;
});


/*
    |--------------------------------------------------------------------------
    | 一覧画面の削除アイコンクリック時
    |--------------------------------------------------------------------------
    | 赤いボタンのマイナスの削除ボタンを押した時の処理
    |
    */

$(document).on("click", ".fa-trash", function (event) {
    /**********************
     * あ
     ***********************/
    var token = $('meta[name="csrf-token"]').attr("content");
    var url = $(this).data("url");
    /**********************
     * スイートアラートの設定内容
     *  title: "削除してよろしいですか？",   //タイトル文字
        text: "",  //テキストがあれば入力します
        type: "warning",  //４種類のタイプが存在します
        showCancelButton: true,  //キャンセルボタンを表示するか
        confirmButtonColor: "#3085d6",  //決定ボタンの色
        cancelButtonColor: "#d33",  //キャンセルボタンの色
        confirmButtonText: "OK",  //決定ボタンのメッセージ
     ***********************/
    Swal.fire({
        title: "削除してよろしいですか？",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "OK",
    }).then((result) => {
        /**********************
         * then()は成功した時の処理
         ***********************/
        if (result.value) {
            /**********************
             * あ
             ***********************/ Ï;
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            /**********************
             * あ
             ***********************/
            $.ajax({
                /**********************
                 * あ
                 ***********************/
                type: "POST",
                data: { _method: "DELETE", _token: token },
                url: url,
                /**********************
                 * 成功した時の対応
                 ***********************/
                success: function (data) {
                    $("#modalDelete").modal("hide");
                    locationUrl = location.href;
                    ajaxTableLoad(locationUrl, "result");
                    Swal.fire("削除が完了しました", "", "success");
                },
                /**********************
                 * 失敗した時の対応
                 ***********************/
                error: function (xhr, status, error) {
                    alert(xhr.responseText);
                },
            });
        }
    });
});

/*
    |--------------------------------------------------------------------------
    | あ
    |--------------------------------------------------------------------------
    |
    |
    */

/**
 * modal 読込処理
 * @param {type} filename
 * @param {type} content
 * @returns {undefined}
 */
function ajaxLoad(filename, content) {
    content = typeof content !== "undefined" ? content : "content";
    $(".loading").show();
    $.ajax({
        type: "GET",
        url: filename,
        contentType: false,
        success: function (data) {
            $("#" + content).html(data);
            $(".loading").hide();
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        },
    });
}

/*
    |--------------------------------------------------------------------------
    | あ
    |--------------------------------------------------------------------------
    |
    |
    */

/**
 * データ更新後（Ajax通信後）にテーブル内のHTMLを置換して最新データを表示
 * @param {type} filename
 * @param {type} content
 * @returns {undefined}
 */
function ajaxTableLoad(filename, content) {
    content = typeof content !== "undefined" ? content : "content";
    $(".loading").show();

    $.ajax({
        type: "GET",
        url: filename,
        contentType: false,
        success: function (data) {
            $("#result").html($(data).find("#result").html());
            history.pushState("", "", filename);
            $(".loading").hide();
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        },
    });
}

$("#modalForm").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    ajaxLoad(button.data("href"), "modal_content");

    //iOSの場合のみモーダルの背景のスクロールを抑制するクラスを付与
    var ua = navigator.userAgent;
    if (ua.indexOf("iPhone") > 0 || ua.indexOf("iPad") > 0) {
        $("body").addClass("ios-showing-modal");
    }
});
$("#modalDelete").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    $("#delete_id").val(button.data("id"));
    $("#delete_token").val(button.data("token"));
});
$("#modalForm").on("shown.bs.modal", function () {
    $("#focus").trigger("focus");
});

//モーダルが閉じるときのイベント
$("#modalForm").on("hidden.bs.modal", function () {
    //iOSならモーダルのスクロール抑制のクラスを解除する
    var ua = navigator.userAgent;
    if (ua.indexOf("iPhone") > 0 || ua.indexOf("iPad") > 0) {
        $("body").removeClass("ios-showing-modal");
    }
});
/*
    |--------------------------------------------------------------------------
    |
    |--------------------------------------------------------------------------
    |
    |
    */

$(function () {
    var dateFormat = "YYYY/MM/DD";
    moment.locale("ja"); //日本語化
    //精算管理(1-3)
    $("#date-range1")
        .daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: dateFormat,
                applyLabel: "反映",
                cancelLabel: "取消",
                fromLabel: "開始日",
                toLabel: "終了日",
                customRangeLabel: "指定",
            },
            ranges: {
                本日: [moment(), moment()],
                昨日: [
                    moment().subtract("days", 1),
                    moment().subtract("days", 1),
                ],
                今月: [moment().startOf("month"), moment().endOf("month")],
                先月: [
                    moment().subtract("month", 1).startOf("month"),
                    moment().subtract("month", 1).endOf("month"),
                ],
                直近10日: [moment().subtract("days", 9), moment()],
                直近30日: [moment().subtract("days", 29), moment()],
                直近90日: [moment().subtract("days", 89), moment()],
            },
        })
        .attr("autocomplete", "off");
    $("#date-range1").on("apply.daterangepicker", function (ev, picker) {
        $(this).val(
            picker.startDate.format(dateFormat) +
                " - " +
                picker.endDate.format(dateFormat)
        );
    });
    $("#date-range1").on("cancel.daterangepicker", function (ev, picker) {
        $(this).val("");
    });
});

/*
    |--------------------------------------------------------------------------
    | デートピッカーの設定
    |--------------------------------------------------------------------------
    |
    |
    */
$(function () {
    //モーダルでのdatepicker設定
    $(".date-picker").each(function () {
        $(this).datepicker({
            dateFormat: "yy/mm/dd",
            onSelect: function (selected_date) {
                //指定のinputnameに値を代入
                var name = $("#ui-datepicker-div").attr("data-name");
                $('input[name="' + name + '"]').val(selected_date);
            },
        });
    });

    //表示高さ調整
    $(".date-picker").on("click", function () {
        //クリックした高さを取得

        /**********************
         * 変数の定義
         *
         * ***********************/
        var top = $("#ui-datepicker-div").css("top");
        top = parseInt(top);
        var scrollTop = $(document).scrollTop();
        var modaltop = top - scrollTop;
        //トップを書き換える
        $("#ui-datepicker-div").css({
            top: modaltop + "px",
        });
        /**********************
         * data追加
         * メソッドチェーンで考える
         *
         * ***********************/
        $("#ui-datepicker-div").attr("data-name", $(this).attr("name"));
    });
});
/*
    |--------------------------------------------------------------------------
    | 各申請画面  明細行追加ボタン
    |--------------------------------------------------------------------------
    |
    | プラスボタンの設定はこちらに記述
    |
    |
    */
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 各申請画面 明細行追加ボタン
 *
 * 機能解説
 * .click（）
 * closest("table")
 * .attr("id")
 * addRow(id);
 *
 */
$(".row-add-btn").click(function (event) {
    /**********************
     * 変数の定義
     * 親<table>のIDを取得
     ************************/
    var id = $(this).closest("table").attr("id"); //親項目のIDを取得
    /**********************
     * 変数を使った実行内容
     * 行を追加する
     ************************/

    addRow(id);
});

/*
    |--------------------------------------------------------------------------
    | 各申請画面  明細行追加ボタン
    |--------------------------------------------------------------------------
    |
    | プラスボタンの設定はこちらに記述
    |
    |
    */
/**
 * 今日の日付を返します
 */
function getNowYMD() {
    var dt = new Date();
    var y = dt.getFullYear();
    var m = ("00" + (dt.getMonth() + 1)).slice(-2);
    var d = ("00" + dt.getDate()).slice(-2);
    var result = y + "/" + m + "/" + d;
    return result;
}


/*
    |--------------------------------------------------------------------------
    | あ
    |--------------------------------------------------------------------------
    |
    |
    */

/**
 * npmビルドの影響で他jsファイルを読み込めない為、こちらにも記述
 * @param {*} obj
 */
let mainDateFormat = "yy/mm/dd";
function setDatepickerForObj(obj) {
    $(obj).datepicker({
        dateFormat: mainDateFormat,
        closeText: "閉じる",
        prevText: "&#x3C;前",
        nextText: "次&#x3E;",
        currentText: "今日",
        monthNames: [
            "1月",
            "2月",
            "3月",
            "4月",
            "5月",
            "6月",
            "7月",
            "8月",
            "9月",
            "10月",
            "11月",
            "12月",
        ],
        monthNamesShort: [
            "1月",
            "2月",
            "3月",
            "4月",
            "5月",
            "6月",
            "7月",
            "8月",
            "9月",
            "10月",
            "11月",
            "12月",
        ],
        dayNames: [
            "日曜日",
            "月曜日",
            "火曜日",
            "水曜日",
            "木曜日",
            "金曜日",
            "土曜日",
        ],
        dayNamesShort: ["日", "月", "火", "水", "木", "金", "土"],
        dayNamesMin: ["日", "月", "火", "水", "木", "金", "土"],
        weekHeader: "週",
        isRTL: false,
        // showMonthAfterYear: true,
        yearSuffix: "年",
        firstDay: 1, // 週の初めは月曜
        showButtonPanel: false, // "今日"ボタン, "閉じる"ボタンを表示する
    });
}

/*
    |--------------------------------------------------------------------------
    | リストの申請行を追加する
    |--------------------------------------------------------------------------
    |
    |
    */

/*
 * 各申請行追加
 * @param {type} id
 * @returns {undefined}
 */
function addRow(id) {
    //新規行を複製
    $("#" + id + " tbody tr:last-child")
        .clone(true)
        .appendTo("#" + id + " tbody");
    $("#" + id + " tbody tr:last-child td select").val("");
    $("#" + id + " tbody tr:last-child td input").val("");
    var name = $("#" + id + " tbody tr:last-child td")
        .children()
        .attr("name");
    var num =
        parseInt(name.substring(name.indexOf("[") + 1, name.indexOf("]"))) + 1;
    var newName = "";
    //td内のinput名称変更
    $("#" + id + " tbody tr:last-child td").each(function () {
        newName = $(this).children().attr("name");
        if (newName) {
            newName = newName.replace(/\[[0-9]+\]/, "[" + num + "]");
            $(this).children().attr("name", newName);
        }
        if ($(this).children().hasClass("date-picker")) {
            var datepickerId = $(this).children().attr("id");
            if (datepickerId !== undefined) {
                var newDatepickerId = datepickerId.replace(
                    /^(.*)\d$/,
                    "$1" + num
                );
                $(this).children().attr("id", newDatepickerId);
            }

            $(this)
                .children()
                .removeClass("hasDatepicker")
                .removeData("datepicker")
                .unbind();
            setDatepickerForObj($(this).children());
            $(this).children().val = getNowYMD();
        }
    });
    var row =
        parseInt($("#" + id + " tbody tr:last-child").attr("data-row")) + 1;
    $("#" + id + " tbody tr:last-child").attr("data-row", row);
}

/*
    |--------------------------------------------------------------------------
    | 新規登録の行 削除ボタン
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */
/**
 *
 */
$(document).on("click", ".row-remove-btn", function (event) {
    var row = $(this).parents(".table tbody").children().length;
    var dataRow = $(this).closest("tr").data("row");
    if (row <= 1) {
        Swal.fire({
            type: "warning",
            title: "これ以上削除出来ません",
            text: "",
        });
        return;
    }
    Swal.fire({
        title: "削除してよろしいですか？",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "OK",
    }).then((result) => {
        if (result.value) {
            $("#del-id").append(
                '<input type="hidden" name="deleteId[]" value="' +
                    dataRow +
                    '">'
            );
            Swal.fire("削除しました", "", "success");
            $(this).parent().parent().remove();
        }
    });
});

/*
    |--------------------------------------------------------------------------
    | 半角数字チェック
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */

/**
 * @param {type} val
 * @returns {undefined}
 */
function intCheck(val) {
    if (!val.match(/^[0-9]+$/)) {
        Swal.fire({
            type: "warning",
            title: "距離、日数、金額は半角数字を入力してください。",
            text: "",
        });
        return false;
    }
    return true;
}

/*
    |--------------------------------------------------------------------------
    | クリアボタン
    |--------------------------------------------------------------------------
    |
    | CSSでセットした（".reset-form"）をクリックするとフォームの中の属性に処理をかける
    |
    |
    */

$(".reset-form").on("click", function () {
    $(this.form)
        .find("textarea, :text, select")
        .val("")
        .end()
        .find(":checked")
        .prop("checked", false);
});
/*
    |--------------------------------------------------------------------------
    |
    |--------------------------------------------------------------------------
    |
    |
    */
