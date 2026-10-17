<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TaskFlow') — TaskFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="shell">
    <header class="topbar">
        <a class="brand" href="{{ auth()->check() ? route('dashboard') : route('home') }}">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"/></svg>
            </span>
            TaskFlow
        </a>
        @auth
            <div class="top-actions">
                <a class="nav-link" href="{{ route('dashboard') }}">Vue d’ensemble</a>
                <a class="nav-link" href="{{ route('tasks.index') }}">Mes tâches</a>
                <div class="user-menu">
                    <span class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <span>{{ auth()->user()->name }}</span>
                </div>
                <form class="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="button secondary small" type="submit">Déconnexion</button>
                </form>
            </div>
        @else
            <div class="top-actions">
                <span class="auth-top-register">Nouveau sur TaskFlow ? <a href="{{ route('register') }}">Créer un compte</a></span>
            </div>
        @endauth
    </header>

    @if (session('success'))
        <div class="main" style="padding-bottom:0">
            <div class="alert" role="status">{{ session('success') }}</div>
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
