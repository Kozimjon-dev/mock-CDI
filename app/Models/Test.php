<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'listening_time',
        'reading_time',
        'writing_time',
        'status',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function testSessions(): HasMany
    {
        return $this->hasMany(TestSession::class);
    }

    public function listeningMaterials(): HasMany
    {
        return $this->materials()->where('module', 'listening');
    }

    public function readingMaterials(): HasMany
    {
        return $this->materials()->where('module', 'reading');
    }

    public function writingMaterials(): HasMany
    {
        return $this->materials()->where('module', 'writing');
    }

    public function listeningQuestions(): HasMany
    {
        return $this->questions()->where('module', 'listening')->orderBy('part')->orderBy('order');
    }

    public function readingQuestions(): HasMany
    {
        return $this->questions()->where('module', 'reading')->orderBy('part')->orderBy('order');
    }

    public function writingQuestions(): HasMany
    {
        return $this->questions()->where('module', 'writing')->orderBy('part')->orderBy('order');
    }

    public function getTotalTimeAttribute(): int
    {
        return $this->listening_time + $this->reading_time + $this->writing_time;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->where('status', 'active');
    }
}
