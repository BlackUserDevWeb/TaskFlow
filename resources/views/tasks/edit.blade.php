@extends('layouts.app')

@section('title', 'Modifier la tâche')

@section('content')
<main class="main">
    <div class="page-heading">
        <div><p class="eyebrow">Organisation</p><h1>Modifier la tâche</h1><p class="subtitle">Ajustez les détails au fil de votre progression.</p></div>
    </div>
    @include('tasks.form', ['action' => route('tasks.update', $task), 'method' => 'PUT', 'task' => $task])
</main>
@endsection
