<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'matric_number',
        'graduation_year',
        'department_name',
        'email',
        'phone',
        'id_document_path',
        'notes',
        'status',
        'reviewed_by_user_id',
        'reviewed_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'graduation_year' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }
}
