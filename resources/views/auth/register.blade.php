@extends('layouts.app')

@section('title', 'Créer un compte')

@section('content')
<main class="auth-wrap">
    <section class="auth-aside">
        <p class="eyebrow">Un espace, votre méthode</p>
        <h1>Transformez vos intentions en avancées concrètes.</h1>
        <p>Créez votre compte pour structurer vos tâches, suivre vos délais et visualiser vos résultats.</p>
        <p class="aside-note">Votre espace est privé. Chaque compte ne voit que ses propres tâches.</p>
    </section>
    <section class="auth-panel">
        <div class="auth-box">
            <p class="eyebrow">Commencer</p>
            <h2>Créez votre espace.</h2>
            <p class="subtitle">Quelques informations suffisent pour démarrer.</p>
            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <div class="field">
                    <label for="name">Nom</label>
                    <input class="input" id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus>
                    @error('name')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="field">
                    <label for="email">Adresse e-mail</label>
                    <input class="input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                    @error('email')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="field">
                    <label for="password">Mot de passe</label>
                    <input class="input" id="password" name="password" type="password" autocomplete="new-password" required>
                    <p class="subtitle" style="margin-top:6px;font-size:11px">8 caractères minimum.</p>
                    @error('password')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                </div>
                <button class="button" type="submit">Créer mon compte</button>
            </form>
            <p class="auth-foot">Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>
    </section>
</main>
@endsection
