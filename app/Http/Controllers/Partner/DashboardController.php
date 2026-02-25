<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Trabalho;
use App\Models\EsgInitiative;
use App\Models\CompanyProject;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $jobs = Trabalho::query();
        $esg  = EsgInitiative::query();
        $proj = CompanyProject::query();

        if (!$user->isAdmin()) {
            $jobs->where('user_id', $user->id);
            $esg->where('user_id', $user->id);
            $proj->where('user_id', $user->id);
        }

        return view('partner.dashboard', [
            'jobsCount' => (clone $jobs)->count(),
            'jobsActiveCount' => (clone $jobs)->where('is_active', true)->count(),
            'esgCount' => (clone $esg)->count(),
            'esgActiveCount' => (clone $esg)->where('is_active', true)->count(),
            'projectsCount' => (clone $proj)->count(),
            'projectsActiveCount' => (clone $proj)->where('is_active', true)->count(),
        ]);
    }
}
