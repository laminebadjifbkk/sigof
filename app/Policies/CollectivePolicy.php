<?php
namespace App\Policies;

use App\Models\Collective;
use App\Models\User;

class CollectivePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    /*    public function viewAny(User $user): bool
    {
        //
    } */

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Collective $collective): bool
    {
        // Si l'utilisateur a un rôle autorisé
        if ($user->hasAnyRole(['super-admin', 'Ingenieur', 'DIOF', 'DEC'])) {
            return true;
        }

        // Sinon, il doit être le propriétaire de la collective
        return $user->id === $collective->users_id;
    }

    /**
     * Determine whether the user can create models.
     */
/*     public function create(User $user): bool
    {
        //
    } */

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Collective $collective): bool
    {
        // Si l'utilisateur a un rôle autorisé
        if ($user->hasAnyRole(['super-admin', 'Ingenieur', 'DIOF', 'DEC'])) {
            return true;
        }

        // Sinon, il doit être le propriétaire de la collective
        return $user->id === $collective->users_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Collective $collective): bool
    {
        // Si l'utilisateur a un rôle autorisé
        if ($user->hasAnyRole(['super-admin', 'Ingenieur', 'DIOF', 'DEC'])) {
            return true;
        }

        // Sinon, il doit être le propriétaire de la collective
        return $user->id === $collective->users_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    /*    public function restore(User $user, Collective $collective): bool
    {
        //
    } */

    /**
     * Determine whether the user can permanently delete the model.
     */
    /*   public function forceDelete(User $user, Collective $collective): bool
    {
        //
    } */
}
