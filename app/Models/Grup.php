<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Grup extends Model
{
    use HasFactory;

    protected $table = 'grup';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    /**
     * Relasi: Grup berisi banyak Penerima (many-to-many)
     */
    public function penerima(): BelongsToMany
    {
        return $this->belongsToMany(
            Penerima::class,
            'grup_penerima',
            'grup_id',
            'penerima_id'
        )->withTimestamps();
    }

    /**
     * Hitung jumlah anggota (untuk badge)
     */
    public function getJumlahAnggotaAttribute(): int
    {
        return $this->penerima()->count();
    }
}