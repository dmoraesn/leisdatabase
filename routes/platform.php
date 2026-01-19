<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Lei\LeiCreateScreen;
use App\Orchid\Screens\Lei\LeiEditScreen;
use App\Orchid\Screens\Lei\LeiListScreen;
use App\Orchid\Screens\Lei\LeiArtigosScreen;
use App\Orchid\Screens\Lei\LeiImportacaoReviewScreen;

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
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

/*
|--------------------------------------------------------------------------
| Gerenciamento de Usuários
|--------------------------------------------------------------------------
*/
Route::prefix('users')->group(function () {
    Route::screen('/', UserListScreen::class)
        ->name('platform.systems.users');

    Route::screen('create', UserEditScreen::class)
        ->name('platform.systems.users.create');

    Route::screen('{user}/edit', UserEditScreen::class)
        ->name('platform.systems.users.edit');
});

/*
|--------------------------------------------------------------------------
| Gerenciamento de Perfis / Roles
|--------------------------------------------------------------------------
*/
Route::prefix('roles')->group(function () {
    Route::screen('/', RoleListScreen::class)
        ->name('platform.systems.roles');

    Route::screen('create', RoleEditScreen::class)
        ->name('platform.systems.roles.create');

    Route::screen('{role}/edit', RoleEditScreen::class)
        ->name('platform.systems.roles.edit');
});

/*
|--------------------------------------------------------------------------
| Leis - Módulo Principal
|--------------------------------------------------------------------------
*/
Route::prefix('leis')->group(function () {
    Route::screen('/', LeiListScreen::class)
        ->name('platform.leis.list');

    Route::screen('criar', LeiCreateScreen::class)
        ->name('platform.leis.create');

    Route::screen('{lei}/editar', LeiEditScreen::class)
        ->name('platform.leis.edit');

    Route::screen('{lei}/artigos', LeiArtigosScreen::class)
        ->name('platform.leis.artigos');

    Route::screen('{lei}/revisao-importacao', LeiImportacaoReviewScreen::class)
        ->name('platform.leis.revisao');
});