<?php

namespace App\Http\Controllers;

use App\Models\WorkoutLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WorkoutCalendarController extends Controller
{
    /**
     * Menampilkan halaman kalender workout
     */
    public function index()
    {
        return view('calendar');
    }

    /**
     * Mendapatkan data workout untuk kalender dalam format JSON
     */
    public function getWorkoutData(Request $request)
    {
        $userId = auth()->id();
        
        // Ambil parameter bulan dan tahun dari request
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        // Query workout logs berdasarkan user dan periode
        $workoutLogs = WorkoutLog::with(['program'])
            ->where('user_id', $userId)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'asc')
            ->get();

        // Format data untuk kalender
        $calendarData = $this->formatForCalendar($workoutLogs);
        
        return response()->json([
            'success' => true,
            'data' => $calendarData,
            'total' => $workoutLogs->count()
        ]);
    }

    /**
     * Mendapatkan data workout untuk rentang tanggal tertentu
     */
    public function getWorkoutByDateRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $userId = auth()->id();
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $workoutLogs = WorkoutLog::with(['program'])
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $this->formatForCalendar($workoutLogs),
            'total' => $workoutLogs->count(),
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Mendapatkan detail workout untuk tanggal tertentu
     */
    public function getWorkoutByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $userId = auth()->id();
        $date = Carbon::parse($request->date);

        $workoutLogs = WorkoutLog::with(['program'])
            ->where('user_id', $userId)
            ->whereDate('created_at', $date->format('Y-m-d'))
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'date' => $date->format('Y-m-d'),
            'workouts' => $workoutLogs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'program_name' => $log->program->nama_program ?? 'Program Tidak Diketahui',
                    'tanggal_workout' => $log->tanggal_workout,
                    'created_at' => $log->created_at->format('H:i'),
                    'program_id' => $log->program_id
                ];
            }),
            'total' => $workoutLogs->count()
        ]);
    }

    /**
     * Mendapatkan statistik workout bulanan
     */
    public function getMonthlyStats(Request $request)
    {
        $userId = auth()->id();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $workoutLogs = WorkoutLog::where('user_id', $userId)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        // Hitung statistik
        $totalWorkouts = $workoutLogs->count();
        $uniqueDays = $workoutLogs->groupBy(function ($log) {
            return $log->created_at->format('Y-m-d');
        })->count();

        $programStats = $workoutLogs->with('program')
            ->get()
            ->groupBy('program_id')
            ->map(function ($group) {
                return [
                    'program_name' => $group->first()->program->nama_program ?? 'Tidak Diketahui',
                    'count' => $group->count()
                ];
            });

        return response()->json([
            'success' => true,
            'month' => $month,
            'year' => $year,
            'stats' => [
                'total_workouts' => $totalWorkouts,
                'active_days' => $uniqueDays,
                'programs' => $programStats->values()
            ]
        ]);
    }

    /**
     * Mendapatkan data streak workout (hari berturut-turut)
     */
    public function getWorkoutStreak()
    {
        $userId = auth()->id();
        
        // Ambil semua tanggal workout yang unique
        $workoutDates = WorkoutLog::where('user_id', $userId)
            ->selectRaw('DATE(created_at) as workout_date')
            ->distinct()
            ->orderBy('workout_date', 'desc')
            ->pluck('workout_date')
            ->map(function ($date) {
                return Carbon::parse($date);
            });

        if ($workoutDates->isEmpty()) {
            return response()->json([
                'success' => true,
                'current_streak' => 0,
                'longest_streak' => 0
            ]);
        }

        $currentStreak = $this->calculateCurrentStreak($workoutDates);
        $longestStreak = $this->calculateLongestStreak($workoutDates);

        return response()->json([
            'success' => true,
            'current_streak' => $currentStreak,
            'longest_streak' => $longestStreak
        ]);
    }

    /**
     * Format data untuk kalender
     */
    private function formatForCalendar($workoutLogs)
    {
        return $workoutLogs->groupBy(function ($log) {
            return $log->created_at->format('Y-m-d');
        })->map(function ($dayLogs, $date) {
            return [
                'date' => $date,
                'count' => $dayLogs->count(),
                'workouts' => $dayLogs->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'program_name' => $log->program->nama_program ?? 'Program Tidak Diketahui',
                        'time' => $log->created_at->format('H:i')
                    ];
                })
            ];
        })->values();
    }

    /**
     * Hitung current streak
     */
    private function calculateCurrentStreak($workoutDates)
    {
        if ($workoutDates->isEmpty()) {
            return 0;
        }

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        
        // Jika tidak workout hari ini atau kemarin, streak = 0
        if (!$workoutDates->contains(function ($date) use ($today, $yesterday) {
            return $date->isSameDay($today) || $date->isSameDay($yesterday);
        })) {
            return 0;
        }

        $streak = 0;
        $currentDate = $workoutDates->first()->copy();

        foreach ($workoutDates as $workoutDate) {
            if ($workoutDate->isSameDay($currentDate)) {
                $streak++;
                $currentDate->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Hitung longest streak
     */
    private function calculateLongestStreak($workoutDates)
    {
        if ($workoutDates->isEmpty()) {
            return 0;
        }

        $longestStreak = 1;
        $currentStreak = 1;

        for ($i = 1; $i < $workoutDates->count(); $i++) {
            $currentDate = $workoutDates[$i];
            $previousDate = $workoutDates[$i - 1];

            if ($previousDate->diffInDays($currentDate) == 1) {
                $currentStreak++;
                $longestStreak = max($longestStreak, $currentStreak);
            } else {
                $currentStreak = 1;
            }
        }

        return $longestStreak;
    }
}