<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware(['role:super-admin|admin']);
        $this->middleware("permission:role-view", ["only" => ["index"]]);
        $this->middleware("permission:role-create", ["only" => ["create", "store"]]);
        $this->middleware("permission:role-update", ["only" => ["update", "edit"]]);
        $this->middleware("permission:role-show", ["only" => ["show"]]);
        $this->middleware("permission:role-delete", ["only" => ["destroy"]]);
        $this->middleware("permission:give-role-permissions", ["only" => ["givePermissionsToRole"]]);

    }

    public function index()
    {
        $roles = Role::orderBy('created_at', 'desc')->get();
        return view("role-permission.role.index", compact('roles'));
    }

    public function create()
    {
        return view("role-permission.role.create");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => ["required", "string", "unique:roles,name"],
        ]);

        $role = Role::create([
            "name"           => $request->name,
            "user_create_id" => Auth::user()->id,
            "user_update_id" => Auth::user()->id,
        ]);

        Alert::success('Succès ', 'Le rôle ' . $role->name . ' a été créé avec succès');
        return redirect()->back();
    }

    public function edit($id)
    {
        $role        = Role::find($id);
        $user_create = User::find($role->user_create_id);
        $user_update = User::find($role->user_update_id);

        $user_create_name = $user_create->firstname . ' ' . $user_create->name;
        $user_update_name = $user_update->firstname . ' ' . $user_update->name;

        return view("role-permission.role.update", compact('role', 'user_create_name', 'user_update_name'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $this->authorize('update', $role);

        $this->validate($request, [
            'name' => ['required', 'string', Rule::unique(Role::class)->ignore($id)],
        ]);

        $role->update([
            'name'           => $request->name,
            'user_update_id' => Auth::user()->id,
        ]);

        Alert::success('Succès !', 'Le rôle ' . $role->name . ' a été modifié avec succès');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        $this->authorize('delete', $role);

        $role->delete();

        Alert::success('Succès ', 'Le rôle ' . $role->name . ' a été supprimé avec succès');

        return redirect()->back();
    }

    public function addPermissionsToRole($roleId)
    {
        $permissions = Permission::orderBy('created_at', 'desc')->get();

        $role = Role::findOrFail($roleId);

        $this->authorize('update', $role);

        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $roleId)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        return view("role-permission.role.add-permissions", compact('role', 'permissions', 'rolePermissions'));
    }

    public function givePermissionsToRole($roleId, Request $request)
    {
        $request->validate([
            'permissions' => ['required'],
        ]);

        $role = Role::findOrFail($roleId);
        $this->authorize('update', $role);
        $role->syncPermissions($request->permissions);

        $messages = "Permissions accordée(s)";

        Alert::success('Succès !', 'Permissions accordées');

        return redirect()->back();

    }

    public function getUsersToRole($roleName, Request $request)
    {
        $role  = Role::where('name', $roleName)->first();
        $users = User::orderBy('created_at', 'desc')->get();

        $roleUsers = DB::table('model_has_roles')
            ->where('model_has_roles.role_id', $role->id)
            ->pluck('model_has_roles.model_id', 'model_has_roles.model_id')
            ->all();

        foreach ($users as $user) {
            return view("role-permission.role.role-users", compact('role', 'users', 'roleUsers', 'roleName', 'user'));
        }

    }
}
