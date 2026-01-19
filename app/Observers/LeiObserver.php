<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Lei;
use App\Jobs\ProcessarLeiPdfJob;
use Orchid\Attachment\Models\Attachment;

class LeiObserver
{
    /**
     * Dispara o processamento quando um PDF é anexado ou alterado.
     */
    public function updated(Lei $lei): void
    {
        // Verifica se houve mudança nos attachments
        if (! $lei->wasChanged()) {
            return;
        }

        // Se a lei possui PDF anexado, dispara o job
        if ($lei->hasPdf()) {
            ProcessarLeiPdfJob::dispatch($lei->id);
        }
    }

    /**
     * Também cobre o caso de criação com PDF já anexado.
     */
    public function created(Lei $lei): void
    {
        if ($lei->hasPdf()) {
            ProcessarLeiPdfJob::dispatch($lei->id);
        }
    }
}
