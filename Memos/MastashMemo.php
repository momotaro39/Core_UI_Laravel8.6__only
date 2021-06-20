<?php


/*
|--------------------------------------------------------------------------
| 基本の設定  参照先 https://qiita.com/shunsuke_takahashi/items/8f697d1bbf6ba3902ca2
|--------------------------------------------------------------------------
|
| マスタッシュ記号が重複する場合の対応
|
|
*/



/******************************************
 *      bladeでのdataの出力では"{{}}"が使用されており、bladeテンプレート内で普通にmustache記法を使用すると競合してしまい、ErrorExceptionになるか、予測していない情報が表示されたりしてしまいます。ということで2つ対応方法をご紹介。
 *
 *     vue.jsのmustache記法に使用する記号を変更する（"{{}}"を別の記号にする）
 *    vue.jsドキュメントGlobal APIのVue.configに"delimiters" という設定があります。
 ****************************************** */




    Vue.config
    {
      // print stack trace for warnings?
      debug: true,
      // attribute prefix for directives
      prefix: 'v-',
      // interpolation delimiters
      // for HTML interpolations, add
      // 1 extra outer-most character.
      delimiters: ['{{', '}}'],   //ここが変更ポイントです
      // suppress warnings?
      silent: false,
      // interpolate mustache bindings?
      interpolate: true,
      // use async updates (for directives & watchers)?
      async: true,
      // allow altering observed Array's prototype chain?
      proto: true
    }

/******************************************
 *  見本
 *    "{{}}"を"(%%)"の様な書き方に変更する場合
 *    "new Vue()"を実行する前にconfigを変更する。
 ****************************************** */



    example.js
    Vue.config.delimiters = ['(%', '%)'];
    var vm = new Vue({
        el: '#myApp',
        data: {
            username: 'hogehoge'
        }
    });



    example.blade.php


    <div id="myApp">
        <p>(%username%)</p>
    </div>

    /******************************************
     *       blade template内で"{{}}"の前に"@"を付ける
     *
     *     blade templateでは"{{}}"の前に"@"を付けると"{{}}"をそのまま出力することができる。
     *
     *     Blade Templatingの「Displaying Raw Text With Curly Braces」に書かれています。
    ****************************************** */


    example.blade.php

    @{{ This will not be processed by Blade }}

    こうすれば"{{}}"が直接出力されるのでErrorExceptionは発生しません。