<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HandleInertiaRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    
    
    public function handle(Request $request, Closure $next)
    {
        
        $menu = [];
        $permissions = [];
        
    
        if(Auth()->user() != null){

            // assign permission list
            $permissions = $this->permissionNames();

            // assign menu
            if(\in_array('Dashboard Default View', $permissions)){
                $menu = array_merge($menu, [
                    'Members' => 'members',
                ]);
            }
            

        }
        
        Inertia::share('menu', $menu);
        Inertia::share('permissions', $permissions);
        

        
        return $next($request);
    }

    function permissionNames(){
        $permissions = [];

        foreach(Auth()->user()->getAllPermissions() as $permission){
            $permissions[] = $permission->name;
        }

        return $permissions;
    }
    

}
