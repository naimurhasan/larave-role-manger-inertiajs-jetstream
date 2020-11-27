<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminPanelController extends Controller
{
    // uri: dashbaord
    public function dashboard() {
        return Inertia::render('Dashboard');
    }
}
