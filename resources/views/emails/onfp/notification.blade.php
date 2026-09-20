<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">

    <div
        style="border-left: 4px solid {{ $priorite === 'urgente' ? '#dc3545' : '#0d6efd' }}; padding-left: 16px; margin-bottom: 20px;">
        <h2 style="margin: 0 0 8px 0;">{{ $titre }}</h2>
    </div>

    <p style="line-height: 1.6;">
        {{ $message }}
    </p>

    @if ($activite)
        <div style="background: #f8f9fa; padding: 12px 16px; border-radius: 6px; margin-top: 16px;">
            <strong>Activité :</strong> {{ $activite->reference }} - {{ $activite->titre }}
        </div>

        <p style="margin-top: 20px;">
            <a href="{{ route('onfp.activites.show', $activite) }}"
                style="background: #0d6efd; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none;">
                Voir l'activité
            </a>
        </p>
    @endif

    <hr style="margin-top: 30px; border: none; border-top: 1px solid #eee;">
    <p style="font-size: 12px; color: #999;">
        {{-- Notification automatique — SIGOF / ONFP --}}

        @include('emails.footer_mail')
    </p>

</body>

</html>
