<div class="bg-white rounded shadow-sm p-4 h-100" data-controller="location-selector">
    
    <div class="mb-3">
        <h4 class="text-black font-thin">Localização Geográfica</h4>
        <p class="text-muted small">Selecione o estado e a cidade de abrangência da lei.</p>
        <hr class="my-2">
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="form-group">
                <label for="uf" class="form-label">Estado (UF) <sup class="text-danger">*</sup></label>
                <select name="lei[estado]" 
                        id="uf" 
                        class="form-control" 
                        required>
                    <option value="">Carregando...</option>
                </select>
            </div>
        </div>

        <div class="col-md-8">
            <div class="form-group position-relative">
                <label for="cidade" class="form-label">Cidade <sup class="text-danger">*</sup></label>
                <select name="lei[cidade]" 
                        id="cidade" 
                        class="form-control" 
                        disabled 
                        required>
                    <option value="">Selecione um estado primeiro...</option>
                </select>
                
                <div id="city-loading" class="position-absolute end-0 bottom-0 mb-2 me-3 d-none text-primary small">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </div>
            </div>
        </div>
    </div>

    <div id="error-message" class="alert alert-danger mt-3 d-none d-flex align-items-center" role="alert">
        <span id="error-text"></span>
    </div>
</div>

<script>
document.addEventListener('turbo:load', function() {
    const initialUf = @json($lei->estado ?? old('lei.estado'));
    const initialCity = @json($lei->cidade ?? old('lei.cidade'));

    const urlEstados = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome';
    const urlCidades = (uf) => `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios?orderBy=nome`;

    const selectUf = document.getElementById('uf');
    const selectCidade = document.getElementById('cidade');
    const cityLoading = document.getElementById('city-loading');
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');

    if (!selectUf || !selectCidade) return;

    function showError(msg) {
        errorMessage.classList.remove('d-none');
        errorText.textContent = msg;
    }

    function hideError() {
        errorMessage.classList.add('d-none');
    }

    async function carregarEstados() {
        try {
            const response = await fetch(urlEstados);
            if (!response.ok) throw new Error('Falha na API IBGE');
            
            const estados = await response.json();
            selectUf.innerHTML = '<option value="">Selecione...</option>';

            estados.forEach(estado => {
                const option = document.createElement('option');
                option.value = estado.sigla;
                option.textContent = `${estado.nome} (${estado.sigla})`;
                if (initialUf && estado.sigla === initialUf) option.selected = true;
                selectUf.appendChild(option);
            });

            if (initialUf) await carregarCidades(initialUf);

        } catch (error) {
            console.error(error);
            showError('Erro ao carregar estados.');
        }
    }

    async function carregarCidades(uf) {
        selectCidade.disabled = true;
        selectCidade.innerHTML = '<option value="">Carregando...</option>';
        cityLoading.classList.remove('d-none');
        hideError();

        try {
            const response = await fetch(urlCidades(uf));
            if (!response.ok) throw new Error('Falha na API IBGE');

            const cidades = await response.json();
            selectCidade.innerHTML = '<option value="">Selecione...</option>';

            cidades.forEach(cidade => {
                const option = document.createElement('option');
                option.value = cidade.nome;
                option.textContent = cidade.nome;
                if (initialCity && cidade.nome === initialCity) option.selected = true;
                selectCidade.appendChild(option);
            });

            selectCidade.disabled = false;

        } catch (error) {
            console.error(error);
            showError(`Erro ao carregar cidades.`);
            selectCidade.innerHTML = '<option value="">Erro</option>';
        } finally {
            cityLoading.classList.add('d-none');
        }
    }

    selectUf.addEventListener('change', (e) => {
        const uf = e.target.value;
        if (uf) {
            carregarCidades(uf);
        } else {
            selectCidade.innerHTML = '<option value="">Selecione um estado primeiro...</option>';
            selectCidade.disabled = true;
        }
    });

    carregarEstados();
});
</script>