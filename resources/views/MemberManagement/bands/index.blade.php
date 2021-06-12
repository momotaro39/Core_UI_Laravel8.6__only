@extends('layouts.core_ui_set.core_layout')
{{-- ページのタイトルを挿入 --}}
@section('title', 'バンド一覧ページ')
    {{-- メインコンテンツの内容を入れていきます --}}
@section('content')
    <div class="container-fluid">


            {{-- ここから検索項目設定 --}}
            <div class="card" style="user-select: auto;">
                <div class="card-header" style="user-select: auto;"><strong style="user-select: auto;">検索一覧</strong>
                </div>
                <div class="card-body" style="user-select: auto;">
                    <form class="form-horizontal" action="" method="post" style="user-select: auto;">
                        <div class="form-group row" style="user-select: auto;">
                            <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                                バンド名</label>
                            <div class="col-sm-6" style="user-select: auto;">
                                <input class="form-control" id="input-normal" type="text" name="input-normal"
                                    placeholder="Normal" style="user-select: auto;">
                            </div>
                        </div>
                        <div class="form-group row" style="user-select: auto;">
                            <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                                レーベル</label>
                            <div class="col-sm-6" style="user-select: auto;">
                                <input class="form-control" id="input-normal" type="text" name="input-normal"
                                    placeholder="Normal" style="user-select: auto;">
                            </div>
                        </div>
                        <div class="form-group row" style="user-select: auto;">
                            <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                                検索項目4</label>
                            <div class="col-sm-6" style="user-select: auto;">
                                <input class="form-control" id="input-normal" type="text" name="input-normal"
                                    placeholder="Normal" style="user-select: auto;">
                            </div>
                        </div>
                        <div class="form-group row" style="user-select: auto;">
                            <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                                検索項目5</label>
                            <div class="col-sm-6" style="user-select: auto;">
                                <input class="form-control" id="input-normal" type="text" name="input-normal"
                                    placeholder="Normal" style="user-select: auto;">
                            </div>
                        </div>
                        <div class="form-group row" style="user-select: auto;">
                            <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                                検索項目6</label>
                            <div class="col-sm-6" style="user-select: auto;">
                                <input class="form-control" id="input-normal" type="text" name="input-normal"
                                    placeholder="Normal" style="user-select: auto;">
                            </div>
                        </div>

                    </form>
                </div>
                <div class="card-footer" style="user-select: auto;">
                    <button class="btn btn-sm btn-primary" type="submit" style="user-select: auto;"> 検索</button>
                    <button class="btn btn-sm btn-danger" type="reset" style="user-select: auto;"> クリア</button>
                </div>
            </div>
            {{-- ここまで検索項目設定 --}}


            {{-- ここまからカード1 --}}
            <div class="row">
                <div class="col-lg-12" style="user-select: auto;">
                    <div class="card" style="user-select: auto;">
                        <div class="card-header" style="user-select: auto;"><i class="fa fa-align-justify"
                                style="user-select: auto;"></i> バンド一覧</div>
                        <div class="card-body" style="user-select: auto;">
                            <table class="table table-responsive-sm table-bordered table-striped table-sm"
                                style="user-select: auto;">
                                <thead style="user-select: auto;">
                                    <tr style="user-select: auto;">
                                        <th style="user-select: auto;">バンド名</th>
                                        <th style="user-select: auto;">レーベル</th>
                                        <th style="user-select: auto;">結成年月日</th>

                                        <th style="user-select: auto;">編集</th>
                                        <th style="user-select: auto;">削除</th>
                                    </tr>
                                </thead>
                                <tbody style="user-select: auto;">
                                    <tr style="user-select: auto;">

                                        @foreach ($adminRoles as $role)
                                    </tr>
                                    <tr style="user-select: auto;">
                                        <td style="user-select: auto;">テキスト1</td>
                                        <td style="user-select: auto;">テキスト2</td>
                                        <td style="user-select: auto;"><span class="badge badge-danger"
                                                style="user-select: auto;">Banned</span>
                                        </td>
                                        <td style="user-select: auto;"><span class="badge badge-danger"
                                                style="user-select: auto;">Banned</span>
                                        </td>
                                        <td style="user-select: auto;"><span class="badge badge-danger"
                                                style="user-select: auto;">Banned</span>
                                        </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>

                            {{-- ページネーション --}}

                            @include('layouts.core_ui_set.pagination')

                            {{-- ページネーションここまで --}}
                        </div>
                    </div>
                </div>
            </div>
            {{-- ここまでカード1 --}}






            {{-- ここまからカード1 --}}
            <div class="row">
                <div class="col-lg-12" style="user-select: auto;">
                    <div class="card" style="user-select: auto;">
                        <div class="card-header" style="user-select: auto;"><i class="fa fa-align-justify"
                                style="user-select: auto;"></i> バンドメンバー詳細</div>
                        <div class="card-body" style="user-select: auto;">
                            <table class="table table-responsive-sm table-bordered table-striped table-sm"
                                style="user-select: auto;">
                                <thead style="user-select: auto;">
                                    <tr style="user-select: auto;">
                                        <th style="user-select: auto;">メンバー名</th>
                                        <th style="user-select: auto;">担当楽器</th>
                                        <th style="user-select: auto;">メールアドレス</th>
                                        <th style="user-select: auto;">代表orメンバー</th>
                                        <th style="user-select: auto;">編集</th>
                                        <th style="user-select: auto;">削除</th>
                                    </tr>
                                </thead>
                                <tbody style="user-select: auto;">
                                    <tr style="user-select: auto;">

                                        @foreach ($adminRoles as $role)
                                    </tr>
                                    <tr style="user-select: auto;">
                                        <td style="user-select: auto;">テキスト1</td>
                                        <td style="user-select: auto;">テキスト2</td>
                                        <td style="user-select: auto;">テキスト3</td>
                                        <td style="user-select: auto;">テキスト4</td>

                                        <td style="user-select: auto;"><span class="badge badge-danger"
                                                style="user-select: auto;">Banned</span>
                                        </td>
                                        <td style="user-select: auto;"><span class="badge badge-danger"
                                                style="user-select: auto;">Banned</span>
                                        </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>

                            {{-- ページネーション --}}
                            <nav style="user-select: auto;">
                                <ul class="pagination" style="user-select: auto;">
                                    <li class="page-item" style="user-select: auto;"><a class="page-link" href="#"
                                            style="user-select: auto;">Prev</a></li>
                                    <li class="page-item active" style="user-select: auto;"><a class="page-link" href="#"
                                            style="user-select: auto;">1</a></li>
                                    <li class="page-item" style="user-select: auto;"><a class="page-link" href="#"
                                            style="user-select: auto;">2</a></li>
                                    <li class="page-item" style="user-select: auto;"><a class="page-link" href="#"
                                            style="user-select: auto;">3</a></li>
                                    <li class="page-item" style="user-select: auto;"><a class="page-link" href="#"
                                            style="user-select: auto;">4</a></li>
                                    <li class="page-item" style="user-select: auto;"><a class="page-link" href="#"
                                            style="user-select: auto;">Next</a></li>
                                </ul>
                            </nav>
                            {{-- ページネーションここまで --}}
                        </div>
                    </div>
                </div>
            </div>
            {{-- ここまでカード1 --}}



    </div>


@endsection


@section('add-script')
    {{-- このページに必要なスクリプトがあれば以下に追加 --}}

@endsection
