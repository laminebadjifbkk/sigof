<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard') · SIGOF Admin</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('assets/css/sigof.css') }}">
  @stack('styles')
</head>
<body>

  @include('partials.svg-defs')

  {{-- Le dashboard n'affiche pas la navbar/footer publics : sidebar + topbar suffisent --}}
  <div class="dash-shell">
    @include('partials.sidebar')

    <main class="dash-main page-enter">
      @yield('content')
    </main>
  </div>

  <script src="{{ asset('assets/js/sigof.js') }}"></script>
  @stack('scripts')
</body>
</html>
