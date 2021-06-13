@extends('dashboard.base')
{{-- ページのタイトルを挿入 --}}
@section('title','タイトル名を入力')
{{-- メインコンテンツの内容を入れていきます --}}
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">バンドメンバー新規登録</div>

                <!-- form -->
                <!-- TODO アクション名はWeb .phpで変更する -->
                <!-- メソッド名は大文字でも小文字でも良いっぽい -->
                <form action="/users/update/{{$bandMember->id}}" method="POST">
                    @csrf
                    <!-- こちらで入れても良い -->
                    <!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->


                    <!-- Valueの中身をeditの場合は帰る -->
                    <p>氏名：<input type="text" name="name" value="{{$bandMember->name}}"></p>
                    <p>所属バンド番号：<input type="text" name="band_id" value="{{$bandMember->band}}"></p>
                    <p style="font-size: 0.75em">現在登録しているバンド 1 見本バンド, 2 アコースティックバンド, 3 ロックバンド</p>
                    <p>郵便番号：<input type="text" name="post" value="{{$bandMember->post}}"></p>
                    <p>住所：<input type="text" name="address" value="{{$bandMember->address}}"></p>
                    <p>メールアドレス：<input type="text" name="email" value="{{$bandMember->email}}></p>
                    <p>生年月日：<input type=" text" name="birth" value="{{{$bandMember->birth}}"></p>
                    <p>電話番号：<input type="text" name="phone" value="{{$bandMember->phone}}"></p>
                    <p>クレーマーフラグ：<input type="text" name="claimer_flag" value="{{$bandMember->claimer_flag}}"></p>
                    <p style="font-size: 0.75em">0 問題ないメンバー</p>
                    <p style="font-size: 0.75em">1 問題のあるメンバー</p>

                    <p style="text-align: center"><button class="btn btn-primary" type="submit"> 更 新 </button></p>
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
@section('javascript')

<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
<script src="{{ asset('js/main.js') }}" defer></script>
@endsection