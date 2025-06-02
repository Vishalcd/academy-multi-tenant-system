<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Academy extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'email',
        'photo',
        'favicon'
    ];

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

    // Relation to transaction Model
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relation to sport Model
    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }
}
