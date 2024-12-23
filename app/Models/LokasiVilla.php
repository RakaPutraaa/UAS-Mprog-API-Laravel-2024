<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LokasiVilla extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_villa',
        'lokasi_villa'
    ];

    /**
     * Get the villa that owns the LokasiVilla
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function villa(): BelongsTo
    {
        return $this->belongsTo(ListVilla::class, 'id_villa', 'id');
    }
}
