<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserWorkout;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
<<<<<<< HEAD
use App\Models\Calender;
=======
use App\Models\DetailProgram;
>>>>>>> 6457c1484a279be02bc84d1f9da73e3d858fc6dd

class LoadController extends Controller
{
    /**
     * Display user's workout load page
     */
    public function index(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return redirect()->route('login.form')->with('error', 'Please login to view your workout load.');
            }
            
            $userId = Auth::user()->id_nama;
            
            // Get search parameter
            $search = $request->get('search');
            
            // Get user's workout programs with program details
            $query = UserWorkout::where('user_id', $userId)
                ->with(['program' => function($q) {
                    $q->select('id_program', 'nama_program', 'deskripsi_program', 'kategori_program', 'program_images');
                }]);
            
            // Apply search if provided
            if ($search) {
                $query->whereHas('program', function($q) use ($search) {
                    $q->where('nama_program', 'like', '%' . $search . '%')
                      ->orWhere('deskripsi_program', 'like', '%' . $search . '%');
                });
            }
            
            // Get workout load ordered by creation date (newest first)
            $workoutLoad = $query->orderBy('created_at', 'desc')->get();
            
            // Transform data untuk view
            $programs = $workoutLoad->map(function($workout) {
                return (object) [
                    'workout_id' => $workout->id,
                    'id_program' => $workout->program->id_program,
                    'nama_program' => $workout->program->nama_program,
                    'deskripsi_program' => $workout->program->deskripsi_program,
                    'kategori_program' => $workout->program->kategori_program,
                    'program_image_url' => $workout->program->program_image_url ?? asset('images/default-workout.jpg'),
                    'added_at' => $workout->created_at
                ];
            });
            
            return view('load', compact('programs', 'search'));
            
        } catch (\Exception $e) {
            Log::error('Error in load index method: ' . $e->getMessage());
            
            return view('load', [
                'programs' => collect(),
                'search' => $search ?? ''
            ])->with('error', 'Failed to load your workout programs. Please try again.');
        }
    }
    
    /**
     * Start workout - redirect to list page with program details
     */
    public function startWorkout(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to start workout.'
                ], 401);
            }
            
            // Validate request
            $request->validate([
                'program_id' => 'required|exists:programs,id_program'
            ]);
            
            $userId = Auth::user()->id_nama;
            $programId = $request->program_id;
            
            // Verify user has this program in their load
            $userWorkout = UserWorkout::where('user_id', $userId)
                ->where('program_id', $programId)
                ->with('program')
                ->first();
            
            if (!$userWorkout) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found in your workout load.'
                ], 404);
            }
            
            // Log workout start
            Log::info('User started workout', [
                'user_id' => $userId,
                'program_id' => $programId,
                'program_name' => $userWorkout->program->nama_program
            ]);
            
            // Return success with redirect URL
            return response()->json([
                'success' => true,
                'message' => 'Starting workout: ' . $userWorkout->program->nama_program,
                'redirect_url' => route('programs.show', $programId)
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid program selected.',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error starting workout: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while starting the workout. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Remove program from user's workout load
     */
    public function removeFromLoad(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to manage your workout load.'
                ], 401);
            }
            
            // Validate request
            $request->validate([
                'workout_id' => 'required|integer'
            ]);
            
            $userId = Auth::user()->id_nama;
            $workoutId = $request->workout_id;
            
            // Find user workout and verify ownership
            $userWorkout = UserWorkout::where('id', $workoutId)
                ->where('user_id', $userId)
                ->with('program')
                ->first();
            
            if (!$userWorkout) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found in your workout load.'
                ], 404);
            }
            
            $programName = $userWorkout->program->nama_program;
            $userWorkout->delete();
            
            Log::info('Program removed from load', [
                'user_id' => $userId,
                'workout_id' => $workoutId,
                'program_name' => $programName
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Program "' . $programName . '" has been removed from your load.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error removing program from load: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the program. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Get total workout programs count for user
     */
    public function getLoadCount()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['count' => 0]);
            }
            
            $count = UserWorkout::where('user_id', Auth::user()->id_nama)->count();
            
            return response()->json(['count' => $count]);
            
        } catch (\Exception $e) {
            Log::error('Error getting load count: ' . $e->getMessage());
            return response()->json(['count' => 0]);
        }

    }
    /**
<<<<<<< HEAD
     * Show checklist gerakan untuk program tertentu di load user.
     */
    public function exercises($program_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form');
        }

        $userId = Auth::user()->id_nama;

        // Pastikan program ini ada di load user
        $exists = UserWorkout::where('user_id', $userId)
                            ->where('program_id', $program_id)
                            ->exists();

        if (! $exists) {
            abort(403, 'Program tidak ada di load Anda.');
        }

        // Ambil program + detail program + gerakannya
        $program = Program::with('detailPrograms.gerakan')
                        ->where('id_program', $program_id)
                        ->firstOrFail();

        $detailPrograms = $program->detailPrograms;

        return view('load.exercises', compact('program', 'detailPrograms'));
=======
 * Show checklist gerakan untuk program tertentu.
 */
public function exercises($program_id)
{
    // 1) Pastikan user login
    if (!Auth::check()) {
        return redirect()->route('login.form');
>>>>>>> 6457c1484a279be02bc84d1f9da73e3d858fc6dd
    }

    public function finishWorkout(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id_program',
        ]);

<<<<<<< HEAD
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId = Auth::user()->id_nama;
        $programId = $request->program_id;
        $tanggalHariIni = now()->format('Y-m-d');

        // 1. Cari atau buat entri kalender hari ini
        $calendar = Calender::firstOrCreate(
            ['tanggal_penuh' => $tanggalHariIni],
            [
                'hari_calender' => now()->format('l'), // Contoh: Monday
                'status_calender' => true,
            ]
        );

        // 2. Update user_workout untuk set calender_id dan update status
        $userWorkout = UserWorkout::where('user_id', $userId)
            ->where('program_id', $programId)
            ->first();

        if (!$userWorkout) {
            return redirect()->route('load.index')->with('error', 'Program tidak ditemukan di workout Anda.');
        }

        $userWorkout->update([
            'calender_id' => $calendar->id_calender,
            'status' => 'completed',
            'updated_at' => now(),
        ]);

        return redirect()->route('load.index')->with('success', 'Workout selesai dan dicatat di kalender!');
    }
=======
    // 2) Cek ownership di user_workouts
    if (! UserWorkout::where('user_id', $userId)
                     ->where('program_id', $program_id)
                     ->exists()) {
        abort(403, 'Program tidak ada di load Anda.');
    }

    // 3) Ambil program + detailPrograms (pivot detail_programs → gerakan)
    $program = Program::with('details.gerakan')
                      ->where('id_program', $program_id)
                      ->firstOrFail();

    $detailPrograms = $program->details; 

    // 4) RETURN ke VIEW 'list' (resources/views/list.blade.php)
    return view('list', compact('program', 'detailPrograms'));
}


    /**
 * Show checklist gerakan untuk program tertentu di load user.
 */


>>>>>>> 6457c1484a279be02bc84d1f9da73e3d858fc6dd
}