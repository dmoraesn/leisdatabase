<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Core Screens
|--------------------------------------------------------------------------
*/
use App\Orchid\Screens\PlatformScreen;

/*
|--------------------------------------------------------------------------
| User Management
|--------------------------------------------------------------------------
*/
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;

/*
|--------------------------------------------------------------------------
| Role Management
|--------------------------------------------------------------------------
*/
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;

/*
|--------------------------------------------------------------------------
| Leis (Domínio principal do sistema)
|--------------------------------------------------------------------------
*/
use App\Orchid\Screens\Lei\LeiCreateScreen;
use App\Orchid\Screens\Lei\LeiEditScreen;
use App\Orchid\Screens\Lei\LeiListScreen;

/*
|--------------------------------------------------------------------------
| Orchid Examples (DEMO)
|--------------------------------------------------------------------------
| Pode remover futuramente sem impacto no sistema
|--------------------------------------------------------------------------
*/
use App\Orchid\Screens\Examples\{
    ExampleScreen,
    ExampleFieldsScreen,
    ExampleFieldsAdvancedScreen,
    ExampleTextEditorsScreen,
    ExampleActionsScreen,
    ExampleLayoutsScreen,
    ExampleGridScreen,
    ExampleChartsScreen,
    ExampleCardsScreen
};

/*
|--------------------------------------------------------------------------
| Dashboard Principal
|--------------------------------------------------------------------------
*/
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

/*
|--------------------------------------------------------------------------
| Perfil do Usuário
|--------------------------------------------------------------------------
*/
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile'))
    );

/*
|--------------------------------------------------------------------------
| Usuários
|--------------------------------------------------------------------------
*/
Route::prefix('users')->group(function () {

    Route::screen('/', UserListScreen::class)
        ->name('platform.systems.users')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push(__('Users'), route('platform.systems.users'))
        );

    Route::screen('create', UserEditScreen::class)
        ->name('platform.systems.users.create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.systems.users')
            ->push(__('Create'))
        );

    Route::screen('{user}/edit', UserEditScreen::class)
        ->name('platform.systems.users.edit')
        ->breadcrumbs(fn (Trail $trail, $user) => $trail
            ->parent('platform.systems.users')
            ->push($user->name)
        );

});

/*
|--------------------------------------------------------------------------
| Perfis / Roles
|--------------------------------------------------------------------------
*/
Route::prefix('roles')->group(function () {

    Route::screen('/', RoleListScreen::class)
        ->name('platform.systems.roles')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'))
        );

    Route::screen('create', RoleEditScreen::class)
        ->name('platform.systems.roles.create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'))
        );

    Route::screen('{role}/edit', RoleEditScreen::class)
        ->name('platform.systems.roles.edit')
        ->breadcrumbs(fn (Trail $trail, $role) => $trail
            ->parent('platform.systems.roles')
            ->push($role->name)
        );

});

/*
|--------------------------------------------------------------------------
| Leis
|--------------------------------------------------------------------------
*/
Route::prefix('leis')->group(function () {

    Route::screen('/', LeiListScreen::class)
        ->name('platform.leis.list');

    Route::screen('criar', LeiCreateScreen::class)
        ->name('platform.leis.create');

    Route::screen('{lei}/editar', LeiEditScreen::class)
        ->name('platform.leis.edit');

});

/*
|--------------------------------------------------------------------------
| Orchid Example Screens (DEMO)
|--------------------------------------------------------------------------
*/
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Example Screen')
    );

Route::prefix('examples')->group(function () {

    Route::screen('form/fields', ExampleFieldsScreen::class)
        ->name('platform.example.fields');

    Route::screen('form/advanced', ExampleFieldsAdvancedScreen::class)
        ->name('platform.example.advanced');

    Route::screen('form/editors', ExampleTextEditorsScreen::class)
        ->name('platform.example.editors');

    Route::screen('form/actions', ExampleActionsScreen::class)
        ->name('platform.example.actions');

    Route::screen('layouts', ExampleLayoutsScreen::class)
        ->name('platform.example.layouts');

    Route::screen('grid', ExampleGridScreen::class)
        ->name('platform.example.grid');

    Route::screen('charts', ExampleChartsScreen::class)
        ->name('platform.example.charts');

    Route::screen('cards', ExampleCardsScreen::class)
        ->name('platform.example.cards');

});
