<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    const TYPES = [
        'multiple_choice',
        'gap_filling',
        'select_options',
        'true_false_notgiven',
        'yes_no_notgiven',
        'matching',
        'sentence_completion',
        'short_answer',
        'diagram_labeling',
        'ordering',
    ];

    const TYPE_LABELS = [
        'multiple_choice' => 'Multiple Choice',
        'gap_filling' => 'Gap Filling',
        'select_options' => 'Select Options (Multiple Answers)',
        'true_false_notgiven' => 'True / False / Not Given',
        'yes_no_notgiven' => 'Yes / No / Not Given',
        'matching' => 'Matching',
        'sentence_completion' => 'Sentence/Summary Completion',
        'short_answer' => 'Short Answer',
        'diagram_labeling' => 'Diagram/Map Labeling',
        'ordering' => 'Ordering/Sequencing',
    ];

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

    // Type check methods
    public function isMultipleChoice(): bool { return $this->type === 'multiple_choice'; }
    public function isGapFilling(): bool { return $this->type === 'gap_filling'; }
    public function isSelectOptions(): bool { return $this->type === 'select_options'; }
    public function isTrueFalseNotGiven(): bool { return $this->type === 'true_false_notgiven'; }
    public function isYesNoNotGiven(): bool { return $this->type === 'yes_no_notgiven'; }
    public function isMatching(): bool { return $this->type === 'matching'; }
    public function isSentenceCompletion(): bool { return $this->type === 'sentence_completion'; }
    public function isShortAnswer(): bool { return $this->type === 'short_answer'; }
    public function isDiagramLabeling(): bool { return $this->type === 'diagram_labeling'; }
    public function isOrdering(): bool { return $this->type === 'ordering'; }

    public function getOptionsArrayAttribute(): array
    {
        return $this->options ?? [];
    }

    public function getCorrectAnswersArrayAttribute(): array
    {
        return $this->correct_answers ?? [];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    /**
     * Get metadata field helper
     */
    public function meta(string $key, $default = null)
    {
        return data_get($this->metadata, $key, $default);
    }

    public function checkAnswer($studentAnswer): bool
    {
        if ($this->isMultipleChoice()) {
            return in_array($studentAnswer, $this->correct_answers);
        }

        if ($this->isGapFilling()) {
            if (is_array($studentAnswer)) {
                $student = array_map(fn($a) => strtolower(trim($a)), $studentAnswer);
                $correct = array_map(fn($a) => strtolower(trim($a)), $this->correct_answers);
                return $student == $correct;
            }
            return false;
        }

        if ($this->isSelectOptions()) {
            if (is_array($studentAnswer)) {
                $student = collect($studentAnswer)->sort()->values()->all();
                $correct = collect($this->correct_answers)->sort()->values()->all();
                return $student == $correct;
            }
            return false;
        }

        if ($this->isTrueFalseNotGiven() || $this->isYesNoNotGiven()) {
            // Single answer: "True", "False", "Not Given" or "Yes", "No", "Not Given"
            return strtolower(trim($studentAnswer)) === strtolower(trim($this->correct_answers[0] ?? ''));
        }

        if ($this->isMatching()) {
            // studentAnswer is an object/assoc array: {"1": "B", "2": "A", ...}
            // correct_answers is same format
            if (is_array($studentAnswer)) {
                $correct = $this->correct_answers;
                foreach ($correct as $key => $value) {
                    if (!isset($studentAnswer[$key]) || strtolower(trim($studentAnswer[$key])) !== strtolower(trim($value))) {
                        return false;
                    }
                }
                return count($studentAnswer) === count($correct);
            }
            return false;
        }

        if ($this->isSentenceCompletion()) {
            // studentAnswer is array of filled blanks in order
            if (is_array($studentAnswer)) {
                $student = array_map(fn($a) => strtolower(trim($a)), array_values($studentAnswer));
                $correct = array_map(fn($a) => strtolower(trim($a)), array_values($this->correct_answers));
                return $student == $correct;
            }
            return false;
        }

        if ($this->isShortAnswer()) {
            // Single text answer, case-insensitive, trimmed
            $student = strtolower(trim(is_array($studentAnswer) ? ($studentAnswer[0] ?? '') : $studentAnswer));
            // Accept any of the correct answers (alternatives)
            foreach ($this->correct_answers as $correct) {
                if ($student === strtolower(trim($correct))) {
                    return true;
                }
            }
            return false;
        }

        if ($this->isDiagramLabeling()) {
            // studentAnswer is array of label values keyed by position
            if (is_array($studentAnswer)) {
                $correct = $this->correct_answers;
                foreach ($correct as $key => $value) {
                    if (!isset($studentAnswer[$key]) || strtolower(trim($studentAnswer[$key])) !== strtolower(trim($value))) {
                        return false;
                    }
                }
                return count($studentAnswer) === count($correct);
            }
            return false;
        }

        if ($this->isOrdering()) {
            // studentAnswer is array of items in student's order
            if (is_array($studentAnswer)) {
                return array_values($studentAnswer) == array_values($this->correct_answers);
            }
            return false;
        }

        return false;
    }
}
