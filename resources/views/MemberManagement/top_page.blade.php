@extends('dashboard.base')
{{-- ページのタイトルを挿入 --}}
@section('title', 'タイトル名を入力')
{{-- メインコンテンツの内容を入れていきます --}}
@section('content')
{{-- ここからひとつのセットで掲載 --}}
<div class="container-fluid">
    <div class="fade-in">


        <div class="card">
            <div class="card-header"> TOPページの一覧<small>一覧系はここに掲載します</small></div>
            <div class="card-body">
                <div class="list-group"><a class="list-group-item active">マスタ一覧セット</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/adminroles') }}">管理役割マスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/userroles') }}">利用者役割マスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/goodstype') }}">バンドグッズタイプマスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/musicalinstrument') }}">楽器マスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/users') }}">ユーザーマスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/members') }}">バンドメンバーマスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/bands') }}">バンド名マスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/bandadmin') }}">バンド管理者マスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/bandgoods') }}">バンドグッズマスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/albums') }}">アルバムマスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/events') }}">イベントマスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/halls') }}">イベントホールマスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/labels') }}">レーベルマスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/musics') }}">曲マスタ</a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/entries') }}">バンドエントリー </a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/performancelists') }}">イベントセットリスト一覧 </a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/guestreservation') }}">イベント予約状況一覧 </a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/proceeds') }}">イベント売上一覧 </a>
                    <a class="list-group-item list-group-item-action" href="{{ url('/core/ticketlists') }}">チケットリスト一覧 </a>

                    <a class="list-group-item list-group-item-action disabled" href="#">リンクなし 見本</a>
                </div>
            </div>
            <div class="card-body">
                <div class="list-group"><a class="list-group-item active" href="#">登録一覧セット</a>
                    <a class="list-group-item list-group-item-action" href="#">役割 新規登録</a>
                    <a class="list-group-item list-group-item-action" href="#">レーベル 新規登録</a>
                    <a class="list-group-item list-group-item-action" href="#">バンド 新規登録</a>
                    <a class="list-group-item list-group-item-action" href="#">バンドメンバー 新規登録</a>
                    <a class="list-group-item list-group-item-action" href="#">バンドグッズ 新規登録</a>
                    <a class="list-group-item list-group-item-action" href="#">グッズ種類 新規登録</a>
                    <a class="list-group-item list-group-item-action" href="#">楽器 新規登録</a>
                    <a class="list-group-item list-group-item-action" href="#">アルバム 新規登録</a>
                    <a class="list-group-item list-group-item-action" href="#">曲 新規登録</a>
                    <a class="list-group-item list-group-item-action" href="#">イベント 新規登録</a>
                    <a class="list-group-item list-group-item-action" href="#">ホール一覧 新規登録</a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@section('add-script')
{{-- このページに必要なスクリプトがあれば以下に追加 --}}

@endsection