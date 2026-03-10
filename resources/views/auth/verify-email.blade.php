@extends('layout.user-layout')

@section('title', 'ONFP | Vérification Email')

@section('space-work')

    <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center text-center">

        <i class="bi bi-envelope-check text-primary" style="font-size: 4rem;"></i>
        <h2 class="mt-3 text-secondary">Merci de vous être inscrit !</h2>

        <p class="mt-2 text-muted" style="max-width: 600px;">
            Avant de commencer, veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous
            envoyer.<br>
            Si vous n'avez pas reçu l'e-mail, nous pouvons vous en envoyer un nouveau.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success mt-3" role="alert">
                Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-primary shadow-sm">
                Renvoyer l'e-mail de vérification
            </button>
        </form>

        <p class="mt-3">
            <a href="{{ route('logout') }}" class="text-decoration-none text-danger"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Déconnexion
            </a>
        </p>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

    </section>

@endsection
