<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Lei;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;
use Orchid\Support\Facades\Layout;

class LeiLocalizacaoListener extends Listener
{
    protected $targets = [
        'lei.abrangencia',
        'lei.estado',
    ];

    public function handle(Repository $repository, Request $request): Repository
    {
        $repository->set('lei.abrangencia', $request->input('lei.abrangencia'));
        $repository->set('lei.estado', $request->input('lei.estado'));
        $repository->set('lei.cidade', $request->input('lei.cidade'));

        return $repository;
    }

    protected function layouts(): iterable
    {
        $abrangencia = $this->query->get('lei.abrangencia');
        $estadoSelecionado = $this->query->get('lei.estado');

        $mostrarCampoEstado = in_array($abrangencia, ['estadual', 'municipal']);
        $mostrarCampoCidade = ($abrangencia === 'municipal');

        // Cache Estados
        $listaEstados = Cache::remember('ibge_estados_api', 86400, function () {
            $response = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');
            return $response->successful() ? collect($response->json())->pluck('sigla', 'sigla')->toArray() : [];
        });

        // Cache Cidades
        $listaCidades = [];
        if ($mostrarCampoCidade && $estadoSelecionado) {
            $cacheKey = "ibge_cidades_{$estadoSelecionado}";
            $listaCidades = Cache::remember($cacheKey, 86400, function () use ($estadoSelecionado) {
                $url = "https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$estadoSelecionado}/municipios";
                $response = Http::get($url);
                return $response->successful() ? collect($response->json())->pluck('nome', 'nome')->toArray() : [];
            });
        }

        return [
            // AGORA RETORNAMOS UM BLOCO, NÃO APENAS ROWS
            Layout::block(Layout::rows([
                Group::make([
                    Select::make('lei.abrangencia')
                        ->title('Abrangência')
                        ->options([
                            'federal'   => 'Federal',
                            'estadual'  => 'Estadual',
                            'municipal' => 'Municipal',
                        ])
                        ->empty('Selecione')
                        ->required(),

                    Select::make('lei.estado')
                        ->title('Estado (UF)')
                        ->options($listaEstados)
                        ->empty('Selecione')
                        ->canSee($mostrarCampoEstado)
                        ->required($mostrarCampoEstado),

                    Select::make('lei.cidade')
                        ->title('Cidade')
                        ->options($listaCidades)
                        ->empty('Selecione o Estado')
                        ->disabled(empty($listaCidades))
                        ->canSee($mostrarCampoCidade)
                        ->required($mostrarCampoCidade),
                ]),
            ]))
            ->title('Abrangência Geográfica')
            ->description('Defina onde esta lei é aplicada para facilitar a busca regional.')
        ];
    }
}