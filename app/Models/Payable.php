<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payable extends Model
{
    /** @use HasFactory<\Database\Factories\PayableFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
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
        'paid_at',
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
        'paid_at' => 'datetime',
        'is_recurring' => 'boolean',
    ];

    /**
     * Payable owner.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
