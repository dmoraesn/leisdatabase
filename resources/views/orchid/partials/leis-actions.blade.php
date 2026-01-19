<div class="d-flex justify-content-center gap-1">
    <a href="{{ route('platform.leis.edit', $lei) }}"
       class="btn btn-sm btn-outline-primary"
       title="Editar">
        <x-orchid-icon path="pencil"/>
    </a>

    <form method="POST"
          action="{{ route('platform.leis.list') }}"
          style="display:inline">
        @csrf
        <input type="hidden" name="id" value="{{ $lei->id }}">
        <button class="btn btn-sm btn-outline-warning"
                formaction="{{ route('platform.leis.list', ['method' => 'reprocessar']) }}"
                title="Reprocessar">
            <x-orchid-icon path="reload"/>
        </button>
    </form>
</div>
