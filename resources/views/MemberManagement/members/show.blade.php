@extends('layouts.core_ui_set.core_layout')
{{-- ページのタイトルを挿入 --}}
@section('title','タイトル名を入力')
{{-- メインコンテンツの内容を入れていきます --}}
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ $bandMembers->name }}({{ $bandMembers->id }})</div>
                    <p>バンド{{ $bandMembers->band['name'] }}</p>
                    <p>郵便番号：{{ $bandMembers->post }}</p>
                    <p>住所：{{ $bandMembers->address }}</p>
                    <p>メール：{{ $bandMembers->email }}</p>
                    <p>生年月日：{{ $bandMembers->birth }}</p>
                    <p>電話番号：{{ $bandMembers->phone }}</p>
                    <p>クレーマーフラグ：{{ $bandMembers->claimer_flag }}</p>
                    <p>更新日：{{ $bandMembers->created_at }}</p>
                    <p>登録日：{{ $bandMembers->updated_at }}</p>

                </div>
                <br/>
                <div class="card">
                    <form action="/bandMembers/{{$bandMembers->id}}/logs" method="POST">
                        @csrf
                        Log: <input type="text" name="log" value="{{old('log')}}">
                        <button type="submit" class="btn btn-sm btn-outline-primary">投稿</button>
                    </form>

                </div>
                <br/>
                <div class="card">
                    <ul>
                        @foreach($bandMembers->bandMembersLogs as $log)
                            <li>
                                {{ $log->log }}<br/>
                                記入時刻：{{ $log->created_at }} <br/>
                                記入者：{{ $log->user->name }}<br/>
                                <br/>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('add-script')
{{-- このページに必要なスクリプトがあれば以下に追加 --}}

@endsection