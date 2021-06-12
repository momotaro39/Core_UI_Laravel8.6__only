@extends('layouts.core_ui_set.core_layout')
{{-- ページのタイトルを挿入 --}}
@section('title', 'チケット一覧ページ')
    {{-- メインコンテンツの内容を入れていきます --}}
@section('content')
    <div class="container-fluid">




            {{-- ここまからカード1 --}}
            <div class="row">
                <div class="col-lg-12" style="user-select: auto;">
                    <div class="card" style="user-select: auto;">
                        <div class="card-header" style="user-select: auto;"><i class="fa fa-align-justify"
                                style="user-select: auto;"></i> チケットマスタ</div>
                        <div class="card-body" style="user-select: auto;">
                            <table class="table table-responsive-sm table-bordered table-striped table-sm"
                                style="user-select: auto;">
                                <thead style="user-select: auto;">
                                    <tr style="user-select: auto;">
                                        <th style="user-select: auto;">チケットタイプ</th>
                                        <th style="user-select: auto;">備考</th>
                                        <th style="user-select: auto;">編集</th>
                                        <th style="user-select: auto;">削除</th>
                                    </tr>
                                </thead>
                                <tbody style="user-select: auto;">
                                    <tr style="user-select: auto;">

                                        @foreach ($ticketLists as $ticketList)
                                    </tr>
                                    <tr style="user-select: auto;">
                                        <td style="user-select: auto;">{{$ticketList->rank}}</td>
                                        <td style="user-select: auto;">{{$ticketList->memo}}</td>

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



    </div>


@endsection


@section('add-script')
    {{-- このページに必要なスクリプトがあれば以下に追加 --}}

@endsection
