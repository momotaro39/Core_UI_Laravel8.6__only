@extends('dashboard.base')
{{-- ページのタイトルを挿入 --}}
@section('title', 'イベント一覧ページ')
{{-- メインコンテンツの内容を入れていきます --}}
@section('content')
<div class="container-fluid">



    {{-- ここまからカード1 --}}
    <div class="row">
        <div class="col-lg-12" style="user-select: auto;">
            <div class="card" style="user-select: auto;">
                <div class="card-header" style="user-select: auto;"><i class="fa fa-align-justify" style="user-select: auto;"></i> イベント詳細</div>
                <div class="card-body" style="user-select: auto;">
                    <table class="table table-responsive-sm table-bordered table-striped table-sm" style="user-select: auto;">
                        <p>イベント名称</p>
                        <thead style="user-select: auto;">
                            <tr style="user-select: auto;">
                                <th style="user-select: auto;">イベント日</th>
                                <th style="user-select: auto;">開場時間</th>
                                <th style="user-select: auto;">開始時間</th>
                                <th style="user-select: auto;">終了時間</th>
                                <th style="user-select: auto;">予約開始日</th>
                                <th style="user-select: auto;">予約終了日</th>

                            </tr>
                        </thead>
                        <tbody style="user-select: auto;">
                            <tr style="user-select: auto;">

                            </tr>
                            <tr style="user-select: auto;">
                                <td style="user-select: auto;">テキスト1</td>
                                <td style="user-select: auto;">テキスト2</td>
                                <td style="user-select: auto;">テキスト3</td>
                                <td style="user-select: auto;">テキスト4</td>
                                <td style="user-select: auto;">テキスト5</td>
                                <td style="user-select: auto;">テキスト6</td>

                            </tr>
                        </tbody>
                    </table>


                    <table class="table table-responsive-sm table-bordered table-striped table-sm" style="user-select: auto;">
                        <p>ホール情報</p>
                        <thead style="user-select: auto;">
                            <tr style="user-select: auto;">
                                <th style="user-select: auto;">名称</th>
                                <th style="user-select: auto;">住所</th>
                                <th style="user-select: auto;">最寄駅</th>
                            </tr>
                        </thead>
                        <tbody style="user-select: auto;">
                            <tr style="user-select: auto;">
                            </tr>
                            <tr style="user-select: auto;">
                                <td style="user-select: auto;">テキスト1</td>
                                <td style="user-select: auto;">テキスト2</td>
                                <td style="user-select: auto;">テキスト3</td>
                            </tr>
                        </tbody>
                    </table>


                    <table class="table table-responsive-sm table-bordered table-striped table-sm" style="user-select: auto;">
                        <p>チケット情報</p>
                        <thead style="user-select: auto;">
                            <tr style="user-select: auto;">
                                <th style="user-select: auto;">チケットタイプ</th>
                                <th style="user-select: auto;">チケット単価</th>
                                <th style="user-select: auto;">販売チケット枚数（アリーナ）</th>
                                <th style="user-select: auto;">販売チケット枚数（ホール）</th>

                            </tr>
                        </thead>
                        <tbody style="user-select: auto;">
                            <tr style="user-select: auto;">
                            </tr>
                            <tr style="user-select: auto;">
                                <td style="user-select: auto;">テキスト1</td>
                                <td style="user-select: auto;">テキスト2</td>
                                <td style="user-select: auto;">テキスト3</td>
                                <td style="user-select: auto;">テキスト4</td>

                            </tr>
                        </tbody>
                    </table>

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