<style>
    ul {
        padding-left: 15px;
    }
    li {
        margin: 5px 0;
    }
</style>
<li>
    <div class="d-flex align-items-center">
        @if ($head->children->isNotEmpty())
            <span class="arrow-toggle" style="cursor: pointer; margin-right: 8px;">
                &#9656; <!-- Right Arrow (default) -->
            </span>
        @endif
        <span class="toggle-head" style="cursor: pointer;" data-id="{{ $head->id }}">
            {{ $head->name }}
        </span>
    </div>

    @if ($head->children->isNotEmpty())
        <ul class="child-heads" style="list-style-type: none; display: none; padding-left: 20px;">
            @foreach ($head->children as $child)
                @include('account_heads.partials.head', ['head' => $child])
            @endforeach
        </ul>
    @endif
</li>

