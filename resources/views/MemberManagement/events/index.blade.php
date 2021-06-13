@extends('dashboard.base')
{{-- ページのタイトルを挿入 --}}
@section('title', 'イベント一覧ページ')
{{-- メインコンテンツの内容を入れていきます --}}
@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <a href="/core/events/create" class="btn btn-primary" style="margin:20px;">新規登録</a>
        </div>
    </div>

    {{-- ここから検索項目設定 --}}
    <div class="card" style="user-select: auto;">
        <div class="card-header" style="user-select: auto;"><strong style="user-select: auto;">検索一覧</strong>
        </div>
        <div class="card-body" style="user-select: auto;">
            {{-- formのアクション設定 --}}
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
                        開催ホール</label>
                    <div class="col-sm-6" style="user-select: auto;">
                        <input class="form-control" id="input-normal" type="text" name="input-normal"
                            placeholder="Normal" style="user-select: auto;">
                    </div>
                </div>
                <div class="form-group row" style="user-select: auto;">
                    <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                        売上指定 min</label>
                    <div class="col-sm-6" style="user-select: auto;">
                        <input class="form-control" id="input-normal" type="text" name="input-normal"
                            placeholder="Normal" style="user-select: auto;">
                    </div>
                </div>
                <div class="form-group row" style="user-select: auto;">
                    <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                        売上指定 Max</label>
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
                        style="user-select: auto;"></i> イベント一覧 マスタ</div>
                <div class="card-body" style="user-select: auto;">
                    <table class="table table-responsive-sm table-bordered table-striped table-sm"
                        style="user-select: auto;">
                        <thead style="user-select: auto;">
                            <tr style="user-select: auto;">
                                <th style="user-select: auto;">イベント名</th>
                                <th style="user-select: auto;">開催ホール</th>
                                <th style="user-select: auto;">売上状況 プログレスバー</th>
                                <th style="user-select: auto;">詳細</th>
                                <th style="user-select: auto;">編集</th>
                                <th style="user-select: auto;">削除</th>
                            </tr>
                        </thead>
                        <tbody style="user-select: auto;">
                            <tr style="user-select: auto;">

                                @foreach ($events as $event)
                            </tr>
                            <tr style="user-select: auto;">
                                <td style="user-select: auto;">{{$event->name}}</td>
                                <td style="user-select: auto;">{{$event->hall->name}}</td>
                                <td style="user-select: auto;">テキスト3</td>
                                <td><a href="/core/events/{{$event->id}}" class="btn btn-primary btn-sm">詳細</a>
                                </td>
                                <td><a href="/core/events/{{$event->id}}/edit" class="btn btn-primary btn-sm">編集</a>
                                </td>
                                <td>

                                    <form action="/core/events/{{$event->id}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" name="" value="削除"
                                            class="btn btn-danger btn-sm btn-destroy">
                                    </form>


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
<script>
$(function() {
    $(".btn-destroy").click(function() {
        if (confirm("本当に削除しますか？")) {
            //そのままsubmit（削除）
        } else {
            //cancel
            return false;
        }
    });
});
</script>
@endsection