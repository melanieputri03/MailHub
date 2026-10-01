<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penerima extends Model
{
    use HasFactory;

    protected $table = 'penerima';
    protected $primaryKey = 'penerima_id';      

    protected $fillable = [
        'nama',
        'email',
        'divisi_id',
        'jabatan',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi: Penerima milik 1 Divisi
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi_id', 'divisi_id');
    }

    // Relasi: Penerima bisa masuk banyak Grup (many-to-many)
    public function grup(): BelongsToMany
    {
        return $this->belongsToMany(
            Grup::class,
            'grup_penerima',
            'penerima_id',     // FK di pivot
            'grup_id',         // FK lawan
            'penerima_id',     // PK lokal (Penerima)
            'grup_id'          // PK lawan (Grup)
        )->withTimestamps();
    }

    // Relasi: Penerima bisa terima banyak Email (many-to-many)
    public function email(): BelongsToMany
    {
        return $this->belongsToMany(
            Email::class,
            'email_penerima',
            'penerima_id',     // FK di pivot
            'email_id',        // FK lawan
            'penerima_id',     // PK lokal
            'email_id'         // PK lawan
        )->withTimestamps();
    }

    // Relasi: Penerima punya banyak Log pengiriman
    public function emailLog(): HasMany
    {
        return $this->hasMany(EmailLog::class, 'penerima_id', 'penerima_id');
    }

    // Scope: hanya penerima aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'active');
    }
}