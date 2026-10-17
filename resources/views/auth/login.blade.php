@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<main class="auth-wrap">
    <section class="auth-aside">
        <p class="eyebrow">Votre espace de travail</p>
        <h1>Reprenez le fil, sans perdre de vue l’essentiel.</h1>
        <p>Retrouvez vos priorités, vos échéances et les progrès accomplis, au même endroit.</p>
        <p class="aside-note">Clair, personnel et prêt à suivre votre rythme.</p>
    </section>
    <section class="auth-panel">
        <div class="auth-box">
            <p class="eyebrow">TaskFlow</p>
            <h2>Heureux de vous revoir.</h2>
            <p class="subtitle">Connectez-vous pour retrouver votre espace.</p>
            @if ($errors->any())
                <div class="alert error-alert" role="alert">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="field">
                    <label for="email">Adresse e-mail</label>
                    <input class="input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    @error('email')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="field">
                    <label for="password">Mot de passe</label>
                    <input class="input" id="password" name="password" type="password" autocomplete="current-password" required>
                    @error('password')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="field">
                    <label class="checkbox"><input type="checkbox" name="remember" value="1"> Garder ma session ouverte</label>
                </div>
                <button class="button" type="submit">Se connecter</button>
            </form>
            <p class="auth-foot">Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a></p>
        </div>
    </section>
</main>
@endsection
