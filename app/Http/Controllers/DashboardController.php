<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        return view('home.index');
    }

    public function showPigManagement()
    {
        return view('pig.index');
    }

    public function showFeedingManagement()
    {
        return view('feeding.index');
    }
    public function showMonitorManagement()
    {
        return view('monitor.index');
    }

    public function showNotifications()
    {
        return view('notifications.index');
    }

}
