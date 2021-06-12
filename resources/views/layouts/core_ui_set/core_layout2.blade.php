@include('layouts.core_ui_set.head')

<body class="c-app">
    {{-- サイドバーはここから --}}
    {{-- 左上のロゴなどの設定 --}}
    <div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
        <div class="c-sidebar-brand d-lg-down-none">
            <svg class="c-sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
                <use xlink:href="/coreui_assets/brand/coreui.svg#full"></use>
            </svg>
            <svg class="c-sidebar-brand-minimized" width="46" height="46" alt="CoreUI Logo">
                <use xlink:href="/coreui_assets/brand/coreui.svg#signet"></use>
            </svg>
        </div>
        {{-- ここからサイドバーの文字列を設定します --}}
        <ul class="c-sidebar-nav">
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speedometer"></use>
                    </svg> Dashboard<span class="badge badge-info">NEW</span></a></li>
            <li class="c-sidebar-nav-title">Original API テスト</li>
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/name">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                    </svg>name API テスト</a></li>
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/todo">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                    </svg>todo API 一覧</a></li>
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/core">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                    </svg>Original API 1 </a></li>
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/top_page">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                    </svg> バンドメンバー管理一覧</a></li>

            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/home">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                    </svg> バンドメンバー管理ダッシュボード</a></li>

            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/Welcome">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                    </svg> Laravel Welcome画面</a></li>
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="https://coreui.io/demo/free/3.4.0/">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                    </svg> 参考DEMO</a></li>

            <li class="c-sidebar-nav-title">フロントエンド テスト</li>
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/marble">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                    </svg>merble テンプレート</a></li>
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/front/lp/polo">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                    </svg>polo テンプレート</a></li>
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/front/lp/polo">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                    </svg>polo テンプレート</a></li>
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="http://localhost/front/lp/polo">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-chart-pie"></use>
                    </svg>polo テンプレート</a></li>

            <li class="c-sidebar-nav-title">Components</li>
            <li class="c-sidebar-nav-item c-sidebar-nav-dropdown"><a
                    class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-puzzle"></use>
                    </svg> Base</a>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="base/breadcrumb.html"><span
                                class="c-sidebar-nav-icon"></span> Breadcrumb</a></li>
                    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="base/cards.html"><span
                                class="c-sidebar-nav-icon"></span> Cards</a></li>
                </ul>
            </li>

            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="widgets.html">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-calculator"></use>
                    </svg> Widgets<span class="badge badge-info">NEW</span></a></li>
            <li class="c-sidebar-nav-divider"></li>

            <li class="c-sidebar-nav-item c-sidebar-nav-dropdown"><a
                    class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-star"></use>
                    </svg> Pages</a>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="login.html" target="_top">
                            <svg class="c-sidebar-nav-icon">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-account-logout"></use>
                            </svg> Login</a></li>
                    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="register.html" target="_top">
                            <svg class="c-sidebar-nav-icon">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-account-logout"></use>
                            </svg> Register</a></li>

                </ul>
            </li>
            <li class="c-sidebar-nav-item mt-auto"><a class="c-sidebar-nav-link c-sidebar-nav-link-success"
                    href="https://coreui.io" target="_top">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-cloud-download"></use>
                    </svg> Download CoreUI</a></li>
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link c-sidebar-nav-link-danger"
                    href="https://coreui.io/pro/" target="_top">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-layers"></use>
                    </svg> Try CoreUI</a></li>
        </ul>
        <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent"
            data-class="c-sidebar-minimized"></button>
    </div>
{{-- サイドバーはここまで --}}
    {{-- ヘッドの部分の記載 --}}
    <div class="c-wrapper c-fixed-components">
        <header class="c-header c-header-light c-header-fixed c-header-with-subheader">
            <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar"
                data-class="c-sidebar-show">
                <svg class="c-icon c-icon-lg">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
                </svg>
            </button><a class="c-header-brand d-lg-none" href="#">
                <svg width="118" height="46" alt="CoreUI Logo">
                    <use xlink:href="assets/coreui_assets//brand/coreui.svg#full"></use>
                </svg></a>
            <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar"
                data-class="c-sidebar-lg-show" responsive="true">
                <svg class="c-icon c-icon-lg">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
                </svg>
            </button>

            {{-- ヘッダーの←上のメニュー一覧 --}}
            <ul class="c-header-nav d-md-down-none">
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link"
                        href="http://localhost/core">Dashboard1</a></li>
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link"
                        href="http://localhost/core">Dashboard2</a></li>
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link"
                        href="http://localhost/core">Dashboard3</a></li>
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link"
                        href="http://localhost/core">Dashboard4</a></li>

            </ul>
            {{-- ヘッダー右上のエリア （現在消えています。リンクを設定しましょう） --}}
            <ul class="c-header-nav ml-auto mr-4">
                <li class="c-header-nav-item d-md-down-none mx-2"><a class="c-header-nav-link" href="#">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-bell"></use>
                        </svg></a></li>
                <li class="c-header-nav-item d-md-down-none mx-2"><a class="c-header-nav-link" href="#">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-list-rich"></use>
                        </svg></a></li>
                <li class="c-header-nav-item d-md-down-none mx-2"><a class="c-header-nav-link" href="#">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-envelope-open"></use>
                        </svg></a></li>
                <li class="c-header-nav-item dropdown"><a class="c-header-nav-link" data-toggle="dropdown" href="#"
                        role="button" aria-haspopup="true" aria-expanded="false">
                        <div class="c-avatar"><img class="c-avatar-img" src="/coreui_assets/img/avatars/6.jpg"
                                alt="user@email.com"></div>
                    </a>

                    {{-- ドロップダウンリストがここに該当します --}}
                    <div class="dropdown-menu dropdown-menu-right pt-0">
                        <div class="dropdown-header bg-light py-2"><strong>Account</strong></div><a
                            class="dropdown-item" href="#">
                            <svg class="c-icon mr-2">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-bell"></use>
                            </svg> Updates<span class="badge badge-info ml-auto">42</span></a><a class="dropdown-item"
                            href="#">
                            <svg class="c-icon mr-2">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-envelope-open"></use>
                            </svg> Messages<span class="badge badge-success ml-auto">42</span></a><a
                            class="dropdown-item" href="#">
                            <svg class="c-icon mr-2">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-task"></use>
                            </svg> Tasks<span class="badge badge-danger ml-auto">42</span></a><a class="dropdown-item"
                            href="#">
                            <svg class="c-icon mr-2">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-comment-square"></use>
                            </svg> Comments<span class="badge badge-warning ml-auto">42</span></a>
                        <div class="dropdown-header bg-light py-2"><strong>Settings</strong></div><a
                            class="dropdown-item" href="#">
                            <svg class="c-icon mr-2">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                            </svg> Profile</a><a class="dropdown-item" href="#">
                            <svg class="c-icon mr-2">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-settings"></use>
                            </svg> Settings</a><a class="dropdown-item" href="#">
                            <svg class="c-icon mr-2">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-credit-card"></use>
                            </svg> Payments<span class="badge badge-secondary ml-auto">42</span></a><a
                            class="dropdown-item" href="#">
                            <svg class="c-icon mr-2">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-file"></use>
                            </svg> Projects<span class="badge badge-primary ml-auto">42</span></a>
                        <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
                            <svg class="c-icon mr-2">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                            </svg> Lock Account</a><a class="dropdown-item" href="#">
                            <svg class="c-icon mr-2">
                                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-account-logout"></use>
                            </svg> Logout</a>
                    </div>
                </li>
            </ul>
            {{-- ヘッダーバンくずの部分の設定 --}}
            <div class="c-subheader px-3">
                <ol class="breadcrumb border-0 m-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>

                </ol>
            </div>
        </header>

        {{-- ここからコンテンツの部分 --}}
        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">ここに機能を掲載していきます</div>
                                    <div class="card-body">

                                        <br>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </main>
            {{-- コンテンツのメイン部分はここまで --}}
            {{-- ここからフッターの部分 --}}
            @include('layouts.core_ui_set.footer')


        </div>
    </div>
    {{-- ここからJavaScriptの読み込み部分 --}}
    <script src="/coreui_assets/js/coreui.bundle.min.js"></script>
    <!--[if IE]><!-->
    <script src="/coreui_assets/vendors/icons/js/svgxuse.min.js"></script>
    <!--<![endif]-->

    <script src="/coreui_assets/js/chartjs/js/coreui-chartjs.bundle.js"></script>

    <script src="/coreui_assets/js/utils/js/coreui-utils.js"></script>
    <script src="/coreui_assets/js/main.js"></script>
</body>

</html>
