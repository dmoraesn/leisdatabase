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

        // Registro de log de importação no banco
        $importacao = LeiImportacao::create([
            'lei_id'      => $lei->id,
            'arquivo_pdf' => $attachment->original_name ?? $attachment->name,
            'status'      => 'processando',
        ]);

        try {
            /**
             * 1. Resolução do caminho do arquivo (Suporte a Storage do Laravel/Orchid)
             */
            $fileName = $attachment->name;

            if (! empty($attachment->extension)) {
                $fileName .= '.' . $attachment->extension;
            }

            $pdfPath = Storage::disk($attachment->disk)
                ->path($attachment->path . $fileName);

            if (! is_file($pdfPath)) {
                throw new \RuntimeException(
                    'Arquivo PDF não encontrado no caminho físico: ' . $pdfPath
                );
            }

            /**
             * 2. Extração do texto bruto via Smalot/PdfParser
             */
            $parser = new Parser();
            $pdf = $parser->parseFile($pdfPath);
            $textoExtraido = $pdf->getText();

            // Atualiza registro de importação com o texto bruto
            $importacao->update([
                'texto_extraido' => $textoExtraido,
                'status'         => 'concluida', // Texto extraído, agora vamos parsear
            ]);

            /**
             * 3. Parse inteligente em artigos (Service)
             */
            $artigos = $parserService->parse($textoExtraido);

            /**
             * 4. Limpeza e Persistência
             * CRÍTICO: Deleta APENAS artigos que NÃO foram editados manualmente.
             */
            $lei->artigos()
                ->where('origem', '!=', 'manual') // Proteção de dados manuais
                ->delete();

            // Insere os novos artigos detectados
            foreach ($artigos as $artigo) {
                $lei->artigos()->create([
                    'numero'     => $artigo['numero'],
                    'texto'      => $artigo['texto'],
                    'ordem'      => $artigo['ordem'],
                    'origem'     => 'auto', // Marca explicitamente como automático
                    'confidence' => $artigo['confidence'] ?? 'medium',
                ]);
            }

            /**
             * 5. Finalização
             */
            $lei->update([
                'status' => 'processada',
            ]);

        } catch (Throwable $e) {
            // Em caso de erro, salva o log e atualiza o status da Lei
            $importacao->update([
                'status' => 'erro',
                'erro'   => $e->getMessage(),
            ]);

            $lei->update([
                'status' => 'erro',
            ]);

            // Re-throw para o worker do Laravel saber que falhou
            throw $e;
        }
    }
}