<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StudentResponse;

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
        return $this->completed_at !== null || $this->current_module === 'completed';
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

    /**
     * Convert a raw score to an IELTS band score.
     * Uses percentage-based mapping so it works regardless of total question count.
     */
    public function calculateBandScore(string $module): float
    {
        $correct = StudentResponse::where('student_id', $this->student_id)
            ->where('test_id', $this->test_id)
            ->where('module', $module)
            ->where('is_correct', true)
            ->count();

        $total = $this->test->questions()->where('module', $module)->count();

        if ($total === 0) {
            return 0;
        }

        // Normalize to a 40-question scale for band mapping
        $normalized = ($correct / $total) * 40;

        return self::rawToBand($normalized);
    }

    /**
     * Map a normalized raw score (out of 40) to an IELTS band.
     */
    public static function rawToBand(float $raw): float
    {
        // IELTS approximate band conversion table (for 40-question modules)
        $table = [
            [39, 40, 9.0],
            [37, 38, 8.5],
            [35, 36, 8.0],
            [33, 34, 7.5],
            [30, 32, 7.0],
            [27, 29, 6.5],
            [23, 26, 6.0],
            [20, 22, 5.5],
            [16, 19, 5.0],
            [13, 15, 4.5],
            [10, 12, 4.0],
            [6,  9,  3.5],
            [4,  5,  3.0],
            [0,  3,  2.0],
        ];

        foreach ($table as [$min, $max, $band]) {
            if ($raw >= $min && $raw <= $max) {
                return $band;
            }
        }

        return 2.0;
    }

    /**
     * Get overall band score (average of listening + reading, rounded to nearest 0.5).
     */
    public function getOverallBandScore(): float
    {
        $listening = $this->calculateBandScore('listening');
        $reading = $this->calculateBandScore('reading');

        $average = ($listening + $reading) / 2;

        return round($average * 2) / 2; // round to nearest 0.5
    }

    /**
     * Get detailed module scores with raw counts and band scores.
     */
    public function getModuleScores(): array
    {
        $scores = [];

        foreach (['listening', 'reading'] as $module) {
            $correct = StudentResponse::where('student_id', $this->student_id)
                ->where('test_id', $this->test_id)
                ->where('module', $module)
                ->where('is_correct', true)
                ->count();

            $total = $this->test->questions()->where('module', $module)->count();
            $normalized = $total > 0 ? ($correct / $total) * 40 : 0;

            $scores[$module] = [
                'correct' => $correct,
                'total' => $total,
                'band' => self::rawToBand($normalized),
            ];
        }

        $average = ($scores['listening']['band'] + $scores['reading']['band']) / 2;

        $scores['overall'] = [
            'correct' => $scores['listening']['correct'] + $scores['reading']['correct'],
            'total' => $scores['listening']['total'] + $scores['reading']['total'],
            'band' => round($average * 2) / 2,
        ];

        return $scores;
    }
}
