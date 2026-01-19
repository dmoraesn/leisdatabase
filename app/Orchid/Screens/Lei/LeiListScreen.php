<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Models\Lei;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\HtmlString;

class LeiListScreen extends Screen
{
    public function name(): string
    {
        return 'Leis Cadastradas';
    }

    public function description(): string
    {
        return 'Gerenciamento das leis registradas no sistema';
    }

    public function query(): iterable
    {
        return [
            'leis' => Lei::query()
                ->orderByDesc('created_at')
                ->paginate(15),
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Nova Lei')
                ->icon('bs.plus-circle')
                ->route('platform.leis.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('leis', [

                TD::make('id', 'ID')
                    ->sort()
                    ->alignCenter()
                    ->width(70),

                TD::make('titulo', 'Título')
                    ->render(fn (Lei $lei) => Str::limit($lei->titulo, 70)),

                TD::make('numero', 'Número')
                    ->alignCenter()
                    ->render(fn (Lei $lei) => $lei->numero ?? '—'),

                TD::make('ano', 'Ano')
                    ->alignCenter(),

                TD::make('abrangencia', 'Abrangência')
                    ->alignCenter()
                    ->render(fn (Lei $lei) => ucfirst($lei->abrangencia)),

                TD::make('status', 'Status')
                    ->alignCenter()
                    ->render(function (Lei $lei) {
                        $html = match ($lei->status) {
                            'processado'  => '<span class="badge bg-success">Processado</span>',
                            'processando' => '<span class="badge bg-warning">Processando</span>',
                            'erro'        => '<span class="badge bg-danger">Erro</span>',
                            default       => '<span class="badge bg-secondary">Pendente</span>',
                        };

                        return new HtmlString($html);
                    }),

                TD::make('pdf', 'PDF')
                    ->alignCenter()
                    ->render(function (Lei $lei) {
                        if (! $lei->hasPdf()) {
                            return '—';
                        }

                        return Link::make('Baixar')
                            ->icon('bs.cloud-download')
                            ->href($lei->getPdf()->url)
                            ->target('_blank');
                    }),

                TD::make('actions', 'Ações')
                    ->alignCenter()
                    ->render(fn (Lei $lei) =>
                        view('orchid.partials.leis-actions', compact('lei'))
                    ),
            ]),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    public function remove(Request $request): void
    {
        Lei::findOrFail($request->get('id'))->delete();
        Toast::info('Lei removida com sucesso.');
    }

    public function reprocessar(Request $request): void
    {
        Lei::findOrFail($request->get('id'))
            ->update(['status' => 'processando']);

        Toast::info('Lei enviada para reprocessamento.');
    }
}
