@extends('layouts.core_ui_set.core_layout')
{{-- ページのタイトルを挿入 --}}
@section('title', 'タイトル名を入力')
    {{-- メインコンテンツの内容を入れていきます --}}
@section('content')
    {{-- ここからひとつのセットで掲載 --}}
    <div class="container-fluid">
        <div class="fade-in">


            {{-- ここまからカード1 --}}
            <div class="row">
                <div class="col-lg-12" style="user-select: auto;">
                    <div class="card" style="user-select: auto;">
                        <div class="card-header" style="user-select: auto;"><i class="fa fa-align-justify"
                                style="user-select: auto;"></i> 利用役割一覧</div>
                        <div class="card-body" style="user-select: auto;">
                            <table class="table table-responsive-sm table-bordered table-striped table-sm"
                                style="user-select: auto;">
                                <thead style="user-select: auto;">
                                    <tr style="user-select: auto;">
                                        <th style="user-select: auto;">ID</th>
                                        <th style="user-select: auto;">役割コード</th>
                                        <th style="user-select: auto;">役割名</th>
                                        <th style="user-select: auto;">ステータス</th>
                                    </tr>
                                </thead>
                                <tbody style="user-select: auto;">
                                    <tr style="user-select: auto;">

                                        @foreach ($userRoles as $userRole)
                                    </tr>
                                    <tr style="user-select: auto;">
                                        <td style="user-select: auto;">{{ $userRole->id }}</td>
                                        <td style="user-select: auto;">{{ $userRole->name }}</td>
                                        <td style="user-select: auto;">{{ $userRole->memo }}</td>
                                        <td style="user-select: auto;">
                                            <span class="badge badge-success" style="user-select: auto;">Active</span><span
                                                class="badge badge-danger" style="user-select: auto;">Banned</span><span
                                                class="badge badge-secondary"
                                                style="user-select: auto;">Inactive</span><span class="badge badge-warning"
                                                style="user-select: auto;">Pending</span>
                                        </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>



                            {{-- ページネーション --}}

                            @include('layouts.core_ui_set.pagination')

                            {{-- ページネーションここまで --}}
                        </div>
                    </div>
                </div>
            </div>
            {{-- ここまでカード1 --}}


        </div>
    </div>
@endsection

@section('add-script')
    {{-- このページに必要なスクリプトがあれば以下に追加 --}}

@endsection
