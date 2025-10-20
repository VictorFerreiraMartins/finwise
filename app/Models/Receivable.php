<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receivable extends Model
{
    /** @use HasFactory<\Database\Factories\ReceivableFactory> */
    use HasFactory;

    public const STATUS_EXPECTED = 'expected';
    public const STATUS_RECEIVED = 'received';
    public const STATUS_OVERDUE = 'overdue';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'amount',
        'due_date',
        'reminder_date',
        'status',
        'received_at',
        'is_recurring',
        'recurrence_interval',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'reminder_date' => 'date',
        'received_at' => 'datetime',
        'is_recurring' => 'boolean',
    ];

    /**
     * Receivable owner.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
