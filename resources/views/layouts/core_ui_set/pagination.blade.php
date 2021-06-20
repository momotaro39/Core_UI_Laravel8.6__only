{{-- pagenation link パターン1------------------------------------------------------------------------------- --}}

{{-- <div class="pagination pull-right">
    <span class="page">
        全 {{ $listCount }} 件中

{{ $paginateNum * ($list->currentPage() - 1) + 1 }} - {{ $paginateNum * $list->currentPage() }} 件目

@if ($list->currentPage() == 1)
最初 前の{{ $paginateNum }}件
@else
<a href="{{ url($list->appends($conditions)->url(1)) }}">最初</a>
<a href="{{ url($list->appends($conditions)->previousPageUrl()) }}">前の{{ $paginateNum }}件</a>
@endif

{{ $list->currentPage() }}

@if ($list->currentPage() == $list->lastPage())
次の{{ $paginateNum }}件 最後
@else
<a href="{{ url($list->appends($conditions)->nextPageUrl()) }}">次の{{ $paginateNum }}件</a>
<a href="{{ url($list->appends($conditions)->url($list->lastPage())) }}">最後</a>
@endif

</span>
</div> --}}

{{-- End of pagenation link ------------------------------------------------------------------------- --}}


{{-- pagenation link パターン2------------------------------------------------------------------------------- --}}
{{-- ページネーション --}}
{{-- <nav style="user-select: auto;">
    <ul class="pagination" style="user-select: auto;">

        <li class="page-item" style="user-select: auto;"><a class="page-link" href="{{ $bandMembers->url(0) }}"
style="user-select: auto;">Top</a></li>

@if ($bandMembers->previousPageUrl())
<li class="page-item" style="user-select: auto;"><a class="page-link" href="{{ $bandMembers->previousPageUrl() }}"
        style="user-select: auto;">Prev</a></li>
@endif


@if ($bandMembers->nextPageUrl())
<li class="page-item" style="user-select: auto;"><a class="page-link" href="{{ $bandMembers->nextPageUrl() }}"
        style="user-select: auto;">Next</a>
</li>
@endif

<li class="page-item" style="user-select: auto;"><a class="page-link"
        href="{{ $bandMembers->url($bandMembers->lastPage()) }}" style="user-select: auto;">End</a></li>

<li class="page-item active" style="user-select: auto;">{{ $bandMembers->currentPage() }}
    / {{ $bandMembers->lastPage() }}</li>


</ul>
</nav> --}}
{{-- End of pagenation link ------------------------------------------------------------------------- --}}




{{-- pagenation link パターン3------------------------------------------------------------------------------- --}}
{{-- <table width="100%">
    <tr>
        @if ($bandMembers->lastPage() > 1)
            <td width="120px"><a href="{{ $bandMembers->url(0) }}">最初のページへ</a></td>

<td width="120px">
    @if ($bandMembers->previousPageUrl())
    <a href="{{ $bandMembers->previousPageUrl() }}">前のページへ</a>
    @endif
</td>

<td width="120px" style="text-align: center">{{ $bandMembers->currentPage() }}
    / {{ $bandMembers->lastPage() }}</td>

<td width="120px">
    @if ($bandMembers->nextPageUrl())
    <a href="{{ $bandMembers->nextPageUrl() }}">次のページへ</a>
    @endif
</td>
<td width="120px"><a href="{{ $bandMembers->url($bandMembers->lastPage()) }}">最後のページへ</a>
</td>

@endif
</tr>
</table> --}}
{{-- End of pagenation link ------------------------------------------------------------------------- --}}

{{-- ページネーションここまで --}}


{{-- pagenation link パターン4------------------------------------------------------------------------------- --}}
<nav style="user-select: auto;">
    <ul class="pagination" style="user-select: auto;">
        <li class="page-item" style="user-select: auto;"><a class="page-link" href="#"
                style="user-select: auto;">Prev</a></li>
        <li class="page-item active" style="user-select: auto;"><a class="page-link" href="#"
                style="user-select: auto;">1</a></li>
        <li class="page-item" style="user-select: auto;"><a class="page-link" href="#" style="user-select: auto;">2</a>
        </li>
        <li class="page-item" style="user-select: auto;"><a class="page-link" href="#" style="user-select: auto;">3</a>
        </li>
        <li class="page-item" style="user-select: auto;"><a class="page-link" href="#" style="user-select: auto;">4</a>
        </li>
        <li class="page-item" style="user-select: auto;"><a class="page-link" href="#"
                style="user-select: auto;">Next</a></li>
    </ul>
</nav>

{{-- End of pagenation link ------------------------------------------------------------------------- --}}


{{-- pagenation link パターン５ ブートストラップのライブラリ使用------------------------------------------------------------------------------- --}}



{{-- End of pagenation link ------------------------------------------------------------------------- --}}