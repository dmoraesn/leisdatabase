<?php

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

    protected $table = 'leis';

    protected $fillable = [
        'titulo',
        'numero',
        'ano',
        'abrangencia',
        'estado',  // Novo campo
        'cidade',  // Novo campo
        'status',
        'json_original',
    ];

    protected $casts = [
        'json_original' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos
    |--------------------------------------------------------------------------
    */

    public function artigos(): HasMany
    {
        return $this->hasMany(Artigo::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers (⚠️ NÃO são relacionamentos)
    |--------------------------------------------------------------------------
    */

    /**
     * Retorna o primeiro PDF anexado (ou null)
     */
    public function getPdf(): ?Attachment
    {
        return $this->attachment()->orderBy('sort')->first();
    }

    /**
     * Verifica se existe PDF
     */
    public function hasPdf(): bool
    {
        return $this->attachment()->exists();
    }
}