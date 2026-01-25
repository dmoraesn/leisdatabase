<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Dashboard;

use Orchid\Screen\Layouts\Chart;

class LawsByScopeChart extends Chart
{
    /**
     * Título do gráfico.
     */
    protected $title = 'Distribuição por Abrangência';

    /**
     * Altura do gráfico (px).
     */
    protected $height = 300;

    /**
     * Chave dos dados (definida no método query da Screen).
     */
    protected $target = 'lawsByScope';

    /**
     * Tipo de gráfico (pie, bar, line, etc).
     */
    protected $type = 'pie';

    /**
     * Cores personalizadas para Federal (Azul), Estadual (Verde), Municipal (Laranja).
     */
    protected $colors = [
        '#007bff', // Federal
        '#28a745', // Estadual
        '#fd7e14', // Municipal
    ];
}