<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Models\Lei;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class LeiArtigosScreen extends Screen
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
        ];
    }

    /**
     * Display header name.
     */
    public function name(): ?string
    {
        return 'Artigos da Lei ' . ($this->lei->numero ?? '');
    }

    /**
     * Button commands.
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                // Aqui depois colocaremos a lista de artigos
            ]),
        ];
    }
}