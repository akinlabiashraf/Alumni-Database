<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'matric_number',
        'phone',
        'password',
        'role',
        'status',
        'graduate_record_id',
        'ndpa_consent',
        'ndpa_consent_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'ndpa_consent' => 'boolean',
            'ndpa_consent_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function graduateRecord(): BelongsTo
    {
        return $this->belongsTo(GraduateRecord::class, 'graduate_record_id');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function privacySetting(): HasOne
    {
        return $this->hasOne(PrivacySetting::class);
    }

    public function manualVerifications(): HasMany
    {
        return $this->hasMany(ManualVerification::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerator(): bool
    {
        return in_array($this->role, ['admin', 'moderator']);
    }
}
