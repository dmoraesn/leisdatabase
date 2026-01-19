<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Models\Lei;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;

class LeiEditScreen extends Screen
{
    /**
     * ⚠️ NÃO tipar esta propriedade no Orchid
     */
    public $lei;

    public function query(Lei $lei): iterable
    {
        $this->lei = $lei;

        return [
            'lei' => $lei,
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

                Input::make('lei.numero')
                    ->title('Número'),

                Input::make('lei.ano')
                    ->title('Ano'),

                Select::make('lei.abrangencia')
                    ->title('Abrangência')
                    ->options([
                        'federal'   => 'Federal',
                        'estadual'  => 'Estadual',
                        'municipal' => 'Municipal',
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
        ]);

        if (! empty($data['pdf'])) {
            $lei->attachment()->sync($data['pdf']);
            $lei->update(['status' => 'processando']);
        }

        Toast::success('Lei atualizada com sucesso.');
    }
}
