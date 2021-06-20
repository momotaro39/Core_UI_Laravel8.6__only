@extends('dashboard.base')
{{-- ページのタイトルを挿入 --}}
@section('title', 'イベント顧客予約一覧ページ')
{{-- メインコンテンツの内容を入れていきます --}}
@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            {{-- formのアクション設定 --}}
            <a href="{{ route("guestreservation.create") }}" class="btn btn-primary" style="margin:20px;">新規登録</a>

            {{-- formのアクション設定ここまで --}}

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-store">
                Bootstrap modal 登録
            </button>
            <!-- ここからモーダル画面 -->
            <div class="modal fade" id="modal-store" tabindex="-1" role="dialog" aria-labelledby="basicModal"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="modalLabelId">新規登録</h3>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="title-text" class="col-form-label">ユーザーID:</label>
                                    <input type="text" class="form-control" id="recipient-name">
                                </div>
                                <div class="form-group">
                                    <label for="title-text" class="col-form-label">イベントID</label>
                                    <input type="text" class="form-control" id="recipient-name">
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                            <button type="button" class="btn btn-primary">登録</button>
                        </div>
                    </div>
                    <!-- ここまでモーダル -->

                </div>
            </div>

        </div>
    </div>


    {{-- ここから検索項目設定 --}}
    <div class="card" style="user-select: auto;">
        <div class="card-header" style="user-select: auto;"><strong style="user-select: auto;">検索一覧</strong>
        </div>
        <div class="card-body" style="user-select: auto;">
            <form class="form-horizontal" action="" method="post" style="user-select: auto;">
                <div class="form-group row" style="user-select: auto;">
                    <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                        イベント名</label>
                    <div class="col-sm-6" style="user-select: auto;">
                        <input class="form-control" id="input-normal" type="text" name="input-normal"
                            placeholder="Normal" style="user-select: auto;">
                    </div>
                </div>
                <div class="form-group row" style="user-select: auto;">
                    <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                        ユーザー名</label>
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
                        style="user-select: auto;"></i> イベント参加ユーザー一覧</div>
                <div class="card-body" style="user-select: auto;">
                    <table class="table table-responsive-sm table-bordered table-striped table-sm"
                        style="user-select: auto;">
                        <thead style="user-select: auto;">
                            <tr style="user-select: auto;">
                                <th style="user-select: auto;">ユーザー名</th>
                                <th style="user-select: auto;">イベント名</th>
                                <th style="user-select: auto;">編集</th>
                            </tr>
                        </thead>
                        <tbody style="user-select: auto;">
                            <tr style="user-select: auto;">

                                @foreach ($guestReservations as $guestReservation)
                            </tr>
                            <tr style="user-select: auto;">
                                <td style="user-select: auto;">{{$guestReservation->userName}}</td>
                                <td style="user-select: auto;">{{$guestReservation->eventName}}</td>
                                <td class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-edit">編集
                                </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>

                    <!-- ここからモーダル編集部分 -->
                    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="basicModal"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="modalLabelId">編集</h3>
                                </div>
                                <div class="modal-body">
                                    <!-- formデータスタート -->
                                    <form action="{{ route('guestreservation.update', $guestReservation->id)}}"
                                        method="post">
                                        <!-- formデータ hiddenを設定 -->
                                        @csrf
                                        <!-- formデータ methodを設定 -->
                                        @method('PUT')

                                        <div class="form-group">
                                            <label for="title-text" class="col-form-label">ユーザーID:</label>
                                            <input type="text" class="form-control" id="recipient-name"
                                                value="{{$guestReservation->user_id}}" name="user_id">
                                        </div>
                                        <div class="form-group">
                                            <label for="title-text" class="col-form-label">イベントID</label>
                                            <input type="text" class="form-control" id="recipient-name"
                                                value="{{$guestReservation->event_id}}" name="event_id">
                                        </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                                    <button type="submit" class="btn btn-primary">登録</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- ここまでモーダル -->

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