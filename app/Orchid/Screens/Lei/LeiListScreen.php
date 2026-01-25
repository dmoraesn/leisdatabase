<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Models\Lei;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\HtmlString;

class LeiListScreen extends Screen
{
    /**
     * Nome de exibição da tela.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Leis Cadastradas';
    }

    /**
     * Descrição da tela.
     *
     * @return string
     */
    public function description(): string
    {
        return 'Gerenciamento completo das leis e normativas registradas no sistema.';
    }

    /**
     * Definição dos dados da consulta (Query).
     *
     * @return iterable
     */
    public function query(): iterable
    {
        return [
            'leis' => Lei::query()
                ->orderByDesc('created_at')
                ->paginate(15),
        ];
    }

    /**
     * Barra de comandos superior.
     *
     * @return iterable
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Nova Lei')
                ->icon('bs.plus-circle')
                ->route('platform.leis.create')
                ->class('btn btn-primary'),
        ];
    }

    /**
     * Definição do Layout (Tabela).
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            Layout::table('leis', [

                TD::make('id', 'ID')
                    ->sort()
                    ->alignCenter()
                    ->width(70),

                TD::make('titulo', 'Título da Lei')
                    ->render(fn (Lei $lei) => Str::limit($lei->titulo, 60))
                    ->width(300),

                TD::make('local', 'Localização / Esfera')
                    ->render(fn (Lei $lei) => $lei->getLocalizacaoFormatada()),

                TD::make('abrangencia', 'Abrangência')
                    ->sort()
                    ->alignCenter()
                    ->render(fn (Lei $lei) => ucfirst($lei->abrangencia ?? 'N/A')),

                TD::make('status', 'Status de Processamento')
                    ->alignCenter()
                    ->render(function (Lei $lei) {
                        $statusColors = [
                            'processada'  => 'success',
                            'processando' => 'warning',
                            'erro'        => 'danger',
                            'pendente'    => 'secondary',
                        ];

                        $color = $statusColors[$lei->status] ?? 'light';
                        $label = ucfirst($lei->status ?? 'Desconhecido');

                        return new HtmlString("<span class='badge bg-{$color}'>{$label}</span>");
                    }),

                TD::make('actions', 'Ações')
                    ->alignCenter()
                    ->width(100)
                    ->render(fn (Lei $lei) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Editar Metadados')
                                ->route('platform.leis.edit', $lei)
                                ->icon('bs.pencil'),

                            Link::make('Ver Artigos e Texto')
                                ->route('platform.leis.artigos', $lei)
                                ->icon('bs.file-text'),

                            Button::make('Reprocessar PDF')
                                ->method('reprocessar')
                                ->icon('bs.arrow-repeat')
                                ->confirm('Atenção: Isso recriará os artigos automáticos. Edições manuais serão preservadas.')
                                ->parameters(['id' => $lei->id]),

                            Button::make('Remover Registro')
                                ->method('remove')
                                ->icon('bs.trash')
                                ->confirm('Tem certeza que deseja excluir esta lei permanentemente?')
                                ->parameters(['id' => $lei->id]),
                        ])),
            ]),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions do Controller
    |--------------------------------------------------------------------------
    */

    /**
     * Remove uma lei do banco de dados.
     */
    public function remove(Request $request): void
    {
        $lei = Lei::findOrFail($request->get('id'));
        $lei->delete();

        Toast::info('A Lei foi removida com sucesso do sistema.');
    }

    /**
     * Marca a lei para reprocessamento pelo Job.
     */
    public function reprocessar(Request $request): void
    {
        $lei = Lei::findOrFail($request->get('id'));
        
        $lei->update([
            'status' => 'processando'
        ]);

        // O Job será disparado via Observer ou manualmente se configurado aqui.
        // Assumindo que o trigger seja manual no fluxo atual:
        // ProcessarLeiPdfJob::dispatch($lei->id, $lei->getPdf()->id);

        Toast::info('Solicitação enviada. O PDF será reprocessado em segundo plano.');
    }
}