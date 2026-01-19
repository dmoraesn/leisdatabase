<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Models\Lei;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString; // Importação Obrigatória para renderizar HTML
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class LeiCreateScreen extends Screen
{
    /**
     * Define o nome da tela exibido no cabeçalho.
     */
    public function name(): ?string
    {
        return 'Cadastro de Legislação';
    }

    /**
     * Descrição da tela.
     */
    public function description(): ?string
    {
        return 'Preencha os dados abaixo para registrar uma nova lei no sistema.';
    }

    /**
     * Carrega os dados iniciais da tela.
     */
    public function query(Request $request): iterable
    {
        return [
            'lei' => new Lei(),
        ];
    }

    /**
     * Botões de ação na barra superior.
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Salvar Registro')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    /**
     * Layout da tela.
     */
    public function layout(): iterable
    {
        return [
            Layout::columns([
                // ---------------------------------------------------------
                // COLUNA 1: Dados Principais (Com Título Interno)
                // ---------------------------------------------------------
                Layout::rows([
                    // CORREÇÃO: Uso de HtmlString para renderizar o cabeçalho
                    Label::make('header_dados')
                        ->value(new HtmlString('
                            <div class="mb-3">
                                <h4 class="text-black font-thin">Dados da Lei</h4>
                                <p class="text-muted small">Informações básicas do documento.</p>
                                <hr class="my-2">
                            </div>
                        ')),

                    Input::make('lei.titulo')
                        ->title('Título da Lei')
                        ->placeholder('Ex: Dispõe sobre o plano diretor...')
                        ->required(),

                    Group::make([
                        Input::make('lei.numero')
                            ->title('Número')
                            ->placeholder('Ex: 12.345'),

                        Input::make('lei.ano')
                            ->type('number')
                            ->title('Ano')
                            ->placeholder((string) date('Y')),
                    ]),

                    Select::make('lei.abrangencia')
                        ->title('Abrangência')
                        ->options([
                            'municipal' => 'Municipal',
                            'estadual'  => 'Estadual',
                            'federal'   => 'Federal',
                        ])
                        ->empty('Selecione')
                        ->help('Define o nível de aplicação da lei.'),

                    Upload::make('pdf')
                        ->title('Arquivo Digital')
                        ->acceptedFiles('.pdf')
                        ->maxFiles(1),
                ]), 

                // ---------------------------------------------------------
                // COLUNA 2: Localização (Blade Component)
                // ---------------------------------------------------------
                Layout::view('orchid.partials.location-selector'),
            ]),
        ];
    }

    /**
     * Ação de Salvar.
     */
    public function save(Request $request)
    {
        $request->validate([
            'lei.titulo'      => ['required', 'string', 'min:3', 'max:255'],
            'lei.estado'      => ['required', 'string', 'size:2'],
            'lei.cidade'      => ['required', 'string'],
        ], [
            'lei.titulo.required' => 'O título da lei é obrigatório.',
            'lei.estado.required' => 'Selecione um Estado.',
            'lei.cidade.required' => 'Selecione uma Cidade.',
        ]);

        $lei = Lei::create(array_merge(
            $request->input('lei'),
            ['status' => 'processando']
        ));

        if ($request->filled('pdf')) {
            $lei->attachment()->sync($request->input('pdf'));
        }

        Toast::info('Lei cadastrada com sucesso!');

        return redirect()->route('platform.leis.list');
    }
}