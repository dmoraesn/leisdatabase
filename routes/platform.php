<?php

declare(strict_types=1);

use App\Orchid\Screens\DashboardScreen;
use App\Orchid\Screens\Lei\LeiArtigosScreen;
use App\Orchid\Screens\Lei\LeiCreateScreen;
use App\Orchid\Screens\Lei\LeiEditScreen;
use App\Orchid\Screens\Lei\LeiImportacaoReviewScreen;
use App\Orchid\Screens\Lei\LeiListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/

// Substituímos o PlatformScreen padrão pelo nosso DashboardScreen com gráficos
Route::screen('/main', DashboardScreen::class)
    ->name('platform.main');

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Meu Perfil', route('platform.profile')));

/*
|--------------------------------------------------------------------------
| Users & Roles (Sistema de Acesso)
|--------------------------------------------------------------------------
*/

Route::prefix('users')->group(function () {
    Route::screen('/', UserListScreen::class)
        ->name('platform.systems.users')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push('Usuários', route('platform.systems.users')));

    Route::screen('create', UserEditScreen::class)
        ->name('platform.systems.users.create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.systems.users')
            ->push('Criar Usuário'));

    Route::screen('{user}/edit', UserEditScreen::class)
        ->name('platform.systems.users.edit')
        ->breadcrumbs(fn (Trail $trail, $user) => $trail
            ->parent('platform.systems.users')
            ->push($user->name, route('platform.systems.users.edit', $user)));
});

Route::prefix('roles')->group(function () {
    Route::screen('/', RoleListScreen::class)
        ->name('platform.systems.roles')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push('Papéis', route('platform.systems.roles')));

    Route::screen('create', RoleEditScreen::class)
        ->name('platform.systems.roles.create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.systems.roles')
            ->push('Criar Papel'));

    Route::screen('{role}/edit', RoleEditScreen::class)
        ->name('platform.systems.roles.edit')
        ->breadcrumbs(fn (Trail $trail, $role) => $trail
            ->parent('platform.systems.roles')
            ->push($role->name, route('platform.systems.roles.edit', $role)));
});

/*
|--------------------------------------------------------------------------
| Módulo de Leis
|--------------------------------------------------------------------------
*/

Route::prefix('leis')->group(function () {

    // 1. Listagem Geral
    Route::screen('/', LeiListScreen::class)
        ->name('platform.leis.list')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push('Leis', route('platform.leis.list')));

    // 2. Cadastro de Nova Lei
    Route::screen('criar', LeiCreateScreen::class)
        ->name('platform.leis.create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.leis.list')
            ->push('Nova Lei'));

    // 3. Edição de Metadados (Upload, Título, Localização)
    Route::screen('{lei}/editar', LeiEditScreen::class)
        ->name('platform.leis.edit')
        ->breadcrumbs(fn (Trail $trail, $lei) => $trail
            ->parent('platform.leis.list')
            ->push('Editar Lei', route('platform.leis.edit', $lei)));

    // 4. Gerenciamento de Artigos (CRUD Rico)
    Route::screen('{lei}/artigos', LeiArtigosScreen::class)
        ->name('platform.leis.artigos')
        ->breadcrumbs(fn (Trail $trail, $lei) => $trail
            ->parent('platform.leis.list') // Volta para a lista ao clicar no breadcrumb
            ->push('Gerenciar Artigos', route('platform.leis.artigos', $lei)));

    // 5. Revisão de Importação (Pós-upload)
    Route::screen('{lei}/revisao', LeiImportacaoReviewScreen::class)
        ->name('platform.leis.revisao')
        ->breadcrumbs(fn (Trail $trail, $lei) => $trail
            ->parent('platform.leis.edit', $lei)
            ->push('Revisão da Importação'));
});