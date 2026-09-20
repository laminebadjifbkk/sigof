<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notifications d'échéance
    |--------------------------------------------------------------------------
    |
    | Nombre de jours avant la date de fin prévue d'une activité pour
    | déclencher une alerte aux responsables et agents de suivi.
    | Modifiable via la variable d'environnement ONFP_NOTIF_JOURS_ECHEANCE
    | sans toucher au code.
    |
    */

    'notification_jours_avant_echeance' => env('ONFP_NOTIF_JOURS_ECHEANCE', 3),

];
