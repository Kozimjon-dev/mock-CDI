<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone_number',
        'address',
        'email',
        'session_id'
    ];

    public function testSessions(): HasMany
    {
        return $this->hasMany(TestSession::class);
    }

    public function studentResponses(): HasMany
    {
        return $this->hasMany(StudentResponse::class);
    }

    public function writingResponses(): HasMany
    {
        return $this->hasMany(WritingResponse::class);
    }

    public function getCurrentSessionAttribute(): ?TestSession
    {
        return $this->testSessions()
            ->whereNull('completed_at')
            ->latest()
            ->first();
    }

    public function hasActiveSession(): bool
    {
        return $this->currentSession !== null;
    }

    public function getCompletedTestsAttribute()
    {
        return $this->testSessions()
            ->whereNotNull('completed_at')
            ->with('test')
            ->get();
    }
}
