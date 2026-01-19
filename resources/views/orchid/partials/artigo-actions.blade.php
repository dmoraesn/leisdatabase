<div class="d-flex justify-content-center gap-1">

    <x-orchid-action
        method="openArtigoModal"
        :parameters="['artigo' => $artigo->id]"
        icon="bs.pencil"
        title="Editar"
        class="btn btn-sm btn-outline-primary" />

    <x-orchid-action
        method="deleteArtigo"
        :parameters="['id' => $artigo->id]"
        icon="bs.trash"
        title="Excluir"
        confirm="Deseja realmente excluir este artigo?"
        class="btn btn-sm btn-outline-danger" />

</div>
