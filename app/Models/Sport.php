<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sport extends Model
{
    use HasFactory;

    protected $fillable = [
        'sport_title',
        'sport_fees',
        'photo',
        'academy_id'
    ];

    // Relation to student Model
    public function student(): BelongsTo
    {
        return $this->BelongsTo(Student::class);
    }

    // Relation to employee Model
    public function employee(): BelongsTo
    {
        return $this->BelongsTo(Employee::class);
    }

    // Relation to academy Model
    public function academy(): BelongsTo
    {
        return $this->belongsTo(Academy::class);
    }
}
