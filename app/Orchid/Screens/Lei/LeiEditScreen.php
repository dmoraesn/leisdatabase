<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Lei;

use App\Jobs\ProcessarLeiPdfJob;
use App\Models\Lei;
use App\Orchid\Layouts\Lei\LeiLocalizacaoListener;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class LeiEditScreen extends Screen
{
    /**
     * @var Lei
     */
    public $lei;

    public function query(Lei $lei): iterable
    {
        return [
            'lei' => $lei,
        ];
    }

    public function name(): string
    {
        return 'Editar Legislação';
    }

    public function description(): string
    {
        return 'Atualize as informações, abrangência ou o arquivo digital da norma.';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Salvar Alterações')
                ->icon('bs.check-circle')
                ->method('save'),
            
            Button::make('Reprocessar PDF')
                ->icon('bs.arrow-repeat')
                ->method('reprocessarPdf')
                ->canSee($this->lei->exists && $this->lei->hasPdf()),
        ];
    }

    public function layout(): iterable
    {
        return [
            // ---------------------------------------------------------
            // BLOCO 1: Dados Principais
            // ---------------------------------------------------------
            Layout::block(Layout::rows([
                Input::make('lei.titulo')
                    ->title('Título da Lei')
                    ->placeholder('Digite o título completo')
                    ->required(),

                Group::make([
                    Input::make('lei.numero')
                        ->title('Número da Lei')
                        ->placeholder('Ex: 12.345'),

                    Input::make('lei.ano')
                        ->type('number')
                        ->title('Ano de Publicação'),
                ]),
            ]))
            ->title('Identificação da Norma')
            ->description('Dados básicos de identificação e referência do documento.'),

            // ---------------------------------------------------------
            // BLOCO 2: Localização (Listener Dinâmico)
            // ---------------------------------------------------------
            // Como atualizamos o Listener para retornar um Layout::block,
            // basta chamá-lo aqui. Ele cuidará do título e descrição.
            LeiLocalizacaoListener::class,

            // ---------------------------------------------------------
            // BLOCO 3: Arquivos
            // ---------------------------------------------------------
            Layout::block(Layout::rows([
                Upload::make('lei.pdf')
                    ->title('Arquivo PDF da Lei')
                    ->groups('leis')
                    ->acceptedFiles('.pdf')
                    ->maxFiles(1)
                    // Carrega os IDs dos anexos existentes
                    ->value($this->lei->attachment->pluck('id')->toArray())
                    ->help('Se você substituir o arquivo, o sistema reprocessará o texto e os artigos automaticamente.'),
            ]))
            ->title('Digitalização')
            ->description('Gerenciamento do arquivo original e processamento.'),
        ];
    }

    /**
     * Salva as alterações, limpa dados inconsistentes e dispara Jobs.
     */
    public function save(Request $request, Lei $lei): void
    {
        $dadosFormulario = $request->get('lei');
        $abrangencia = $dadosFormulario['abrangencia'] ?? null;

        // 1. Validação Básica
        $request->validate([
            'lei.titulo'      => 'required|string|max:255',
            'lei.abrangencia' => 'required',
        ]);

        // 2. Regra de Higienização de Localização
        if ($abrangencia === 'federal') {
            $dadosFormulario['estado'] = null;
            $dadosFormulario['cidade'] = null;
        } elseif ($abrangencia === 'estadual') {
            $dadosFormulario['cidade'] = null;
        }

        // 3. Persistência dos Dados
        $lei->fill($dadosFormulario);
        $lei->save();

        // 4. Tratamento Inteligente do PDF
        // Como o campo é 'lei.pdf', ele vem dentro do array 'lei'
        $anexos = $dadosFormulario['pdf'] ?? [];
        
        $mudancas = $lei->attachment()->sync($anexos);

        // Se houver novo arquivo anexado ('attached'), dispara o Job
        if (count($mudancas['attached']) > 0) {
            $novoAnexoId = $mudancas['attached'][0];

            $lei->update(['status' => 'processando']);
            ProcessarLeiPdfJob::dispatch($lei->id, $novoAnexoId);

            Toast::info('Novo PDF salvo. O processamento iniciou em segundo plano.');
        } else {
            Toast::success('Os dados da lei foram atualizados com sucesso.');
        }
    }

    /**
     * Ação manual para reprocessar PDF existente.
     */
    public function reprocessarPdf(Lei $lei): void
    {
        if ($lei->hasPdf()) {
            $lei->update(['status' => 'processando']);
            ProcessarLeiPdfJob::dispatch($lei->id, $lei->getPdf()->id);
            
            Toast::info('Solicitação enviada. O PDF será reprocessado.');
        } else {
            Toast::warning('Nenhum PDF encontrado para processar.');
        }
    }
}