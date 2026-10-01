<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Email extends Model
{
    use HasFactory;

    protected $table = 'email';
    protected $primaryKey = 'email_id';        

    protected $fillable = [
        'template_id',
        'nama',
        'subject',
        'body',
        'message_id',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at'    => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi: Email dibuat dari 1 Template (nullable)
    public function template(): BelongsTo
    {
        return $this->belongsTo(TemplateEmail::class, 'template_id', 'template_email_id');
    }

    // Relasi: Email dikirim ke banyak Penerima (many-to-many)
    public function penerima(): BelongsToMany
    {
        return $this->belongsToMany(
            Penerima::class,
            'email_penerima',
            'email_id',        
            'penerima_id',     
            'email_id',        
            'penerima_id'      
        )->withTimestamps();
    }

    // Relasi: Email punya banyak Log pengiriman
    public function emailLog(): HasMany
    {
        return $this->hasMany(EmailLog::class, 'email_id', 'email_id');
    }

    // ACCESSOR
    public function getTotalPenerimaAttribute(): int
    {
        return $this->emailLog()->count();
    }

    public function getTotalBerhasilAttribute(): int
    {
        return $this->emailLog()->where('status', 'success')->count();
    }

    public function getTotalGagalAttribute(): int
    {
        return $this->emailLog()->where('status', 'failed')->count();
    }
}