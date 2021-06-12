        {{-- ここからコンテンツの部分 --}}
        <div class="c-body">
            <main class="c-main">
                @yield('content')
            </main>
            {{-- コンテンツのメイン部分はここまで --}}
            {{-- ここからフッターの部分 --}}
            @include('layouts.core_ui_set.footer')
        </div>
