<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Orchid\Screen\AsSource;

class Artigo extends Model
{
    use AsSource;

    /**
     * @var string
     */
    protected $table = 'artigos';

    /**
     * @var array
     */
    protected $fillable = [
        'lei_id',
        'numero',
        'texto',
        'ordem',
        'origem', // 'auto' (importação) ou 'manual' (edição humana)
        'confidence', // 'high', 'medium', 'low'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'ordem' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos
    |--------------------------------------------------------------------------
    */

    /**
     * Relacionamento reverso com a Lei.
     *
     * @return BelongsTo
     */
    public function lei(): BelongsTo
    {
        return $this->belongsTo(Lei::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (UI / Orchid)
    |--------------------------------------------------------------------------
    */

    /**
     * Retorna o texto completo do artigo convertido para string.
     * Útil para garantir que null vire string vazia.
     *
     * @return string
     */
    public function getTextoCompletoAttribute(): string
    {
        return (string) $this->texto;
    }

    /**
     * Gera um preview seguro para exibição em tabelas.
     * Remove quebras de linha excessivas e trunca o texto.
     *
     * @return string
     */
    public function getTextoPreviewAttribute(): string
    {
        return Str::of($this->texto)
            ->replace(["\r\n", "\r"], "\n")
            ->limit(150, '...')
            ->toString();
    }
}