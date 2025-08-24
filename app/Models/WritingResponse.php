<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WritingResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'test_id',
        'test_session_id',
        'task',
        'response_content',
        'word_count',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function testSession(): BelongsTo
    {
        return $this->belongsTo(TestSession::class);
    }

    public function isTask1(): bool
    {
        return $this->task === 'task_1';
    }

    public function isTask2(): bool
    {
        return $this->task === 'task_2';
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function getDurationAttribute(): ?int
    {
        if ($this->started_at && $this->completed_at) {
            return $this->completed_at->diffInSeconds($this->started_at);
        }
        return null;
    }

    public function getDurationFormattedAttribute(): string
    {
        $duration = $this->duration;
        if (!$duration) {
            return 'Not completed';
        }

        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        $seconds = $duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function calculateWordCount(): int
    {
        $content = strip_tags($this->response_content);
        $words = preg_split('/\s+/', trim($content));
        return count(array_filter($words));
    }

    public function updateWordCount(): void
    {
        $this->update(['word_count' => $this->calculateWordCount()]);
    }
}
