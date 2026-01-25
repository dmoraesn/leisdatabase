<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Dashboard;

use Orchid\Screen\Layouts\Chart;

class LawsGrowthChart extends Chart
{
    /**
     * Título.
     */
    protected $title = 'Volume de Leis por Ano de Publicação';

    /**
     * Altura.
     */
    protected $height = 300;

    /**
     * Target.
     */
    protected $target = 'lawsByYear';

    /**
     * Tipo.
     */
    protected $type = 'bar'; // Pode ser 'line' se preferir
}