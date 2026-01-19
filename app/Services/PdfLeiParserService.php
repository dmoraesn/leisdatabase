<?php

namespace App\Services;

use App\Models\Lei;
use App\Models\LeiImportacao;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use Throwable;

class PdfLeiParserService
{
    /**
     * Processa o PDF de uma lei
     */
    public function processar(LeiImportacao $importacao): void
    {
        try {
            $importacao->update([
                'status' => 'processando',
                'erro' => null,
            ]);

            $parser = new Parser();

            $pdf = $parser->parseFile(
                storage_path('app/' . $importacao->arquivo_pdf)
            );

            $texto = $this->normalizarTexto($pdf->getText());

            $importacao->update([
                'texto_extraido' => $texto,
            ]);

            $artigos = $this->extrairArtigos($texto);

            $this->salvarArtigos(
                $importacao->lei,
                $artigos
            );

            $importacao->update([
                'status' => 'concluida',
            ]);

            $importacao->lei->update([
                'status' => 'processada',
                'json_original' => $artigos,
            ]);

        } catch (Throwable $e) {

            $mensagem = mb_convert_encoding(
                $e->getMessage(),
                'UTF-8',
                'UTF-8'
            );

            $importacao->update([
                'status' => 'erro',
                'erro' => $mensagem,
            ]);

            $importacao->lei->update([
                'status' => 'erro',
            ]);
        }
    }

    /**
     * Normaliza texto jurídico (UTF-8 seguro)
     */
    protected function normalizarTexto(string $texto): string
    {
        // Garante UTF-8 válido
        $texto = mb_convert_encoding($texto, 'UTF-8', 'UTF-8');

        // Remove caracteres invisíveis de PDF
        $texto = preg_replace('/[\x00-\x1F\x7F]/u', '', $texto);

        $texto = Str::of($texto)
            ->replace(["\r\n", "\r"], "\n")
            ->replace("\t", ' ')
            ->replaceMatches('/\n{2,}/', "\n\n")
            ->trim();

        return (string) $texto;
    }

    /**
     * Extrai artigos do texto
     */
    protected function extrairArtigos(string $texto): array
    {
        // Captura: Art. 1º, Art 2, Artigo 3º
        $pattern = '/(Art\.?\s*\d+[ºo]?)/iu';

        $partes = preg_split(
            $pattern,
            $texto,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

        $artigos = [];
        $ordem = 1;

        for ($i = 1; $i < count($partes); $i += 2) {

            $numero = trim($partes[$i] ?? '');
            $conteudo = trim($partes[$i + 1] ?? '');

            if (mb_strlen($conteudo) < 15) {
                continue;
            }

            $artigos[] = [
                'numero' => $numero,
                'texto' => $conteudo,
                'ordem' => $ordem++,
            ];
        }

        return $artigos;
    }

    /**
     * Salva artigos no banco
     */
    protected function salvarArtigos(
        Lei $lei,
        array $artigos
    ): void {
        // Limpa artigos antigos (reprocessamento)
        $lei->artigos()->delete();

        foreach ($artigos as $artigo) {
            $lei->artigos()->create($artigo);
        }
    }
}
