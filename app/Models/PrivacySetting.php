<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivacySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_visibility',
        'email_visibility',
        'phone_visibility',
        'work_visibility',
        'location_visibility',
        'allow_messaging',
    ];

    protected function casts(): array
    {
        return [
            'allow_messaging' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
