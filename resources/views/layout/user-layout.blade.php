<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-3DG0GRFHQ4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-3DG0GRFHQ4');
    </script>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'ONFP | HOME')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-NGJKZ3DD');
    </script>
    <!-- End Google Tag Manager -->
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
    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="favicon-onfp">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    {{-- <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet"> --}}
    {{-- <link href="https://cdn.datatables.net/v/bs5/dt-2.0.2/datatables.min.css" rel="stylesheet"> --}}

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">

    {{-- Pour sweetAlert --}}
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>


    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">


    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Jan 29 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
    <style>
        .nouvelle {
            background-color: #ff9966;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Nouvelle {
            background-color: #ff9966;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .nouveau {
            background-color: #36e0e0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Nouveau {
            background-color: #36e0e0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .rejeter {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Rejetée {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .rejeté {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .annuler {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Aucun {
            background-color: white;
            color: #DC3545;
            padding: 4px 8px;
            text-align: center;
            border-radius: 25% 10%;
            /* border-radius: 5px; */
        }

        .Annulée {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .retirer {
            background-color: #ffcc00;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .corriger {
            background-color: #ffcc00;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .attente {
            background-color: #6C757D;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Attente {
            background-color: #6C757D;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .cours {
            background-color: #28a745;
            /* vert vif classique pour succès */
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .accepter {
            background-color: #0D6EFD;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .réserve {
            background-color: #ffcc00;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .new {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .retiré {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Imputée {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Retirée {
            background-color: #ffcc00;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Retiré {
            background-color: #ffcc00;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .renew {
            background-color: #ffcc00;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Renouvellement {
            background-color: #ffcc00;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Absent {
            background-color: #ffcc00;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .expirer {
            background-color: #ffcc00;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Expiré {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .expiré {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Absente {
            background-color: #ffcc00;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .démarrer {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .cours {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .ouvert {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .disponible {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .terminer {
            background-color: #198754;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .confirmer {
            background-color: #198754;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Terminée {
            background-color: #198754;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .formés {
            background-color: #198754;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .formés {
            background-color: #198754;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .formé {
            background-color: #198754;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Présent {
            background-color: #198754;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Présente {
            background-color: #198754;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .former {
            background-color: #198754;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .commission {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Conforme {
            background-color: #198754;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .agréé {
            background-color: #198754;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .programmer {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Oui {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .retenue {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .retenu {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Retenu {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Retenue {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Sélectionné {
            background-color: #0DCAF0;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        /*  .sélectionnée {
            background-color: #0DCAF0;
            color: white;
            padding: 4px 8px;
            text-align: center;
            border-radius: 25% 10%;
        } */

        .non {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Abandon {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .fermer {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Non {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .incomplète {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .invalide {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .fin {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .décliner {
            background-color: #DC3545;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .valide {
            background-color: #46a579;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Validée {
            background-color: #46a579;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        .Validé {
            background-color: #46a579;
            /* couleur cyan Bootstrap (info) */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            /* plus doux et moderne */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* légère ombre */
            display: inline-block;
        }

        a {
            text-decoration: none;
        }

        #productList {
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ddd;
            display: none;
        }

        #productList ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #productList li {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
        }

        #productList li:hover {
            background: #f8f9fa;
        }

        .vertical-align-middle {
            vertical-align: middle;
            /* Aligne le contenu au milieu */
        }

        .modal-header-sm {
            padding: 10px 20px;
            /* Réduit l'espace autour du contenu */
            font-size: 1rem;
            /* Ajuste la taille du texte */
            line-height: 1.2;
            /* Espace vertical réduit pour une taille plus petite */
        }

        /* Effet de survol sur le bouton */
        .btn:hover {
            transform: scale(1.05);
            /* Agrandit légèrement le bouton */
        }

        /* Pour les dropdowns, on peut ajouter un fond pour les actions */
        .dropdown-menu {
            background-color: #f8f9fa;
            /* Couleur de fond claire */
            border-radius: 0.5rem;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Ajouter un effet de survol pour les éléments du menu */
        .dropdown-item:hover {
            background-color: #e9ecef;
        }
    </style>

</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            @php
                $user = auth()->user();
            @endphp

            @if ($user->hasAnyRole(['super-admin', 'admin', 'DIOF', 'DEC', 'Ingenieur', 'Employe']))
                <a href="{{ url('/home') }}" class="logo d-flex align-items-center">
                @else
                    <a href="{{ url('/profil') }}" class="logo d-flex align-items-center">
            @endif
            <img src="{{ asset('assets/img/logo_sigle.png') }}" alt="Logo SIGOF">
            <span class="d-none d-lg-block">SIGOF</span>
            {{-- Système d'information et de gestion des opérations de formation --}}
            </a>

            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        {{--   <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div> --}}
        <!-- End Search Bar -->

        {{-- Mode sombre --}}
        {{-- <div class="form-check form-switch mx-4">
            <input class="form-check-input p-2" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked
                onclick="myFunction()" />
        </div> --}}
        @include('layout.page-navbar')
        <!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('layout.page-sidebar')
    <!-- End Sidebar-->

    <main id="main" class="main">

        @yield('space-work')

    </main><!-- End #main -->
    @include('sweetalert::alert')

    <!-- ======= Footer ======= -->
    @include('layout.page-footer')
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>


    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    <script>
        setTimeout(function() {
            $('.alert-success').remove();
        }, 60000);
    </script>
    <script>
        setTimeout(function() {
            $('.alert-danger').remove();
        }, 60000);
    </script>
    <script>
        function myFunction() {
            var element = document.body;
            element.dataset.bsTheme =
                element.dataset.bsTheme == "light" ? "dark" : "light";
        }

        function stepFunction(event) {
            debugger;
            var element = document.getElementsByClassName(("html")[0].innerHTML);
            for (var i = 0; i < element.length; i++) {
                if (element[i] !== event.target.ariaControls) {
                    element[i].classList.remove("show");
                }
            }
        }
    </script>

    <script>
        $(document).ready(function() {

            $('#module_name').keyup(function() {
                var query = $(this).val();
                if (query != '') {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('autocomplete.fetch') }}",
                        method: "POST",
                        data: {
                            query: query,
                            _token: _token
                        },
                        success: function(data) {
                            $('#countryList').fadeIn();
                            $('#countryList').html(data);
                        }
                    });
                }
            });

            $(document).on('click', 'li', function() {
                $('#module_name').val($(this).text());
                $('#countryList').fadeOut();
            });

        });
    </script>
    <script>
        $(document).ready(function() {

            $('#module_operateur').keyup(function() {
                var query = $(this).val();
                if (query != '') {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('autocomplete.fetchModuleOperateur') }}",
                        method: "POST",
                        data: {
                            query: query,
                            _token: _token
                        },
                        success: function(data) {
                            $('#moduleList').fadeIn();
                            $('#moduleList').html(data);
                        }
                    });
                }
            });

            $(document).on('click', 'li', function() {
                $('#module_operateur').val($(this).text());
                $('#moduleList').fadeOut();
            });

        });
    </script>

    <script>
        $(document).ready(function() {

            $('#conventionid').keyup(function() {
                var query = $(this).val();
                if (query != '') {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('conventions.fetch') }}",
                        method: "POST",
                        data: {
                            query: query,
                            _token: _token
                        },
                        success: function(data) {
                            $('#conventionList').fadeIn();
                            $('#conventionList').html(data);
                        }
                    });
                }
            });

            $(document).on('click', 'li', function() {
                $('#conventionid').val($(this).text());
                $('#conventionList').fadeOut();
            });

        });
    </script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>

    {{-- Pour sweetAlert --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr de vouloir supprimer cet enregistrement ?`,
                    text: "Si vous supprimez ceci, il disparaîtra pour toujours.",
                    icon: "warning",
                    buttons: ["Annuler", "Oui, Supprimer !"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.show_confirmDeleteImage').click(function(event) {
                event.preventDefault();
                var url = $(this).data('url'); // Récupère l'URL de suppression

                swal({
                    title: "Êtes-vous sûr de vouloir supprimer ?",
                    text: "Si vous supprimez, l'image disparaîtra pour toujours.",
                    icon: "warning",
                    buttons: ["Annuler", "Oui, Supprimer !"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: "DELETE"
                            },
                            success: function(response) {
                                swal("Succès", "Votre image a été supprimée.",
                                        "success")
                                    .then(() => location.reload());
                            },
                            error: function(response) {
                                swal("Erreur", "Une erreur s'est produite.", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>


    <script type="text/javascript">
        $('.show_confirm_disconnect').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr de vouloir vous déconnecter ?`,
                    text: "Vous pouvez cliquer sur ok pour confirmer ou cliquer sur cancel pour annuler.",
                    icon: "warning",
                    buttons: ["Annuler", "Oui, déconnecter !"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>

    <script type="text/javascript">
        $('.show_confirm_nettoyer').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr de vouloir nettoyer ?`,
                    text: "Vous pouvez cliquer sur ok pour confirmer ou cliquer sur cancel pour annuler.",
                    icon: "warning",
                    buttons: ["Annuler", "Oui, nettoyer !"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Délégation d'événement pour s'assurer que tous les éléments dynamiques sont pris en charge
            $(document).on('click', '.show_confirm_restaurer', function(event) {
                event.preventDefault(); // Empêche la soumission automatique

                const form = $(this).closest("form");

                swal({
                    title: "Êtes-vous sûr de vouloir restaurer ?",
                    text: "Vous pouvez cliquer sur OK pour confirmer ou sur Annuler pour annuler.",
                    icon: "warning", // "warning" est plus adapté pour une action risquée
                    buttons: {
                        cancel: "Annuler",
                        confirm: {
                            text: "Oui, restaurer !",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    },
                    dangerMode: true
                }).then((willRestore) => {
                    if (willRestore) {
                        form.submit(); // Soumet le formulaire si confirmé
                    }
                });
            });
        });
    </script>

    <script>
        $("#checkAll").click(function() {
            $(".form-check-input").prop('checked', $(this).prop('checked'));
        });
    </script>
    <script type="text/javascript">
        $('.show_confirm_valider').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr ?`,
                    text: "Si oui, cliquer sur ok.",
                    icon: "success",
                    buttons: ["Annuler", "Oui, valider !"],
                })
                .then((willValide) => {
                    if (willValide) {
                        form.submit();
                    }
                });
        });
    </script>
    <script type="text/javascript">
        $('.show_confirm_ouvrir').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr ?`,
                    text: "Si oui, cliquer sur ok.",
                    icon: "success",
                    buttons: ["Annuler", "Oui, Ouvrir !"],
                })
                .then((willValide) => {
                    if (willValide) {
                        form.submit();
                    }
                });
        });
    </script>
    <script type="text/javascript">
        $('.show_confirm_terminer').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr ?`,
                    text: "Si oui, cliquer sur ok.",
                    icon: "success",
                    buttons: ["Annuler", "Oui, Terminer !"],
                })
                .then((willValide) => {
                    if (willValide) {
                        form.submit();
                    }
                });
        });
    </script>
    <script type="text/javascript">
        $('.show_confirm_fermer').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr ?`,
                    text: "Si oui, cliquer sur ok.",
                    icon: "success",
                    buttons: ["Annuler", "Oui, Fermer !"],
                    dangerMode: true,
                })
                .then((willValide) => {
                    if (willValide) {
                        form.submit();
                    }
                });
        });
    </script>
    <script type="text/javascript">
        $('.show_confirm_rejeter').on('click', function(event) {
            event.preventDefault();
            const form = $(this).closest("form");

            swal({
                title: "Êtes-vous sûr de vouloir rejeter ce fichier ?",
                text: "Cliquez sur 'Oui, rejeter' pour confirmer.",
                icon: "warning",
                buttons: ["Annuler", "Oui, rejeter"],
                dangerMode: true,
            }).then((willReject) => {
                if (willReject) {
                    form.submit();
                }
            });
        });
    </script>
    <script type="text/javascript">
        $('.show_confirm_suivi').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr de bien vouloir suivre ce bénéficiaire ?`,
                    text: "Si oui, cliquer sur ok.",
                    icon: "success",
                    buttons: ["Annuler", "Oui, suivre !"],
                })
                .then((willValide) => {
                    if (willValide) {
                        form.submit();
                    }
                });
        });
    </script>
    <script type="text/javascript">
        $('.show_confirm_employes').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr de vouloir ajouter à la base de données des employés ?`,
                    text: "Si oui, cliquer sur ok.",
                    icon: "success",
                    buttons: ["Annuler", "Oui, ajouter !"],
                })
                .then((willValide) => {
                    if (willValide) {
                        form.submit();
                    }
                });
        });
    </script>
    <script type="text/javascript">
        $('.show_confirm_retirer').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr de vouloir retirer ?`,
                    text: "Si oui, cliquer sur ok.",
                    icon: "success",
                    buttons: ["Annuler", "Oui, retirer !"],
                })
                .then((willValide) => {
                    if (willValide) {
                        form.submit();
                    }
                });
        });
    </script>
    <script type="text/javascript">
        $('.show_confirm_annuler').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr de vouloir rejeter ?`,
                    text: "Si oui, cliquer sur ok.",
                    icon: "error",
                    buttons: ["Annuler", "Oui, rejeter !"],
                    dangerMode: true,
                })
                .then((willValide) => {
                    if (willValide) {
                        form.submit();
                    }
                });
        });
    </script>
    <script type="text/javascript">
        $('.une_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr ?`,
                    text: "Si oui, cliquez sur ok.",
                    icon: "success",
                    buttons: ["Annuler", "Oui, mettre !"],
                    dangerMode: false,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>
    <script type="text/javascript">
        $('.une_confirmer').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Êtes-vous sûr ?`,
                    text: "Si oui, cliquez sur ok.",
                    icon: "success",
                    buttons: ["Annuler", "Oui, enlever !"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#select-field-civilite').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>
    <script>
        $('#select-field-niveau_qualification').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-registre-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-onfp').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-statut-attestations').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-feuille_presences').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#PresenceModal'),
        });
    </script>

    <script>
        $('#select-field-registre-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-civilite-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-departement-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-types_formation_update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-categorie-emp').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-niveau_qualification_update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-statut-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-categorie-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-arrete_creation').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#categorie').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#rccm').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#civilite').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-projet').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-programme').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-choixoperateurs').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-registre').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-familiale').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-statut').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-statut-col').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-professionnelle').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#formationSelect').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddlettreEvaluationModal'),
        });
    </script>

    <script>
        $('#evaluateurSelect').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddlettreEvaluationModal'),
        });
    </script>

    <script>
        $('#onfpevaluateurSelect').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddlettreEvaluationModal'),
        });
    </script>

    <script>
        $('#formationSelected').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#evaluateurSelected').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#onfpevaluateurSelected').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-niveau_etude').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-diplome_academique').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-projet_poste_formation').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-diplome_professionnel').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-region').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddFormationModal'),
        });
    </script>

    <script>
        $('#select-field-convention').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddRefModal'),
        });
    </script>

    <script>
        $('#select-field-directionn').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#addEmploye'),
        });
    </script>

    <script>
        $('#select-field-field-fonction').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#addIngenieur'),
        });
    </script>
    <script>
        $('#select-field-titre').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-convention-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-statut-rapport').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#generate_rapport'),
        });
    </script>

    <script>
        $('#select-field-statut-report').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#generate_rapport_module_region'),
        });
    </script>


    <script>
        $('#select-field-legende-add').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddFileModal'),
        });
    </script>

    <script>
        $('#select-field-user-add').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddFileModal'),
        });
    </script>

    <script>
        $('#select-field-projet-module-rapport').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#generate_rapport_module_region'),
        });
    </script>

    <script>
        $('#select-field-role-user').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#generate_rapport_role'),
        });
    </script>

    <script>
        $('#civiliteMembre').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-statut-rappor').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#generate_rapport_module'),
        });
    </script>

    <script>
        $('#select-field-statut-rappo').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#generate_rapport_module_region'),
        });
    </script>

    <script>
        $('#select-field-statut-collective').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#generate_rapport'),
        });
    </script>

    <script>
        $('#select-field-chef-antenne').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddAntenneModal'),
        });
    </script>

    <script>
        $('#multiple-select-field-region-antenne').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddAntenneModal'),
        });
    </script>

    <script>
        $('#select-field-visite_conformite').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#addobservations'),
        });
    </script>

    <script>
        $('#select-field-chef-antenne-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#multiple-select-field-region-antenne-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field_type_demande').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddoperateurModal'),
        });
    </script>

    <script>
        $('#select-field-statutop').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field-departementop').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field-registreop').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-fieldop').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field-arrete_creation-op').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field-formulaire_signeop').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field-quitusfiscal').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field-cvsigne').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field-quitusfiscalup').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-cvsigneup').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-civiliteop').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field_categorie_op').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddoperateurModal'),
        });
    </script>

    <script>
        $('#select-field-statut_op').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddoperateurModal'),
        });
    </script>

    <script>
        $('#select-field-registre_op').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddoperateurModal'),
        });
    </script>

    <script>
        $('#select-field-departement_op').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddoperateurModal'),
        });
    </script>

    <script>
        $('#select-field-operateur-localite').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddlocaliteModal'),
        });
    </script>
    <script>
        $('#select-field-etat-add').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddRefModal'),
        });
    </script>

    <script>
        $('#select-field-type-add').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddRefModal'),
        });
    </script>

    <script>
        $('#select-field-region-module-rapport').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#generate_rapport_module_region'),
        });
    </script>

    <script>
        $('#select-field-region-rapport').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#generate_rapport'),
        });
    </script>

    <script>
        $('#select-field-etat-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>
    <script>
        $('#select-field-type-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-types_formation').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddFormationModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-niveau_qualification').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddFormationModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-type_certification').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddFormationModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-type_certification_update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-departement-modal-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-types_formation-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-niveau_qualification-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-departement').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-departement-col').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddCollectiveModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-statut-col').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddCollectiveModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-departement-ind').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelleModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-projetmodule-ind').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelleModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-departement-col').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddCollectiveModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-module-ind').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelleModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-module-col').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddCollectiveModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-module-col').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddCollectiveModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-niveau_etude-ind').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelleModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-niveau_etude-col').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddCollectiveModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-diplome_academique-ind').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelleModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-diplome_academique-col').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddCollectiveModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-diplome_professionnel-ind').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelleModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-diplome_professionnel-col').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddCollectiveModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-projet_poste_formation-ind').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelleModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-projet_poste_formation-col').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddCollectiveModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-civilite-col').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddCollectiveModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#niveau_qualification').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            dropdownParent: $('#AddmoduleModal'),
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-civilite-indiv').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-module-indiv').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-domaine-module').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-domaine-indiv').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-secteur-indiv').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-departement-indiv').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-niveau_etude-indiv').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-diplome_academique-indiv').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-diplome_professionnel-indiv').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-projet_poste_formation-indiv').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-familiale-indiv').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-module-demande').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-civilite-demande').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-file').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-departement-demande').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-niveau_etude-demande').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-diplome_academique-demande').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-diplome_professionnel-demande').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-projet_poste_formation-demande').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-professionnelle-indiv').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            /*dropdownParent: $('#AddIndividuelModal'),*/
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-departement-modal').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddFormationModal'),
        });
    </script>

    <script>
        $('#select-field-typelocalite').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddProjetModal'),
        });
    </script>

    <script>
        $('#select-field-formation-region-rapport').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#generate_rapportFormation'),
        });
    </script>

    <script>
        $('#select-field-formation-annee-rapport').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#generate_rapportFormation'),
        });
    </script>
    <script>
        $('#select-field-projetprogramme').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddProjetModal'),
        });
    </script>

    <script>
        $('#select-field-employe').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    {{-- type de dirction/service/cellule --}}
    <script>
        $('#select-field-type').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>


    <script>
        $('#select-field-categorie').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddFormationModal'),
        });
    </script>

    <script>
        $('#select-field-categories').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-categorie-pro').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field-arrete_creation-pro').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field-demande_signe').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field-formulaire_signe').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddOperateurModal'),
        });
    </script>

    <script>
        $('#select-field-demande_signe_update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-formulaire_signe_update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#statut-operateur').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-arrete_creation-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-operateur').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddFormationModal'),
        });
    </script>

    <script>
        $('#select-field-fonction').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-category').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-arrondissement').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#multiple-select-field').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-module').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-module-modal').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
            dropdownParent: $('#AddFormationModal'),
        });
    </script>

    <script>
        $('#select-field-civilite-update').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

    <script>
        $(function() {
            $("#datepicker").kendoDatePicker().datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true,
                yearRange: "-40:+0",
                allowMultidate: true,
                monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                    'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ],
                monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août',
                    'Sept.', 'Oct.', 'Nov.', 'Déc.'
                ],
                dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
                dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                weekHeader: 'Sem.',
            });
            $(".datepicker").datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true,
                yearRange: "-40:+0",
                allowMultidate: true,
                monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                    'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ],
                monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août',
                    'Sept.', 'Oct.', 'Nov.', 'Déc.'
                ],
                dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
                dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                weekHeader: 'Sem.',
            });
        });
    </script>

    <!-- Script pour ajouter un masque de saisie -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var dateInput = document.getElementById("datepicker");

            dateInput.addEventListener("input", function(e) {
                var v = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres
                if (v.length >= 2) v = v.slice(0, 2) + "/" + v.slice(2);
                if (v.length >= 5) v = v.slice(0, 5) + "/" + v.slice(5, 9);
                e.target.value = v.slice(0, 10); // Limite à 10 caractères
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#datepicker").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                yearRange: "1900:2100"
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#datepicker1").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                yearRange: "1900:2100"
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#datepicker2").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                yearRange: "1900:2100"
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var telephoneInput = document.getElementById("telephone");

            telephoneInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres

                // Appliquer le format XX:XXX:XX:XX
                if (value.length > 2) value = value.slice(0, 2) + " " + value.slice(2);
                if (value.length > 6) value = value.slice(0, 6) + " " + value.slice(6);
                if (value.length > 9) value = value.slice(0, 9) + " " + value.slice(9, 11);

                e.target.value = value.slice(0, 12); // Limite à 12 caractères (avec les ":")
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var telephoneInput = document.getElementById("contact");

            telephoneInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres

                // Appliquer le format XX:XXX:XX:XX
                if (value.length > 2) value = value.slice(0, 2) + " " + value.slice(2);
                if (value.length > 6) value = value.slice(0, 6) + " " + value.slice(6);
                if (value.length > 9) value = value.slice(0, 9) + " " + value.slice(9, 11);

                e.target.value = value.slice(0, 12); // Limite à 12 caractères (avec les ":")
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ajouter un événement pour tous les modals
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('shown.bs.modal', function(e) {
                    // Cibler l'élément spécifique dans ce modal
                    var telephoneInput = modal.querySelector('[name="telephone_secondaire"]');

                    if (telephoneInput) {
                        telephoneInput.addEventListener("input", function(e) {
                            var value = e.target.value.replace(/\D/g,
                                ""); // Supprime tout sauf les chiffres

                            // Appliquer le format XX:XXX:XX:XX
                            if (value.length > 2) value = value.slice(0, 2) + " " + value
                                .slice(2);
                            if (value.length > 6) value = value.slice(0, 6) + " " + value
                                .slice(6);
                            if (value.length > 9) value = value.slice(0, 9) + " " + value
                                .slice(9, 11);

                            // Limite à 12 caractères (y compris les ":")
                            e.target.value = value.slice(0,
                                12
                            ); // On applique le formatage et on limite à 12 caractères
                        });
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var cinInput = document.getElementById("cin");

            cinInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/[^A-Za-z0-9]/g,
                    ""); // Supprimer tout sauf lettres et chiffres

                // Convertir toutes les lettres en majuscule si elles existent
                value = value.toUpperCase();

                // Appliquer le format: 1 chiffre - espace - 3 chiffres - espace - 4 chiffres - espace - 5 ou 6 chiffres
                if (value.length > 1) value = value.slice(0, 1) + " " + value.slice(
                    1); // 1er chiffre + espace
                if (value.length > 5) value = value.slice(0, 5) + " " + value.slice(
                    5); // 3 chiffres + espace
                if (value.length > 10) value = value.slice(0, 10) + " " + value.slice(
                    10); // 4 chiffres + espace

                // Limiter à 16 ou 17 caractères (espaces inclus)
                e.target.value = value.slice(0, 17); // 16 ou 17 caractères au total
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var cinInput = document.getElementById("cin2");

            cinInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/[^A-Za-z0-9]/g,
                    ""); // Supprimer tout sauf lettres et chiffres

                // Convertir toutes les lettres en majuscule si elles existent
                value = value.toUpperCase();

                // Appliquer le format: 1 chiffre - espace - 3 chiffres - espace - 4 chiffres - espace - 5 ou 6 chiffres
                if (value.length > 1) value = value.slice(0, 1) + " " + value.slice(
                    1); // 1er chiffre + espace
                if (value.length > 5) value = value.slice(0, 5) + " " + value.slice(
                    5); // 3 chiffres + espace
                if (value.length > 10) value = value.slice(0, 10) + " " + value.slice(
                    10); // 4 chiffres + espace

                // Limiter à 16 ou 17 caractères (espaces inclus)
                e.target.value = value.slice(0, 17); // 16 ou 17 caractères au total
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var telephoneInput = document.getElementById("telephone_responsable");

            telephoneInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres

                // Appliquer le format XX:XXX:XX:XX
                if (value.length > 2) value = value.slice(0, 2) + " " + value.slice(2);
                if (value.length > 6) value = value.slice(0, 6) + " " + value.slice(6);
                if (value.length > 9) value = value.slice(0, 9) + " " + value.slice(9, 11);

                e.target.value = value.slice(0, 12); // Limite à 12 caractères (avec les ":")
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var telephoneInput = document.getElementById("fixe");

            telephoneInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres

                // Appliquer le format XX:XXX:XX:XX
                if (value.length > 2) value = value.slice(0, 2) + " " + value.slice(2);
                if (value.length > 6) value = value.slice(0, 6) + " " + value.slice(6);
                if (value.length > 9) value = value.slice(0, 9) + " " + value.slice(9, 11);

                e.target.value = value.slice(0, 12); // Limite à 12 caractères (avec les ":")
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var telephoneInput = document.getElementById("telephone_parent");

            telephoneInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres

                // Appliquer le format XX:XXX:XX:XX
                if (value.length > 2) value = value.slice(0, 2) + " " + value.slice(2);
                if (value.length > 6) value = value.slice(0, 6) + " " + value.slice(6);
                if (value.length > 9) value = value.slice(0, 9) + " " + value.slice(9, 11);

                e.target.value = value.slice(0, 12); // Limite à 12 caractères (avec les ":")
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var telephoneInput = document.getElementById("phone");

            telephoneInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres

                // Appliquer le format XX:XXX:XX:XX
                if (value.length > 2) value = value.slice(0, 2) + " " + value.slice(2);
                if (value.length > 6) value = value.slice(0, 6) + " " + value.slice(6);
                if (value.length > 9) value = value.slice(0, 9) + " " + value.slice(9, 11);

                e.target.value = value.slice(0, 12); // Limite à 12 caractères (avec les ":")
            });
        });
    </script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NGJKZ3DD" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    @stack('scripts')
</body>

</html>
