<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Artigo;
use App\Models\Lei;
use App\Orchid\Layouts\Dashboard\LawsByScopeChart;
use App\Orchid\Layouts\Dashboard\LawsGrowthChart;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class DashboardScreen extends Screen
{
    /**
     * Dados da consulta (Query).
     */
    public function query(): iterable
    {
        // 1. Métricas Totais (Cards do Topo)
        $totalLeis = Lei::count();
        $totalArtigos = Artigo::count();
        
        $statusProcessamento = [
            'Concluídas' => Lei::where('status', 'processada')->count(),
            'Pendentes'  => Lei::where('status', 'processando')->count(),
            'Erros'      => Lei::where('status', 'erro')->count(),
        ];

        // 2. Dados para o Gráfico de Abrangência (Pizza)
        // Agrupamos por abrangência e contamos
        $leisPorAbrangencia = Lei::selectRaw('abrangencia, count(*) as total')
            ->groupBy('abrangencia')
            ->pluck('total', 'abrangencia')
            ->toArray();

        // Formato exigido pelo Orchid para Gráfico Pie
        $datasetScope = [
            [
                'labels' => [
                    'Federal', 
                    'Estadual', 
                    'Municipal'
                ],
                'values' => [
                    $leisPorAbrangencia['federal'] ?? 0,
                    $leisPorAbrangencia['estadual'] ?? 0,
                    $leisPorAbrangencia['municipal'] ?? 0,
                ],
            ],
        ];

        // 3. Dados para o Gráfico de Ano (Barras)
        // Pegamos os últimos 10 anos que têm leis
        $leisPorAno = Lei::selectRaw('ano, count(*) as total')
            ->whereNotNull('ano')
            ->groupBy('ano')
            ->orderBy('ano', 'asc')
            ->limit(10) // Limita para não quebrar o gráfico se tiver 100 anos
            ->get();

        $datasetYear = [
            [
                'name'   => 'Quantidade de Leis',
                'values' => $leisPorAno->pluck('total')->toArray(),
                'labels' => $leisPorAno->pluck('ano')->toArray(),
            ],
        ];

        return [
            'metrics'     => [
                'Total de Leis'      => number_format($totalLeis),
                'Total de Artigos'   => number_format($totalArtigos),
                'PDFs Processados'   => $statusProcessamento['Concluídas'],
                'Fila de Processamento' => $statusProcessamento['Pendentes'],
            ],
            'lawsByScope' => $datasetScope,
            'lawsByYear'  => $datasetYear,
        ];
    }

    /**
     * Nome da tela.
     */
    public function name(): ?string
    {
        return 'Painel de Controle';
    }

    /**
     * Descrição.
     */
    public function description(): ?string
    {
        return 'Visão geral das normas e estatísticas do sistema.';
    }

    /**
     * Botões de ação.
     */
    public function commandBar(): iterable
    {
        return [
            // Podemos adicionar atalhos rápidos aqui se necessário
            // Link::make('GitHub')->href('...'),
        ];
    }

    /**
     * Layout visual.
     */
    public function layout(): iterable
    {
        return [
            // LINHA 1: Métricas (Cards Numéricos)
            Layout::metrics([
                'Total de Normas'       => 'metrics.Total de Leis',
                'Base de Artigos'       => 'metrics.Total de Artigos',
                'Leis Processadas'      => 'metrics.PDFs Processados',
                'Pendentes / Fila'      => 'metrics.Fila de Processamento',
            ]),

            // LINHA 2: Gráficos lado a lado
            Layout::columns([
                LawsByScopeChart::class,
                LawsGrowthChart::class,
            ]),
        ];
    }
}