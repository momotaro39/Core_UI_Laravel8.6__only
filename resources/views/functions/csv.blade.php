@extends('dashboard.base')

@section('content')

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>追加機能AXIOS</div>
                    <div class="card-body">
                        <h1>Laravel で CSV インポート 演習</h1>
                        <p>CSVファイルを csv_users テーブルに登録します。</p>
                        <form action="" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <label class="col-1 text-right" for="form-file-1">File:</label>
                                <div class="col-11">
                                    <div class="custom-file">
                                        <input type="file" name="csv" class="custom-file-input" id="customFile">
                                        <label class="custom-file-label" for="customFile"
                                            data-browse="参照">ファイル選択...</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">送信</button>
                        </form>

                        {!! $lists->render() !!}
                        <br>
                        <table class="table table-striped">
                            <tr>
                                <th>name</th>
                                <th>email</th>
                                <th>password</th>
                            </tr>
                            @foreach($lists as $list)
                            <tr>
                                <td>{{$list->name}}</td>
                                <td>{{$list->email}}</td>
                                <td>{{$list->password}}</td>
                            </tr>
                            @endforeach
                        </table>

                        <form action="{{url('/csv/search')}}" method="GET">
                            {{ csrf_field() }}
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="名前を入力してください">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default">検索</button>
                                </span>
                            </div>
                        </form>
                        <p><a class="btn btn-primary" href="{{url('/csv/download1')}}" target="_blank"> CSV download
                                その1</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/app.js') }}"></script>
<script>
// ファイルを選択すると、コントロール部分にファイル名を表示
$('.custom-file-input').on('change', function() {
    $(this).next('.custom-file-label').html($(this)[0].files[0].name);
})
</script>


@if(Session::has('flashmessage'))
<script>
$(window).on('load', function() {
    $('#myModal').modal('show');
});
</script>

<!-- モーダルウィンドウの中身 -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                {{ session('flashmessage') }}
            </div>
            <div class="modal-footer text-center">
            </div>
        </div>
    </div>
</div>
@endif

@endsection


@section('javascript')

@endsection