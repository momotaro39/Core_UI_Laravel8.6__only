<?php

/*
|--------------------------------------------------------------------------
| モデルの場所追加
|--------------------------------------------------------------------------
|
| App\Http\Controllers が頭についてるっぽい
|
|
|
*/


use App\Http\Controllers\TodoController;



use \Band\Admin\UserRoleController;
use \Band\Admin\UserController;
use \Band\Admin\AlbumController;
use \Band\Admin\BandMemberController;
use \Band\Admin\BandGoodsController;
use \Band\Admin\EntryController;
use \Band\Admin\EventController;
use \Band\Admin\GoodsTypeController;
use \Band\Admin\GuestReservationController;
use \Band\Admin\LabelController;
use \Band\Admin\HallController;
use \Band\Admin\MusicController;
use \Band\Admin\MusicalInstrumentController;
use \Band\Admin\PerformanceListController;
use \Band\Admin\ProceedController;
use \Band\Admin\TicketController;


use \App\Http\Controllers\ApiSet\GitHubApiController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/tasks', function () {
    return view('app');
});




// CoreUI用ルーティング
Route::group(['middleware' => ['get.menu']], function () {
    Route::get('/', function () {
        return view('dashboard.homepage');
    });



    /*
|--------------------------------------------------------------------------
| 追加機能用ルーティング
|--------------------------------------------------------------------------
|
| サイドバーにも表示するために利用
| Axio編
|
*/

    // Api ランダムユーザー表示
    Route::get('/function/randomuser', 'RandomuserController@index');

    // QiitaApi 取得表示のみ
    Route::get('/function/qiita', 'QiitaApiPostController@index');
    // QiitaApi 取得投稿編
    Route::get('/function/qiita/create', function () {
        return view('QiitaApi.form');
    });
    Route::post('/function/qiita/send', 'QiitaApiPostController@send');




    //Api GitHubapi表示に使用
    Route::get('/function/github', [\App\Http\Controllers\ApiSet\GitHubApiController::class, 'index']);




    /************************ LINE *************************/
    // line webhook受取用
    Route::post('/function/line/callback', 'LineApiController@postWebhook');
    // line メッセージ送信用
    Route::get('/function/line/message/send', 'LineApiController@sendMessage');



    //Axios導入テスト フォルダとファイル名でビューファイルを指定
    Route::get('/function/users', function () {
        return view('axios.index');
    });
    //Axios導入テスト api用のファイルを確認のために配列を作成
    // Route::get('userapi', function () {
    //     return ['けん', 'サイトう', 'John', 'Lisa'];
    // });

    //Axios導入テスト api用のデータをデータベースから取得
    Route::get('userapi', function () {
        return App\User::all();
    });

    // Laravel_Excel追加
    // Route::get('excelusers', [ExcelUsersController::class, 'export']);

    /*
|--------------------------------------------------------------------------
| 追加機能用ルーティング
|--------------------------------------------------------------------------
|
| サイドバーにも表示するために利用
| user権限のみ
|
*/


    //user権限のみ
    Route::group(['middleware' => ['role:user']], function () {
        Route::get('/colors', function () {
            return view('dashboard.colors');
        });
        Route::get('/typography', function () {
            return view('dashboard.typography');
        });
        Route::get('/charts', function () {
            return view('dashboard.charts');
        });
        Route::get('/widgets', function () {
            return view('dashboard.widgets');
        });
        Route::get('/404', function () {
            return view('dashboard.404');
        });
        Route::get('/500', function () {
            return view('dashboard.500');
        });
        Route::prefix('base')->group(function () {
            Route::get('/breadcrumb', function () {
                return view('dashboard.base.breadcrumb');
            });
            Route::get('/cards', function () {
                return view('dashboard.base.cards');
            });
            Route::get('/carousel', function () {
                return view('dashboard.base.carousel');
            });
            Route::get('/collapse', function () {
                return view('dashboard.base.collapse');
            });

            Route::get('/forms', function () {
                return view('dashboard.base.forms');
            });
            Route::get('/jumbotron', function () {
                return view('dashboard.base.jumbotron');
            });
            Route::get('/list-group', function () {
                return view('dashboard.base.list-group');
            });
            Route::get('/navs', function () {
                return view('dashboard.base.navs');
            });

            Route::get('/pagination', function () {
                return view('dashboard.base.pagination');
            });
            Route::get('/popovers', function () {
                return view('dashboard.base.popovers');
            });
            Route::get('/progress', function () {
                return view('dashboard.base.progress');
            });
            Route::get('/scrollspy', function () {
                return view('dashboard.base.scrollspy');
            });

            Route::get('/switches', function () {
                return view('dashboard.base.switches');
            });
            Route::get('/tables', function () {
                return view('dashboard.base.tables');
            });
            Route::get('/tabs', function () {
                return view('dashboard.base.tabs');
            });
            Route::get('/tooltips', function () {
                return view('dashboard.base.tooltips');
            });
        });
        Route::prefix('buttons')->group(function () {
            Route::get('/buttons', function () {
                return view('dashboard.buttons.buttons');
            });
            Route::get('/button-group', function () {
                return view('dashboard.buttons.button-group');
            });
            Route::get('/dropdowns', function () {
                return view('dashboard.buttons.dropdowns');
            });
            Route::get('/brand-buttons', function () {
                return view('dashboard.buttons.brand-buttons');
            });
        });
        Route::prefix('icon')->group(function () {  // word: "icons" - not working as part of adress
            Route::get('/coreui-icons', function () {
                return view('dashboard.icons.coreui-icons');
            });
            Route::get('/flags', function () {
                return view('dashboard.icons.flags');
            });
            Route::get('/brands', function () {
                return view('dashboard.icons.brands');
            });
        });
        Route::prefix('notifications')->group(function () {
            Route::get('/alerts', function () {
                return view('dashboard.notifications.alerts');
            });
            Route::get('/badge', function () {
                return view('dashboard.notifications.badge');
            });
            Route::get('/modals', function () {
                return view('dashboard.notifications.modals');
            });
        });
        Route::resource('notes', 'NotesController');
    });
    Auth::routes();

    Route::resource('resource/{table}/resource', 'ResourceController')->names([
        'index'     => 'resource.index',
        'create'    => 'resource.create',
        'store'     => 'resource.store',
        'show'      => 'resource.show',
        'edit'      => 'resource.edit',
        'update'    => 'resource.update',
        'destroy'   => 'resource.destroy'
    ]);


    // 管理者権限のみ
    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('bread',  'BreadController');   //create BREAD (resource)
        Route::resource('users', 'UsersController')->except(['create', 'store']);
        Route::resource('roles',        'RolesController');
        Route::resource('mail',        'MailController');
        Route::get('prepareSend/{id}',        'MailController@prepareSend')->name('prepareSend');
        Route::post('mailSend/{id}',        'MailController@send')->name('mailSend');
        Route::get('/roles/move/move-up',      'RolesController@moveUp')->name('roles.up');
        Route::get('/roles/move/move-down',    'RolesController@moveDown')->name('roles.down');
        Route::prefix('menu/element')->group(function () {
            Route::get('/',             'MenuElementController@index')->name('menu.index');
            Route::get('/move-up',      'MenuElementController@moveUp')->name('menu.up');
            Route::get('/move-down',    'MenuElementController@moveDown')->name('menu.down');
            Route::get('/create',       'MenuElementController@create')->name('menu.create');
            Route::post('/store',       'MenuElementController@store')->name('menu.store');
            Route::get('/get-parents',  'MenuElementController@getParents');
            Route::get('/edit',         'MenuElementController@edit')->name('menu.edit');
            Route::post('/update',      'MenuElementController@update')->name('menu.update');
            Route::get('/show',         'MenuElementController@show')->name('menu.show');
            Route::get('/delete',       'MenuElementController@delete')->name('menu.delete');
        });
        Route::prefix('menu/menu')->group(function () {
            Route::get('/',         'MenuController@index')->name('menu.menu.index');
            Route::get('/create',   'MenuController@create')->name('menu.menu.create');
            Route::post('/store',   'MenuController@store')->name('menu.menu.store');
            Route::get('/edit',     'MenuController@edit')->name('menu.menu.edit');
            Route::post('/update',  'MenuController@update')->name('menu.menu.update');
            Route::get('/delete',   'MenuController@delete')->name('menu.menu.delete');
        });
        Route::prefix('media')->group(function () {
            Route::get('/',                 'MediaController@index')->name('media.folder.index');
            Route::get('/folder/store',     'MediaController@folderAdd')->name('media.folder.add');
            Route::post('/folder/update',   'MediaController@folderUpdate')->name('media.folder.update');
            Route::get('/folder',           'MediaController@folder')->name('media.folder');
            Route::post('/folder/move',     'MediaController@folderMove')->name('media.folder.move');
            Route::post('/folder/delete',   'MediaController@folderDelete')->name('media.folder.delete');;

            Route::post('/file/store',      'MediaController@fileAdd')->name('media.file.add');
            Route::get('/file',             'MediaController@file');
            Route::post('/file/delete',     'MediaController@fileDelete')->name('media.file.delete');
            Route::post('/file/update',     'MediaController@fileUpdate')->name('media.file.update');
            Route::post('/file/move',       'MediaController@fileMove')->name('media.file.move');
            Route::post('/file/cropp',      'MediaController@cropp');
            Route::get('/file/copy',        'MediaController@fileCopy')->name('media.file.copy');
        });



        // グループルーティング始まり

        Route::prefix('core')->group(function () {


            // TOP画面

            Route::get('/top_page', [\App\Http\Controllers\TopPageController::class, 'top_page'])->name('top_page');


            // システム役割
            // メソッドがひとつの_invokeメソッドの場合のルーティング
            // Route::get('/roles', '\App\Http\Controllers\RoleController')->name('役割一覧')->middleware('auth');
            Route::get('/adminroles', '\App\Http\Controllers\Band\Admin\AdminRoleController')->name('管理役割一覧')->middleware('auth'); //管理者・バンド代表者・バンドメンバー  管理画面役割設定
            Route::get('/userroles', '\App\Http\Controllers\Band\Admin\UserRoleController')->name('利用役割一覧')->middleware('auth'); //バンド代表・バンドメンバー・お客さん




            // バンド
            // Route::resource('/bands', BandMemberController::class)->middleware('auth');

            // バンドメンバー
            // namespaceに書いていない場合はこの書き方でもOK
            Route::resource('/members', '\App\Http\Controllers\Band\Admin\BandMemberController')->middleware('auth');


            // ユーザー
            // Route::get('/users', 'App\Http\Controllers\Band\Admin\UserController')->name('ユーザー一覧')->middleware('auth');

            // グループルーティング前の名前付きルートを設定する  参照先 https://readouble.com/laravel/8.x/ja/routing.html
            Route::post('/events/table', '\App\Http\Controllers\Band\Admin\EventController@tableAdd')->name('table');




            // まとめて設定することができる。６種類のルートを使う場合に使用。詳細だけいらない。インデックスだけ必要な場合は部分的なリソースルートかルーティングの個別に作成したほうが楽
            Route::resources([
                'user'            => UserController::class,
                'bands'            => BandMemberController::class,
                // 'bandgoods'        => BandGoodsController::class,
                'albums'           => AlbumController::class,
                'events'           => EventController::class,
                // 'goodstype'        => GoodsTypeController::class,
                'halls'            => HallController::class,
                'labels'           => LabelController::class,
                'musics'           => MusicController::class,
                // 'musicalinstrument' => MusicalInstrumentController::class,
                'performancelists' => PerformanceListController::class,
                'proceeds'         => ProceedController::class,
                // 'Tickets'      => TicketController::class,

            ]);

            // グループルーティング終わり


            // 部分的なリソースルート  参照先 https://readouble.com/laravel/8.x/ja/controllers.html
            // 中間テーブルで使用
            Route::resource('guestreservation', GuestReservationController::class)->only([
                'index',  'create', 'store', 'update', 'edit',

            ]);
            Route::resource('entries', EntryController::class)->only([
                'index',  'create', 'store', 'update',
            ]);


            // マスタ系のリソース
            Route::resource('bandgoods', BandGoodsController::class)->except([
                'show',
            ]);
            Route::resource('goodstype', GoodsTypeController::class)->except([
                'show',
            ]);
            Route::resource('tickets', TicketController::class)->except([
                'show',
            ]);
            Route::resource('musicalinstrument', MusicalInstrumentController::class)->except([
                'show',
            ]);
        });
    });
});