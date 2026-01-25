<?php

declare(strict_types=1);

namespace App\Services;

class LeiPdfParserService
{
    /**
     * Parse heurístico do texto bruto em array de artigos estruturados.
     *
     * @param string $texto Texto extraído do PDF
     * @return array Lista de artigos encontrados
     */
    public function parse(string $texto): array
    {
        // Divide o texto em linhas, tratando diferentes quebras (\r\n, \n, \r)
        $linhas = preg_split('/\R+/', $texto);

        $artigos = [];
        $artigoAtual = null;

        $ordem = 0;
        $ultimoNumero = null;
        $numeroDetectado = null;

        foreach ($linhas as $linha) {
            $linha = trim($linha);

            if ($linha === '') {
                continue;
            }

            // 🔒 Regra: Parágrafos (§) NUNCA iniciam novo artigo, pertencem ao anterior
            if ($this->ehParagrafo($linha)) {
                if ($artigoAtual) {
                    $artigoAtual['texto'] .= "\n" . $linha;
                }
                continue;
            }

            // ✅ Regra: Início REAL de artigo (Art. X)
            if ($this->ehInicioDeArtigo($linha, $ultimoNumero, $numeroDetectado)) {
                
                // Se já havia um artigo sendo montado, salva ele antes de começar o novo
                if ($artigoAtual) {
                    $artigos[] = $artigoAtual;
                }

                $ordem++;
                $ultimoNumero = $numeroDetectado;

                // Inicia nova estrutura de artigo
                $artigoAtual = [
                    'numero'     => $numeroDetectado,
                    'texto'      => $linha,
                    'ordem'      => $ordem,
                    'origem'     => 'auto',
                    'confidence' => 'high',
                ];

                continue;
            }

            // 🧠 Regra: Se não é início nem parágrafo, é continuação do texto do artigo atual
            if ($artigoAtual) {
                $artigoAtual['texto'] .= "\n" . $linha;
            }
        }

        // Adiciona o último artigo encontrado ao loop terminar
        if ($artigoAtual) {
            $artigos[] = $artigoAtual;
        }

        return $this->ajustarConfidence($artigos);
    }

    /**
     * Detecta início REAL de artigo (regra jurídica).
     * Popula a variável $numeroDetectado por referência.
     */
    protected function ehInicioDeArtigo(
        string $linha,
        ?string $ultimoNumero,
        ?string &$numeroDetectado
    ): bool {
        
        // ⚠️ Regex Rigorosa: Obrigatoriamente no INÍCIO da linha
        // Aceita "Art. 1", "Artigo 1", "Art. 1º", "Art. 22-A"
        if (! preg_match('/^(Art\.?|Artigo)\s+(\d+[A-Z\-º]*)\b/i', $linha, $matches)) {
            return false;
        }

        $numeroDetectado = $matches[2];

        // ❌ Ignora citações textuais comuns que parecem artigos (falsos positivos)
        // Ex: "Conforme o artigo anterior..."
        if ($this->ehCitacaoTextual($linha)) {
            return false;
        }

        // Validação sequencial para aumentar a confiança
        if ($ultimoNumero !== null) {
            if (! $this->numeroSequencialValido($ultimoNumero, $numeroDetectado)) {
                // Se a sequência quebrou muito (ex: Art 1 pular para Art 500), pode ser cabeçalho
                // Por enquanto retornamos false para ser conservador, ou true se aceitarmos gaps.
                // Mantemos a lógica rigorosa:
                // return false; 
                // (Comentado para aceitar leis com artigos revogados que criam buracos na numeração)
            }
        }

        return true;
    }

    /**
     * Detecta parágrafos (§ 1º, § único, etc.)
     */
    protected function ehParagrafo(string $linha): bool
    {
        // Começa com o símbolo § ou a palavra Parágrafo
        return (bool) preg_match('/^(§|Parágrafo)\s*(\d+|único)/i', $linha);
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
     * Valida continuidade numérica real para evitar falsos positivos de OCR.
     */
    protected function numeroSequencialValido(string $anterior, string $atual): bool
    {
        // Remove caracteres não numéricos para comparação matemática
        $anteriorBase = (int) preg_replace('/\D/', '', $anterior);
        $atualBase    = (int) preg_replace('/\D/', '', $atual);

        // Sequência normal: 33 → 34
        if ($atualBase === $anteriorBase + 1) {
            return true;
        }

        // Subartigos: 10 → 10-A (Base numérica mantém-se)
        if ($atualBase === $anteriorBase) {
            return true;
        }

        // Se o salto for muito grande (ex: erro de leitura), retorna false
        if ($atualBase > $anteriorBase + 50) {
            return false;
        }

        return true; // Aceita pequenos saltos (artigos revogados)
    }

    /**
     * Ajusta confidence baseado em heurísticas pós-parse.
     * Artigos muito curtos podem ser erros de OCR (ex: cabeçalho de página lido como artigo).
     */
    protected function ajustarConfidence(array $artigos): array
    {
        foreach ($artigos as &$artigo) {
            $tamanho = mb_strlen($artigo['texto']);

            // Artigos com menos de 15 caracteres são suspeitos (Ex: "Art. 1º Revogado")
            // Mas "Revogado" é válido. "Art 1 o" (erro de OCR) não.
            if ($tamanho < 20) {
                $artigo['confidence'] = 'low';
            } elseif ($tamanho < 100) {
                $artigo['confidence'] = 'medium';
            }
        }

        return $artigos;
    }
}