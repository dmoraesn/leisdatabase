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
use Illuminate\Http\RedirectResponse; // Importante para tipagem do retorno

class LeiCreateScreen extends Screen
{
    /**
     * Define o nome da tela exibido no cabeçalho.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Cadastro de Legislação';
    }

    /**
     * Descrição da tela.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Preencha os blocos abaixo para registrar uma nova lei no sistema.';
    }

    /**
     * Carrega os dados iniciais da tela.
     *
     * @param Request $request
     * @return iterable
     */
    public function query(Request $request): iterable
    {
        return [
            'lei' => new Lei(),
        ];
    }

    /**
     * Botões de ação na barra superior.
     *
     * @return iterable
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Salvar Registro')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    /**
     * Layout da tela dividido em blocos lógicos.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            // ---------------------------------------------------------
            // BLOCO 1: Dados Principais
            // ---------------------------------------------------------
            Layout::block(Layout::rows([
                Input::make('lei.titulo')
                    ->title('Título da Lei')
                    ->placeholder('Ex: Lei Orgânica do Município...')
                    ->help('O título deve ser claro e descritivo.')
                    ->required(),

                Group::make([
                    Input::make('lei.numero')
                        ->title('Número')
                        ->placeholder('Ex: 12.345'),

                    Input::make('lei.ano')
                        ->type('number')
                        ->title('Ano')
                        ->placeholder((string) date('Y')),
                ]),
            ]))
            ->title('Identificação da Norma')
            ->description('Informações básicas para identificação oficial do documento legal.'),

            // ---------------------------------------------------------
            // BLOCO 2: Localização (Listener Dinâmico)
            // ---------------------------------------------------------
            LeiLocalizacaoListener::class,

            // ---------------------------------------------------------
            // BLOCO 3: Arquivos
            // ---------------------------------------------------------
            Layout::block(Layout::rows([
                Upload::make('pdf')
                    ->title('Arquivo PDF Original')
                    ->acceptedFiles('.pdf')
                    ->maxFiles(1)
                    ->help('O sistema processará este arquivo automaticamente para extrair os artigos. Certifique-se de que o PDF é pesquisável (OCR).'),
            ]))
            ->title('Digitalização')
            ->description('Faça o upload do texto original da lei para processamento.'),
        ];
    }

    /**
     * Ação de Salvar.
     * Realiza validação, limpeza, persistência e dispara o job de processamento.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function save(Request $request): RedirectResponse
    {
        // 1. Validação dos dados recebidos
        $dados = $request->input('lei');
        $abrangencia = $dados['abrangencia'] ?? null;

        $regras = [
            'lei.titulo'      => ['required', 'string', 'min:3', 'max:255'],
            'lei.abrangencia' => ['required', 'in:federal,estadual,municipal'],
        ];

        // Regras condicionais baseadas na abrangência
        if ($abrangencia === 'estadual') {
            $regras['lei.estado'] = ['required', 'string', 'size:2'];
        }
        if ($abrangencia === 'municipal') {
            $regras['lei.estado'] = ['required', 'string', 'size:2'];
            $regras['lei.cidade'] = ['required', 'string'];
        }

        $request->validate($regras, [
            'lei.titulo.required'      => 'O título da lei é obrigatório.',
            'lei.abrangencia.required' => 'A abrangência é obrigatória.',
            'lei.estado.required'      => 'Para leis estaduais/municipais, o Estado é obrigatório.',
            'lei.cidade.required'      => 'Para leis municipais, a Cidade é obrigatória.',
        ]);

        // 2. Limpeza de dados (Sanitization)
        if ($abrangencia === 'federal') {
            $dados['estado'] = null;
            $dados['cidade'] = null;
        } elseif ($abrangencia === 'estadual') {
            $dados['cidade'] = null;
        }

        // 3. Persistência da Lei no Banco de Dados
        $lei = Lei::create(array_merge(
            $dados,
            ['status' => 'processando']
        ));

        // 4. Associação do PDF e Disparo do Job
        if ($request->filled('pdf')) {
            $lei->attachment()->sync($request->input('pdf'));
            
            // Recupera o anexo recém salvo para garantir que temos o ID correto
            $anexo = $lei->attachment()->first();

            if ($anexo) {
                ProcessarLeiPdfJob::dispatch($lei->id, $anexo->id);
            }
        }

        Toast::info('Lei cadastrada com sucesso! O processamento iniciou em segundo plano.');

        // REDIRECIONAMENTO EXPLÍCITO PARA A LISTA
        return redirect()->route('platform.leis.list');
    }
}