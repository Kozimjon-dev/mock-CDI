<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'test_id',
        'question_id',
        'student_answer',
        'is_correct',
        'points_earned',
        'module',
        'answered_at'
    ];

    protected $casts = [
        'student_answer' => 'array',
        'is_correct' => 'boolean',
        'answered_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function getStudentAnswerArrayAttribute(): array
    {
        return $this->student_answer ?? [];
    }

    public function getFormattedAnswerAttribute(): string
    {
        if (is_array($this->student_answer)) {
            return implode(', ', $this->student_answer);
        }
        return (string) $this->student_answer;
    }

    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }
}
