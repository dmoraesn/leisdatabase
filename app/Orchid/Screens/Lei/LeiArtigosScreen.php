<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Models\Artigo;
use App\Models\Lei;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class LeiArtigosScreen extends Screen
{
    /**
     * @var Lei
     */
    public $lei;

    /**
     * Query data.
     */
    public function query(Lei $lei): iterable
    {
        return [
            'lei' => $lei,
            // Mantemos a ordenação por 'ordem' no banco para garantir a sequência correta
            'artigos' => $lei->artigos()
                ->orderBy('ordem', 'asc')
                ->orderBy('id', 'asc')
                ->paginate(20),
        ];
    }

    /**
     * Nome da tela.
     */
    public function name(): ?string
    {
        return 'Gerenciamento de Artigos: ' . ($this->lei->titulo ?? 'Nova Lei');
    }

    /**
     * Descrição.
     */
    public function description(): ?string
    {
        return 'Visualize, edite ou adicione novos artigos manualmente para a lei: ' . ($this->lei->numero ?? 'S/N');
    }

    /**
     * Barra de comandos.
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Voltar')
                ->icon('bs.arrow-left')
                ->route('platform.leis.list'),

            ModalToggle::make('Adicionar Artigo')
                ->modal('artigoModal')
                ->method('saveArtigo')
                ->icon('bs.plus-lg')
                ->class('btn btn-primary'),
        ];
    }

    /**
     * Layout.
     */
    public function layout(): iterable
    {
        return [
            Layout::table('artigos', [
                
                // --- REMOVIDA A COLUNA 'ORDEM' VISUALMENTE ---
                // A ordem continua existindo no banco para fins de classificação,
                // mas não polui mais a tela do usuário.

                TD::make('numero', 'Artigo') // Renomeado para "Artigo" para ficar mais natural
                    ->width(100)
                    ->render(fn (Artigo $a) => 
                        // Destaque visual para o número
                        "<span class='text-dark fw-bold' style='font-size: 1.1em;'>{$a->numero}</span>"
                    ),

                TD::make('texto', 'Conteúdo')
                    ->render(function (Artigo $a) {
                        return ModalToggle::make(Str::limit($a->texto, 140))
                            ->modal('artigoModal')
                            ->method('saveArtigo')
                            ->asyncParameters(['artigo_id' => $a->id])
                            ->style('text-decoration: none; color: #57606a; display: block;');
                    }),

                TD::make('confidence', 'Confiança')
                    ->width(100)
                    ->alignCenter()
                    ->render(fn (Artigo $a) => new HtmlString(match ($a->confidence) {
                        'high'   => '<span class="badge bg-success">Alta</span>',
                        'medium' => '<span class="badge bg-warning text-dark">Média</span>',
                        'low'    => '<span class="badge bg-danger">Baixa</span>',
                        default  => '<span class="badge bg-light text-dark">N/A</span>',
                    })),

                TD::make('origem', 'Origem')
                    ->width(100)
                    ->alignCenter()
                    ->render(fn (Artigo $a) => $a->origem === 'manual'
                        ? '<span class="badge bg-info text-dark">Manual</span>'
                        : '<span class="badge bg-light text-dark border">Auto</span>'),
                
                TD::make('actions', 'Ações')
                    ->alignRight()
                    ->width(80)
                    ->render(fn (Artigo $a) => Button::make('')
                        ->icon('bs.trash')
                        ->confirm('Tem certeza que deseja remover este artigo?')
                        ->method('removeArtigo', ['id' => $a->id])
                        ->class('btn btn-link text-danger')
                    ),
            ]),

            // Modal de Edição / Criação
            Layout::modal('artigoModal', Layout::rows([
                Input::make('artigo.id')
                    ->type('hidden'),

                Input::make('artigo.numero')
                    ->title('Numeração (Ex: 1º, 22-A)')
                    ->required(),

                // Mantemos o campo Ordem no modal para casos de ajuste fino,
                // mas ele não aparece mais na tabela principal.
                Input::make('artigo.ordem')
                    ->type('number')
                    ->title('Ordem Sequencial')
                    ->help('Use este campo apenas para reordenar artigos fora de sequência.'),

                TextArea::make('artigo.texto')
                    ->title('Texto Completo')
                    ->rows(12)
                    ->required()
                    ->style('font-family: monospace; font-size: 14px; line-height: 1.5;'),
            ]))
            ->async('asyncGetArtigo')
            ->title('Editar / Criar Artigo')
            ->size('modal-lg')
            ->applyButton('Salvar Artigo'),
        ];
    }

    /**
     * Carrega dados para o modal.
     */
    public function asyncGetArtigo(Lei $lei, Request $request): array
    {
        $artigoId = $request->get('artigo_id');
        $artigo = Artigo::find($artigoId);

        return [
            'artigo' => $artigo ?? new Artigo(),
        ];
    }

    /**
     * Salva o artigo.
     */
    public function saveArtigo(Lei $lei, Request $request): void
    {
        $dados = $request->validate([
            'artigo.id'     => 'nullable|integer',
            'artigo.numero' => 'required|string|max:50',
            'artigo.texto'  => 'required|string',
            'artigo.ordem'  => 'nullable|integer',
        ]);

        $artigo = Artigo::findOrNew($dados['artigo']['id']);

        $artigo->fill([
            'lei_id'     => $lei->id,
            'numero'     => $dados['artigo']['numero'],
            'texto'      => $dados['artigo']['texto'],
            'ordem'      => $dados['artigo']['ordem'] ?? 0,
            'origem'     => 'manual', 
            'confidence' => 'high',
        ]);

        $artigo->save();
        Toast::success('Artigo salvo com sucesso.');
    }

    /**
     * Remove artigo.
     */
    public function removeArtigo(Lei $lei, Request $request): void
    {
        $artigo = Artigo::findOrFail($request->get('id'));
        $artigo->delete();
        Toast::info('Artigo removido.');
    }
}