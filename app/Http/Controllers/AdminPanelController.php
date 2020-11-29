<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminPanelController extends Controller
{
     
    // uri: dashbaord
    public function dashboard() {

        

        if(Auth()->user()->can('Dashboard Default View'))
        {

            return Inertia::render('Dashboard',);
            
        }else{

            
            return Inertia::render('NoPermissionToDashboard');

        }
        
        
    }

    
}
