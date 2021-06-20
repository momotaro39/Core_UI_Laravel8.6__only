<?php


/*
|--------------------------------------------------------------------------
| appendChildでフォームを追加する【JavaScript】  参照先 https://web-tsuku.life/add-form-appendchild/
|--------------------------------------------------------------------------
|
| ToDoリストやお問い合わせフォームなどでもたまに見かける機能
|
|
*/



/********************************************
 * 「フォーム追加」というボタンをクリックするとイベントが発火
 *
 * createElement()によってinput要素が生成
 * appendChild()によって要素追加される
 *
 * というプログラムを作ってみます。
 *
 *
 * createElement()でinputを生成、
 * typeとid、placeholder情報を加えて、
 * appendChild()で要素を追加
 * ※変数iをカウントアップすることで、id名とplaceholder名が連番で追加される。
 * ******************************************/





var i = 1 ;

function addForm() {

  var input_data = document.createElement('input');
  input_data.type = 'text';
  input_data.id = 'inputform_' + i;
  input_data.placeholder = 'フォーム-' + i;

  var parent = document.getElementById('form_area');

  parent.appendChild(input_data);
  i++ ;
}

# view

<div id="form_area">
  <input type="text" id="inputform_0" placeholder="フォーム-0">
</div>
<input type="button" value="フォーム追加" onclick="addForm()">



appendChild()とcreateElement()が重要なポイントです。

この2つについて詳しくは「appendChild()の使い方とデモ」と「createElement()の使い方とデモ」で解説しています。


/*
|--------------------------------------------------------------------------
| 追加や削除のできるフォームを作る【appendChild・removeChild】【JavaScript】  参照先 https://web-tsuku.life/add-delete-form/
|--------------------------------------------------------------------------
|
|
|
|
*/



/********************************************
 * メソッド定義
 * ******************************************/

フォームの追加をクリックすると、新しくフォームと削除ボタンが追加されます。

削除ボタンをクリックすると、セットのフォームも削除されます。


サンプルコード
inputとbuttonをcreateElement()で生成し、appendChild()で要素を追加しています。

フォームの削除は、buttonのonclickイベントにて発火します。

削除対象に該当するidのフォームとボタンをremoveChild()によって削除しています。

var i = 1 ;
function addForm() {
  var input_data = document.createElement('input');
  input_data.type = 'text';
  input_data.id = 'inputform_' + i;
  input_data.placeholder = 'フォーム-' + i;
  var parent = document.getElementById('form_area');
  parent.appendChild(input_data);

  var button_data = document.createElement('button');
  button_data.id = i;
  button_data.onclick = function(){deleteBtn(this);}
  button_data.innerHTML = '削除';
  var input_area = document.getElementById(input_data.id);
  parent.appendChild(button_data);

  i++ ;
}

function deleteBtn(target) {
  var target_id = target.id;
  var parent = document.getElementById('form_area');
  var ipt_id = document.getElementById('inputform_' + target_id);
  var tgt_id = document.getElementById(target_id);
  parent.removeChild(ipt_id);
  parent.removeChild(tgt_id);
}
<div id="form_area">
  <input type="text" id="inputform_0" placeholder="フォーム-0">
  <button id="0" onclick="deleteBtn(this)">削除</button>
</div>
<input type="button" value="フォーム追加" onclick="addForm()">
input {
  display: block;
  margin-top: 10px;
}
分かりづらいポイントは、cleateElement()でbuttonを生成した後、onclickに関数を追加する方法です。

下記のような書き方で追加できます。

var button_data = document.createElement('button');
button_data.onclick = function(){deleteBtn(this);}

以上、追加や削除のできるフォームを作るデモでした。

createElement()とappendChild()は必須ですね。それらについて詳しく知りたい方は、「appendChild()の使い方とデモ【JavaScript】」「createElement()の使い方・デモ【JavaScript】」をご覧ください。



/*
|--------------------------------------------------------------------------
| appendChild()の使い方とデモ【JavaScript】  参照先 https://web-tsuku.life/appendchild-javascript/
|--------------------------------------------------------------------------
| 予備知識
|
|
|
*/



/********************************************
 * メソッド定義
 * ******************************************/


appendChild()の使い方
appendChild()は、特定の親ノードの子ノードリストの末尾にノードを追加します。

親要素.appendChild(追加したい要素)という形で使われます。

親要素の末尾に要素が追加されなす。

例えばulタグにliタグを追加する場合、下記のようにulタグの末尾に追加されます。


appendChild()を使ったデモ
デモ
親要素がul、追加したい要素をliというデモです。

フォームに入力したテキストが、liとして追加されます。

onchangeで入力欄を変更するとイベントが発火します。


サンプルコード
createElementで生成されたli要素に、入力テキストがセットされ、appendChildによってulタグ末尾に追加されます。

function addList() {
  var input_area = document.getElementById('input_form');
  var input_value = input_area.value;
  var li_text = document.createElement('li');
  li_text.innerHTML = input_value;

  var parent = document.getElementById('frame');
  parent.appendChild(li_text);
  input_area.value = '';
}

function deleteList() {
  var parent = document.getElementById('frame');
  parent.innerHTML = '';
}
<span>ここに追加されます↓</span>
<ul id="frame">
</ul>
<input type="text" id="input_form" onchange="addList()">
<input type="button" value="削除" onclick="deleteList()">
li {
  margin-left: 10px;
}
input {
  display: block;
  margin-top: 5px;
}
#frame {
  background-color: #A9E2F3;
  padding: 10px;
  max-width: 300px;
}
似た動作をするものに、insertBefore()があります。

insertBefore()は特定の要素の直前に追加できることが特徴です。下記の記事で解説しています。

/*
|--------------------------------------------------------------------------
| createElement()の使い方・デモ【JavaScript】  参照先 https://web-tsuku.life/createelement/
|--------------------------------------------------------------------------
| 予備知識
|
|
|
*/



/********************************************
 * メソッド定義
 * ******************************************/

createElement()とは？使い方
createElement()メソッドは、document.createElement(tagName)といった形式で使われます。

引数のtagNameで指定されたHTML要素を生成します。

createElement()を使ったデモ
createElement()を使ったデモとサンプルコードです。

追加ボタンをクリックすると、イベントが発生します。

createElement()を使って、div要素を生成し、appendChild()で追加されています。（参考：「appendChildで要素の追加」）

デモ

サンプルコード
<input type="button" value="追加" onclick="createArea()">
<div id="parent">parentです。</div>
function createArea () {
  var area = document.createElement('div');
  area.id = 'blue';
  area.innerHTML = '追加された要素です。';
  var parent = document.getElementById('parent');
  parent.appendChild(area);
}
#parent {
  background-color: #eee;
  padding: 10px;
}
#blue {
  background-color: blue;
  padding: 10px;
  color: #fff;
}
createElement('div')だけでは要素が生成されるだけなので、生成した要素をappendChild()で追加しましょう。

補足：appendChildは末尾、insertBeforeは直前に追加
上記のデモではappendChild()は、末尾に追加しました。

その反対で、特定の要素の直前に追加したい場合には、insertBefore()を使います。
