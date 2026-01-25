<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Models\Attachment;

class Lei extends Model
{
    use AsSource;
    use Attachable;

    /**
     * @var string
     */
    protected $table = 'leis';

    /**
     * @var array
     */
    protected $fillable = [
        'titulo',
        'numero',
        'ano',
        'abrangencia', // federal, estadual, municipal
        'estado',
        'cidade',
        'status',
        'json_original',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'json_original' => 'array',
        'ano'           => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos
    |--------------------------------------------------------------------------
    */

    /**
     * Relacionamento com Artigos.
     * Uma lei pode ter múltiplos artigos associados.
     *
     * @return HasMany
     */
    public function artigos(): HasMany
    {
        return $this->hasMany(Artigo::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers e Métodos Auxiliares
    |--------------------------------------------------------------------------
    */

    /**
     * Retorna o primeiro arquivo PDF anexado à Lei.
     * Utiliza a ordenação padrão do Orchid (sort).
     *
     * @return Attachment|null
     */
    public function getPdf(): ?Attachment
    {
        return $this->attachment()
            ->orderBy('sort')
            ->first();
    }

    /**
     * Verifica se existe ao menos um arquivo PDF anexado.
     * Útil para condicionais de exibição na interface (CanSee).
     *
     * @return bool
     */
    public function hasPdf(): bool
    {
        return $this->attachment()->exists();
    }

    /**
     * Retorna uma string formatada da localização baseada na abrangência.
     * Ex: "Fortaleza - CE" ou "Brasil (Federal)".
     *
     * @return string
     */
    public function getLocalizacaoFormatada(): string
    {
        if ($this->abrangencia === 'federal') {
            return 'Brasil (Federal)';
        }

        if ($this->abrangencia === 'estadual') {
            return $this->estado ?? 'Estado não informado';
        }

        if ($this->cidade && $this->estado) {
            return "{$this->cidade} - {$this->estado}";
        }

        return 'Localização não definida';
    }
}