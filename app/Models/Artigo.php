<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Artigo extends Model
{
    protected $table = 'artigos';

    protected $fillable = [
        'lei_id',
        'numero',
        'texto',
        'ordem',
    ];

    /**
     * Lei a que pertence
     */
    public function lei(): BelongsTo
    {
        return $this->belongsTo(Lei::class);
    }
}
