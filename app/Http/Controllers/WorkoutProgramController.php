<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\UserWorkout;
use App\Models\Calender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WorkoutProgramController extends Controller
{
    /**
     * Display a listing of workout programs.
     */
    public function index(Request $request)
{
    $search = $request->input('search');
    $filter = $request->input('filter', 'all');

    $programsQuery = Program::query();

    // Terapkan pencarian jika ada
    if ($search) {
        $programsQuery->where(function($query) use ($search) {
            $query->where('nama_program', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi_program', 'like', '%' . $search . '%');
        });
    }

    // Terapkan filter kategori
    if ($filter === 'body-weight') {
        $programsQuery->where('kategori_program', 0);
    } elseif ($filter === 'tools-weight') {
        $programsQuery->where('kategori_program', 1);
    }

    $programs = $programsQuery->get();

    return view('workout-programs', compact('programs', 'search', 'filter'));
}
    
    /**
     * Add program to user's workouts.
     */
    public function addToWorkout(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Debug log
            Log::info('Add to Workout request received', [
                'request' => $request->all(),
                'user' => Auth::check() ? Auth::id() : 'Not logged in'
            ]);
            
            // Validasi request
            $request->validate([
                'program_id' => 'required|exists:programs,id_program',
            ]);
            
            $programId = $request->input('program_id');
            $userId = Auth::id();
            $today = Carbon::today();
            
            // Debug log
            Log::info('Add to Workout - Processing', [
                'user_id' => $userId,
                'program_id' => $programId,
                'today' => $today->format('Y-m-d')
            ]);
            
            // Cari atau buat calender untuk hari ini
            $calender = Calender::firstOrCreate(
                [
                    'tanggal_calender' => (int)$today->format('d'),
                ],
                [
                    'hari_calender' => $today->format('l'),
                    'tahun_calender' => $today->year,
                    'status_calender' => 1, // Asumsikan 1 = aktif
                ]
            );
            
            Log::info('Calender Created/Found', [
                'calender_id' => $calender->id_calender,
                'calender_data' => $calender->toArray()
            ]);
            
            // Cek apakah program sudah ada di user_workouts untuk hari ini
            $existingWorkout = UserWorkout::where('user_id', $userId)
                                      ->where('program_id', $programId)
                                      ->where('calender_id', $calender->id_calender)
                                      ->first();
            
            if ($existingWorkout) {
                // Jika workout sudah ada
                Log::info('Workout already exists', [
                    'workout_id' => $existingWorkout->id
                ]);
                
                DB::commit();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Program sudah ada di workout Anda untuk hari ini'
                ]);
            }
            
            // Tambahkan workout baru
            $userWorkout = new UserWorkout();
            $userWorkout->user_id = $userId;
            $userWorkout->program_id = $programId;  // Tidak perlu konversi lagi karena tipe data sudah string
            $userWorkout->calender_id = $calender->id_calender;
            
            // Debug log sebelum save
            Log::info('Before saving UserWorkout', [
                'userWorkout' => [
                    'user_id' => $userWorkout->user_id,
                    'program_id' => $userWorkout->program_id,
                    'calender_id' => $userWorkout->calender_id
                ]
            ]);
            
            // Save dan tangkap hasilnya
            $saved = $userWorkout->save();
            
            // Debug log hasil save
            Log::info('After saving UserWorkout', [
                'saved' => $saved,
                'userWorkout' => $userWorkout->toArray()
            ]);
            
            if ($saved) {
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Program berhasil ditambahkan ke workout Anda'
                ]);
            } else {
                // Jika save gagal
                DB::rollBack();
                
                Log::error('Failed to save UserWorkout', [
                    'userWorkout' => $userWorkout->toArray()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan workout, silakan coba lagi'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error
            Log::error('Error in addToWorkout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}