<?php
use App\Http\Controllers\Api\IbgeController;

Route::prefix('ibge')->group(function () {
    Route::get('/estados', [IbgeController::class, 'estados']);
    Route::get('/cidades/{uf}', [IbgeController::class, 'cidades']);
});
