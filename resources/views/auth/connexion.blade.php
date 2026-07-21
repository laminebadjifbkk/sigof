@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
  <div class="auth-wrap">
    <div class="auth-side">
      <div>
        <p class="eyebrow">Espace Junior Linguist Operators</p>
        <h2>Retrouvez votre parcours de formation en un instant.</h2>
        <p>Suivez l'état de votre candidature et votre affectation auprès des délégations Dakar 2026.</p>
      </div>
      <div class="lang-dots" style="margin-top:30px;">
        <span style="background:var(--gold)"></span><span style="background:var(--green)"></span>
        <span style="background:var(--brick)"></span><span style="background:var(--navy)"></span><span style="background:var(--cream)"></span>
      </div>
      <svg class="rings-deco" width="260" height="220" viewBox="0 0 260 220">
        <circle cx="60" cy="60" r="50" fill="none" stroke="var(--gold)" stroke-width="10"/>
        <circle cx="140" cy="60" r="50" fill="none" stroke="var(--cream)" stroke-width="10"/>
        <circle cx="100" cy="130" r="50" fill="none" stroke="var(--brick)" stroke-width="10"/>
      </svg>
    </div>
    <div class="auth-form-col">
      <div class="auth-card">
        <span class="eyebrow" style="margin-bottom:14px;">Connexion</span>
        <h3>Bon retour</h3>
        <p class="muted">Connectez-vous à votre espace SIGOF.</p>

        {{-- Adaptez l'action à votre contrôleur d'authentification (ex: AuthenticatedSessionController) --}}
        <form method="POST" action="{{ route('login.attempt') }}">
          @csrf
          <div class="field">
            <label for="login-email">Adresse e-mail</label>
            <input id="login-email" name="email" type="email" value="{{ old('email') }}" placeholder="prenom.nom@exemple.sn" required autofocus>
            @error('email') <small style="color:var(--brick);">{{ $message }}</small> @enderror
          </div>
          <div class="field">
            <label for="login-pass">Mot de passe</label>
            <input id="login-pass" name="password" type="password" placeholder="••••••••••" required>
            @error('password') <small style="color:var(--brick);">{{ $message }}</small> @enderror
          </div>
          <div class="form-row-between">
            <label class="checkline"><input type="checkbox" name="remember"> Se souvenir de moi</label>
            <a href="{{ route('mot-de-passe.request') }}" class="link-accent">Mot de passe oublié ?</a>
          </div>
          <button class="btn btn-primary btn-block" type="submit">Se connecter</button>
        </form>
        <p class="auth-switch">Pas encore de compte ? <a href="{{ route('inscription') }}" class="link-accent">Créer mon profil Junior Linguist Operators</a></p>
      </div>
    </div>
  </div>
@endsection
