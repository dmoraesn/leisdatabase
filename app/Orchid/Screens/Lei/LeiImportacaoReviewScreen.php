<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Models\Lei;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class LeiImportacaoReviewScreen extends Screen
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
            'artigos' => $lei->artigos()
                ->orderBy('ordem')
                ->paginate(20),
        ];
    }

    /**
     * Nome da tela.
     */
    public function name(): string
    {
        return 'Revisão Rápida da Importação';
    }

    /**
     * Barra de comandos.
     */
    public function commandBar(): iterable
    {
        return [
            // Link para a tela completa (o arquivo 2 abaixo)
            Link::make('Gerenciamento Completo de Artigos')
                ->route('platform.leis.artigos', $this->lei)
                ->icon('bs.list-check')
                ->type('primary'),

            Button::make('Concluir Revisão e Validar')
                ->method('concluir')
                ->icon('bs.check-circle-fill')
                ->type('success'),
        ];
    }

    /**
     * Layout.
     */
    public function layout(): iterable
    {
        return [
            Layout::table('artigos', [
                TD::make('numero', 'Número')->width(100),
                TD::make('texto', 'Texto')->render(fn($a) => mb_strimwidth($a->texto, 0, 100, '...')),
                TD::make('confidence', 'Confiança'),
                TD::make('origem', 'Origem'),
            ]),
        ];
    }

    /**
     * Ação para validar a lei.
     */
    public function concluir(Lei $lei): void
    {
        $lei->update(['status' => 'processada']);
        Toast::success('Lei marcada como processada e validada.');
        
        // Redireciona para a lista
        redirect()->route('platform.leis.list');
    }
}