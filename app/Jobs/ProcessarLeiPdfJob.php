<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Lei;
use App\Models\LeiImportacao;
use App\Services\LeiPdfParserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Orchid\Attachment\Models\Attachment;
use Smalot\PdfParser\Parser;
use Throwable;

class ProcessarLeiPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $leiId;
    public int $attachmentId;

    public function __construct(int $leiId, int $attachmentId)
    {
        $this->leiId = $leiId;
        $this->attachmentId = $attachmentId;
    }

    public function handle(LeiPdfParserService $parserService): void
    {
        $lei = Lei::findOrFail($this->leiId);
        $attachment = Attachment::findOrFail($this->attachmentId);

        $importacao = LeiImportacao::create([
            'lei_id'      => $lei->id,
            'arquivo_pdf' => $attachment->original_name ?? $attachment->name,
            'status'      => 'processando',
        ]);

        try {
            /**
             * ✅ MONTAGEM CORRETA DO PATH DO ARQUIVO (ORCHID)
             */
            $fileName = $attachment->name;

            if (! empty($attachment->extension)) {
                $fileName .= '.' . $attachment->extension;
            }

            $pdfPath = Storage::disk($attachment->disk)
                ->path($attachment->path . $fileName);

            if (! is_file($pdfPath)) {
                throw new \RuntimeException(
                    'Arquivo PDF não encontrado: ' . $pdfPath
                );
            }

            /**
             * 1️⃣ Extração do texto bruto
             */
            $parser = new Parser();
            $pdf = $parser->parseFile($pdfPath);
            $textoExtraido = $pdf->getText();

            $importacao->update([
                'texto_extraido' => $textoExtraido,
                'status'         => 'concluida',
            ]);

            /**
             * 2️⃣ Parse automático em artigos
             */
            $artigos = $parserService->parse($textoExtraido);

            /**
             * 3️⃣ Remove apenas artigos automáticos antigos
             */
            $lei->artigos()
                ->where('origem', 'auto')
                ->delete();

            /**
             * 4️⃣ Persiste novos artigos
             */
            foreach ($artigos as $artigo) {
                $lei->artigos()->create([
                    'numero'     => $artigo['numero'],
                    'texto'      => $artigo['texto'],
                    'ordem'      => $artigo['ordem'],
                    'origem'     => 'auto',
                    'confidence' => $artigo['confidence'] ?? null,
                ]);
            }

            /**
             * 5️⃣ Finaliza processamento
             */
            $lei->update([
                'status' => 'processada',
            ]);

        } catch (Throwable $e) {

            $importacao->update([
                'status' => 'erro',
                'erro'   => $e->getMessage(),
            ]);

            $lei->update([
                'status' => 'erro',
            ]);

            throw $e;
        }
    }
}
