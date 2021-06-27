<?php


/*
|--------------------------------------------------------------------------
| 基本の設定  参照はこちら https://biz.addisteria.com/laravel8_pagination/
|--------------------------------------------------------------------------
|
| 順番にそって、段落をわけていきます。
|
|
*/



    /****************************************** */
    /*  メソッド定義
    /****************************************** */


/*
|--------------------------------------------------------------------------
| ①コントローラーでpaginate関数を使う  参照はこちら https://biz.addisteria.com/laravel8_pagination/
|--------------------------------------------------------------------------
|
| 順番にそって、段落をわけていきます。
|
|
*/




    // ページネーションを表示したいビューのコントローラの一番上に、次のuse宣言を入れておきます。

    use Illuminate\Pagination\Paginator;

    // ページネーションを入れる部分には、次のように記述します。

    $変数名＝クラス名::paginate(１ページの件数);


    // 〇〇クラスを使い、１ページに10件ずつ表示したい場合には、次のようにします。
// ページねーとの数字はコンストファイルに設定します

            // $inquiries=〇〇::paginate(10);

            $paginations = 〇〇::paginate(config('const.paginate.other'));

/*
|--------------------------------------------------------------------------
| ②ビューファイルに記述  参照はこちら https://biz.addisteria.com/laravel8_pagination/
|--------------------------------------------------------------------------
|
| 基本の書き方
| {{ 変数名（複数形）->links() }}
|
*/


    // ビューファイルには、リストの下に次のように入れます。

        {{ $inquiries->links() }}



/*
|--------------------------------------------------------------------------
| ③pagination用のCSSを入れる 参照はこちら https://biz.addisteria.com/laravel8_pagination/
|--------------------------------------------------------------------------
|
| venderフォルダにCSSを導入
| resources/views の中に venderフォルダができます。
| そのなかにpagination用のファイルが入っています。
|
*/


    コマンドを入力

    php artisan vendor:publish --tag=laravel-pagination






/*
|--------------------------------------------------------------------------
| ④Bootstrap使用を設定  参照はこちら https://biz.addisteria.com/laravel8_pagination/
|--------------------------------------------------------------------------
|
| 順番にそって、段落をわけていきます。
|
|
*/

    // 注意事項
    // デフォルトのCSSフレームワークが変わったためLaravel８の場合は一手間加えます。
    // ※Laravel7まではBootstrapがデフォルトでしたが、Laravel8からはTailwindがデフォルトになりました。

/*
|--------------------------------------------------------------------------
| ④Bootstrap使用を設定  Laravel８  参照はこちら https://biz.addisteria.com/laravel8_pagination/
|--------------------------------------------------------------------------
|
| Bootstrap4を使用する場合
| app/Providers AppServiceProvider.php を編集
|
*/




    ファイルの上部に下記のuse宣言を追加します。

    use Illuminate\Pagination\Paginator;



    bootに次のように記述します。

    public function boot {
        Paginator::useBootstrap();
    }



/*
|--------------------------------------------------------------------------
|  色々カスタマイズしたい場合 参照はこちら https://biz.addisteria.com/laravel8_pagination/
|--------------------------------------------------------------------------
|
| 公式マニュアルの下のほうにあるページネーション情報も参考にしてください。
| https://readouble.com/laravel/8.x/ja/pagination.html
|
*/