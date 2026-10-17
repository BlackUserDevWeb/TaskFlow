@extends('layouts.app')

@section('title', 'Nouvelle tâche')

@section('content')
<main class="main">
    <div class="page-heading">
        <div><p class="eyebrow">Organisation</p><h1>Nouvelle tâche</h1><p class="subtitle">Définissez clairement la prochaine étape.</p></div>
    </div>
    @include('tasks.form', ['action' => route('tasks.store'), 'method' => 'POST', 'task' => null])
</main>
@endsection
