<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'expense_id',
        'transaction_type',
        'transaction_amount',
        'transaction_method',
        'transaction_for',
        'academy_id',
    ];

    // Relation to academy Model
    public function academy(): BelongsTo
    {
        return $this->belongsTo(Academy::class);
    }

    // Relation to user Model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relation to expense Model
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

}
