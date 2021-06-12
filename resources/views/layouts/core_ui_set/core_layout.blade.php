{{-- head情報はここから --}}
@include('layouts.core_ui_set.head')
{{-- head情報はここまで --}}

<body class="c-app">
    {{-- サイドバーはここから --}}
    @include('layouts.core_ui_set.sidebar')
    {{-- サイドバーはここまで --}}

    {{-- コンテンツ部分はここから --}}
    <div class="c-wrapper c-fixed-components">
        @include('layouts.core_ui_set.main-total')
    </div>
    @include('layouts.core_ui_set.script-read')

</body>

</html>
