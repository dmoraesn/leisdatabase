<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Models\Lei;
use Illuminate\Http\Request;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class LeiCreateScreen extends Screen
{
    public function name(): ?string
    {
        return 'Nova Lei';
    }

    public function description(): ?string
    {
        return 'Cadastro de uma nova lei';
    }

    public function query(): iterable
    {
        return [
            'lei' => new Lei(),
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Salvar')
                ->icon('check')
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

                Input::make('lei.numero')
                    ->title('Número'),

                Input::make('lei.ano')
                    ->type('number')
                    ->title('Ano'),

                Input::make('lei.abrangencia')
                    ->title('Abrangência'),

                Upload::make('pdf')
                    ->title('Arquivo PDF da Lei')
                    ->acceptedFiles('.pdf')
                    ->maxFiles(1),
            ]),
        ];
    }

    public function save(Request $request)
    {
        $lei = Lei::create(
            $request->input('lei')
            + ['status' => 'processando']
        );

        if ($request->filled('pdf')) {
            $lei->attachment()->sync(
                $request->input('pdf')
            );
        }

        Alert::success('Lei cadastrada com sucesso.');

        return redirect()->route('platform.leis.list');
    }
}
