<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'material_id',
        'module',
        'part',
        'type',
        'question_text',
        'options',
        'correct_answers',
        'points',
        'order',
        'metadata'
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'metadata' => 'array',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function studentResponses(): HasMany
    {
        return $this->hasMany(StudentResponse::class);
    }

    public function isMultipleChoice(): bool
    {
        return $this->type === 'multiple_choice';
    }

    public function isGapFilling(): bool
    {
        return $this->type === 'gap_filling';
    }

    public function isSelectOptions(): bool
    {
        return $this->type === 'select_options';
    }

    public function getOptionsArrayAttribute(): array
    {
        return $this->options ?? [];
    }

    public function getCorrectAnswersArrayAttribute(): array
    {
        return $this->correct_answers ?? [];
    }

    public function checkAnswer($studentAnswer): bool
    {
        if ($this->isMultipleChoice()) {
            return in_array($studentAnswer, $this->correct_answers);
        } elseif ($this->isGapFilling()) {
            // For gap filling, check if all answers match
            if (is_array($studentAnswer)) {
                return count(array_diff($studentAnswer, $this->correct_answers)) === 0 &&
                       count(array_diff($this->correct_answers, $studentAnswer)) === 0;
            }
            return false;
        } elseif ($this->isSelectOptions()) {
            // For select options, check if selected options match
            if (is_array($studentAnswer)) {
                return count(array_diff($studentAnswer, $this->correct_answers)) === 0 &&
                       count(array_diff($this->correct_answers, $studentAnswer)) === 0;
            }
            return false;
        }
        return false;
    }
}
