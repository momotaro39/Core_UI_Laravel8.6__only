@extends('dashboard.base')
{{-- ページのタイトルを挿入 --}}
@section('title', 'イベント一覧ページ')
{{-- メインコンテンツの内容を入れていきます --}}
@section('content')
<div class="container-fluid">

    <h1>編集</h1>

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
                    <!-- form Editの時はPUT -->

                    <form class="form-horizontal" action="{{ route("events.update",  $event->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- formデータ hiddenを設定 -->
                        @csrf
                        <!-- formデータ methodを設定 -->
                        @method('PUT')


                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">イベント名</label>
                            <div class="col-md-9">
                                <p class="form-control-static">データベースから出力</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="text-input">イベント名</label>
                            <div class="col-md-9">

                                <input class="form-control" id="text-input" type="text" value="{{$event->name}}" name="name" placeholder="">

                                <span class="help-block">００を入力してください</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="email-input">ホール</label>
                            <div class="col-md-9">
                                <input class="form-control" id="text-input" type="text" value="{{$event->hall->name}}" name="" placeholder="" autocomplete="email">
                                <span class="help-block">※ホール一覧を取得します ホール名を入力してください。</span>
                            </div>
                        </div>

                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-primary" type="submit"> 更新</button>
                    <button class="btn btn-sm btn-danger" type="reset"> リセット</button>
                </div>
            </div>
        </div>
    </div>

    </form>


</div>


@endsection


@section('add-script')
{{-- このページに必要なスクリプトがあれば以下に追加 --}}

@endsection