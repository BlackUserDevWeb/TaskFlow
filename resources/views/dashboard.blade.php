@extends('layouts.app')

@section('title', 'Vue d’ensemble')

@section('content')
<main class="main">
    <div class="page-heading">
        <div>
            <p class="eyebrow">Votre espace de travail</p>
            <h1>Bonjour, {{ auth()->user()->name }}.</h1>
            <p class="subtitle">Voici où en sont vos projets aujourd’hui.</p>
        </div>
        <a class="button" href="{{ route('tasks.create') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Nouvelle tâche
        </a>
    </div>

    <div class="grid stats-grid">
        <a class="card stat-card" href="{{ route('tasks.index') }}">
            <span class="stat-label">Toutes les tâches <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg></span></span>
            <strong class="stat-value">{{ $total }}</strong><span class="stat-foot">Dans votre espace</span>
        </a>
        <a class="card stat-card" href="{{ route('tasks.index', ['status' => 'todo']) }}">
            <span class="stat-label">À faire <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/></svg></span></span>
            <strong class="stat-value">{{ $todo }}</strong><span class="stat-foot">En attente de démarrage</span>
        </a>
        <a class="card stat-card" href="{{ route('tasks.index', ['status' => 'in_progress']) }}">
            <span class="stat-label">En cours <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 3v9l6 3"/></svg></span></span>
            <strong class="stat-value">{{ $inProgress }}</strong><span class="stat-foot">Actuellement actives</span>
        </a>
        <a class="card stat-card" href="{{ route('tasks.index', ['status' => 'completed']) }}">
            <span class="stat-label">Terminées <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"/></svg></span></span>
            <strong class="stat-value">{{ $completed }}</strong><span class="stat-foot">Accomplies avec succès</span>
        </a>
        <a class="card stat-card" href="{{ route('tasks.index', ['overdue' => 1]) }}">
            <span class="stat-label">En retard <span class="stat-icon" style="color:var(--red);background:#fbe9e7"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span></span>
            <strong class="stat-value">{{ $overdue }}</strong><span class="stat-foot">À échéance dépassée</span>
        </a>
        <div class="card stat-card">
            <span class="stat-label">Taux d’achèvement <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5M4 19h16M8 15l3-4 3 2 5-7"/></svg></span></span>
            <strong class="stat-value">{{ $completionRate }}%</strong><span class="stat-foot">Tâches terminées sur le total</span>
        </div>
    </div>

    <section class="card content-card">
        <div class="section-heading">
            <div><h2>Dernières tâches</h2><p class="subtitle" style="margin-top:5px;font-size:12px">Votre activité la plus récente</p></div>
            <a class="link" href="{{ route('tasks.index') }}">Voir toutes les tâches <span aria-hidden="true">→</span></a>
        </div>
        @if ($recentTasks->isEmpty())
            <div class="empty-state">
                <span class="empty-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></span>
                <h3>Votre espace est prêt.</h3>
                <p>Ajoutez une première tâche pour commencer à suivre votre progression.</p>
                <a class="button small" href="{{ route('tasks.create') }}">Créer une tâche</a>
            </div>
        @else
            <div class="task-list">
                @foreach ($recentTasks as $task)
                    <a class="task-row" href="{{ route('tasks.show', $task) }}">
                        <span class="task-row-main">
                            <span class="status-dot {{ $task->status }}" aria-hidden="true"></span>
                            <span class="line-clamp">
                                <span class="task-title">{{ $task->title }}</span>
                                <span class="task-description">{{ $task->description ?: 'Aucune description' }}</span>
                            </span>
                        </span>
                        <span class="task-row-meta">
                            <span class="badge status-{{ $task->status }}">{{ $taskStatuses[$task->status] }}</span>
                            <span @class(['overdue-text' => $task->isOverdue()])>{{ $task->due_date?->format('d/m/Y') ?? 'Sans échéance' }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</main>
@endsection
