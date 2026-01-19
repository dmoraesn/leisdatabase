<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Models\Lei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class LeiEditScreen extends Screen
{
    /**
     * ⚠️ NÃO tipar esta propriedade no Orchid
     */
    public $lei;

    /**
     * Dados auxiliares para os selects
     */
    public array $estados = [];
    public array $cidades = [];

    public function query(Lei $lei, Request $request): iterable
    {
        $this->lei = $lei;

        // 1. Carregar Estados
        $this->estados = Cache::remember('ibge_estados', 86400, function () {
            $response = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');
            return $response->ok()
                ? collect($response->json())->pluck('sigla', 'sigla')->toArray()
                : [];
        });

        // 2. Definir o Estado Atual (Prioridade: Input do Usuário > Banco de Dados)
        $estadoAtual = $request->input('lei.estado') ?? $lei->estado;

        // 3. Carregar Cidades se houver estado definido
        if ($estadoAtual) {
            $cacheKey = "ibge_cidades_{$estadoAtual}";
            $this->cidades = Cache::remember($cacheKey, 86400, function () use ($estadoAtual) {
                $response = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$estadoAtual}/municipios");
                return $response->ok()
                    ? collect($response->json())->pluck('nome', 'nome')->toArray()
                    : [];
            });
        }

        return [
            'lei' => $lei,
            'estados' => $this->estados,
            'cidades' => $this->cidades,
        ];
    }

    public function name(): string
    {
        return 'Editar Lei';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Salvar')
                ->icon('bs.save')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([

                Input::make('lei.titulo')
                    ->title('Título')
                    ->required(),

                Group::make([
                    Input::make('lei.numero')
                        ->title('Número'),

                    Input::make('lei.ano')
                        ->title('Ano'),
                ]),

                Select::make('lei.abrangencia')
                    ->title('Abrangência')
                    ->options([
                        'federal'   => 'Federal',
                        'estadual'  => 'Estadual',
                        'municipal' => 'Municipal',
                    ]),

                Group::make([
                    Select::make('lei.estado')
                        ->title('Estado (UF)')
                        ->options($this->estados)
                        ->empty('Selecione')
                        // submitOnChange recarrega a tela para atualizar a lista de cidades
                        ->submitOnChange(),

                    Select::make('lei.cidade')
                        ->title('Cidade')
                        ->options($this->cidades)
                        ->empty('Selecione o Estado primeiro')
                        ->disabled(empty($this->cidades)),
                ]),

                Upload::make('lei.pdf')
                    ->title('PDF da Lei')
                    ->groups('leis')
                    ->acceptedFiles('.pdf')
                    ->maxFiles(1)
                    ->value(
                        $this->lei
                            ? $this->lei->attachment->pluck('id')->toArray()
                            : []
                    ),
            ]),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Action
    |--------------------------------------------------------------------------
    */

    public function save(Request $request, Lei $lei): void
    {
        $data = $request->get('lei');

        $lei->update([
            'titulo'      => $data['titulo'],
            'numero'      => $data['numero'] ?? null,
            'ano'         => $data['ano'] ?? null,
            'abrangencia' => $data['abrangencia'] ?? null,
            'estado'      => $data['estado'] ?? null,
            'cidade'      => $data['cidade'] ?? null,
        ]);

        if (! empty($data['pdf'])) {
            $lei->attachment()->sync($data['pdf']);
            $lei->update(['status' => 'processando']);
        }

        Toast::success('Lei atualizada com sucesso.');
    }
}