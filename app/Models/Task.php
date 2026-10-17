<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'description', 'priority', 'status', 'due_date'])]
class Task extends Model
{
    use HasFactory;

    public const STATUSES = [
        'todo' => 'À faire',
        'in_progress' => 'En cours',
        'completed' => 'Terminée',
    ];

    public const PRIORITIES = [
        'low' => 'Basse',
        'medium' => 'Moyenne',
        'high' => 'Haute',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'completed'
            && $this->due_date !== null
            && $this->due_date->isBefore(today());
    }
}
