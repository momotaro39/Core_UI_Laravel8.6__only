@extends('dashboard.base')
{{-- ページのタイトルを挿入 --}}
@section('title', 'タイトル名を入力')
{{-- メインコンテンツの内容を入れていきます --}}
@section('content')


<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <a href="core/members/create" class="btn btn-primary" style="margin:20px;">新規登録</a>
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
                        バンド名</label>
                    <div class="col-sm-6" style="user-select: auto;">
                        <input class="form-control" id="input-normal" type="text" name="input-normal"
                            placeholder="Normal" style="user-select: auto;">
                    </div>
                </div>
                <div class="form-group row" style="user-select: auto;">
                    <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                        名前</label>
                    <div class="col-sm-6" style="user-select: auto;">
                        <input class="form-control" id="input-normal" type="text" name="input-normal"
                            placeholder="Normal" style="user-select: auto;">
                    </div>
                </div>
                <div class="form-group row" style="user-select: auto;">
                    <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                        楽器</label>
                    <div class="col-sm-6" style="user-select: auto;">
                        <input class="form-control" id="input-normal" type="text" name="input-normal"
                            placeholder="Normal" style="user-select: auto;">
                    </div>
                </div>
                <div class="form-group row" style="user-select: auto;">
                    <label class="col-sm-5 col-form-label" for="input-normal" style="user-select: auto;">
                        メンバータイプ</label>
                    <div class="col-sm-6" style="user-select: auto;">
                        <input class="form-control" id="input-normal" type="text" name="input-normal"
                            placeholder="Normal" style="user-select: auto;">
                    </div>
                </div>

            </form>
        </div>
        <div class="card-footer" style="user-select: auto;">
            <button class="btn btn-sm btn-primary" type="submit" style="user-select: auto;">検索</button>
            <button class="btn btn-sm btn-danger" type="reset" style="user-select: auto;"> クリア</button>
        </div>
    </div>
    {{-- ここまで検索項目設定 --}}


    {{-- ここまからカード1 --}}
    <div class="row">
        <div class="col-lg-12" style="user-select: auto;">
            <div class="card" style="user-select: auto;">
                <div class="card-header" style="user-select: auto;"><i class="fa fa-align-justify"
                        style="user-select: auto;"></i> メンバー マスタ</div>
                <div class="card-body" style="user-select: auto;">
                    <table class="table table-responsive-sm table-bordered table-striped table-sm"
                        style="user-select: auto;">
                        <thead style="user-select: auto;">
                            <tr style="user-select: auto;">
                                <th style="user-select: auto;">氏名</th>
                                <th style="user-select: auto;">所属バンド</th>
                                <th style="user-select: auto;">楽器</th>
                                <th style="user-select: auto;">メンバータイプ</th>
                                <th style="user-select: auto;">詳細</th>
                                <th style="user-select: auto;">編集</th>
                                <th style="user-select: auto;">削除</th>
                            </tr>
                        </thead>
                        <tbody style="user-select: auto;">
                            <tr style="user-select: auto;">

                                @foreach ($bandMembers as $bandMember)
                            </tr>
                            <tr style="user-select: auto;">
                                <td style="user-select: auto;">{{ $bandMember->user->name }}</td>
                                <td style="user-select: auto;">{{ $bandMember->band->name }}</td>
                                <td style="user-select: auto;">{{ $bandMember->musical_instrument->name }}</td>
                                <td style="user-select: auto;">{{ $bandMember->user->user_role_id }}</td>

                                <td><a href="/members/show/{{$bandMember->id}}" class="btn btn-primary btn-sm">詳細</a>
                                </td>
                                <td><a href="/members/edit/{{$bandMember->id}}" class="btn btn-primary btn-sm">編集</a>
                                </td>
                                <td>
                                    <form method="post" action="/members/destroy/{{$bandMember->id}}">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="submit" value="削除" class="btn btn-danger btn-sm btn-destroy">
                                    </form>
                                </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>


                    {{-- ページネーション --}}
                    <nav style="user-select: auto;">
                        <ul class="pagination" style="user-select: auto;">
                            <li class="page-item" style="user-select: auto;"><a class="page-link"
                                    href="{{ $bandMembers->url(0) }}" style="user-select: auto;">Top</a></li>

                            @if ($bandMembers->previousPageUrl())
                            <li class="page-item" style="user-select: auto;"><a class="page-link"
                                    href="{{ $bandMembers->previousPageUrl() }}" style="user-select: auto;">Prev</a>
                            </li>
                            @endif


                            @if ($bandMembers->nextPageUrl())
                            <li class="page-item" style="user-select: auto;"><a class="page-link"
                                    href="{{ $bandMembers->nextPageUrl() }}" style="user-select: auto;">Next</a>
                            </li>
                            @endif

                            <li class="page-item" style="user-select: auto;"><a class="page-link"
                                    href="{{ $bandMembers->url($bandMembers->lastPage()) }}"
                                    style="user-select: auto;">End</a></li>

                            <li class="page-item active" style="user-select: auto;">{{ $bandMembers->currentPage() }}
                                / {{ $bandMembers->lastPage() }}</li>


                        </ul>
                    </nav>
                    {{-- pagenation link ------------------------------------------------------------------------------- --}}
                    <table width="100%">
                        <tr>
                            @if ($bandMembers->lastPage() > 1)
                            <td width="120px"><a href="{{ $bandMembers->url(0) }}">最初のページへ</a></td>
                            <td width="120px">
                                @if ($bandMembers->previousPageUrl())
                                <a href="{{ $bandMembers->previousPageUrl() }}">前のページへ</a>
                                @endif
                            </td>
                            <td width="120px" style="text-align: center">{{ $bandMembers->currentPage() }}
                                / {{ $bandMembers->lastPage() }}</td>
                            <td width="120px">
                                @if ($bandMembers->nextPageUrl())
                                <a href="{{ $bandMembers->nextPageUrl() }}">次のページへ</a>
                                @endif
                            </td>
                            <td width="120px"><a href="{{ $bandMembers->url($bandMembers->lastPage()) }}">最後のページへ</a>
                            </td>

                            @endif
                        </tr>
                    </table>
                    {{-- End of pagenation link ------------------------------------------------------------------------- --}}

                    {{-- ページネーションここまで --}}
                </div>
            </div>
        </div>
    </div>
    {{-- ここまでカード1 --}}


    <div id="app">
        <vue-bootstrap4-table />

    </div>
    <script src="{{ mix('js/app.js') }}"></script>


</div>



@endsection

{{-- このページに必要なスクリプトがあれば以下に追加 --}}


@section('add-script')
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
@section('javascript')

<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
<script src="{{ asset('js/main.js') }}" defer></script>
@endsection