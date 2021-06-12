@extends('layouts.core_ui_set.core_layout')
{{-- ページのタイトルを挿入 --}}
@section('title','タイトル名を入力')
{{-- メインコンテンツの内容を入れていきます --}}
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">バンドメンバー新規登録</div>
                    <form action="/members" method="POST">
                        @csrf
                        <p>氏名：<input type="text" name="name" value="{{ old('name') }}"></p>
                        <p>所属バンド番号：<input type="text" name="band_id" value="{{ old('band') }}"></p>
                        <p style="font-size: 0.75em">現在登録しているバンド 1 見本バンド, 2 アコースティックバンド, 3 ロックバンド</p>
                        <p>郵便番号：<input type="text" name="post" value="{{ old('post') }}"></p>
                        <p>住所：<input type="text" name="address" value="{{ old('address') }}"></p>
                        <p>メールアドレス：<input type="text" name="email" value="{{ old('email') }}"></p>
                        <p>生年月日：<input type="text" name="birth" value="{{ old('birth') }}"></p>
                        <p>電話番号：<input type="text" name="phone" value="{{ old('phone') }}"></p>
                        <p>クレーマーフラグ：<input type="text" name="claimer_flag" value="{{ old('claimer_flag') }}"></p>
                        <p style="font-size: 0.75em">0 問題ないメンバー</p>
                        <p style="font-size: 0.75em">1 問題のあるメンバー</p>

                        <p style="text-align: center"><button class="btn btn-primary" type="submit">  登  録 </button></p>
                    </form>
                    {{-- エラーがあった場合エラー内容を表示--}}
                    @if( $errors->any() )
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-script')
{{-- このページに必要なスクリプトがあれば以下に追加 --}}

@endsection