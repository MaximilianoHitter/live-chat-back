<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermisoRequest;
use App\Http\Requests\RolRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\{Permission, Role, User};
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function getPermissions()
    {
        try {
            $data = PermissionResource::collection(Permission::all());
            return respuesta($data);
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }//ok

    public function getPermission($id)
    {
        try {
            $permiso = Permission::where('id', $id)->first();
            if (!$permiso) {
                return respuesta(null, ['general'=>'No existe el rol']);
            }
            return respuesta(new PermissionResource($permiso));
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }//ok

    public function getRoles()
    {
        try {
            $data = RoleResource::collection(Role::all());
            return respuesta($data);
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }//ok

    public function getRole($id)
    {
        try {
            $role = Role::where('id', $id)->first();
            if (!$role) {
                return respuesta(null, ['general'=>'No existe el rol']);
            }
            return respuesta(new RoleResource($role));
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }//ok

    public function asignarPermisos(PermisoRequest $request)
    {
        try {
            if (!$user = User::find($request->user_id)) {
                return respuesta(null, ['general'=>'No existe el usuario'], 404);
            }

            $permission = Permission::where('name', $request->permission_name)->first();
            if (!$permission) {
                return respuesta(null, ['general'=>'No existe el permiso'], 404);
            }

            $user->givePermissionTo($permission->name);

            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            return respuesta('Se otorgo el permiso');
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }//ok

    public function retirarPermiso(PermisoRequest $request)
    {
        try {
            if (!$user = User::find($request->user_id)) {
                return respuesta(null, ['general'=>'No existe el usuario'], 404);
            }

            $permission = Permission::where('name', $request->permission_name)->first();
            if (!$permission) {
                return respuesta(null, ['general'=>'No existe el permiso'], 404);
            }

            $user->revokePermissionTo($permission->name);

            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            return respuesta('Se retiro el permiso');
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }//ok

    public function viewPermissions($user_id)
    {
        try {
            if (!$user = User::find($user_id)) {
                return respuesta(null, ['general'=>'No existe el usuario'], 404);
            }

            $permissions = $user->getAllPermissions();

            return respuesta($permissions);
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }//ok

    public function asignarRol(RolRequest $request)
    {
        try {
            if (!$user = User::find($request->user_id)) {
                return respuesta(null, ['general'=>'No existe el usuario'], 404);
            }

            $role = Role::where('name', $request->role_name)->first();
            if (!$role) {
                return respuesta(null, ['general'=>'No existe el rol'], 404);
            }

            $user->assignRole($role->name);

            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            return respuesta('Se otorgo el rol');
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }//ok

    public function retirarRol(RolRequest $request)
    {
        try {
            if (!$user = User::find($request->user_id)) {
                return respuesta(null, ['general'=>'No existe el usuario'], 404);
            }

            $role = Role::where('name', $request->role_name)->first();
            if (!$role) {
                return respuesta(null, ['general'=>'No existe el rol'], 404);
            }

            $user->removeRole($role->name);

            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            return respuesta('Se retiro el rol');
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }//ok

    public function viewRoles($user_id)
    {
        try {
            if (!$user = User::find($user_id)) {
                return respuesta(null, ['general'=>'No existe el usuario'], 404);
            }

            $permissions = $user->roles;

            return respuesta($permissions);
        } catch (\Exception $e) {
            setLog($e->getMessage(), get_class() . '::' . __FUNCTION__, $e->getTrace());
            return respuesta(null, ['general'=>'Ha ocurrido un error interno.'], 490);
        }
    }//ok
}
