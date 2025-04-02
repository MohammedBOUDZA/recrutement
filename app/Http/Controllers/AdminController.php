<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Emploi;
use App\Models\Application;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_jobs' => Emploi::count(),
            'total_applications' => Application::count(),
            'total_companies' => Entreprise::count(),
            'active_jobs' => Emploi::where('status', 'active')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_applications_today' => Application::whereDate('created_at', today())->count(),
        ];

        $userTrend = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->take(7)
            ->get()
            ->reverse();

        $applicationTrend = Application::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->take(7)
            ->get()
            ->reverse();

        $usersByRole = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get();

        $topCategories = Emploi::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'DESC')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'userTrend',
            'applicationTrend',
            'usersByRole',
            'topCategories'
        ));
    }

    public function users()
    {
        $users = User::with(['chercheur', 'entreprise'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function jobs()
    {
        $jobs = Emploi::with(['entreprise', 'applications'])
            ->latest()
            ->paginate(20);

        return view('admin.jobs.index', compact('jobs'));
    }

    public function applications()
    {
        $applications = Application::with(['user', 'emploi.entreprise'])
            ->latest()
            ->paginate(20);

        return view('admin.applications.index', compact('applications'));
    }

    public function statistics()
    {
        $monthlyStats = [
            'users' => User::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )->groupBy('year', 'month')
                ->orderBy('year', 'DESC')
                ->orderBy('month', 'DESC')
                ->take(12)
                ->get(),

            'jobs' => Emploi::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )->groupBy('year', 'month')
                ->orderBy('year', 'DESC')
                ->orderBy('month', 'DESC')
                ->take(12)
                ->get(),

            'applications' => Application::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )->groupBy('year', 'month')
                ->orderBy('year', 'DESC')
                ->orderBy('month', 'DESC')
                ->take(12)
                ->get(),
        ];

        $categoryStats = Emploi::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        $statusStats = Application::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $locationStats = Emploi::select('location', DB::raw('count(*) as count'))
            ->groupBy('location')
            ->orderBy('count', 'DESC')
            ->take(10)
            ->get();

        return view('admin.statistics', compact(
            'monthlyStats',
            'categoryStats',
            'statusStats',
            'locationStats'
        ));
    }
}
