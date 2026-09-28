<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateEmail extends Model
{
    use HasFactory;

    protected $table = 'template_email';

    protected $fillable = [
        'nama',
        'deskripsi',
        'subject',
        'body',
        'status',
    ];

    /**
     * Relasi: 1 Template bisa dipakai banyak Email
     */
    public function email(): HasMany
    {
        return $this->hasMany(Email::class, 'template_id');
    }

    /**
     * Scope: hanya template aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'active');
    }
}