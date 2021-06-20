<?php


/*
|--------------------------------------------------------------------------
| 【Laravel】GoogleMapsAPIを使って地図を表示させる方法（完全版）  参照先  https://zenn.dev/kota111/articles/a4272be51371c28b86a0
|--------------------------------------------------------------------------
|
|
|
|
*/



/********************************************
 * メソッド定義
 * ******************************************/



GoogleMapsAPIを使って地図を表示させる
2-1.GoogleMapsAPIの記述
まず、エディターで「maps」プロジェクトを開いてください。

今回は、わかりやすいように、resource/welcome.blade.php に地図を表示させていきます。

welcome.blade.phpには、初めからコードが書かれているので、このように一度きれいにしておきましょう。

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    中略
    </head>
    <body>
    // この中にあったものをすべて消しました
    </body>
</html>
きれいになったら、地図を描画する領域とその領域のサイズを指定します。

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    中略
    </head>
    <body>
	    <div id="map" style="height:500px"> //追加
	    </div>
    </body>
</html>
ここでは、<div></div>のなかに、地図を描画していきます。高さのみを500pxに指定しています。

つぎに、scriptタグ（javaScriptを使って）GoogleMapsのAPIを読み込みます

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    中略
    </head>
    <body>
	   <div id="map" style="height:500px">
	   </div>
	   <script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=[APIキーをここに入力]&callback=initMap" async defer>
	   </script>
</body>
</html>
<script></script>を追加しました。
中身は

scr属性でhttps://maps.googleapis.com/maps/api/js を指定
?language=ja → 言語を日本語に設定
&region=JP → 地域を日本に設定
key=[APIキーをここに入力] → APIキーを設定します。APIキー取得は、後ほど解説します。
&callback=initMap → APIを読み終わったあとに、initmapというcallback関数を実行します。あとで記述します。
async defer → 非同期でスクリプトを読み込むために必要です。
※<script>タグの中は長いですが、見やすくしようと、途中で改行すると、エラーがでるので、このまま記述してください。

2-2.APIキーの取得
APIキーを取得してきます。
APIキーはプロジェクトごとに発行されるので、プロジェクトを作って、APIキーを取得していきます

まず、検索エンジンにて「google api console」と検索します。
「Google Developer console」のページに行きます。


初めての方はこのページに遷移するので、チェックをつけて、「同意して実行」をクリック


このページに遷移したら、右の「プロジェクトの作成」クリック


プロジェクト名を入力します(私はzenn-testにしました)
場所は「組織なし」でいいと思います。


この画面に遷移します。
次に、APIライブラリを有効にします。
左の「ライブラリ」をクリック。


「MapsJavaScriptAPI」をクリックします（みなさまの環境によっては、この位置にないかもしれません。その場合は、スクロールして探してみてください）


「有効にする」をクリックします。


次にAPIキーを取得します。
左上のハンバーガーメニューから、「APIとサービス」→「認証情報」をクリックします


「認証情報を作成」→「APIキー」をクリックします。


すると、下記画像のようにAPIキーが取得できます。


このAPIキーを先程の

<script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=[APIキーをここに入力]&callback=initMap" async defer></script>
[APIキーをここに入力]ここにペーストします。

<script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyBrAYTpz9zu0CARYKdQNyYxjuICkA5ajI&callback=initMap" async defer>
</script>
※このコード上では、取得したキーとペーストしたキーが違いますが、みなさまは同じキーを貼り付けしてください。
これでAPIキーの取得は完了です。

2-3.JSファイルの記述
つぎは、
GoogleMapsAPI利用後にコールバック関数で呼ばれるinitMap関数を作っていきます。

今回は、jsファイルを別に作ってそこにinitMap関数を記述していきます。

まずはjsファイル作成しましょう。
maps/public/js/result.jsとします
（publicの下にjsディレクトリ作って、その中にresult.jsを作ります）

result.js

// googleMapsAPIを持ってくるときに,callback=initMapと記述しているため、initMap関数を作成
function initMap() {
    // welcome.blade.phpで描画領域を設定するときに、id=mapとしたため、その領域を取得し、mapに格納します。
    map = document.getElementById("map");
    // 東京タワーの緯度は35.6585769,経度は139.7454506と事前に調べておいた
    let tokyoTower = {lat: 35.6585769, lng: 139.7454506};
    // オプションを設定
    opt = {
        zoom: 13, //地図の縮尺を指定
        center: tokyoTower, //センターを東京タワーに指定
    };
    // 地図のインスタンスを作成します。第一引数にはマップを描画する領域、第二引数にはオプションを指定
    mapObj = new google.maps.Map(map, opt);
}
そしてwelcome.blade.phpに、今作ったresult.jsを表示させるために

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    中略
    </head>
    <body>
        <div id="map" style="height:500px">
	</div>
        <script src="{{ asset('/js/result.js') }}"></script> //追加
　　　　　<script src="https://maps.googleapis.com/maps/api/jslanguage=ja&region=JP&key=AIzaSyBrAYTpz9zu0CARYKdQNyYxjuICkA5ajI&callback=initMap" async defer>
	</script>
    </body>
</html>
<script src="{{ asset('/js/result.js') }}"></script>これを追加しました。
これにより、JSファイルを利用することができます。
※GoogleMapAPIの上においてください。

ここまでできたら、ブラウザを見ていきましょう。

「このページでは Google マップが正しく読み込まれませんでした。」と表示される場合は、請求先アカウントの設定が必要となります。


2-4.請求先アカウント設定
なぜ、請求先アカウントが必要なのか？
お支払い情報の確認
無料トライアルの登録時に、クレジット カードまたは他のお支払い方法を入力いただく必要があります。このお支払い情報は、次の目的で使用します。
本人確認を行うため。
お客様がロボットではないことを確認するため。
（GoogleCloudより)

また、

請求先アカウントを設定しただけでは、課金は有効になりません。有料アカウントにアップグレードすることで明示的に課金を有効にしない限り、課金されることはありません。
（GoogleCloudより)

とのことでした。
請求先アカウントを設定しただけでは、課金は有効にはならないようなので、安心して、作業を進められますね。（もし、心配な方は、GoogleConsoleについて調べてみてください ）

次は、請求先アカウントの設定方法です。

請求先アカウント設定方法
さきほどの
「Google Developer console」へ行き、左上のハンバーガーメニューから、「APIとサービス」→「お支払い」をクリックします。


「請求先アカウントをリンク」をクリックします。


「請求先アカウントを作成」をクリックします。


チェックをして、「続行」をクリック


必要情報を記入し、作成します。


ブラウザに戻り、ページのリロードをしましょう。
このように地図が表示されれば、とりあえずオッケーです。


3.東京タワーにピンを差す
3-1.マーカーの作成
最後に東京タワーにピンを刺しましょう。

result.jsにて

function initMap() {
    // welcome.blade.phpで描画領域を設定するときに、id=mapとしたため、その領域を取得し、mapに格納します。
    map = document.getElementById("map");
    // 東京タワーの緯度は35.6585769,経度は139.7454506と事前に調べておいた
    let tokyoTower = {lat: 35.6585769, lng: 139.7454506};
    // オプションを設定
    opt = {
        zoom: 13, //地図の縮尺を指定
        center: tokyoTower, //センターを東京タワーに指定
    };
    // 地図のインスタンスを作成します。第一引数にはマップを描画する領域、第二引数にはオプションを指定
    mapObj = new google.maps.Map(map, opt);

    // 追加
    marker = new google.maps.Marker({
        // ピンを差す位置を決めます。
        position: tokyoTower,
	// ピンを差すマップを決めます。
        map: mapObj,
	// ホバーしたときに「tokyotower」と表示されるようにします。
        title: 'tokyotower',
    });
}
ここでは、マーカーのインスタンスを作成してます。

このようにピンが刺されていれば、完成です。


ピンの上にホバーする（カーソルを乗せる）と「tokyotower」と表示されるはずです。

無事、Laravelのプロジェクト内に、GoogleMapsAPIを使って地図を表示させ、東京タワーにピンを差すことができました。

おわりに
GoogleMapsAPIを用いて、地図を描画する方法は以上となります。次回は現在地を取得して、ピンを差す方法を共有したいと思います。