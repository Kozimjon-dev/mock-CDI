<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'test_id',
        'session_token',
        'current_module',
        'started_at',
        'listening_started_at',
        'listening_completed_at',
        'reading_started_at',
        'reading_completed_at',
        'writing_started_at',
        'writing_completed_at',
        'completed_at',
        'is_fullscreen',
        'has_cheated',
        'cheat_attempts'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'listening_started_at' => 'datetime',
        'listening_completed_at' => 'datetime',
        'reading_started_at' => 'datetime',
        'reading_completed_at' => 'datetime',
        'writing_started_at' => 'datetime',
        'writing_completed_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_fullscreen' => 'boolean',
        'has_cheated' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function studentResponses(): HasMany
    {
        return $this->hasMany(StudentResponse::class);
    }

    public function writingResponses(): HasMany
    {
        return $this->hasMany(WritingResponse::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function isModuleCompleted(string $module): bool
    {
        $completionField = $module . '_completed_at';
        return $this->$completionField !== null;
    }

    public function getModuleStartTime(string $module): ?string
    {
        $startField = $module . '_started_at';
        return $this->$startField;
    }

    public function getModuleEndTime(string $module): ?string
    {
        $endField = $module . '_completed_at';
        return $this->$endField;
    }

    public function getModuleDuration(string $module): ?int
    {
        $start = $this->getModuleStartTime($module);
        $end = $this->getModuleEndTime($module);
        
        if ($start && $end) {
            return $end->diffInSeconds($start);
        }
        
        return null;
    }

    public function markModuleStarted(string $module): void
    {
        $field = $module . '_started_at';
        if (!$this->$field) {
            $this->update([$field => now()]);
        }
    }

    public function markModuleCompleted(string $module): void
    {
        $field = $module . '_completed_at';
        if (!$this->$field) {
            $this->update([$field => now()]);
        }
    }

    public function recordCheatAttempt(string $attempt): void
    {
        $attempts = $this->cheat_attempts ? json_decode($this->cheat_attempts, true) : [];
        $attempts[] = [
            'attempt' => $attempt,
            'timestamp' => now()->toISOString()
        ];
        
        $this->update([
            'cheat_attempts' => json_encode($attempts),
            'has_cheated' => true
        ]);
    }
}
