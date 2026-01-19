<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Jobs\ProcessarLeiPdfJob;
use App\Models\Lei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
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
     * ⚠️ Propriedade pública NÃO tipada (regra do Orchid)
     */
    public $lei;

    /**
     * Dados auxiliares
     */
    public array $estados = [];
    public array $cidades = [];

    /**
     * Query inicial
     */
    public function query(Lei $lei, Request $request): iterable
    {
        $this->lei = $lei;

        // Estados (IBGE)
        $this->estados = Cache::remember('ibge_estados', 86400, function () {
            $response = Http::get(
                'https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome'
            );

            return $response->ok()
                ? collect($response->json())->pluck('sigla', 'sigla')->toArray()
                : [];
        });

        // Estado atual (input > banco)
        $estadoAtual = $request->input('lei.estado') ?? $lei->estado;

        // Cidades (IBGE)
        if ($estadoAtual) {
            $cacheKey = "ibge_cidades_{$estadoAtual}";

            $this->cidades = Cache::remember($cacheKey, 86400, function () use ($estadoAtual) {
                $response = Http::get(
                    "https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$estadoAtual}/municipios"
                );

                return $response->ok()
                    ? collect($response->json())->pluck('nome', 'nome')->toArray()
                    : [];
            });
        }

        return [
            'lei'     => $lei,
            'estados' => $this->estados,
            'cidades' => $this->cidades,
        ];
    }

    /**
     * Nome da tela
     */
    public function name(): string
    {
        return 'Editar Lei';
    }

    /**
     * Barra de ações
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Salvar')
                ->icon('bs.save')
                ->method('save'),

            Link::make('Revisar Importação')
                ->icon('bs.search')
                ->route('platform.leis.revisao', $this->lei)
                ->canSee($this->lei->status === 'processada'),

            Button::make('Reprocessar PDF')
                ->icon('bs.arrow-repeat')
                ->method('reprocessarPdf')
                ->confirm(
                    'O PDF será reprocessado. ' .
                    'Artigos automáticos serão recriados, ' .
                    'mas ajustes manuais serão preservados.'
                )
                ->canSee($this->lei->hasPdf()),
        ];
    }

    /**
     * Layout
     */
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
                        ->type('number')
                        ->title('Ano'),
                ]),

                Select::make('lei.abrangencia')
                    ->title('Abrangência')
                    ->options([
                        'federal'   => 'Federal',
                        'estadual'  => 'Estadual',
                        'municipal' => 'Municipal',
                    ])
                    ->empty('Selecione'),

                Group::make([
                    Select::make('lei.estado')
                        ->title('Estado (UF)')
                        ->options($this->estados)
                        ->empty('Selecione')
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
    | Actions
    |--------------------------------------------------------------------------
    */

    /**
     * Salva alterações da Lei
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

        // Se PDF foi alterado, sincroniza e reprocessa automaticamente
        if (! empty($data['pdf'])) {
            $lei->attachment()->sync($data['pdf']);
            $lei->update(['status' => 'processando']);
        }

        Toast::success('Lei atualizada com sucesso.');
    }

    /**
     * Reprocessa explicitamente o PDF
     */
    public function reprocessarPdf(Lei $lei): void
    {
        $attachment = $lei->attachment()
            ->orderBy('sort')
            ->first();

        if (! $attachment) {
            Toast::error('Nenhum PDF encontrado para esta lei.');
            return;
        }

        ProcessarLeiPdfJob::dispatch(
            $lei->id,
            $attachment->id
        );

        Toast::info('PDF enviado para reprocessamento.');
    }
}
