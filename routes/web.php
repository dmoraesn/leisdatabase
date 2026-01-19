<?php

use App\Models\LeiImportacao;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/admin/leis/{lei}/download', function ($leiId) {

    $importacao = LeiImportacao::where('lei_id', $leiId)
        ->latest()
        ->firstOrFail();

    $path = $importacao->arquivo_pdf;

    if (!Storage::exists($path)) {
        abort(404, 'Arquivo PDF não encontrado.');
    }

    return Storage::download($path);

})->name('platform.leis.download');
