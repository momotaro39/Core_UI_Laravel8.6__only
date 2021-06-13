@extends('dashboard.base')
{{-- ページのタイトルを挿入 --}}
@section('title', 'イベント一覧ページ')
{{-- メインコンテンツの内容を入れていきます --}}
@section('content')
<div class="container-fluid">

    <h1>新規作成</h1>

    <div class="row">
        <div class="col-sm-12">
            <a href="/core/events" class="btn btn-primary" style="margin:20px;">一覧に戻る</a>
        </div>
    </div>



    <!-- Formのリスト-->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>Form</strong>
                </div>
                <div class="card-body">


                    <!-- formデータスタート -->

                    <form aclass="form-horizontal" action="/core/events/">
                        <!-- formデータ hiddenを設定 -->
                        @csrf
                        <!-- formデータ methodを設定 -->
                        @method('POST')





                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="text-input">イベント名</label>
                            <div class="col-md-9">

                                <input class="form-control" id="text-input" type="text" name="name" placeholder="">

                                <span class="help-block">００を入力してください</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="email-input">ホール</label>
                            <div class="col-md-9">
                                <input class="form-control" id="text-input" type="text" name="" placeholder="">
                                <span class="help-block">ホール名を入力してください。</span>
                            </div>
                        </div>

                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-primary" type="submit"> 登録</button>
                    <button class="btn btn-sm btn-danger" type="reset"> リセット</button>
                </div>
                </form>
            </div>
        </div>
    </div>



</div>


@endsection


@section('add-script')
{{-- このページに必要なスクリプトがあれば以下に追加 --}}

@endsection