<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GraduateRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'matric_number',
        'first_name',
        'middle_name',
        'last_name',
        'full_name',
        'faculty_id',
        'department_id',
        'degree',
        'class_of_degree',
        'graduation_year',
        'status',
        'claimed_by_user_id',
        'claimed_at',
        'verification_hash',
    ];

    protected function casts(): array
    {
        return [
            'graduation_year' => 'integer',
            'claimed_at' => 'datetime',
        ];
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function claimedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimed_by_user_id');
    }
}
