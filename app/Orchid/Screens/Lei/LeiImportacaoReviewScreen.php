<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Models\Artigo;
use App\Models\Lei;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Select;

class LeiImportacaoReviewScreen extends Screen
{
    /**
     * Orchid exige propriedade pública sem type hint
     */
    public $lei = null;

    public function query(Lei $lei): iterable
    {
        $this->lei = $lei;

        return [
            'lei'     => $lei,
            'artigos' => $lei->artigos()
                ->orderBy('ordem')
                ->orderBy('id')
                ->paginate(20),
        ];
    }

    public function name(): string
    {
        return 'Revisão de Importação da Lei';
    }

    public function description(): string
    {
        return sprintf(
            'Lei nº %s — Revisão dos artigos importados automaticamente',
            $this->lei->numero ?? '—'
        );
    }

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
                    ->width(120)
                    ->alignCenter()
                    ->render(function (Artigo $a) {
                        return match ($a->confidence) {
                            'high'   => '<span class="badge bg-success">Alta</span>',
                            'medium' => '<span class="badge bg-warning text-dark">Média</span>',
                            'low'    => '<span class="badge bg-danger">Baixa</span>',
                            default  => '<span class="badge bg-secondary">—</span>',
                        };
                    }),

                TD::make('origem', 'Origem')
                    ->width(120)
                    ->alignCenter()
                    ->render(function (Artigo $a) {
                        return $a->origem === 'manual'
                            ? '<span class="badge bg-secondary">Manual</span>'
                            : '<span class="badge bg-primary">Automático</span>';
                    }),

                TD::make('texto', 'Trecho')
                    ->style('min-width: 480px; max-width: 650px;')
                    ->render(function (Artigo $a) {
                        return sprintf(
                            '<div style="
                                max-height: 80px;
                                overflow: hidden;
                                line-height: 1.5;
                                white-space: pre-wrap;
                                text-align: justify;
                                font-size: 0.9em;
                                color: #4a5568;
                            ">
                                %s
                            </div>',
                            e(str($a->texto)->limit(220))
                        );
                    }),

                TD::make(__('Ações'))
                    ->width(170)
                    ->alignRight()
                    ->render(function (Artigo $a) {
                        return '<div class="d-flex justify-content-end gap-1">' .

                            Button::make('Ver mais')
                                ->icon('bs.eye')
                                ->method('asyncGetArtigo')
                                ->asyncParameters(['artigo' => $a->id])
                                ->modal('artigoModal')
                                ->type(Color::PRIMARY)
                                ->render() .

                            Button::make('')
                                ->icon('bs.trash')
                                ->confirm('Deseja realmente excluir este artigo?')
                                ->method('deleteArtigo')
                                ->parameters(['id' => $a->id])
                                ->type(Color::DANGER)
                                ->render() .

                        '</div>';
                    }),
            ]),

            Layout::modal('artigoModal', Layout::rows([

                Input::make('artigo.id')->type('hidden'),

                Input::make('artigo.numero')
                    ->title('Número do Artigo')
                    ->required(),

                TextArea::make('artigo.texto')
                    ->title('Texto Completo')
                    ->rows(18)
                    ->style('font-family: serif; font-size: 15px;')
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
                ->async('asyncGetArtigo')
                ->method('saveArtigo')
                ->title('Revisão do Artigo')
                ->size('modal-lg')
                ->applyButton('Salvar alterações'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    public function asyncGetArtigo(Artigo $artigo): array
    {
        return ['artigo' => $artigo];
    }

    public function saveArtigo(Request $request): void
    {
        $data = $request->validate([
            'artigo.id'         => 'required|integer|exists:artigos,id',
            'artigo.numero'     => 'required|string|max:20',
            'artigo.texto'      => 'required|string',
            'artigo.confidence' => 'nullable|in:high,medium,low',
        ]);

        Artigo::findOrFail($data['artigo']['id'])
            ->update(array_merge(
                $data['artigo'],
                ['origem' => 'manual']
            ));

        Toast::success('Artigo atualizado com sucesso.');
    }

    public function deleteArtigo(int $id): void
    {
        Artigo::findOrFail($id)->delete();
        Toast::info('Artigo removido.');
    }

    public function marcarRevisado(): void
    {
        if ($this->lei) {
            $this->lei->update(['status' => 'processada']);
            Toast::success('Lei marcada como revisada.');
        }
    }
}
