<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class Artigo extends Model
{
    use AsSource;

    protected $table = 'artigos';

    protected $fillable = [
        'lei_id',
        'numero',
        'texto',
        'ordem',
        'origem',
        'confidence',
    ];

    protected $casts = [
        'ordem' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos
    |--------------------------------------------------------------------------
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
     * Texto completo do artigo (sem truncamento)
     */
    public function getTextoCompletoAttribute(): string
    {
        return (string) $this->texto;
    }

    /**
     * Preview seguro para tabelas
     */
    public function getTextoPreviewAttribute(): string
    {
        return str($this->texto)
            ->replace("\r\n", "\n")
            ->replace("\r", "\n")
            ->limit(350)
            ->toString();
    }
}
