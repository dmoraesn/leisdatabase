<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeiLocalidade extends Model
{
    protected $table = 'lei_localidades';

    protected $fillable = [
        'lei_id',
        'pais',
        'estado',
        'cidade',
        'ibge_estado_id',
        'ibge_cidade_id',
    ];

    /**
     * Lei relacionada
     */
    public function lei(): BelongsTo
    {
        return $this->belongsTo(Lei::class);
    }
}
