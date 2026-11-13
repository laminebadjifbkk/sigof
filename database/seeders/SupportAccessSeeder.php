<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SupportAccessSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'web';

        /*
         * 1. Déterminer l'utilisateur système chargé
         * de renseigner les champs obligatoires des rôles.
         */
        $actorId = DB::table('model_has_roles as mhr')
            ->join('roles as r', 'r.id', '=', 'mhr.role_id')
            ->whereIn('r.name', ['super-admin', 'admin'])
            ->where('r.guard_name', $guard)
            ->where('mhr.model_type', 'App\Models\User')
            ->value('mhr.model_id');

        $actorId = $actorId ?: DB::table('users')->value('id');

        if (!$actorId) {
            throw new RuntimeException(
                'Impossible de créer les rôles Support : aucun utilisateur SIGOF disponible.'
            );
        }

        /*
         * 2. Définir les permissions du module Support.
         */
        $permissions = [
            // Accès général
            'support.access',

            // Tickets
            'support.tickets.create',
            'support.tickets.view-own',
            'support.tickets.view-assigned',
            'support.tickets.view-all',
            'support.tickets.update',
            'support.tickets.take-charge',
            'support.tickets.assign',
            'support.tickets.change-priority',
            'support.tickets.close',
            'support.tickets.reopen',
            'support.tickets.delete',

            // Commentaires et pièces jointes
            'support.commentaires.view',
            'support.commentaires.create',
            'support.commentaires.delete',
            'support.pieces-jointes.view',
            'support.pieces-jointes.upload',
            'support.pieces-jointes.delete',

            // Interventions
            'support.interventions.view',
            'support.interventions.create',
            'support.interventions.update',
            'support.interventions.delete',

            // Incidents
            'support.incidents.view',
            'support.incidents.create',
            'support.incidents.update',
            'support.incidents.manage',

            // Parc informatique
            'support.equipements.view',
            'support.equipements.create',
            'support.equipements.update',
            'support.equipements.delete',
            'support.equipements.assign',
            'support.equipements.move',

            // Maintenance
            'support.maintenances.view',
            'support.maintenances.create',
            'support.maintenances.update',
            'support.maintenances.manage',

            // Stocks et consommables
            'support.stocks.view',
            'support.stocks.manage',
            'support.stocks.movement',

            // Tableaux de bord et rapports
            'support.dashboard.view',
            'support.rapports.view',
            'support.rapports.export',

            // Paramétrage
            'support.parametres.view',
            'support.parametres.manage',
        ];

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::transaction(function () use (
            $permissions,
            $guard,
            $actorId
        ) {
            /*
             * 3. Créer les permissions sans doublons.
             */
            foreach ($permissions as $permissionName) {
                Permission::findOrCreate($permissionName, $guard);
            }

            /*
             * 4. Définir les droits des profils Support.
             */
            $rolePermissions = [
                'chef-service-informatique' => $permissions,

                'responsable-support' => [
                    'support.access',
                    'support.tickets.create',
                    'support.tickets.view-own',
                    'support.tickets.view-assigned',
                    'support.tickets.view-all',
                    'support.tickets.update',
                    'support.tickets.assign',
                    'support.tickets.change-priority',
                    'support.tickets.close',
                    'support.tickets.reopen',
                    'support.commentaires.view',
                    'support.commentaires.create',
                    'support.pieces-jointes.view',
                    'support.pieces-jointes.upload',
                    'support.interventions.view',
                    'support.interventions.create',
                    'support.interventions.update',
                    'support.incidents.view',
                    'support.incidents.create',
                    'support.incidents.update',
                    'support.incidents.manage',
                    'support.equipements.view',
                    'support.maintenances.view',
                    'support.maintenances.create',
                    'support.maintenances.update',
                    'support.stocks.view',
                    'support.dashboard.view',
                    'support.rapports.view',
                    'support.rapports.export',
                ],

                'technicien-support' => [
                    'support.access',
                    'support.tickets.view-own',
                    'support.tickets.view-assigned',
                    'support.tickets.update',
                    'support.tickets.take-charge',
                    'support.tickets.reopen',
                    'support.commentaires.view',
                    'support.commentaires.create',
                    'support.pieces-jointes.view',
                    'support.pieces-jointes.upload',
                    'support.interventions.view',
                    'support.interventions.create',
                    'support.interventions.update',
                    'support.incidents.view',
                    'support.incidents.create',
                    'support.incidents.update',
                    'support.equipements.view',
                    'support.maintenances.view',
                    'support.maintenances.create',
                    'support.maintenances.update',
                    'support.stocks.view',
                    'support.stocks.movement',
                    'support.dashboard.view',
                ],

                'gestionnaire-parc-informatique' => [
                    'support.access',
                    'support.equipements.view',
                    'support.equipements.create',
                    'support.equipements.update',
                    'support.equipements.delete',
                    'support.equipements.assign',
                    'support.equipements.move',
                    'support.maintenances.view',
                    'support.maintenances.create',
                    'support.maintenances.update',
                    'support.maintenances.manage',
                    'support.stocks.view',
                    'support.stocks.manage',
                    'support.stocks.movement',
                    'support.rapports.view',
                    'support.rapports.export',
                    'support.dashboard.view',
                ],
            ];

            /*
             * 5. Créer les rôles et leur attribuer les permissions.
             *
             * On utilise le modèle Spatie, mais on renseigne
             * explicitement les deux champs personnalisés de SIGOF.
             */
            foreach ($rolePermissions as $roleName => $rolePermissionNames) {
                $role = Role::query()
                    ->where('name', $roleName)
                    ->where('guard_name', $guard)
                    ->first();

                if (!$role) {
                    $role = new Role();

                    $role->name = $roleName;
                    $role->guard_name = $guard;

                    $role->setAttribute('user_create_id', $actorId);
                    $role->setAttribute('user_update_id', $actorId);

                    $role->save();
                } else {
                    $role->setAttribute('user_update_id', $actorId);
                    $role->save();
                }

                // Attribution additive : ne retire pas les autres droits.
                $role->givePermissionTo($rolePermissionNames);
            }
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info(
            'Les permissions et les rôles du module Support ont été initialisés.'
        );
    }
}
