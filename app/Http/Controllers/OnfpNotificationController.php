<?php

namespace App\Http\Controllers;

use App\Models\OnfpActiviteNotification;
use Illuminate\Http\Request;

class OnfpNotificationController extends Controller
{
    /**
     * Liste des notifications de l'utilisateur connecté.
     */
    public function index()
    {
        $employeeId = optional(auth()->user()->employee)->id;

        $notifications = OnfpActiviteNotification::pourEmployee($employeeId)
            ->with('activite')
            ->latest()
            ->paginate(20);

        return view('onfp.notifications.index', compact('notifications'));
    }

    /**
     * Nombre de notifications non lues (pour le badge dans le menu).
     * Peut être appelé en JSON par un petit script de polling si souhaité.
     */
    public function compteurNonLues(Request $request)
    {
        $employeeId = optional(auth()->user()->employee)->id;

        $count = OnfpActiviteNotification::pourEmployee($employeeId)
            ->nonLues()
            ->count();

        if ($request->wantsJson()) {
            return response()->json(['count' => $count]);
        }

        return $count;
    }

    public function marquerLue(OnfpActiviteNotification $notification)
    {
        $employeeId = optional(auth()->user()->employee)->id;

        abort_unless($notification->employee_id === $employeeId, 403);

        $notification->marquerCommeLue();

        return back();
    }

    public function marquerToutesLues()
    {
        $employeeId = optional(auth()->user()->employee)->id;

        OnfpActiviteNotification::pourEmployee($employeeId)
            ->nonLues()
            ->update(['lu_at' => now()]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function destroy(OnfpActiviteNotification $notification)
    {
        $employeeId = optional(auth()->user()->employee)->id;

        abort_unless($notification->employee_id === $employeeId, 403);

        $notification->delete();

        return back();
    }
}
