<?php
namespace App\Policies;

use App\Models\Collectivemodule;
use App\Models\User;

class CollectivemodulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Demandeur', 'DG', 'super-admin', 'admin', 'DIOF', 'ADIOF']); // Vérifie si l'utilisateur a l'un des rôles
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Collectivemodule $collectivemodule): bool
    {
        return $user?->id === $collectivemodule?->collective?->users_id || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Collectivemodule $collectivemodule): bool
    {
        return $user?->id === $collectivemodule?->collective?->users_id || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Collectivemodule $collectivemodule): bool
    {
        return $user?->id === $collectivemodule?->collective?->users_id || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Collectivemodule $collectivemodule): bool
    {
        return $user?->id === $collectivemodule?->collective?->users_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Collectivemodule $collectivemodule): bool
    {
        return $user?->id === $collectivemodule?->collective?->users_id;
    }
}
