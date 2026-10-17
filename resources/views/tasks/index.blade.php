@extends('layouts.app')

@section('title', 'Mes tâches')

@section('content')
<main class="main">
    <div class="page-heading">
        <div>
            <p class="eyebrow">Organisation</p>
            <h1>Mes tâches</h1>
            <p class="subtitle">Retrouvez, filtrez et mettez à jour votre travail.</p>
        </div>
        <a class="button" href="{{ route('tasks.create') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Nouvelle tâche
        </a>
    </div>

    <form class="card filters" method="GET" action="{{ route('tasks.index') }}">
        <div class="field">
            <label class="visually-hidden" for="search">Rechercher par titre</label>
            <input class="input" id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" placeholder="Rechercher par titre…">
        </div>
        <div class="field">
            <label class="visually-hidden" for="status">Filtrer par statut</label>
            <select class="select" id="status" name="status">
                <option value="">Tous les statuts</option>
                @foreach ($taskStatuses as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label class="visually-hidden" for="priority">Filtrer par priorité</label>
            <select class="select" id="priority" name="priority">
                <option value="">Toutes les priorités</option>
                @foreach ($taskPriorities as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['priority'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label class="visually-hidden" for="due_date">Filtrer par date d’échéance</label>
            <input class="input" id="due_date" name="due_date" type="date" value="{{ $filters['due_date'] ?? '' }}">
        </div>
        <div class="filter-actions">
            <button class="button small" type="submit">Filtrer</button>
            <a class="button secondary small" href="{{ route('tasks.index') }}" aria-label="Effacer les filtres">Effacer</a>
        </div>
        @if (! empty($filters['overdue']))
            <input type="hidden" name="overdue" value="1">
        @endif
    </form>

    <section class="card content-card">
        <div class="section-heading">
            <h2>{{ $tasks->total() }} {{ $tasks->total() > 1 ? 'tâches' : 'tâche' }}</h2>
            <a class="link" href="{{ route('tasks.index', ['overdue' => 1]) }}">Afficher les retards</a>
        </div>
        @if ($tasks->isEmpty())
            <div class="empty-state">
                <span class="empty-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg></span>
                <h3>{{ $filtersApplied ? 'Aucun résultat pour ces filtres.' : 'Aucune tâche pour le moment.' }}</h3>
                <p>{{ $filtersApplied ? 'Essayez d’élargir votre recherche.' : 'Créez votre première tâche pour donner forme à vos priorités.' }}</p>
                @if (! $filtersApplied)
                    <a class="button small" href="{{ route('tasks.create') }}">Créer une tâche</a>
                @else
                    <a class="button secondary small" href="{{ route('tasks.index') }}">Réinitialiser les filtres</a>
                @endif
            </div>
        @else
            <div class="task-list">
                @foreach ($tasks as $task)
                    <div class="task-row">
                        <a class="task-row-main" href="{{ route('tasks.show', $task) }}">
                            <span class="status-dot {{ $task->status }}" aria-hidden="true"></span>
                            <span class="line-clamp">
                                <span class="task-title">{{ $task->title }}</span>
                                <span class="task-description">{{ $task->description ?: 'Aucune description' }}</span>
                            </span>
                        </a>
                        <span class="task-row-meta">
                            <span class="badge status-{{ $task->status }}">{{ $taskStatuses[$task->status] }}</span>
                            <span class="badge priority-{{ $task->priority }}">{{ $taskPriorities[$task->priority] }}</span>
                            <span @class(['overdue-text' => $task->isOverdue()])>{{ $task->due_date?->format('d/m/Y') ?? 'Sans échéance' }}</span>
                            <a class="link" href="{{ route('tasks.edit', $task) }}">Modifier</a>
                        </span>
                    </div>
                @endforeach
            </div>
            @if ($tasks->hasPages())
                <div class="pagination">
                    <span>Affichage de {{ $tasks->firstItem() }} à {{ $tasks->lastItem() }} sur {{ $tasks->total() }}</span>
                    <div class="pagination-links">
                        @if ($tasks->onFirstPage())
                            <span aria-disabled="true">Précédent</span>
                        @else
                            <a href="{{ $tasks->previousPageUrl() }}">Précédent</a>
                        @endif
                        @if ($tasks->hasMorePages())
                            <a href="{{ $tasks->nextPageUrl() }}">Suivant</a>
                        @else
                            <span aria-disabled="true">Suivant</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </section>
</main>
@endsection
