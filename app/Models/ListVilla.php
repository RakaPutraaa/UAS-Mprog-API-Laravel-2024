<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ListVilla extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'nama_villa',
        'deskripsi_villa',
        'harga_villa',
        'foto_villa'
    ];

    /**
     * Get the user that owns the ListVilla
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
