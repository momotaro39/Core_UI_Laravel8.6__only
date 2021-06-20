@extends('dashboard.base')
{{-- ページのタイトルを挿入 --}}
@section('title', 'イベント一覧ページ')

@section('css')

<!-- BootstrapのCSS読み込み -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery読み込み -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<!-- BootstrapのJS読み込み -->
<script src="js/bootstrap.min.js"></script>

<!-- tablednd読み込み -->
<script type="text/javascript" src="comn/jquery.tablednd.0.7.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>

@endsection

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
                    <form action="{{ route("events.store") }}" method="post">
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
                                <input class="form-control" id="text-input" type="text" name="hall_id" placeholder="">
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>Form</strong>
                </div>
                <div class="card-body">

                    <!-- クラス名を入れておくこと -->
                    <table class="table tablesorter-bootstrap table-management">
                        <thead>
                            <tr class="nodrop nodrag">
                                <th style="width: 5%;"></th>
                                <th style="width: 30%;">名称</th>
                                <th style="width: 30%;">ホールID</th>
                                <th style="width: 5%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                                <!-- 三本線の画像 -->
                                <td class="handle"><img src="{{ asset('/icons/TableCrud/handle.png') }} "></td>
                                <td><input class="form-control" id="text-input" type="text" name="name" placeholder="">
                                </td>
                                <td><input class="form-control" id="text-input" type="text" name="hall_id"
                                        placeholder=""></td>


                                <!-- マイナスボタンの画像 -->
                                <td class=" MinusBtn"><img src="{{ asset('/icons/TableCrud/minus.png') }}"></td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- プラスボタンの画像 -->
                    <img class="PlusBtn" src="{{ asset('/icons/TableCrud/plus.jpg') }}">
                    <!-- 好きな三本線の画像とマイナスボタンの画像とプラスボタンの画像を用意します。 -->
                </div>
            </div>
        </div>
    </div>




    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>Form2</strong>
                </div>
                <div class="card-body">

                    <!-- formデータスタート -->
                    <form action="{{ route("events.store") }}" method="post">
                        <!-- formデータ hiddenを設定 -->
                        @csrf
                        <!-- formデータ methodを設定 -->
                        @method('POST')


                        <button id="btn_add_row_list" class="btn btn-primary" type="button">追加する</button>

                        <table id="row_list" class="table tablesorter-bootstrap ">
                            <thead>
                                <tr>
                                    <th style="width: 5%;"></th>
                                    <th style="width: 30%;">イベント名称</th>
                                    <th style="width: 30%;">ホールID</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="handle"><img src="{{ asset('/icons/TableCrud/handle.png') }} "></td>
                                    <td><input name="name[]" class="form-control" type="text"
                                            placeholder="名前を入力してください" />
                                    </td>
                                    <td><input name="email[]" class="form-control" type="text"
                                            placeholder="メールアドレスを入力してください" /></td>
                                    <td><button class="btn btn-danger" type="button">削除</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-sm btn-primary" type="submit"> 登録</button>
                    </form>
                </div>
            </div>
        </div>
    </div>












    @endsection


    @section('add-script')
    {{-- このページに必要なスクリプトがあれば以下に追加 --}}
    <script src="{{ asset('js/TableAddRow.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqgrid/4.6.0/plugins/jquery.tablednd.js"></script>
    @endsection