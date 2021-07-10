@extends('dashboard.base')

@section('content')

<div class="container-fluid">
    <div class="main">
        <div class="header">
            <div class="header__title">
                <h1 class="header__title__main">
                    Youtube Title List;
                </h1>
                <p class="header__title__sub">
                    This is Youtube Title GET;
                </p>
            </div>
        </div>
        <div class="content">
            <div class="content__body">
                @foreach ($snippets as $snippet)
                <div class="content__body__videos">
                    <p>
                        {{ $snippet->title }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/app.js') }}"></script>
@endsection


@section('javascript')

@endsection