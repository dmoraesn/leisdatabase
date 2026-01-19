<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeiImportacao extends Model
{
    protected $table = 'lei_importacoes';

    protected $fillable = [
        'lei_id',
        'arquivo_pdf',
        'texto_extraido',
        'status',
        'erro',
    ];

    /**
     * Lei relacionada
     */
    public function lei(): BelongsTo
    {
        return $this->belongsTo(Lei::class);
    }
}
