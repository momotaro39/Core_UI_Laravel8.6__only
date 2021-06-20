@extends('dashboard.base')
{{-- ページのタイトルを挿入 --}}
@section('title', 'イベント一覧ページ')
{{-- メインコンテンツの内容を入れていきます --}}
@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            {{-- formのアクション設定 --}}
            <a href="{{ route("events.create") }}" class="btn btn-primary" style="margin:20px;">新規登録</a>

            {{-- formのアクション設定ここまで --}}
        </div>
    </div>
    {{-- </form> --}}
    {{-- formのアクション設定ここまで --}}

    {{-- ここから検索項目設定 --}}
    <div class="card" style="user-select: auto;">
        <div class="card-header" style="user-select: auto;"><strong style="user-select: auto;">検索一覧</strong>
        </div>
        <div class="card-body" style="user-select: auto;">
            {{-- formのアクション設定 --}}
            <form action="{{ route('events.index') }}" method="post">
                <!-- formデータ hiddenを設定 -->
                @csrf
                <!-- formデータ methodを設定 擬似フォームメソッド HTMLフォームはPUT、PATCH、DELETEアクションをサポートしていません。 -->
                @method('POST')

                <div class="form-group row" style="user-select: auto;">
                    <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                        イベント名</label>
                    <div class="col-sm-6" style="user-select: auto;">
                        <input class="form-control" id="input-normal" type="text" name="name" placeholder="Normal"
                            style="user-select: auto;">
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
        </div>
        <div class="card-footer" style="user-select: auto;">
            <button class="btn btn-sm btn-primary" type="submit" style="user-select: auto;"> 検索</button>
            <button class="btn btn-sm btn-danger" type="reset" style="user-select: auto;"> クリア</button>
        </div>

        </form>
        {{-- formのアクション設定ここまで --}}
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
                            </tr>
                        </thead>
                        <tbody style="user-select: auto;">
                            <tr style="user-select: auto;">

                                @foreach ($querySearchResults as $querySearchResult)
                            </tr>
                            <tr style="user-select: auto;">
                                <td style="user-select: auto;">{{ $querySearchResult->name }}</td>
                                <td style="user-select: auto;">{{ $querySearchResult->hall->name }}</td>
                                <td style="user-select: auto;">テキスト3</td>
                                <td><a href="{{ route('events.show', $querySearchResult->id) }}"
                                        class="btn btn-primary btn-sm">詳細</a>
                                </td>
                                <td><a href="{{ route('events.edit', $querySearchResult->id) }}"
                                        class="btn btn-primary btn-sm">編集</a>
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


@section('add-script')
{{-- このページに必要なスクリプトがあれば以下に追加 --}}

@endsection