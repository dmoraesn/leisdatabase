<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Lei;

use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Layout;

class LeiLocalizacaoListener extends Listener
{
    /**
     * Campos que, ao serem alterados, disparam o evento assíncrono.
     *
     * @var string[]
     */
    protected $targets = [
        'lei.estado',
    ];

    /**
     * Nome do método na Screen que será chamado quando os targets mudarem.
     *
     * @var string
     */
    protected $asyncMethod = 'asyncUpdateCity';

    /**
     * Define o layout dos campos.
     *
     * @return iterable
     */
    protected function layouts(): iterable
    {
        return [
            Layout::rows([
                Select::make('lei.estado')
                    ->title('Estado (UF)')
                    ->options($this->query->get('estados', [])) // Pega da query inicial
                    ->empty('Selecione')
                    ->required(),

                Select::make('lei.cidade')
                    ->title('Cidade')
                    ->options($this->query->get('cidades', [])) // Pega da query ou do retorno do async
                    ->empty('Selecione um estado primeiro')
                    ->required()
                    ->help('A lista de cidades é carregada automaticamente ao selecionar o estado.'),
            ])->title('Localização'),
        ];
    }
}