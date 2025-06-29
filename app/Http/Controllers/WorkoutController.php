<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\UserWorkout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Calender;
use App\Models\WorkoutLog;
use Illuminate\Support\Carbon;

class WorkoutController extends Controller
{
    /**
     * Display workout programs page
     */
    public function programs(Request $request)
    {
        try {
            // Get search and filter parameters
            $search = $request->get('search');
            $filter = $request->get('filter', 'all');
            
            // Start query with all programs
            $query = Program::query();
            
            // Apply search if provided
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_program', 'like', '%' . $search . '%')
                      ->orWhere('deskripsi_program', 'like', '%' . $search . '%');
                });
            }
            
            // Apply category filter
            if ($filter && $filter !== 'all') {
                if ($filter === 'body-weight') {
                    $query->where('kategori_program', 0);
                } elseif ($filter === 'tools-weight') {
                    $query->where('kategori_program', 1);
                }
            }
            
            // Get programs with pagination (optional)
            $programs = $query->orderBy('nama_program', 'asc')->get();
            
            // Get loaded program IDs for current user (if authenticated)
            $loadedProgramIds = [];
            if (Auth::check()) {
                $loadedProgramIds = UserWorkout::where('user_id', Auth::user()->id_nama)
                    ->pluck('program_id')
                    ->toArray();
            }
            
            return view('workout-programs', compact('programs', 'search', 'filter', 'loadedProgramIds'));
            
        } catch (\Exception $e) {
            Log::error('Error in programs method: ' . $e->getMessage());
            
            return view('workout-programs', [
                'programs' => collect(),
                'search' => $search ?? '',
                'filter' => $filter ?? 'all',
                'loadedProgramIds' => []
            ])->with('error', 'Failed to load workout programs. Please try again.');
        }
    }
    
    /**
     * Add program to user's workout load
     */
    public function addToWorkout(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to add programs to your workout.',
                    'redirect' => route('login.form'),
                    'error_code' => 'UNAUTHENTICATED'
                ], 401);
            }

            $validated = $request->validate([
                'program_id' => 'required|string|exists:programs,id_program'
            ]);

            $userId = Auth::user()->id_nama;
            $programId = $validated['program_id'];

            // Cek apakah sudah ada
            $existingWorkout = UserWorkout::where('user_id', $userId)
                ->where('program_id', $programId)
                ->first();

            if ($existingWorkout) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program already added to your workout load.',
                    'error_code' => 'ALREADY_EXISTS'
                ]);
            }

            // Tambahkan program ke workout user (hanya sebagai load/bookmark)
            $userWorkout = UserWorkout::create([
                'user_id' => $userId,
                'program_id' => $programId,
                'status' => 'scheduled'
            ]);

            $program = Program::find($programId);

            return response()->json([
                'success' => true,
                'message' => 'Program "' . $program->nama_program . '" has been added to your workout!',
                'data' => [
                    'workout_id' => $userWorkout->id,
                    'program_name' => $program->nama_program,
                    'program_id' => $program->id_program
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid program selected.',
                'errors' => $e->errors(),
                'error_code' => 'VALIDATION_FAILED'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the program.',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal Server Error',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Remove program from user's workout load
     */
    public function removeFromWorkout(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to manage your workout.'
                ], 401);
            }
            
            // Validate request
            $request->validate([
                'program_id' => 'required|string|exists:programs,id_program'
            ]);
            
            $userId = Auth::user()->id_nama;
            $programId = $request->program_id;
            
            // Find and delete the user workout entry
            $userWorkout = UserWorkout::where('user_id', $userId)
                ->where('program_id', $programId)
                ->first();
            
            if (!$userWorkout) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found in your workout load.'
                ]);
            }
            
            $program = Program::find($programId);
            $userWorkout->delete();
            
            Log::info('Program removed from workout', [
                'user_id' => $userId,
                'program_id' => $programId
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Program "' . ($program ? $program->nama_program : 'Unknown') . '" has been removed from your workout load.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error removing program from workout: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the program. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Get user's workout load
     */
    public function getWorkoutLoad()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to view your workout load.'
                ], 401);
            }
            
            $userId = Auth::user()->id_nama;
            
            // Get user's workout programs with program details
            $workoutLoad = UserWorkout::where('user_id', $userId)
                ->with('program')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $programs = $workoutLoad->map(function($workout) {
                return [
                    'id' => $workout->id,
                    'program_id' => $workout->program_id,
                    'program_name' => $workout->program->nama_program,
                    'program_description' => $workout->program->deskripsi_program,
                    'program_category' => $workout->program->kategori_program,
                    'program_image' => $workout->program->program_image_url,
                    'added_at' => $workout->created_at->format('Y-m-d H:i:s')
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $programs,
                'total' => $programs->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting workout load: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while loading your workout programs.'
            ], 500);
        }
    }

    public function programsList()
    {
        return redirect()->route('load'); // atau view yang sesuai
    }

    public function showProgram($id)
    {
        $program = Program::with('detailPrograms.gerakan')->findOrFail($id);
        $detailPrograms = $program->detailPrograms;
        
        return view('list', compact('program', 'detailPrograms'));
    }

    

    public function completeProgram($id)
    {
        $userId = Auth::user()->id_nama;
        $today = Carbon::now()->format('Y-m-d');

        // Simpan ke calenders (kalau belum ada)
        $calendar = Calender::firstOrCreate(
            ['tanggal_penuh' => $today],
            ['hari_calender' => now()->translatedFormat('l')]
        );

        // Simpan ke workout_logs
        WorkoutLog::create([
            'user_id' => $userId,
            'program_id' => $id,
            'tanggal_workout' => $today,
        ]);

        return redirect()->route('load.index')->with('success', 'Workout berhasil diselesaikan dan dicatat!');
    }

}