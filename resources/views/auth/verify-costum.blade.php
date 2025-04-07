<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ONFP') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript">
        function callbackThen(response) {
            // read Promise object
            response.json().then(function(data) {
                console.log(data);
                if (data.success && data.score > 0.5) {
                    console.log('valid recpatcha');
                } else {
                    document.getElementById('registerForm').addEventListener('submit', function(event) {
                        event.preventDefault();
                        alert('recpatcha error');
                    });
                }
            });
        }

        function callbackCatch(error) {
            console.error('Error:', error)
        }
    </script>

    {!! htmlScriptTagJsApi([
        'callback_then' => 'callbackThen',
        'callback_catch' => 'callbackCatch',
    ]) !!}
</head>

<body>
    <header>
    </header>

    <body>
        <h3>Salut ,{{ $user->username }}</h3>
        <h3>{{ __('Merci pour votre inscription ! Pour accéder à toutes les fonctionnalités de votre compte, veuillez vérifier votre adresse e-mail en cliquant sur le bouton ci-dessous.') }}
        </h3>
        <a href="{{ $url }}"
            style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; text-decoration: none;">Cliquez
            ici pour vérifiez votre adresse e-mail</a>
    </body>
    <footer>
        <p>&copy; ONFP {{ date('Y') }}</p>
    </footer>
</body>

</html>
