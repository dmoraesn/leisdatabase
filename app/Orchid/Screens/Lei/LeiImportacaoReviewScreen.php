<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Models\Artigo;
use App\Models\Lei;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class LeiImportacaoReviewScreen extends Screen
{
    /**
     * Orchid exige propriedade pública sem type hint
     */
    public $lei = null;

    /**
     * Query data.
     */
    public function query(Lei $lei): iterable
    {
        $this->lei = $lei;

        return [
            'lei' => $lei,
            'artigos' => $lei->artigos()
                ->orderBy('ordem')
                ->orderBy('id')
                ->paginate(20),
        ];
    }

    /**
     * Display header name.
     */
    public function name(): string
    {
        return 'Revisão de Importação da Lei';
    }

    /**
     * Display header description.
     */
    public function description(): string
    {
        return sprintf(
            'Lei nº %s — clique em um artigo para visualizar ou editar',
            $this->lei->numero ?? '—'
        );
    }

    /**
     * Command bar.
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Voltar para a Lei')
                ->icon('bs.arrow-left')
                ->route('platform.leis.edit', $this->lei),

            Button::make('Marcar como Revisado')
                ->icon('bs.check-circle')
                ->type(Color::SUCCESS)
                ->method('marcarRevisado'),
        ];
    }

    /**
     * Layout.
     */
    public function layout(): iterable
    {
        return [

            Layout::table('artigos', [

                TD::make('ordem', 'Ordem')
                    ->width(70)
                    ->alignCenter(),

                TD::make('numero', 'Artigo')
                    ->width(90)
                    ->render(fn (Artigo $a) => "Art. {$a->numero}"),

                TD::make('confidence', 'Confiança')
                    ->width(110)
                    ->alignCenter()
                    ->render(fn (Artigo $a) =>
                        new HtmlString(match ($a->confidence) {
                            'high'   => '<span class="badge bg-success">Alta</span>',
                            'medium' => '<span class="badge bg-warning text-dark">Média</span>',
                            'low'    => '<span class="badge bg-danger">Baixa</span>',
                            default  => '<span class="badge bg-secondary">—</span>',
                        })
                    ),

                TD::make('origem', 'Origem')
                    ->width(120)
                    ->alignCenter()
                    ->render(fn (Artigo $a) =>
                        new HtmlString(
                            '<span class="badge bg-info text-dark">' .
                            ucfirst($a->origem ?? 'automático') .
                            '</span>'
                        )
                    ),

                TD::make('texto', 'Texto do Artigo')
                    ->style('min-width: 780px;')
                    ->render(function (Artigo $a) {
                        return ModalToggle::make(e(str($a->texto)->limit(500)))
                            ->modal('artigoModal')
                            ->asyncParameters([
                                // parâmetro NÃO conflita com a rota
                                'artigo_id' => $a->id,
                            ])
                            ->style('
                                display: block;
                                text-align: justify;
                                white-space: pre-wrap;
                                color: #4a5568;
                                text-decoration: none;
                                cursor: pointer;
                                line-height: 1.6;
                            ');
                    }),
            ]),

            Layout::modal('artigoModal', Layout::rows([

                Input::make('artigo.id')
                    ->type('hidden'),

                Input::make('artigo.numero')
                    ->title('Número do Artigo')
                    ->required(),

                TextArea::make('artigo.texto')
                    ->title('Texto Completo')
                    ->rows(18)
                    ->style('font-family: monospace; font-size: 14px;')
                    ->required(),

                Select::make('artigo.confidence')
                    ->title('Confiança')
                    ->options([
                        'high'   => 'Alta',
                        'medium' => 'Média',
                        'low'    => 'Baixa',
                    ])
                    ->empty('—'),

            ]))
                ->title('Artigo da Lei')
                ->size('modal-xl')
                ->async('asyncGetArtigo')
                ->method('saveArtigo')
                ->applyButton('Salvar alterações'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    /**
     * Load article data for modal.
     *
     * A assinatura PRECISA aceitar Lei $lei
     * porque a rota possui {lei}.
     */
    public function asyncGetArtigo(Lei $lei, Request $request): array
    {
        $artigoId = (int) $request->get('artigo_id');

        $artigo = Artigo::findOrFail($artigoId);

        return [
            'artigo' => $artigo,
        ];
    }

    /**
     * Save article changes.
     *
     * A assinatura PRECISA aceitar Lei $lei
     * porque a rota possui {lei}.
     */
    public function saveArtigo(Lei $lei, Request $request): void
    {
        $data = $request->validate([
            'artigo.id'         => 'required|exists:artigos,id',
            'artigo.numero'     => 'required|string|max:20',
            'artigo.texto'      => 'required|string',
            'artigo.confidence' => 'nullable|in:high,medium,low',
        ]);

        Artigo::whereKey($data['artigo']['id'])
            ->update($data['artigo']);

        Toast::success('Artigo atualizado com sucesso.');
    }

    /**
     * Mark lei as processed.
     *
     * Lei $lei é resolvida via route-model-binding.
     */
    public function marcarRevisado(Lei $lei, Request $request): void
    {
        $lei->update([
            'status' => 'processada',
        ]);

        Toast::success('Lei marcada como revisada.');
    }
}
