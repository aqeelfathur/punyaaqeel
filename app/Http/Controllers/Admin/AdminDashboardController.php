<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Program;
use App\Models\UserWorkout;
use App\Models\Komentar;
use App\Models\Gerakan; 
use Carbon\Carbon;

class AdminDashboardController extends Controller
{   
    public function index()
    {
        // Statistik umum
        $totalUsers = User::count();
        $totalPrograms = Program::count();
        $totalCommunities = Komentar::count();
        
        // Gunakan created_at untuk menghitung workout hari ini
        $workoutsToday = UserWorkout::whereDate('created_at', Carbon::today())->count();

        // Data terbaru (ambil 3 teratas)
        $recentUsers = User::orderBy('created_at', 'desc')->take(3)->get();
        $recentPrograms = Program::orderBy('created_at', 'desc')->take(3)->get();
        $recentGerakan = Gerakan::orderBy('created_at', 'desc')->take(3)->get(); 

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPrograms',
            'totalCommunities',
            'workoutsToday',
            'recentUsers',
            'recentPrograms',
            'recentGerakan'
        ));
    }
}