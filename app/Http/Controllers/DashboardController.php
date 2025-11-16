<?php

namespace App\Http\Controllers;

use App\Actions\Dashboard\GetDashboardMetricsAction;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = app(GetDashboardMetricsAction::class)->execute();

        return Inertia::render('Admin/Dashboard', [
            'metrics' => $metrics,
        ]);
    }
}
