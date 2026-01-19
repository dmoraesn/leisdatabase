<?php

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [

            // Dashboard
            Menu::make('Dashboard')
                ->icon('home')
                ->route('platform.main'),

            // Leis
            Menu::make('Leis')
                ->icon('book-open')
                ->route('platform.leis.list'),

            // Usuários
            Menu::make('Usuários')
                ->icon('users')
                ->route('platform.systems.users'),

            // Papéis
            Menu::make('Papéis')
                ->icon('lock')
                ->route('platform.systems.roles'),

        ];
    }
}
