<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminController extends Controller
{
    public function view_roles(Request $request){

        if(!Auth()->user()->can('Role View'))
            return abort(403);
        
        // get all roles
        $roles = Role::all();
        $permissions = Permission::all();
        
        $rolesWithPermissions = [];

        // assign permssions boolean for each roles
        foreach($roles as $role){

            // go through all roles in db
            // and assign boolen if exist in role

            foreach($permissions as $permission){
                $rolesWithPermissions[$role->name][$permission->name] = in_array($permission->name, $role->getPermissionNames()->toArray()); 
            }

        }

        
        return Inertia::render('Roles/ViewRoles', [
            'rolesWithPermissions' => $rolesWithPermissions,
        ]);
    }

    public function update_role(Request $request){
    
        if(!Auth()->user()->can('Role Edit'))
            return abort(403);
        
        $role = Role::findByName($request->input('role'));

        $app_permissions = Permission::all()->pluck('name');

        
        // For Security Reason Not Crowling POST request permssions
        // Crowling over app exising permission
        // and checkintg if the permission exist in request

        foreach($app_permissions as $permission){

        
            // if request permission exist in db
            if ( in_array ( $permission, array_keys ( $request->input( 'permissions') ) ) ){

                
                // if user has ticked for this permission
                if($request->input('permissions')[$permission] == true){
                    
                    // assign permission if already doesn't exist
                    if( ! $role->hasPermissionTo($permission) ){
                        
                        $role->givePermissionTo($permission);
                        

                    }

                    
                     
                }else{
                // if user has unticked
                
                    // if role had this permission then remove
                    if( $role->hasPermissionTo($permission) ){
                        $role->revokePermissionTo($permission);
                    }

                }
                
            }

        }
        

        return response()->json(['statusText' => 'OK'], 200);;

    }
}
