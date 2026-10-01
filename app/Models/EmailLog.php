<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    use HasFactory;

    protected $table = 'email_log';
    protected $primaryKey = 'email_log_id';  

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

    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class, 'email_id', 'email_id');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(Penerima::class, 'penerima_id', 'penerima_id');
    }
}