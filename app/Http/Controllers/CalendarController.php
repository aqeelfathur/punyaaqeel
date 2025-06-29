<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calender;
use App\Models\UserWorkout;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Display the calendar page
     */
    public function index(Request $request)
    {
        // Ambil bulan dan tahun dari request, default ke bulan/tahun sekarang
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        // Validasi input
        $month = max(1, min(12, (int)$month));
        $year = max(2020, min(2030, (int)$year));
        
        // Buat objek Carbon untuk bulan yang dipilih
        $currentDate = Carbon::createFromDate($year, $month, 1);
        
        // Ambil data kalender untuk bulan ini
        $calendarData = $this->buildCalendarData($year, $month);
        
        // Ambil workout untuk user yang sedang login
        $userWorkouts = $this->getUserWorkouts($year, $month);
        
        // Buat array untuk menyimpan data hari dengan workout
        $workoutDays = [];
        foreach ($userWorkouts as $workout) {
            $date = $workout->calender->tanggal_calender;
            if (!isset($workoutDays[$date])) {
                $workoutDays[$date] = [];
            }
            $workoutDays[$date][] = $workout;
        }
        
        // Data untuk navigasi bulan
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
        
        // Hari pertama bulan (untuk menentukan posisi awal)
        $firstDayOfWeek = $currentDate->dayOfWeek; // 0=Sunday, 1=Monday, etc
        
        // Hari dalam bulan
        $daysInMonth = $currentDate->daysInMonth;
        
        return view('calendar', compact(
            'currentDate',
            'calendarData',
            'workoutDays',
            'month',
            'year',
            'prevMonth',
            'nextMonth',
            'firstDayOfWeek',
            'daysInMonth'
        ));
    }
    
    /**
     * Build calendar data for a specific month
     */
    private function buildCalendarData($year, $month)
    {
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        // Ambil semua hari dalam bulan ini
        $calendarDays = [];
        for ($day = 1; $day <= $endDate->day; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $calendarDays[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $day,
                'day_name' => $date->format('l'),
                'is_today' => $date->isToday(),
                'is_weekend' => $date->isWeekend()
            ];
        }
        
        return $calendarDays;
    }
    
    /**
     * Get user workouts for a specific month
     */
    private function getUserWorkouts($year, $month)
    {
        return UserWorkout::with(['calender', 'program'])
            ->where('user_id', Auth::id())
            ->whereHas('calender', function ($query) use ($year, $month) {
                $query->where('tahun_calender', $year)
                      ->whereBetween('tanggal_calender', [1, 31]);
            })
            ->get();
    }
    
    /**
     * Get workout details for a specific date
     */
    public function getWorkoutDetails(Request $request)
    {
        $date = $request->get('date');
        
        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }
        
        // Parse tanggal
        $carbonDate = Carbon::parse($date);
        $day = $carbonDate->day;
        $month = $carbonDate->month;
        $year = $carbonDate->year;
        
        // Cari data kalender untuk tanggal tersebut
        $calender = Calender::where('tanggal_calender', $day)
                           ->where('tahun_calender', $year)
                           ->first();
        
        if (!$calender) {
            return response()->json([
                'date' => $carbonDate->format('j F Y'),
                'workouts' => []
            ]);
        }
        
        // Ambil workout untuk user dan tanggal tersebut
        $userWorkouts = UserWorkout::with('program')
            ->where('user_id', Auth::id())
            ->where('calender_id', $calender->id_calender)
            ->get();
        
        $workouts = $userWorkouts->map(function ($workout) {
            return [
                'id' => $workout->id,
                'title' => $workout->program->nama_program ?? 'Unknown Program',
                'description' => $workout->program->deskripsi_program ?? '',
                'status' => $workout->status,
                'completed_at' => $workout->completed_at
            ];
        });
        
        return response()->json([
            'date' => $carbonDate->format('j F Y'),
            'workouts' => $workouts
        ]);
    }
    
    /**
     * Add workout to calendar
     */
    public function addWorkout(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'program_id' => 'required|exists:programs,id_program'
        ]);
        
        $date = Carbon::parse($request->date);
        
        // Cari atau buat data kalender
        $calender = Calender::firstOrCreate([
            'tanggal_calender' => $date->day,
            'tahun_calender' => $date->year,
            'hari_calender' => $date->format('l')
        ], [
            'status_calender' => true
        ]);
        
        // Cek apakah workout sudah ada
        $existingWorkout = UserWorkout::where('user_id', Auth::id())
            ->where('calender_id', $calender->id_calender)
            ->where('program_id', $request->program_id)
            ->first();
        
        if ($existingWorkout) {
            return response()->json([
                'success' => false,
                'message' => 'Workout already exists for this date'
            ], 400);
        }
        
        // Buat workout baru
        $userWorkout = UserWorkout::create([
            'user_id' => Auth::id(),
            'program_id' => $request->program_id,
            'calender_id' => $calender->id_calender,
            'status' => 'scheduled'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Workout added successfully',
            'workout' => $userWorkout->load('program')
        ]);
    }
    
    /**
     * Remove workout from calendar
     */
    public function removeWorkout(Request $request)
    {
        $request->validate([
            'workout_id' => 'required|exists:user_workouts,id'
        ]);
        
        $workout = UserWorkout::where('id', $request->workout_id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$workout) {
            return response()->json([
                'success' => false,
                'message' => 'Workout not found'
            ], 404);
        }
        
        $workout->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Workout removed successfully'
        ]);
    }
    
    /**
     * Complete workout
     */
    public function completeWorkout(Request $request)
    {
        $request->validate([
            'workout_id' => 'required|exists:user_workouts,id'
        ]);
        
        $workout = UserWorkout::where('id', $request->workout_id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$workout) {
            return response()->json([
                'success' => false,
                'message' => 'Workout not found'
            ], 404);
        }
        
        $workout->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Workout completed successfully',
            'workout' => $workout
        ]);
    }
    
    /**
     * Navigate to previous month
     */
    public function previousMonth(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $date = Carbon::createFromDate($year, $month, 1)->subMonth();
        
        return redirect()->route('calendar', [
            'month' => $date->month,
            'year' => $date->year
        ]);
    }
    
    /**
     * Navigate to next month
     */
    public function nextMonth(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $date = Carbon::createFromDate($year, $month, 1)->addMonth();
        
        return redirect()->route('calendar', [
            'month' => $date->month,
            'year' => $date->year
        ]);
    }
    
    /**
     * Get calendar data for AJAX requests
     */
    public function getCalendarData(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $calendarData = $this->buildCalendarData($year, $month);
        $userWorkouts = $this->getUserWorkouts($year, $month);
        
        // Format data untuk response
        $workoutDays = [];
        foreach ($userWorkouts as $workout) {
            $date = $workout->calender->tanggal_calender;
            if (!isset($workoutDays[$date])) {
                $workoutDays[$date] = [];
            }
            $workoutDays[$date][] = [
                'id' => $workout->id,
                'title' => $workout->program->nama_program ?? 'Unknown Program',
                'description' => $workout->program->deskripsi_program ?? '',
                'status' => $workout->status
            ];
        }
        
        return response()->json([
            'calendar_data' => $calendarData,
            'workout_days' => $workoutDays,
            'month' => $month,
            'year' => $year,
            'month_name' => Carbon::createFromDate($year, $month, 1)->format('F Y')
        ]);
    }
}