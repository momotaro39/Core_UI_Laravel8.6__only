<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    private $menuId = null;
    private $dropdownId = array();
    private $dropdown = false;
    private $sequence = 1;
    private $joinData = array();
    private $adminRole = null;
    private $userRole = null;
    private $subFolder = '';

    public function join($roles, $menusId)
    {
        $roles = explode(',', $roles);
        foreach ($roles as $role) {
            array_push($this->joinData, array('role_name' => $role, 'menus_id' => $menusId));
        }
    }

    /*
        Function assigns menu elements to roles
        Must by use on end of this seeder
    */
    public function joinAllByTransaction()
    {
        DB::beginTransaction();
        foreach ($this->joinData as $data) {
            DB::table('menu_role')->insert([
                'role_name' => $data['role_name'],
                'menus_id' => $data['menus_id'],
            ]);
        }
        DB::commit();
    }

    public function insertLink($roles, $name, $href, $icon = null)
    {
        $href = $this->subFolder . $href;
        if ($this->dropdown === false) {
            DB::table('menus')->insert([
                'slug' => 'link',
                'name' => $name,
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'sequence' => $this->sequence
            ]);
        } else {
            DB::table('menus')->insert([
                'slug' => 'link',
                'name' => $name,
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'parent_id' => $this->dropdownId[count($this->dropdownId) - 1],
                'sequence' => $this->sequence
            ]);
        }
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        $permission = Permission::where('name', '=', $name)->get();
        if (empty($permission)) {
            $permission = Permission::create(['name' => 'visit ' . $name]);
        }
        $roles = explode(',', $roles);
        if (in_array('user', $roles)) {
            $this->userRole->givePermissionTo($permission);
        }
        if (in_array('admin', $roles)) {
            $this->adminRole->givePermissionTo($permission);
        }
        return $lastId;
    }

    public function insertTitle($roles, $name)
    {
        DB::table('menus')->insert([
            'slug' => 'title',
            'name' => $name,
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence
        ]);
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        return $lastId;
    }

    public function beginDropdown($roles, $name, $icon = '')
    {
        if (count($this->dropdownId)) {
            $parentId = $this->dropdownId[count($this->dropdownId) - 1];
        } else {
            $parentId = null;
        }
        DB::table('menus')->insert([
            'slug' => 'dropdown',
            'name' => $name,
            'icon' => $icon,
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence,
            'parent_id' => $parentId
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        array_push($this->dropdownId, $lastId);
        $this->dropdown = true;
        $this->sequence++;
        $this->join($roles, $lastId);
        return $lastId;
    }

    public function endDropdown()
    {
        $this->dropdown = false;
        array_pop($this->dropdownId);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Get roles */
        // 役割を取得する　DBの名称と一致させる
        $this->adminRole = Role::where('name', '=', 'admin')->first();
        $this->userRole = Role::where('name', '=', 'user')->first();
        /* Create Sidebar menu */
        // サイドメニューを一気に追加する　初期値の設定
        DB::table('menulist')->insert([
            'name' => 'sidebar menu'
        ]);
        $this->menuId = DB::getPdo()->lastInsertId();  //set menuId
        $this->insertLink('guest,user,admin', 'ダッシュボード（TOP）', '/', 'cil-speedometer');
        // ここからドロップダウン始まり
        $this->beginDropdown('admin', '初期設定', 'cil-calculator');

        $this->insertLink('admin', 'ノート',                   '/notes');
        $this->insertLink('admin', 'ユーザー',                   '/users');
        $this->insertLink('admin', 'メニュー編集',               '/menu/menu');
        $this->insertLink('admin', '要素の編集',      '/menu/element');
        $this->insertLink('admin', '役割編集',              '/roles');
        $this->insertLink('admin', 'メディア',                   '/media');
        $this->insertLink('admin', 'パンくずリスト',                   '/bread');
        $this->insertLink('admin', 'Email',                   '/mail');
        $this->endDropdown();
        // ここまでドロップダウン





        $this->beginDropdown('admin', 'バンド管理', 'cil-calculator');

        $this->insertLink('admin', 'ユーザー',                   '/notes');
        $this->insertLink('admin', 'バンド',                   '/users');
        $this->insertLink('admin', 'バンド管理者',               '/menu/menu');
        $this->insertLink('admin', 'イベント',      '/menu/element');
        $this->insertLink('admin', 'ホール',              '/roles');
        $this->insertLink('admin', 'メディア',                   '/media');
        $this->insertLink('admin', 'パンくずリスト',                   '/bread');
        $this->insertLink('admin', 'Email',                   '/mail');
        $this->endDropdown();
        // ここまでドロップダウン


        //         <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/adminroles">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg>管理役割マスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/userroles">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg>利用者役割マスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/musicalinstrument">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg>楽器マスタ</a></li>


        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/users">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> ユーザーマスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/members">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> バンドメンバーマスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/bands">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> バンド名マスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/bandadmin">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> バンド管理者マスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/bandgoods">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> バンドグッズマスタ</a></li>


        //         <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/goodstype">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> バンドグッズタイプマスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/albums">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> アルバムマスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/entries">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> バンドエントリー</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/events">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> イベントマスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/halls">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> イベントホールマスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/labels">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> レーベルマスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/musics">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> 曲マスタ</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/performancelists">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> イベントセットリスト一覧</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/guestreservation">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> イベント予約状況一覧</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/proceeds">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> イベント売上一覧</a></li>

        // <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core/ticketlists">
        //         <svg class="c-sidebar-nav-icon">
        //             <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
        //         </svg> チケットリスト一覧</a></li>




        // クリックでドロップダウン（追加機能を足していく）
        $this->insertTitle('user,admin', '追加機能(API)');
        $this->insertLink('user,admin', '名前API',    '/function/randomuser', 'cil-drop1');
        $this->insertLink('user,admin', 'DogAPI',    '/function/randomdog', 'cil-drop1');
        $this->insertLink('user,admin', 'NewsAPI',    '/function/randomnews', 'cil-drop1');
        $this->insertLink('user,admin', 'GitHubAPI',    '/function/github', 'cil-drop1');
        $this->insertLink('user,admin', 'YoutubeAPI',    '/function/github', 'cil-drop1');
        $this->endDropdown();

        // ゲストメンバーのみ表示
        $this->insertLink('guest', 'ログイン設定', '/login', 'cil-account-logout');
        $this->insertLink('guest', '登録設定', '/register', 'cil-account-logout');

        // ここから使い方解説
        $this->insertTitle('user,admin', 'テーマで扱う内容');
        $this->insertLink('user,admin', '色見本', '/colors', 'cil-drop1');
        $this->insertLink('user,admin', '文字装飾', '/typography', 'cil-pencil');
        $this->insertLink('user,admin', 'カード',         '/base/cards');
        $this->endDropdown();
        // クリックでドロップダウン
        $this->beginDropdown('user,admin', '基本的なパーツの紹介', 'cil-puzzle');
        $this->insertLink('user,admin', 'パンくずリスト見本',    '/base/breadcrumb');
        $this->insertLink('user,admin', 'カード',         '/base/cards');
        $this->insertLink('user,admin', 'カルーセル',      '/base/carousel');
        $this->insertLink('user,admin', 'アコーディオン（Collapse）',      '/base/collapse');
        $this->insertLink('user,admin', 'フォーム',         '/base/forms');
        $this->insertLink('user,admin', 'Jumbotron',     '/base/jumbotron');
        $this->insertLink('user,admin', 'リスト表示',    '/base/list-group');
        $this->insertLink('user,admin', 'ナビゲーションメニュー',          '/base/navs');
        $this->insertLink('user,admin', 'ページネーション',    '/base/pagination');
        $this->insertLink('user,admin', 'ポップオーバー',      '/base/popovers');
        $this->insertLink('user,admin', '進捗',      '/base/progress');
        $this->insertLink('user,admin', 'スクロール表示',     '/base/scrollspy');
        $this->insertLink('user,admin', 'スイッチボタン（オンオフ）',      '/base/switches');
        $this->insertLink('user,admin', '表',        '/base/tables');
        $this->insertLink('user,admin', 'タブ',          '/base/tabs');
        $this->insertLink('user,admin', 'ツールチップス',      '/base/tooltips');
        $this->endDropdown();

        $this->beginDropdown('user,admin', 'ボタン類', 'cil-cursor');
        $this->insertLink('user,admin', 'ボタン',           '/buttons/buttons');
        $this->insertLink('user,admin', 'ボタングループ',     '/buttons/button-group');
        $this->insertLink('user,admin', 'ドロップダウン',         '/buttons/dropdowns');
        $this->insertLink('user,admin', '各社ツールボタン（Brand）',     '/buttons/brand-buttons');
        $this->endDropdown();

        $this->insertLink('user,admin', 'チャート', '/charts', 'cil-chart-pie');

        $this->beginDropdown('user,admin', 'アイコン類', 'cil-star');
        $this->insertLink('user,admin', 'CoreUI アイコン',      '/icon/coreui-icons');
        $this->insertLink('user,admin', '旗',             '/icon/flags');
        $this->insertLink('user,admin', 'ブランド',            '/icon/brands');
        $this->endDropdown();

        $this->beginDropdown('user,admin', '目印', 'cil-bell');
        $this->insertLink('user,admin', 'アラート',     '/notifications/alerts');
        $this->insertLink('user,admin', 'バッジ',      '/notifications/badge');
        $this->insertLink('user,admin', 'モーダル',     '/notifications/modals');
        $this->endDropdown();

        $this->insertLink('user,admin', 'ウィジェット', '/widgets', 'cil-calculator');

        $this->insertTitle('user,admin', 'その他');

        $this->beginDropdown('user,admin', 'ページ見本', 'cil-star');
        $this->insertLink('user,admin', 'ログイン',         '/login');
        $this->insertLink('user,admin', '登録',      '/register');
        $this->insertLink('user,admin', '404',     '/404');
        $this->insertLink('user,admin', '500',     '/500');
        $this->endDropdown();

        // $this->insertLink('guest,user,admin', 'Download CoreUI', 'https://coreui.io', 'cil-cloud-download');
        // $this->insertLink('guest,user,admin', 'Try CoreUI PRO', 'https://coreui.io/pro/', 'cil-layers');


        /* Create top menu */
        // ヘッダーメニュー
        DB::table('menulist')->insert([
            'name' => 'top menu'
        ]);
        $this->menuId = DB::getPdo()->lastInsertId();  //set menuId

        $this->beginDropdown('guest,user,admin', 'ページ一覧');
        $id = $this->insertLink('guest,user,admin', 'ダッシュボード',    '/');
        $id = $this->insertLink('user,admin', 'ノート一覧',              '/notes');
        $id = $this->insertLink('admin', 'ユーザー一覧',                   '/users');
        $this->endDropdown();

        $id = $this->beginDropdown('admin', '初期設定');

        $id = $this->insertLink('admin', 'メニュー編集',               '/menu/menu');
        $id = $this->insertLink('admin', '要素編集',      '/menu/element');
        $id = $this->insertLink('admin', '役割編集',              '/roles');
        $id = $this->insertLink('admin', 'メディア',                   '/media');
        $id = $this->insertLink('admin', 'ブレンド',                   '/bread');
        $this->endDropdown();

        $this->joinAllByTransaction(); ///   <===== Must by use on end of this seeder
    }
}