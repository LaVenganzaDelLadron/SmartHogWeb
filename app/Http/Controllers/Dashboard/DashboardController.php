<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function showDashboard(): View
    {
        return view('home.index', [
            'pens' => collect(),
            'growthStages' => collect(),
            'totalPigs' => 0,
            'activeBatches' => 0,
        ]);
    }

    public function showPigManagement(): View
    {
        return view('pig.index', [
            'pens' => collect(),
            'growthStages' => collect(),
            'totalPigs' => 0,
            'activeBatches' => 0,
            'pigBatchCards' => collect(),
            'penCards' => collect(),
        ]);
    }

    public function showFeedingManagement(): View
    {
        return view('feeding.index', [
            'feedingBatches' => collect(),
            'feedingTypes' => collect(),
            'feedingCards' => collect(),
        ]);
    }

    public function showMonitorManagement(): View
    {
        return view('monitor.index');
    }

    public function showNotifications(): View
    {
        return view('notifications.index');
    }

    public function showReports(): View
    {
        return view('reports.index');
    }
}
