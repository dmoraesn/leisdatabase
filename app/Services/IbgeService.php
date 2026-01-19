<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IbgeService
{
    protected string $baseUrl = 'https://servicodados.ibge.gov.br/api/v1/localidades';

    /**
     * Lista estados (UF)
     */
    public function estados(): array
    {
        return Cache::remember('ibge_estados', 86400, function () {

            $response = Http::get("{$this->baseUrl}/estados");

            return collect($response->json())
                ->sortBy('nome')
                ->values()
                ->map(fn ($estado) => [
                    'id'   => $estado['id'],
                    'uf'   => $estado['sigla'],
                    'nome' => $estado['nome'],
                ])
                ->toArray();
        });
    }

    /**
     * Lista cidades por UF
     */
    public function cidades(string $uf): array
    {
        return Cache::remember("ibge_cidades_{$uf}", 86400, function () use ($uf) {

            $response = Http::get(
                "{$this->baseUrl}/estados/{$uf}/municipios"
            );

            return collect($response->json())
                ->sortBy('nome')
                ->values()
                ->map(fn ($cidade) => [
                    'id'   => $cidade['id'],
                    'nome' => $cidade['nome'],
                ])
                ->toArray();
        });
    }
}
