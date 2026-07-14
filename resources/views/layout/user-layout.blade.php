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
    {{--  <style>
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

        .Extension {
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

        .Téléchargé {
            background-color: #28a745;
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

        .missions-title {
            font-size: 0.85rem;
            /* identique à ton inline */
            font-weight: 600;
            /* meilleure lisibilité */
            color: #495057;
            /* gris lisible Bootstrap */
        }

        /* Style commun à tous les boutons d'état */
        /* Style commun (si besoin) */
        .etat-btn {
            background: none !important;
            border: none;
            padding: 0;
            box-shadow: none;
            color: inherit;
            /* hérite simplement la couleur */
        }

        /* Couleurs par état */
        .planifiee {
            color: #343a40;
            /* gris */
        }

        .en_cours {
            color: #0aa2c0;
            /* cyan */
        }

        .en_mission {
            color: #0aa2c0;
            /* cyan */
        }

        .terminee,
        .actif,
        .disponible,
        .operationnel {
            color: #198754;
            /* vert */
        }

        .annulee,
        .indisponible,
        .hors_service {
            color: #dc3545;
            /* rouge */
        }

        .maintenance {
            color: #ffc107;
            /* jaune */
        }

        /* Couleurs par rôle */
        .responsable {
            color: #0d6efd;
            /* bleu */
        }

        .participant,
        .chauffeur {
            color: #198754;
            /* vert */
        }

        .observateur {
            color: #6c757d;
            /* gris */
        }

        .default-role {
            color: #343a40;
            /* sombre */
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

        .Rejet {
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

        .Rejeté {
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

        .Indisponible {
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

        .Injoignable {
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

        .Suspendue {
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
            background-color: #198754;
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

        .Disponible {
            background-color: #198754;
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

        .Délivrés {
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

        .Disponibles {
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

        .permis-expire {
            color: #dc3545;
            /* rouge */
            font-weight: 600;
        }

        .permis-bientot {
            color: #fd7e14;
            /* orange */
            font-weight: 600;
        }

        .permis-ok {
            color: #198754;
            /* vert */
            font-weight: 600;
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

        .Ouvert {
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

        .déjà {
            background-color: #6c9d78;
            /* vert doux/grisé */
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .exécutée {
            background-color: #6c9d78;
            /* vert doux/grisé */
            color: #fff;
            border-color: #6c9d78;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .Exécutée {
            background-color: #6c9d78;
            /* vert doux/grisé */
            color: #fff;
            border-color: #6c9d78;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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

        .Former {
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
            background-color: #0d6efd;
            /* Bleu Bootstrap modernisé */
            color: #fff;
            padding: 6px 16px;
            text-align: center;
            border-radius: 999px;
            /* badge en forme de pilule */
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.15);
            /* ombre douce bleutée */
            display: inline-block;
            transition: all 0.3s ease;
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

        .Fermé {
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

        .fermé {
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



        /* Statuts */
        .En_attente {
            background-color: #FFC107;
            /* jaune bootstrap warning */
            color: #ffffff;
        }

        .En_cours {
            background-color: #17A2B8;
            /* cyan bootstrap info */
            color: #ffffff;
        }

        .Terminee {
            background-color: #28A745;
            /* vert bootstrap success */
            color: #ffffff;
        }

        .Validee {
            background-color: #0D6EFD;
            /* bleu bootstrap primary */
            color: #ffffff;
        }

        .Rejete {
            background-color: #DC3545;
            /* rouge bootstrap danger */
            color: #ffffff;
        }

        /* Priorités */
        .Faible {
            background-color: #6C757D;
            /* gris bootstrap secondary */
            color: #ffffff;
        }

        .Normale {
            background-color: #0D6EFD;
            /* bleu bootstrap primary */
            color: #ffffff;
        }

        .Urgente {
            background-color: #DC3545;
            /* rouge bootstrap danger */
            color: #ffffff;
        }

        /* Style commun pour toutes les badges */
        .badge-activite {
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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

        .dropdown-menu {
            z-index: 1050 !important;
        }

        .nav-profile-image-wrapper {
            width: 36px;
            height: 36px;
            padding: 2px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .nav-profile-image-wrapper.online {
            border: 2px solid #198754;
        }

        .nav-profile-image-wrapper.offline {
            border: 2px solid #dc3545;
        }

        .nav-profile-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .nav-profile-image-wrapper::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .nav-profile-image-wrapper.online::after {
            background: #198754;
        }

        .nav-profile-image-wrapper.offline::after {
            background: #dc3545;
        }

        /* TABLE */

        .table-profile-image-wrapper {
            width: 40px;
            height: 40px;
            padding: 2px;
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }

        .table-profile-image-wrapper.online {
            border: 2px solid #198754;
        }

        .table-profile-image-wrapper.offline {
            border: 2px solid #dc3545;
        }

        .table-profile-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .table-profile-image-wrapper::after {
            content: '';
            position: absolute;
            bottom: -1px;
            right: -1px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .table-profile-image-wrapper.online::after {
            background: #198754;
        }

        .table-profile-image-wrapper.offline::after {
            background: #dc3545;
        }

        /* PROFIL */

        .profile-image-wrapper {
            width: 130px;
            height: 130px;
            padding: 4px;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            transition: all 0.3s ease;
        }

        .profile-image-wrapper.online {
            border: 4px solid #198754;
        }

        .profile-image-wrapper.offline {
            border: 4px solid #dc3545;
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-image-wrapper::after {
            content: '';
            position: absolute;
            bottom: 6px;
            right: 6px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 3px solid #fff;
        }

        .profile-image-wrapper.online::after {
            background: #198754;
        }

        .profile-image-wrapper.offline::after {
            background: #dc3545;
        }

        .profile-image-wrapper:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .social-links a {
            font-size: 20px;
            color: #555;
            transition: color 0.3s;
        }

        .social-links a:hover {
            color: #0d6efd;
        }

        /* Réduire la hauteur des boutons */
        .pagination .page-link {
            padding: 0.35rem 0.65rem;
        }

        /* Ajuster les flèches */
        .pagination .page-link span {
            line-height: 1;
        }

        /* Empêche tout débordement horizontal */
        .ck-editor {
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Zone d’édition */
        .ck-editor__editable {
            min-height: 200px;
            max-width: 100% !important;
        }

        /* Toolbar responsive */
        .ck.ck-toolbar {
            flex-wrap: wrap !important;
        }

        /* Empêche scroll horizontal sur mobile */
        .ck-editor__editable_inline {
            overflow-x: auto;
            word-break: break-word;
        }

        /* Important pour mobile */
        .card {
            overflow-x: hidden;
        }
    </style> --}}


    <style>
        /* Styles communs pour badges de couleur similaire */
        .nouvelle,
        .Nouvelle,
        .Extension {
            background-color: #ff9966;
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .Téléchargé {
            background-color: #28a745;
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .Nouveau {
            background-color: #36e0e0;
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .Attente {
            background-color: #6C757D;
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }


        .attente .Attente {
            background-color: #6C757D;
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;

        }

        .rejeter,
        .Rejetée,
        .Rejet,
        .Rejeté,
        .rejeté,
        .Indisponible,
        .Injoignable,
        .annuler,
        .Abandon,
        .Expiré,
        .expiré,
        .Suspendue,
        .fermer,
        .Fermé,
        .fermé,
        .Non,
        .non,
        .incomplète,
        .invalide,
        .Retard,
        .fin,
        .décliner {
            background-color: #DC3545;
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .retirer,
        .corriger,
        .réserve,
        .Disponibles,
        .Retirée,
        .Retiré,
        .renew,
        .Renouvellement,
        .Absent,
        .expirer,
        .Absente {
            background-color: #ffcc00;
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }


        .Disponible,
        .formés,
        .formé,
        .Présent,
        .Présente,
        .Former,
        .ouvert,
        .Ouvert,
        .terminer,
        .confirmer,
        .Terminée,
        .Validée,
        .agréé {
            background-color: #198754;
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .accepter,
        .Délivrés,
        .Oui,
        .retenue,
        .retenu,
        .Retenu,
        .Retenue,
        .Sélectionné,
        .programmer,
        .commission,
        .démarrer,
        .Imputée,
        .cours,
        .generer,
        .généré,
        .new,
        .retiré {
            background-color: #0DCAF0;
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .Conforme {
            background-color: #0d6efd;
            color: #fff;
            padding: 6px 16px;
            text-align: center;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.15);
            display: inline-block;
            transition: all 0.3s ease;
        }

        .déjà,
        .exécutée,
        .Exécutée {
            background-color: #6c9d78;
            color: #ffffff;
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .Aucun {
            background-color: white;
            color: #DC3545;
            padding: 4px 8px;
            text-align: center;
            border-radius: 25% 10%;
        }

        /* Roles */
        .responsable {
            color: #0d6efd;
        }

        .participant,
        .chauffeur {
            color: #198754;
        }

        .observateur {
            color: #6c757d;
        }

        .default-role {
            color: #343a40;
        }

        /* Statuts texte */
        .planifiee {
            color: #343a40;
        }

        .en_cours,
        .en_mission {
            color: #0aa2c0;
        }

        .terminee,
        .actif,
        .disponible,
        .operationnel {
            color: #198754;
        }

        .annulee,
        .indisponible,
        .hors_service {
            color: #dc3545;
        }

        .maintenance {
            color: #ffc107;
        }

        /* Permis */
        .permis-expire {
            color: #dc3545;
            font-weight: 600;
        }

        .permis-bientot {
            color: #fd7e14;
            font-weight: 600;
        }

        .permis-ok {
            color: #198754;
            font-weight: 600;
        }

        /* Statuts d'activité */
        .En_attente {
            background-color: #6C757D;
            color: #ffffff;
        }

        .En_cours {
            background-color: #17A2B8;
            color: #ffffff;
        }

        .Terminee {
            background-color: #28A745;
            color: #ffffff;
        }

        .Validee {
            background-color: #0D6EFD;
            color: #ffffff;
        }

        .Rejete {
            background-color: #DC3545;
            color: #ffffff;
        }

        /* Priorités */
        .Faible {
            background-color: #ffcc00;
            color: #ffffff;
        }

        .Normale {
            background-color: #0D6EFD;
            color: #ffffff;
        }

        .Urgente {
            background-color: #DC3545;
            color: #ffffff;
        }

        /* Styles communs */
        .badge-activite {
            padding: 6px 12px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .missions-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #495057;
        }

        .etat-btn {
            background: none !important;
            border: none;
            padding: 0;
            box-shadow: none;
            color: inherit;
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
        }

        .modal-header-sm {
            padding: 10px 20px;
            font-size: 1rem;
            line-height: 1.2;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .dropdown-menu {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-profile-image-wrapper {
            width: 36px;
            height: 36px;
            padding: 2px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .nav-profile-image-wrapper.online {
            border: 2px solid #198754;
        }

        .nav-profile-image-wrapper.offline {
            border: 2px solid #dc3545;
        }

        .nav-profile-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .nav-profile-image-wrapper::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .nav-profile-image-wrapper.online::after {
            background: #198754;
        }

        .nav-profile-image-wrapper.offline::after {
            background: #dc3545;
        }

        /* TABLE */

        .table-profile-image-wrapper {
            width: 40px;
            height: 40px;
            padding: 2px;
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }

        .table-profile-image-wrapper.online {
            border: 2px solid #198754;
        }

        .table-profile-image-wrapper.offline {
            border: 2px solid #dc3545;
        }

        .table-profile-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .table-profile-image-wrapper::after {
            content: '';
            position: absolute;
            bottom: -1px;
            right: -1px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .table-profile-image-wrapper.online::after {
            background: #198754;
        }

        .table-profile-image-wrapper.offline::after {
            background: #dc3545;
        }

        /* PROFIL */

        .profile-image-wrapper {
            width: 130px;
            height: 130px;
            padding: 4px;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            transition: all 0.3s ease;
        }

        .profile-image-wrapper.online {
            border: 4px solid #198754;
        }

        .profile-image-wrapper.offline {
            border: 4px solid #dc3545;
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-image-wrapper::after {
            content: '';
            position: absolute;
            bottom: 6px;
            right: 6px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 3px solid #fff;
        }

        .profile-image-wrapper.online::after {
            background: #198754;
        }

        .profile-image-wrapper.offline::after {
            background: #dc3545;
        }

        .profile-image-wrapper:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
        }

        .mediocre {
            color: #dc3545;
            /* rouge */
        }

        .insuffisant {
            color: #fd7e14;
            /* orange */
        }

        .passable {
            color: #6c757d;
            /* gris */
        }

        .assez-bien {
            color: #0dcaf0;
            /* bleu clair */
        }

        .bien {
            color: #0d6efd;
            /* bleu */
        }

        .tres-bien {
            color: #198754;
            /* vert */
        }

        .excellent {
            color: #198754;
            font-weight: bold;
        }

        /* Wrapper pour le défilement */
        .scrolling-message-wrapper {
            white-space: nowrap;
        }

        /* Texte qui défile */
        .scrolling-message {
            display: inline-block;
            padding-left: 100%;
            animation: scroll-left 12s linear infinite;
        }

        /* Animation */
        @keyframes scroll-left {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .hover-card {
            transition: all 0.25s ease-in-out;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .icon-box {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .direction-header {
            background: linear-gradient(135deg, #F28500, #ffb347, #ff8c00);
            border-radius: 12px;
            color: #fff;
        }

        .direction-header .icon {
            width: 55px;
            height: 55px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* hover effet */
        .direction-header:hover {
            transform: translateY(-3px);
            transition: all 0.3s ease-in-out;
            box-shadow: 0 12px 25px rgba(242, 133, 0, 0.35);
        }

        /* globalProgress */
        .donut-chart {
            width: 170px;
            height: 170px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: conic-gradient(#198754 calc(var(--p-terminee) * 1%),
                    #ffc107 calc(var(--p-terminee) * 1%) calc((var(--p-terminee) + var(--p-encours)) * 1%),
                    #e9ecef calc((var(--p-terminee) + var(--p-encours)) * 1%) 100%);
            position: relative;
        }

        .donut-inner {
            width: 122px;
            height: 122px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .legend-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 6px;
        }

        .formations-table tbody tr {
            border-bottom: 1px solid #f1f1f4;
        }

        .formations-table tbody tr:last-child {
            border-bottom: none;
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
            <img src="{{ asset('assets/img/logo_sigle.png') }}" alt="Logo ONFP">
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
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script> --}}

    <style>
        .profile-card {
            cursor: pointer;
            transition: all 0.25s ease;
            border-radius: 12px;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }
    </style>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {

            const actions = [{
                    selector: '.show_confirm',
                    title: 'Êtes-vous sûr de vouloir supprimer cet enregistrement ?',
                    text: "Si vous supprimez ceci, il disparaîtra pour toujours.",
                    icon: 'warning',
                    confirmText: 'Oui, Supprimer !'
                },
                {
                    selector: '.show_confirm_detach',
                    title: 'Êtes-vous sûr de vouloir détacher ?',
                    text: "Si vous supprimez ceci, il disparaîtra pour toujours.",
                    icon: 'warning',
                    confirmText: 'Oui, Détacher !'
                },
                {
                    selector: '.show_confirm_disconnect',
                    title: 'Êtes-vous sûr de vouloir vous déconnecter ?',
                    text: "Cliquez sur OK pour confirmer.",
                    icon: 'warning',
                    confirmText: 'Oui, déconnecter !'
                },
                {
                    selector: '.show_confirm_nettoyer',
                    title: 'Êtes-vous sûr de vouloir nettoyer ?',
                    text: "Cliquez sur OK pour confirmer.",
                    icon: 'warning',
                    confirmText: 'Oui, nettoyer !'
                },
                {
                    selector: '.show_confirm_valider',
                    title: 'Êtes-vous sûr ?',
                    text: "Si oui, cliquez sur OK.",
                    icon: 'success',
                    confirmText: 'Oui, valider !'
                },
                {
                    selector: '.show_confirm_ouvrir',
                    title: 'Êtes-vous sûr ?',
                    text: "Si oui, cliquez sur OK.",
                    icon: 'success',
                    confirmText: 'Oui, Ouvrir !'
                },
                {
                    selector: '.show_confirm_terminer',
                    title: 'Êtes-vous sûr ?',
                    text: "Si oui, cliquez sur OK.",
                    icon: 'success',
                    confirmText: 'Oui, Terminer !'
                },
                {
                    selector: '.show_confirm_certifier',
                    title: 'Êtes-vous sûr ?',
                    text: "Si oui, cliquez sur OK.",
                    icon: 'success',
                    confirmText: 'Oui, Certifier !'
                },
                {
                    selector: '.show_confirm_fermer',
                    title: 'Êtes-vous sûr ?',
                    text: "Si oui, cliquez sur OK.",
                    icon: 'success',
                    confirmText: 'Oui, Fermer !'
                },
                {
                    selector: '.show_confirm_rejeter',
                    title: 'Êtes-vous sûr de vouloir rejeter ce fichier ?',
                    text: "Cliquez sur OK pour confirmer.",
                    icon: 'warning',
                    confirmText: 'Oui, rejeter'
                },
                {
                    selector: '.show_confirm_suivi',
                    title: 'Êtes-vous sûr de bien vouloir suivre ce bénéficiaire ?',
                    text: "Cliquez sur OK pour confirmer.",
                    icon: 'success',
                    confirmText: 'Oui, suivre !'
                },
                {
                    selector: '.show_confirm_employes',
                    title: 'Êtes-vous sûr de vouloir ajouter à la base de données des employés ?',
                    text: "Cliquez sur OK pour confirmer.",
                    icon: 'success',
                    confirmText: 'Oui, ajouter !'
                },
                {
                    selector: '.show_confirm_retirer',
                    title: 'Êtes-vous sûr de vouloir retirer ?',
                    text: "Cliquez sur OK pour confirmer.",
                    icon: 'success',
                    confirmText: 'Oui, retirer !'
                },
                {
                    selector: '.show_confirm_annuler',
                    title: 'Êtes-vous sûr de vouloir rejeter ?',
                    text: "Cliquez sur OK pour confirmer.",
                    icon: 'error',
                    confirmText: 'Oui, rejeter !'
                },
                {
                    selector: '.une_confirm',
                    title: 'Êtes-vous sûr ?',
                    text: "Cliquez sur OK pour confirmer.",
                    icon: 'success',
                    confirmText: 'Oui, mettre !'
                },
                {
                    selector: '.une_confirmer',
                    title: 'Êtes-vous sûr ?',
                    text: "Cliquez sur OK pour confirmer.",
                    icon: 'success',
                    confirmText: 'Oui, enlever !'
                }
            ];

            // Boucle pour toutes les actions de formulaire
            actions.forEach(({
                selector,
                title,
                text,
                icon,
                confirmText
            }) => {
                document.querySelectorAll(selector).forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        if (!form) return;

                        // Empêche double clic
                        if (btn.dataset.submitting === 'true') return;

                        Swal.fire({
                            title: title,
                            text: text,
                            icon: icon,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: confirmText,
                            cancelButtonText: 'Annuler'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                btn.dataset.submitting = 'true';
                                btn.disabled = true;

                                // Affiche loader avant soumission
                                Swal.fire({
                                    title: 'Traitement...',
                                    text: 'Veuillez patienter',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    showConfirmButton: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        setTimeout(() => form.submit(),
                                            200
                                            ); // léger délai pour le loader
                                    }
                                });
                            }
                        });
                    });
                });
            });

            // Gestion AJAX pour suppression d'images/fichiers
            document.querySelectorAll('.show_confirmDeleteImage').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.dataset.url;
                    if (!url) return;

                    Swal.fire({
                        title: 'Êtes-vous sûr de vouloir supprimer ?',
                        text: "Si vous supprimez, l'image disparaîtra pour toujours.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Oui, Supprimer !',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        _method: 'DELETE'
                                    })
                                })
                                .then(res => res.json())
                                .then(() => {
                                    Swal.fire('Succès', "Votre image a été supprimée.",
                                            'success')
                                        .then(() => location.reload());
                                })
                                .catch(() => {
                                    Swal.fire('Erreur', "Une erreur s'est produite.",
                                        'error');
                                });
                        }
                    });
                });
            });

        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Tableau des actions SweetAlert2
            const actions = [{
                    selector: '.show_confirm',
                    title: 'Êtes-vous sûr de vouloir supprimer cet enregistrement ?',
                    text: "Si vous supprimez ceci, il disparaîtra pour toujours.",
                    icon: 'warning',
                    confirmText: 'Oui, Supprimer !'
                },
                {
                    selector: '.show_confirm_detach',
                    title: 'Êtes-vous sûr de vouloir détacher ?',
                    text: "Si vous supprimez ceci, il disparaîtra pour toujours.",
                    icon: 'warning',
                    confirmText: 'Oui, Détacher !'
                },
                {
                    selector: '.show_confirm_disconnect',
                    title: 'Êtes-vous sûr de vouloir vous déconnecter ?',
                    text: "Cliquez sur OK pour confirmer.",
                    icon: 'warning',
                    confirmText: 'Oui, déconnecter !'
                },
                // Ajoute ici toutes tes autres actions show_confirm* comme précédemment
            ];

            // Boucle sur chaque action
            actions.forEach(({
                selector,
                title,
                text,
                icon,
                confirmText
            }) => {
                document.querySelectorAll(selector).forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();

                        const form = this.closest('form');
                        if (!form) return;

                        // Empêche double clic
                        if (btn.dataset.submitting === 'true') return;

                        Swal.fire({
                            title: title,
                            text: text,
                            icon: icon,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: confirmText,
                            cancelButtonText: 'Annuler'
                        }).then((result) => {
                            if (result.isConfirmed) {

                                // Marque comme en cours et désactive le bouton
                                btn.dataset.submitting = 'true';
                                btn.disabled = true;

                                // Affiche loader avant soumission
                                Swal.fire({
                                    title: 'Traitement...',
                                    text: 'Veuillez patienter',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    showConfirmButton: false,
                                    didOpen: () => {
                                        Swal.showLoading();

                                        // Soumission native du formulaire
                                        form.submit();
                                    }
                                });
                            }
                        });
                    });
                });
            });

            // Gestion spécifique des suppressions par AJAX (images, fichiers)
            document.querySelectorAll('.show_confirmDeleteImage').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.dataset.url;
                    if (!url) return;

                    Swal.fire({
                        title: 'Êtes-vous sûr de vouloir supprimer ?',
                        text: "Si vous supprimez, l'image disparaîtra pour toujours.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Oui, Supprimer !',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        _method: 'DELETE'
                                    })
                                })
                                .then(res => res.json())
                                .then(() => {
                                    Swal.fire('Succès', "Votre image a été supprimée.",
                                            'success')
                                        .then(() => location.reload());
                                })
                                .catch(() => {
                                    Swal.fire('Erreur', "Une erreur s'est produite.",
                                        'error');
                                });
                        }
                    });
                });
            });

        });
    </script>

    <script>
        function confirmProfil(profil) {

            let message = profil === 'demandeur' ?
                "Vous allez activer le profil Demandeur de formation." :
                "Vous allez activer le profil Opérateur de formation.";

            Swal.fire({
                title: "Confirmer votre choix",
                text: message,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Oui, activer",
                cancelButtonText: "Annuler",
                confirmButtonColor: "#3085d6"
            }).then((result) => {

                if (result.isConfirmed) {

                    document.getElementById('profilInput').value = profil;
                    document.getElementById('profilForm').submit();

                }

            });

        }
    </script>
    <style>
        .disabled-card {
            pointer-events: none;
            /* Désactive les clics */
            opacity: 0.5;
            /* Grise la carte pour indiquer qu’elle est inactive */
            cursor: not-allowed;
            /* Curseur “interdit” */
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        $('#select-field-operateurcategories-update').select2({
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
        $('#niveau_qualificationmodal').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
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
        $('#select-field-operateurs_id').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-lebelles_id').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
        });
    </script>

    <script>
        $('#select-field-ingenieurs_id').select2({
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
        // Check / Uncheck all
        document.getElementById('checkAll').addEventListener('click', function(e) {
            document.querySelectorAll('.choisir-tout-checkbox').forEach(function(checkbox) {
                checkbox.checked = e.target.checked;
            });
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
        $('#select-field-choisirop').select2({
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
        $('#select-field-formation-pole-rapport').select2({
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
        $('#select-field-membre').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: true,
            selectionCssClass: "select2--small",
            dropdownCssClass: "select2--small",
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

    <script>
        $('#select-field-doamine').select2({
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

    {{--  <script>
        document.addEventListener("DOMContentLoaded", function() {
            var telephoneInput = document.getElementById("telephone");

            telephoneInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres

                // Appliquer le format Téléphone
                if (value.length > 2) value = value.slice(0, 2) + " " + value.slice(2);
                if (value.length > 6) value = value.slice(0, 6) + " " + value.slice(6);
                if (value.length > 9) value = value.slice(0, 9) + " " + value.slice(9, 11);

                e.target.value = value.slice(0, 12); // Limite à 12 caractères (avec les ":")
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var telephoneInput = document.getElementById("telephone_s");

            telephoneInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres

                // Appliquer le format Téléphone
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

                // Appliquer le format Téléphone
                if (value.length > 2) value = value.slice(0, 2) + " " + value.slice(2);
                if (value.length > 6) value = value.slice(0, 6) + " " + value.slice(6);
                if (value.length > 9) value = value.slice(0, 9) + " " + value.slice(9, 11);

                e.target.value = value.slice(0, 12); // Limite à 12 caractères (avec les ":")
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var telephoneInput = document.getElementById("contact_secondaire");

            telephoneInput.addEventListener("input", function(e) {
                var value = e.target.value.replace(/\D/g, ""); // Supprime tout sauf les chiffres

                // Appliquer le format Téléphone
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

                            // Appliquer le format Téléphone
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

                // Appliquer le format Téléphone
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

                // Appliquer le format Téléphone
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

                // Appliquer le format Téléphone
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

                // Appliquer le format Téléphone
                if (value.length > 2) value = value.slice(0, 2) + " " + value.slice(2);
                if (value.length > 6) value = value.slice(0, 6) + " " + value.slice(6);
                if (value.length > 9) value = value.slice(0, 9) + " " + value.slice(9, 11);

                e.target.value = value.slice(0, 12); // Limite à 12 caractères (avec les ":")
            });
        });
    </script> --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectStatut = document.getElementById("select-field-statut-col");
            const autreWrapper = document.getElementById("autre-statut-wrapper");

            function toggleAutreField() {
                if (selectStatut.value === "Autre") {
                    autreWrapper.style.display = "block";
                } else {
                    autreWrapper.style.display = "none";
                }
            }

            toggleAutreField(); // au chargement
            selectStatut.addEventListener("change", toggleAutreField); // au changement
        });
    </script>

    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        '|',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'undo',
                        'redo'
                    ],
                    shouldNotGroupWhenFull: true
                }
            })
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#projetprofessionnel'), {
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        '|',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'undo',
                        'redo'
                    ],
                    shouldNotGroupWhenFull: false
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script> --}}
    {{-- <style>
        #projetprofessionnel+.ck-editor .ck-editor__editable {
            min-height: 200px;
        }

        #description+.ck-editor .ck-editor__editable {
            min-height: 200px;
        }
    </style> --}}

    {{-- <style>
        .ck-editor {
            width: 100% !important;
            max-width: 100%;
        }

        .ck-editor__editable {
            min-height: 200px;
        }

        .ck.ck-toolbar {
            flex-wrap: wrap !important;
        }

        .ck-editor__editable_inline {
            word-break: break-word;
        }

        body {
            overflow-x: hidden;
        }
    </style> --}}
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NGJKZ3DD" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    @stack('scripts')
</body>

</html>
