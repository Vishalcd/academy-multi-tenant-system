<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'job_title',
        'salary',
        'pending_salary',
        'last_paid',
        'salary_settled',
        'sport_id'
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

    // Relation to sport Model
    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }
}
