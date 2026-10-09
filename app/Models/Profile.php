<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar',
        'headline',
        'bio',
        'current_job_title',
        'current_company',
        'industry',
        'work_location',
        'city',
        'state',
        'country',
        'linkedin_url',
        'twitter_url',
        'github_url',
        'portfolio_url',
        'skills',
        'education_history',
        'career_history',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'education_history' => 'array',
            'career_history' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
