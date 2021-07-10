@extends('dashboard.base')

@section('content')

<div class="container-fluid">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

                @if (session('flash_message'))
                <div class="flash_message alert alert-success alert-block">
                    {!! session('flash_message') !!}
                </div>
                @endif


                <div class="card">
                    <h5 class="card-header">記事投稿</h5>
                    <div class="card-body">
                        <form action="/function/qiita/send" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="select">公開範囲選択</label>
                                <select name="private" id="select" class="form-control">
                                    <option value="private">限定公開</option>
                                    <option value="public">一般公開</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tag">タグ</label>
                                <input type="text" id="tag" class="form-control" name="tag">
                            </div>
                            <div class="form-group">
                                <label for="title">タイトル</label>
                                <input type="text" id="title" class="form-control" name="title">
                            </div>
                            <div class="form-group">
                                <label for="textarea">本文</label>
                                <textarea id="textarea" class="form-control" name="body"></textarea>
                            </div>
                            <button class="btn btn-primary" type="submit">投稿</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    @endsection


    @section('javascript')

    @endsection