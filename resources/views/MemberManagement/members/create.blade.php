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


                <!-- /.row-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><strong>Basic Form</strong> Elements</div>
                            <div class="card-body">

                                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Static</label>
                                        <div class="col-md-9">
                                            <p class="form-control-static">Username</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="text-input">Text Input</label>
                                        <div class="col-md-9">

                                            <input class="form-control" id="text-input" type="text" name="text-input"
                                                placeholder="Text">

                                            <span class="help-block">This is a help text</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="email-input">Email Input</label>
                                        <div class="col-md-9">
                                            <input class="form-control" id="email-input" type="email" name="email-input"
                                                placeholder="Enter Email" autocomplete="email"><span
                                                class="help-block">Please
                                                enter your email</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="password-input">Password</label>
                                        <div class="col-md-9">
                                            <input class="form-control" id="password-input" type="password"
                                                name="password-input" placeholder="Password"
                                                autocomplete="new-password"><span class="help-block">Please enter a
                                                complex password</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-primary" type="submit"> 送信</button>
                                <button class="btn btn-sm btn-danger" type="reset"> リセット</button>
                            </div>
                        </div>


                    </div>

                </div>

                <!-- form -->
                <form action="/core/members" method="POST">
                    @csrf
                    <!-- こちらで入れても良い -->
                    <!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->


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

                    <p style="text-align: center"><button class="btn btn-primary" type="submit"> 登 録 </button></p>
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