<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListVilla extends Model
{
    use HasFactory;
    protected $table = 'list_villa';
    protected $fillable = [
        'id_user',
        'id_kabupaten',
        'nama_villa',
        'deskripsi_villa',
        'harga_villa',
        'lokasi_villa',
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

    /**
     * Get the user that owns the ListVilla
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'id_kabupaten', 'id');
    }

}
