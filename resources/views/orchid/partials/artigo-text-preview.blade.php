<div>
    <div class="text-muted small mb-1">
        {{ \Illuminate\Support\Str::limit($artigo->texto, 220) }}
    </div>

    <x-orchid-action
        method="openArtigoModal"
        :parameters="['artigo' => $artigo->id]"
        class="link-primary small">
        Ver texto completo
    </x-orchid-action>
</div>
