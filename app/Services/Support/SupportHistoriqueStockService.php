<?php

namespace App\Services\Support;

use App\Models\Support\SupportHistoriqueStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SupportHistoriqueStockService
{
    public function enregistrer(
        string $entite,
        int $entiteId,
        string $action,
        ?string $description = null,
        ?array $anciennesValeurs = null,
        ?array $nouvellesValeurs = null,
        ?int $userId = null
    ): SupportHistoriqueStock {
        return SupportHistoriqueStock::query()->create([
            'user_id' => $userId ?? Auth::id(),
            'entite' => strtoupper(trim($entite)),
            'entite_id' => $entiteId,
            'action' => strtoupper(trim($action)),
            'description' => $description,
            'anciennes_valeurs' => $anciennesValeurs,
            'nouvelles_valeurs' => $nouvellesValeurs,
            'adresse_ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
