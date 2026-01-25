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

class LeiCreateScreen extends Screen
{
    public function name(): ?string
    {
        return 'Cadastro de Legislação';
    }

    public function description(): ?string
    {
        return 'Preencha os blocos abaixo para registrar uma nova lei.';
    }

    public function query(Request $request): iterable
    {
        return [
            'lei' => new Lei(),
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Salvar Registro')
                ->icon('bs.check-circle')
                ->method('save'),
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
                    ->placeholder('Ex: Lei Orgânica do Município...')
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
            ->description('Informações básicas para identificação do documento legal.'),

            // ---------------------------------------------------------
            // BLOCO 2: Localização (Listener)
            // ---------------------------------------------------------
            // O próprio Listener agora retornará um Bloco visual
            LeiLocalizacaoListener::class,

            // ---------------------------------------------------------
            // BLOCO 3: Arquivos
            // ---------------------------------------------------------
            Layout::block(Layout::rows([
                Upload::make('pdf')
                    ->title('Arquivo PDF Original')
                    ->acceptedFiles('.pdf')
                    ->maxFiles(1)
                    ->help('O sistema processará este arquivo automaticamente para extrair os artigos.'),
            ]))
            ->title('Digitalização')
            ->description('Faça o upload do texto original da lei.'),
        ];
    }

    public function save(Request $request)
    {
        // 1. Validação
        $dados = $request->input('lei');
        $abrangencia = $dados['abrangencia'] ?? null;

        $regras = [
            'lei.titulo'      => ['required', 'string', 'min:3', 'max:255'],
            'lei.abrangencia' => ['required', 'in:federal,estadual,municipal'],
        ];

        if ($abrangencia === 'estadual') {
            $regras['lei.estado'] = ['required', 'string', 'size:2'];
        }
        if ($abrangencia === 'municipal') {
            $regras['lei.estado'] = ['required', 'string', 'size:2'];
            $regras['lei.cidade'] = ['required', 'string'];
        }

        $request->validate($regras);

        // 2. Limpeza
        if ($abrangencia === 'federal') {
            $dados['estado'] = null;
            $dados['cidade'] = null;
        } elseif ($abrangencia === 'estadual') {
            $dados['cidade'] = null;
        }

        // 3. Persistência
        $lei = Lei::create(array_merge(
            $dados,
            ['status' => 'processando']
        ));

        // 4. Upload e Job
        if ($request->filled('pdf')) {
            $lei->attachment()->sync($request->input('pdf'));
            $anexo = $lei->attachment()->first();

            if ($anexo) {
                ProcessarLeiPdfJob::dispatch($lei->id, $anexo->id);
            }
        }

        Toast::info('Lei cadastrada! Processamento iniciado.');
        return redirect()->route('platform.leis.list');
    }
}