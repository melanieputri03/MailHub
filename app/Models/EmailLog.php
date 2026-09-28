<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    use HasFactory;

    protected $table = 'email_log';

    protected $fillable = [
        'email_id',
        'penerima_id',
        'penerima_email',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at'    => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // RELASI
    /**
     * Relasi: Log milik 1 Email
     */
    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class, 'email_id');
    }

    /**
     * Relasi: Log milik 1 Penerima
     */
    public function penerima(): BelongsTo
    {
        return $this->belongsTo(Penerima::class, 'penerima_id');
    }

    // SCOPE
    /**
     * Scope: log yang sukses
     */
    public function scopeSukses($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope: log yang gagal
     */
    public function scopeGagal($query)
    {
        return $query->where('status', 'failed');
    }
}