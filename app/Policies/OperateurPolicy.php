<?php
namespace App\Policies;

use App\Models\Operateur;
use App\Models\User;

class OperateurPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    /* public function viewAny(User $user): bool
    {
        //
    } */

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Operateur $operateur): bool
    {
        // Si l'utilisateur a un rôle autorisé
        if ($user->hasAnyRole(['super-admin', 'Ingenieur', 'DIOF', 'DEC'])) {
            return true;
        }
        // Sinon, il doit être le propriétaire de l'opérateur
        return $user->id === $operateur->users_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->id === auth()->id();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Operateur $operateur): bool
    {
        // Si l'utilisateur a un rôle autorisé
        if ($user->hasAnyRole(['super-admin', 'Ingenieur', 'DIOF', 'DEC'])) {
            return true;
        }
        // Sinon, il doit être le propriétaire de l'opérateur
        return $user->id === $operateur->users_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Operateur $operateur): bool
    {
        // Si l'utilisateur a un rôle autorisé
        if ($user->hasAnyRole(['super-admin', 'Ingenieur', 'DIOF', 'DEC'])) {
            return true;
        }
        // Sinon, il doit être le propriétaire de l'opérateur
        return $user->id === $operateur->users_id;
    }

    public function show(User $user, Operateur $operateur): bool
    {
        // Si l'utilisateur a un rôle autorisé
        if ($user->hasAnyRole(['super-admin', 'Ingenieur', 'DIOF', 'DEC'])) {
            return true;
        }
        // Sinon, il doit être le propriétaire de l'opérateur
        return $user->id === $operateur->users_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    /*  public function restore(User $user, Operateur $operateur): bool
    {
        //
    } */

    /**
     * Determine whether the user can permanently delete the model.
     */
    /*  public function forceDelete(User $user, Operateur $operateur): bool
    {
        //
    } */
}
