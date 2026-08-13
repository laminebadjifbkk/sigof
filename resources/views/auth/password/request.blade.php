@extends('layouts.app')

@section('title', 'Mot de passe oublié')

@section('content')
    <style>
        /* --- Charte "Youth Linguists Programme" (identique à la vue Connexion) --- */
        .auth-side {
            background: linear-gradient(180deg, #D4F1F9 0%, #E8F8FC 100%);
            position: relative;
            overflow: hidden;
        }

        .auth-side .eyebrow {
            color: var(--green);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-size: 12.5px;
        }

        .auth-side h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 800;
            color: #131416;
            line-height: 1.15;
        }



        .auth-side .ylp-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 22px;
            padding: 8px 14px;
            background: #fff;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 700;
            color: #131416;
            box-shadow: 0 2px 8px rgba(19, 20, 22, 0.08);
        }

        .auth-side .ylp-badge .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--green);
        }

        .auth-side .rings-deco {
            opacity: 0.9;
        }

        .auth-form-col .auth-card {
            border-top: 4px solid var(--green);
        }

        .auth-card .btn-primary {
            background: var(--green);
            border-color: var(--green);
        }

        .auth-card .link-accent {
            color: var(--green);
        }
    </style>

    <div class="auth-wrap">
        <div class="auth-side">
            <div>
                <p class="eyebrow">Espace Junior Linguist Operators</p>
                <h2>On vous aide à retrouver l'accès à votre espace.</h2>
                {{-- <p>Indiquez l'adresse e-mail utilisée lors de votre inscription : nous vous envoyons un lien sécurisé pour choisir un nouveau mot de passe.</p> --}}
                <span class="ylp-badge"><span class="dot"></span> Youth Linguists Programme · Dakar 2026</span>
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
                <span class="eyebrow" style="margin-bottom:14px;">Mot de passe oublié</span>
                <h3>Réinitialiser l'accès</h3>
                <p class="muted">Saisissez l'adresse e-mail associée à votre compte SIGOF.</p>

                @if (session('status'))
                    <div class="field"
                        style="background:#DEF0E7; border:2px solid var(--green); border-radius:12px 12px 12px 4px; padding:12px 14px; font-size:13.5px; color:var(--green);">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Adaptez l'action à votre contrôleur (ex: Auth\PasswordResetController@email) --}}
                <form method="POST" action="{{ route('mot-de-passe.email') }}">
                    @csrf
                    <div class="field">
                        <label for="reset-email">Adresse e-mail</label>
                        <input id="reset-email" name="email" type="email" value="{{ old('email') }}"
                            placeholder="prenom.nom@exemple.sn" required autofocus>
                        @error('email')
                            <small style="color:var(--brick);">{{ $message }}</small>
                        @enderror
                    </div>
                    <button class="btn btn-primary btn-block" type="submit">Envoyer le lien de réinitialisation</button>
                </form>
                <p class="auth-switch">Vous vous souvenez de votre mot de passe ? <a href="{{ route('connexion') }}"
                        class="link-accent">Retour à la connexion</a></p>
            </div>
        </div>
    </div>
@endsection
