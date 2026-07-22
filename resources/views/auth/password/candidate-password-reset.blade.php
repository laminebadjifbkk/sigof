@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
    <div class="auth-wrap">
        <div class="auth-side">
            <div>
                <p class="eyebrow">Espace Junior Linguist Operators</p>
                <h2>Choisissez un nouveau mot de passe sécurisé.</h2>
                <p>Ce lien est personnel et valable pour une durée limitée. Une fois votre mot de passe modifié, vous
                    pourrez vous reconnecter immédiatement à votre espace SIGOF.</p>
            </div>
            <div class="lang-dots" style="margin-top:30px;">
                <span style="background:var(--gold)"></span><span style="background:var(--green)"></span>
                <span style="background:var(--brick)"></span><span style="background:var(--navy)"></span><span
                    style="background:var(--cream)"></span>
            </div>
            <svg class="rings-deco" width="260" height="220" viewBox="0 0 260 220">
                <circle cx="60" cy="60" r="50" fill="none" stroke="var(--gold)" stroke-width="10" />
                <circle cx="140" cy="60" r="50" fill="none" stroke="var(--cream)" stroke-width="10" />
                <circle cx="100" cy="130" r="50" fill="none" stroke="var(--brick)" stroke-width="10" />
            </svg>
        </div>
        <div class="auth-form-col">
            <div class="auth-card">
                <span class="eyebrow" style="margin-bottom:14px;">Nouveau mot de passe</span>
                <h3>Réinitialiser l'accès</h3>
                <p class="muted">Choisissez un mot de passe sécurisé pour votre compte SIGOF.</p>

                @if ($errors->any())
                    <div class="field"
                        style="background:#FBEAEA; border:2px solid var(--brick); border-radius:12px 12px 12px 4px; padding:12px 14px; font-size:13.5px; color:var(--brick);">
                        <strong>Veuillez corriger les erreurs suivantes :</strong>
                        <ul style="margin:6px 0 0 18px; padding:0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Adaptez l'action à votre contrôleur (ex: Auth\NewPasswordController@store) --}}
                <form method="POST" action="{{ route('mot-de-passe.update') }}">
                    @csrf

                    {{-- Token de réinitialisation transmis par email, obligatoire pour valider la demande --}}
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="field">
                        <label for="reset-email">Adresse e-mail</label>
                        <input id="reset-email" name="email" type="email" value="{{ old('email', $email ?? '') }}"
                            placeholder="prenom.nom@exemple.sn" required autofocus>
                        @error('email')
                            <small style="color:var(--brick);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="reset-password">Nouveau mot de passe</label>
                        <input id="reset-password" name="password" type="password" placeholder="8 caractères minimum"
                            required>
                        @error('password')
                            <small style="color:var(--brick);">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="reset-password-confirm">Confirmer le mot de passe</label>
                        <input id="reset-password-confirm" name="password_confirmation" type="password"
                            placeholder="Ressaisissez le mot de passe" required>
                    </div>

                    <button class="btn btn-primary btn-block" type="submit">Réinitialiser le mot de passe</button>
                </form>
                <p class="auth-switch">Vous vous souvenez de votre mot de passe ? <a href="{{ route('connexion') }}"
                        class="link-accent">Retour à la connexion</a></p>
            </div>
        </div>
    </div>
@endsection
