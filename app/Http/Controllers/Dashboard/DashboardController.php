<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pen;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        $pens = Pen::query()
            ->select(['pen_code', 'pen_name'])
            ->whereNotNull('pen_code')
            ->orderBy('pen_name')
            ->get();

        return view('home.index', [
            'pens' => $pens,
        ]);
    }

    public function showPigManagement()
    {
        $pens = Pen::query()
            ->select(['pen_code', 'pen_name'])
            ->whereNotNull('pen_code')
            ->orderBy('pen_name')
            ->get();

        return view('pig.index', [
            'pens' => $pens,
        ]);
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

    public function showReports()
    {
        return view('reports.index');
    }
}
