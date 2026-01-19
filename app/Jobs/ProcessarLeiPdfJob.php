<?php

namespace App\Jobs;

use App\Models\LeiImportacao;
use App\Services\PdfLeiParserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessarLeiPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public LeiImportacao $importacao
    ) {}

    public function handle(PdfLeiParserService $service): void
    {
        $service->processar($this->importacao);
    }
}
