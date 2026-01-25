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
     *
     * @param Lei $lei
     * @return iterable
     */
    public function query(Lei $lei): iterable
    {
        return [
            'lei' => $lei,
            // Mantemos a ordenação lógica por 'ordem' no banco
            'artigos' => $lei->artigos()
                ->orderBy('ordem', 'asc')
                ->orderBy('id', 'asc')
                ->paginate(20),
        ];
    }

    /**
     * Nome da tela exibido no cabeçalho.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Gerenciamento de Artigos: ' . ($this->lei->titulo ?? 'Nova Lei');
    }

    /**
     * Descrição da tela.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Visualize, edite ou adicione novos artigos manualmente para a lei: ' . ($this->lei->numero ?? 'S/N');
    }

    /**
     * Barra de comandos.
     *
     * @return iterable
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
     * Layout da tela (Tabela Otimizada).
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            Layout::table('artigos', [
                
                // Coluna: Numeração
                // width: 80px (Fixo pequeno para não ocupar espaço)
                TD::make('numero', 'Art.')
                    ->width(80)
                    ->render(fn (Artigo $a) => 
                        "<span class='text-dark fw-bold' style='white-space: nowrap;'>{$a->numero}</span>"
                    ),

                // Coluna: Conteúdo (Texto)
                // Sem width fixo = Ocupa o espaço restante disponível (Flex)
                TD::make('texto', 'Conteúdo')
                    ->render(function (Artigo $a) {
                        // Limitamos o texto para não estourar a altura da linha excessivamente
                        // O ModalToggle permite ver tudo ao clicar
                        return ModalToggle::make(Str::limit($a->texto, 100))
                            ->modal('artigoModal')
                            ->method('saveArtigo')
                            ->asyncParameters(['artigo_id' => $a->id])
                            ->style('
                                text-decoration: none; 
                                color: #57606a; 
                                display: block;
                                word-wrap: break-word; /* Garante quebra de linha */
                                white-space: normal;   /* Permite múltiplas linhas */
                            ');
                    }),

                // Coluna: Confiança
                // width: 100px
                TD::make('confidence', 'Conf.')
                    ->width(100)
                    ->alignCenter()
                    ->render(fn (Artigo $a) => new HtmlString(match ($a->confidence) {
                        'high'   => '<span class="badge bg-success" title="Confiança Alta">Alta</span>',
                        'medium' => '<span class="badge bg-warning text-dark" title="Confiança Média">Média</span>',
                        'low'    => '<span class="badge bg-danger" title="Confiança Baixa">Baixa</span>',
                        default  => '<span class="badge bg-light text-dark">N/A</span>',
                    })),

                // Coluna: Origem
                // width: 100px
                TD::make('origem', 'Origem')
                    ->width(100)
                    ->alignCenter()
                    ->render(fn (Artigo $a) => $a->origem === 'manual'
                        ? '<span class="badge bg-info text-dark">Manual</span>'
                        : '<span class="badge bg-light text-dark border">Auto</span>'),
                
                // Coluna: Ações
                // width: 60px (Compacto)
                TD::make('actions', '')
                    ->alignRight()
                    ->width(60)
                    ->render(fn (Artigo $a) => Button::make('')
                        ->icon('bs.trash')
                        ->confirm('Tem certeza que deseja remover este artigo?')
                        ->method('removeArtigo', ['id' => $a->id])
                        ->class('btn btn-link text-danger p-0') // p-0 reduz padding do botão
                        ->title('Remover Artigo')
                    ),
            ]),

            // Modal de Edição / Criação
            Layout::modal('artigoModal', Layout::rows([
                Input::make('artigo.id')
                    ->type('hidden'),

                Input::make('artigo.numero')
                    ->title('Numeração (Ex: 1º, 22-A)')
                    ->required()
                    ->help('Identificador único do artigo dentro da lei.'),

                Input::make('artigo.ordem')
                    ->type('number')
                    ->title('Ordem Sequencial')
                    ->help('Utilizado apenas para ordenação interna. Deixe vazio para automático.'),

                TextArea::make('artigo.texto')
                    ->title('Texto Completo')
                    ->rows(12)
                    ->required()
                    ->style('font-family: monospace; font-size: 14px; line-height: 1.5;')
                    ->help('Digite o teor completo do artigo.'),
            ]))
            ->async('asyncGetArtigo')
            ->title('Editar / Criar Artigo')
            ->size('modal-lg')
            ->applyButton('Salvar Artigo'),
        ];
    }

    /**
     * Carrega dados para o modal via AJAX.
     *
     * @param Lei $lei
     * @param Request $request
     * @return array
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
     * Salva ou atualiza o artigo, forçando a origem como 'manual'.
     *
     * @param Lei $lei
     * @param Request $request
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
            
            // PROTEÇÃO DE DADOS:
            // Artigos editados manualmente nunca serão apagados pelo robô de PDF.
            'origem'     => 'manual', 
            'confidence' => 'high',
        ]);

        $artigo->save();

        Toast::success('Artigo salvo com sucesso.');
    }

    /**
     * Remove um artigo individualmente.
     *
     * @param Lei $lei
     * @param Request $request
     */
    public function removeArtigo(Lei $lei, Request $request): void
    {
        $artigo = Artigo::findOrFail($request->get('id'));
        $artigo->delete();

        Toast::info('Artigo removido permanentemente.');
    }
}