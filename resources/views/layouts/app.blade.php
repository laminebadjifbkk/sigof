{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript">
    function callbackThen(response) {
        // read Promise object
        response.json().then(function(data) {
            console.log(data);
            if(data.success && data.score > 0.5) {
                console.log('valid recpatcha');
            } else {
                document.getElementById('registerForm').addEventListener('submit', function(event) {
                    event.preventDefault();
                    alert('recpatcha error');
                });
            }
        });
    }
    
    function callbackCatch(error){
        console.error('Error:', error)
    }
    </script>
        
    {!! htmlScriptTagJsApi([
        'callback_then' => 'callbackThen',
        'callback_catch' => 'callbackCatch',
    ]) !!}
    <title>{{ config('app.name', 'ONFP') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @include('sweetalert::alert')
</body>

</html>
 --}}

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIGOF') · Dakar 2026 - Programme des traducteurs officiels</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap"
        rel="stylesheet">

    {{-- En Blade "classique" (asset()). Si vous utilisez Vite, remplacez par :
       @vite(['resources/css/sigof.css', 'resources/js/sigof.js']) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/sigof.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    @stack('styles')
</head>

<body>

    @include('partials.svg-defs')
    @include('partials.navbar')

    <main class="page-enter">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('assets/js/sigof.js') }}"></script>

    <script>
        document.querySelectorAll('.upload-box input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files.length ? this.files[0].name : 'Aucun fichier sélectionné';
                this.closest('.upload-box').querySelector('.file-name').textContent = fileName;
            });
        });
    </script>
    <script src="{{ asset('assets/js/sigof.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>

</html>
