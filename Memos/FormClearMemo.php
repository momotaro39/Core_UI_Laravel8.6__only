<?php


/*
|--------------------------------------------------------------------------
| フォームをクリア・空にする方法。  参照先  https://web-tsuku.life/input-text-form-clear/
|--------------------------------------------------------------------------
|
| フォームをクリア・空にする方法。input・textarea【Javascript】
|
*/



/********************************************
 * ボタンクリックでクリアする方法
 * 「クリア」ボタンをクリックすると、イベントが発火
 * フォームの内容がクリアされます。
 * ******************************************/

// わかりやすいようにテキストとテキストエリアを分けました

function clearText() {
    // セレクタ form1 の情報を変数にセット
	var textForm = document.getElementById("form1");
    // 変数のテキストを空にする
  textForm.value = '';
}

function clearTextarea() {
    // セレクタ form2 の情報を変数にセット
	var textareaForm = document.getElementById("form2");
    // 変数のテキストを空にする
  textareaForm.value = '';
}



/********************************************
 * ビュー画面
 *
 * idを必ず指定すること
 * <input type="text" id="form1">
 * <textarea id="form2"
 *
 * イベントが発火
 * onclick="clearText()"
 * onclick="clearTextarea()"
 *
 * ******************************************/
<div>
<input type="text" id="form1"><input type="button" value="クリア" onclick="clearText()" />
</div>

<div>
<textarea id="form2" cols="30" rows="4"></textarea><input type="button"  value="クリア" onclick="clearTextarea()" />
</div>
value='';でinputやtextareaのフォームに入力されている内容をクリアしています。

/********************************************
 * 誤情報入力でクリアする方法
 * 続いて、誤ったコードを入力したら、入力内容がクリアされ、エラーが表示されるという方法です。
 *
 * ここでは3文字を超えて入力した場合にエラーが表示されるようにしました。
 * ******************************************/



イベントの発火はonchange

// onchangeの意味は「フィームの値を入力後、フォーカスを外したタイミング」です



function errorCheck() {
// セレクタ form の情報を変数にセット
	var form = document.getElementById('form');


    // idがformの文字を打つ場所（value）の文字カウント(length)が3以上の場合
  if ( form.value.length > 3 ) {
    //   実行する内容を記述
//  idがformの文字を打つ場所（value）を空にする
  	form.value = '';

//  idがerrorTextの文字を打つ場所（errorText）の中身に追加する（innerHTML）
    document.getElementById('errorText').innerHTML = '3文字以内で入力してください。';
  }
}


/********************************************
 * ビュー画面
 *
 * idを必ず指定すること
 * <input type="text" id="form">

 *
 * イベントが発火
 * onchange="errorCheck()
 *
 * 表示する場所をPタグで設定する
 * <p id="errorText"></p>
 *
 * ******************************************/
<input type="text" id="form" onchange="errorCheck()">
<p id="errorText"></p>


/********************************************
 * 誤情報入力でリアルタイムにクリアする方法
 *
 * onchangeではなく、onkeyupを使うと、3文字を超えた瞬間に
 * 即座にエラーメッセージが表示されます。
 *
 * イベントを変えてあげるだけで変化する
 * ******************************************/


function errorCheck() {
	var form = document.getElementById('form');
  if ( form.value.length > 3 ) {
  	form.value = '';
    document.getElementById('errorText').innerHTML = '3文字以内で入力してください。';
  }
}


<input type="text" id="form" onkeyup="errorCheck()">
<p id="errorText"></p>

onkeyupについては、以下の記事で解説しています。

≫onkeydown、onkeypress、onkeyupの動作の違い【JavaScript】
≫フォームの入力テキストをリアルタイムに取得するonkeyup【JavaScript】
フォーム関連については以下の記事で解説しています。

≫追加や削除のできるフォームを作る【appendChild・removeChild】
≫appendChildでフォームを追加する
≫選択項目によって他の選択肢を非表示にするフォーム
≫数値のみ・桁数制限ありの入力フォームを作る
≫【初心者向け】inputフォームの入力文字数チェック

/*
|--------------------------------------------------------------------------
| 【jQuery】formの入力を一括削除  参照先  https://qiita.com/ntm718/items/da6c9f167ddcd217d4bd
|--------------------------------------------------------------------------
|
| form内に複数の入力エリアがあり、
| ボタンを押したら、一括ですべての入力欄の内容が消えるようにしたいと思います。
|
*/



/********************************************
 * メソッド定義
 * ******************************************/



index.html

<form method="post" action="#">
  <input type="text" name="text">
  <input type="password" name="pass">
  <input type="number" name="num">
  <button type="button" id="del-btn">入力削除</button>
</form>

deleteAll.js

$(function()
{
  $('#del-btn').on('click',function()
  {
    $(this).parent('form').find(':text').val("");
    $(this).parent('form').find(':password').val("");
    $(this).parent('form').find('input[type="number"]').val("");
  });
});

/********************************************
 * 解説
 * 削除ボタンが押されたら、
 * 自身の親要素のformの中から、inputのそれぞれ探して,valを書き換えています。
 * $(this).parent('form')としているのは、複数formがある時対策です。
 * この構造の場合findじゃなくて、children()でも良いです。
 * ******************************************/


formが一つであるなら、

deleteAll.js

$(function()
{
  $('#del-btn').on('click',function()
  {
    $('form').find(':text').val("");
    $('form').find(':password').val("");
    $('form').find('input[type="number"]').val("");
  });
});

/********************************************
 * メソッドチェーンを使って繋げることも出来ます。
 * ******************************************/


index.html

$(function()
{
  $('#del-btn').on('click',function()
  {
    $('form').find(':text').val("").end()
        .find(':password').val("").end()
            .find('input[type="number"]').val("");
  });
});


/*
|--------------------------------------------------------------------------
| 【JavaScript】 resetメソッドを使ってフォームをリセット  参照先  https://pikawaka.com/javascript/form-reset
|--------------------------------------------------------------------------
| 【JavaScript】 resetメソッドを使ってフォームをリセットしよう
|
|
|
*/



/********************************************
 * メソッド定義
 * ******************************************/

<form name="フォームの名前">
  <inputタグでフォームを作成>
  <button type="button" onclick="リセットする関数の名前">リセットするボタン名</button>
</form>


上記のようなフォームがある場合は、以下のようにjavascriptファイル内にてresetメソッドを使用してフォームの内容をリセットすることが出来ます。

javascript | resetメソッドの使用例 -->


function リセットする関数の名前 {
  document.フォームの名前.reset();
}

/********************************************
 * resetメソッド
 * ******************************************/



html | 具体的な書き方の例

<script>
function formReset() {
  document.sampleform.reset();
}
</script>


<form name="sampleform">
  <p>名前：<input type="text" name="name"></p>
  <p>年齢：<input type="text" name="age"></p>
  <p><button type="button" onclick="formReset()">リセット</button></p>
</form>




resetメソッド

ポイントはリセットボタンを押すとformResetメソッド内に記述されたresetメソッドが実行され、
フォーム内の全ての内容が一度に初期値に戻るという点です。

/********************************************
 * resetメソッド
 * 初期値があらかじめ設定してある場合
 * ******************************************/


下の例のように初期値を設定しておきます。

html | 初期値の設定
<script>
  function formReset2() {
    document.sampleform2.reset();
  }
</script>

<form name="sampleform2">
  <p>名前：<input type="text" name="name" value="ピカわか"></p>
  <p>年齢：<input type="text" name="age" value="25"></p>
  <p><button type="button" onclick="formReset2()">リセット</button></p>
</form>



値が空になるのではなく、初期値の「ピカわか」と「25」に戻ったのが確認できたかと思います。
/********************************************
 * メソッド定義
 * ******************************************/
初期値にリセット

このようにリセットメソッドは値を空にするメソッドではなく、初期値に戻すメソッドであることがポイントです。


/********************************************
 * jQueryでresetメソッドを使う場合
 * resetメソッドはjQueryでも使うことができます。
 * ******************************************/


  $('セレクタ名')[0].reset();


html | 具体的な書き方の例

#jQueryを使うための記述
<script src="https://code.jquery.com/jquery-3.4.1.js"  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="  crossorigin="anonymous"></script>


#javascriptの記述
<script>
$(function() {
  $('#formreset').click(function(){
    $('#sampleform')[0].reset();
   });
 });
</script>


#フォームを表示するための記述
<form id="sampleform">
  <p>名前：<input type="text" name="name"></p>
  <p>年齢：<input type="text" name="age"></p>
  <p><button type="button" id="formreset">リセット</button></p>
</form>

/********************************************
 * inputタグでの書き方
 * resetメソッドを使わず、inputタグだけで書く方法もあります。
 * ******************************************/



html | inputタグでの書き方

<input type="reset" value="リセット">
このように「type="reset"」と記述します。


html | 書き方の例

<form>
  <p>名前：<input type="text" name="name"></p>
  <p>年齢：<input type="text" name="age"></p>
  <p><input type="reset" value="リセット"></p>
</form>



このようにresetメソッドの時と同じように値が初期値に戻ることが確認できるはずです。

/********************************************
 * resetメソッド
 * ******************************************/


val('')との違い
jQueryのメソッドにval()メソッドがあります。
これはvalue属性を取得・変更・設定することができるメソッドです。


resetメソッドは初期値に戻すメソッドでしたが、val()メソッドは初期値に値があらかじめ値が入っていても空欄の状態にすることができます。

val()メソッドの使い方
val()メソッドは下記のように使用します。

javascript | val()メソッドの書き方 -->
#値を取得するとき
$("セレクタ名").val();

#値を空にするとき
 $("セレクタ名").val("");
具体的には下記のように記述します。



html | val("")の使い方

#jQueryを使うための記述
<script src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>

#javascriptの記述
<script>
$(function() {
  $("#resetButton").click(function() {
    $('#textField').val("");
    $('#imageField').val("");
  });
});
</script>

#フォームを表示するための記述
<form>
  <p><input type="text" name="text" id="textField"></p>
  <p><input type="file" name="image" id="imageField"></p>
  <p><button type="button" id="resetButton">リセット</button></p>
</form>
上のコードで下記のフォームが表示されます。
何か入力し、リセットボタンを押すと値が空になります。



リセット

空になったのが確認できたと思います。
ただどちらのフォームにも「val("")」を書かなくてはならないので面倒ですよね。
上の例だと2つのフォームでしたが、これが10個とかになると非常にコードが長くなってしまいます。

ですので、一括でフォームの内容を初期値に戻したいときにはresetメソッドを使うようにしましょう。

この記事のまとめ

resetメソッドはフォームの内容をリセットするときに使うメソッドだよ
resetメソッドは値を空にするのではなく初期値に戻す
val("")メソッドは値を空にすることができるが、一つ一つのフォームに定義する必要があるので、全てのフォームをリセットしたい場合はresetメソッドを使おう！

