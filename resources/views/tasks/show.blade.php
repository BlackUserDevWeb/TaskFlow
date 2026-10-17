@extends('layouts.app')

@section('title', $task->title)

@section('content')
<main class="main">
    <div class="page-heading">
        <div><p class="eyebrow">Détail de la tâche</p><p class="subtitle"><a class="link" href="{{ route('tasks.index') }}">Mes tâches</a> <span aria-hidden="true">/</span> {{ $task->title }}</p></div>
        <div class="actions-row" style="gap:9px">
            <a class="button secondary" href="{{ route('tasks.edit', $task) }}">Modifier</a>
            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Supprimer définitivement cette tâche ?')">
                @csrf
                @method('DELETE')
                <button class="button danger" type="submit">Supprimer</button>
            </form>
        </div>
    </div>
    <article class="card detail-card">
        <div class="detail-head">
            <div><h1>{{ $task->title }}</h1><p class="subtitle">Créée le {{ $task->created_at->format('d/m/Y') }}</p></div>
            <span class="badge status-{{ $task->status }}">{{ $taskStatuses[$task->status] }}</span>
        </div>
        @if ($task->description)
            <p class="detail-description">{{ $task->description }}</p>
        @else
            <p class="detail-description" style="color:var(--muted);font-style:italic">Aucune description n’a été ajoutée.</p>
        @endif
        <div class="detail-meta">
            <span class="badge priority-{{ $task->priority }}">Priorité : {{ $taskPriorities[$task->priority] }}</span>
            <span @class(['badge', 'overdue-text' => $task->isOverdue()])>
                Échéance : {{ $task->due_date?->format('d/m/Y') ?? 'Sans échéance' }}
                @if ($task->isOverdue()) · En retard @endif
            </span>
        </div>
    </article>
</main>
@endsection
