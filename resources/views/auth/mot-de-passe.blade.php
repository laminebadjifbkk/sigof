@extends('layouts.app')

@section('title', 'Nouveau mot de passe')

@section('content')
  <div class="auth-wrap">
    <div class="auth-side">
      <div>
        <p class="eyebrow">Espace Junior Linguist Operators</p>
        <h2>Choisissez un nouveau mot de passe sécurisé.</h2>
        <p>Une fois validé, vous retrouverez immédiatement l'accès à votre espace et au suivi de votre candidature Dakar 2026.</p>
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
        <span class="eyebrow" style="margin-bottom:14px;">Réinitialisation</span>
        <h3>Nouveau mot de passe</h3>
        <p class="muted">Choisissez un mot de passe pour <strong>{{ $email ?? old('email', request('email')) }}</strong>.</p>

        {{-- Adaptez l'action à votre contrôleur (ex: Auth\PasswordResetController@update) --}}
        <form method="POST" action="{{ route('mot-de-passe.update') }}">
          @csrf
          <input type="hidden" name="token" value="{{ $token ?? request('token') }}">

          <div class="field">
            <label for="reset-email">Adresse e-mail</label>
            <input id="reset-email" name="email" type="email" value="{{ old('email', $email ?? request('email')) }}" placeholder="prenom.nom@exemple.sn" required autofocus>
            @error('email') <small style="color:var(--brick);">{{ $message }}</small> @enderror
          </div>
          <div class="field">
            <label for="reset-password">Nouveau mot de passe</label>
            <input id="reset-password" name="password" type="password" placeholder="••••••••••" required>
            @error('password') <small style="color:var(--brick);">{{ $message }}</small> @enderror
          </div>
          <div class="field">
            <label for="reset-password-confirm">Confirmer le mot de passe</label>
            <input id="reset-password-confirm" name="password_confirmation" type="password" placeholder="••••••••••" required>
          </div>
          <button class="btn btn-primary btn-block" type="submit">Réinitialiser le mot de passe</button>
        </form>
        <p class="auth-switch"><a href="{{ route('login') }}" class="link-accent">Retour à la connexion</a></p>
      </div>
    </div>
  </div>
@endsection
