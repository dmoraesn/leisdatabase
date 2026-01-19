<?php

declare(strict_types=1);

namespace App\Services;

class LeiPdfParserService
{
    /**
     * Parse heurístico do texto bruto em artigos.
     */
    public function parse(string $texto): array
    {
        $linhas = preg_split('/\R+/', $texto);

        $artigos = [];
        $artigoAtual = null;

        $ordem = 0;
        $ultimoNumero = null;

        foreach ($linhas as $linha) {
            $linha = trim($linha);

            if ($linha === '') {
                continue;
            }

            // 🔒 Parágrafos (§) NUNCA iniciam novo artigo
            if ($this->ehParagrafo($linha)) {
                if ($artigoAtual) {
                    $artigoAtual['texto'] .= "\n" . $linha;
                }
                continue;
            }

            // ✅ Início REAL de artigo
            if ($this->ehInicioDeArtigo($linha, $ultimoNumero, $numeroDetectado)) {
                if ($artigoAtual) {
                    $artigos[] = $artigoAtual;
                }

                $ordem++;
                $ultimoNumero = $numeroDetectado;

                $artigoAtual = [
                    'numero'     => $numeroDetectado,
                    'texto'      => $linha,
                    'ordem'      => $ordem,
                    'origem'     => 'auto',
                    'confidence' => 'high',
                ];

                continue;
            }

            // 🧠 Continuação normal do artigo
            if ($artigoAtual) {
                $artigoAtual['texto'] .= "\n" . $linha;
            }
        }

        if ($artigoAtual) {
            $artigos[] = $artigoAtual;
        }

        return $this->ajustarConfidence($artigos);
    }

    /**
     * Detecta início REAL de artigo (regra jurídica).
     */
    protected function ehInicioDeArtigo(
        string $linha,
        ?string $ultimoNumero,
        ?string &$numeroDetectado
    ): bool {
        // ⚠️ Obrigatoriamente no INÍCIO da linha
        if (! preg_match('/^(Art\.?|Artigo)\s+(\d+[A-Z\-º]*)\b/i', $linha, $matches)) {
            return false;
        }

        $numeroDetectado = $matches[2];

        // ❌ Ignora citações textuais comuns
        if ($this->ehCitacaoTextual($linha)) {
            return false;
        }

        // Validação sequencial
        if ($ultimoNumero !== null) {
            if (! $this->numeroSequencialValido($ultimoNumero, $numeroDetectado)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Detecta parágrafos (§ 1º, § único, etc.)
     */
    protected function ehParagrafo(string $linha): bool
    {
        return (bool) preg_match('/^§\s*\d+º?/i', $linha);
    }

    /**
     * Detecta citações textuais que NÃO iniciam artigo.
     */
    protected function ehCitacaoTextual(string $linha): bool
    {
        return (bool) preg_match(
            '/artigo\s+(anterior|seguinte|\d+)/i',
            $linha
        );
    }

    /**
     * Valida continuidade numérica real.
     */
    protected function numeroSequencialValido(string $anterior, string $atual): bool
    {
        $anteriorBase = (int) preg_replace('/\D/', '', $anterior);
        $atualBase    = (int) preg_replace('/\D/', '', $atual);

        // Sequência normal: 33 → 34
        if ($atualBase === $anteriorBase + 1) {
            return true;
        }

        // Subartigos: 10 → 10-A
        if ($atualBase === $anteriorBase) {
            return true;
        }

        return false;
    }

    /**
     * Ajusta confidence baseado em heurísticas pós-parse.
     */
    protected function ajustarConfidence(array $artigos): array
    {
        foreach ($artigos as &$artigo) {
            $tamanho = mb_strlen($artigo['texto']);

            if ($tamanho < 300) {
                $artigo['confidence'] = 'low';
            } elseif ($tamanho < 800) {
                $artigo['confidence'] = 'medium';
            }
        }

        return $artigos;
    }
}
