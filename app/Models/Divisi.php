<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisi';

    protected $fillable = [
        'nama',
        'deskripsi',
        'status',
    ];

     // Relasi: 1 Divisi punya banyak Penerima
    public function penerima(): HasMany
    {
        return $this->hasMany(Penerima::class, 'divisi_id');
    }
}