@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">

                        {{-- アラートを設定します。 --}}
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- ここは権限者のみ表示すること --}}
                        <ul>
                            @canany('viewAny', auth()->user())
                                <li><a href="/users">ユーザー一覧 全員分
                                    </a></li>
                                <p>管理者のみに表示されています</p>
                            @endcanany
                            {{-- これは全員が表示される --}}
                            <li><a href="/roles">役割一覧</a></li>
                            <li><a href="/bands">バンド一覧</a></li>
                            <li><a href="/bands/create">メンバー新規登録</a></li>
                            <li><a href="/band_search">メンバー検索</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
