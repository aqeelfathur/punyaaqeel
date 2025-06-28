<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailProgram;
use App\Models\Program;
use App\Models\Gerakan;

class DetailProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua program dan gerakan yang sudah ada
        $programs = Program::all();
        $gerakans = Gerakan::all();

        if ($programs->isEmpty() || $gerakans->isEmpty()) {
            $this->command->error('Program atau Gerakan belum ada! Pastikan ProgramSeeder dan GerakanSeeder sudah dijalankan.');
            return;
        }

        // Mapping gerakan berdasarkan kategori program
        $bodyWeightGerakans = ['GRK001', 'GRK002', 'GRK003', 'GRK004', 'GRK005', 'GRK006', 'GRK007', 'GRK008', 'GRK009', 'GRK014', 'GRK015']; // Push Up, Sit Up, Squat, Plank, Burpee, Jumping Jack, Mountain Climber, Lunges, Pull Up, High Knees, Russian Twist
        $toolsWeightGerakans = ['GRK010', 'GRK011', 'GRK012', 'GRK013', 'GRK003', 'GRK008']; // Deadlift, Bench Press, Shoulder Press, Dips, Squat, Lunges

        // Detail program untuk setiap program
        $programDetails = [
            // Body Weight Programs (kategori_program = false)
            'Upper Body Calisthenics' => [
                ['id_gerakan' => 'GRK001', 'jumlah_repetisi' => 12], // Push Up
                ['id_gerakan' => 'GRK009', 'jumlah_repetisi' => 8],  // Pull Up
                ['id_gerakan' => 'GRK013', 'jumlah_repetisi' => 10], // Dips
                ['id_gerakan' => 'GRK004', 'jumlah_repetisi' => 30], // Plank (detik)
            ],
            'Core Burner' => [
                ['id_gerakan' => 'GRK002', 'jumlah_repetisi' => 20], // Sit Up
                ['id_gerakan' => 'GRK004', 'jumlah_repetisi' => 45], // Plank (detik)
                ['id_gerakan' => 'GRK015', 'jumlah_repetisi' => 15], // Russian Twist
                ['id_gerakan' => 'GRK007', 'jumlah_repetisi' => 20], // Mountain Climber
            ],
            'Leg Destroyer' => [
                ['id_gerakan' => 'GRK003', 'jumlah_repetisi' => 15], // Squat
                ['id_gerakan' => 'GRK008', 'jumlah_repetisi' => 12], // Lunges
                ['id_gerakan' => 'GRK014', 'jumlah_repetisi' => 30], // High Knees
                ['id_gerakan' => 'GRK005', 'jumlah_repetisi' => 8],  // Burpee
            ],
            'Full Body Calisthenics' => [
                ['id_gerakan' => 'GRK005', 'jumlah_repetisi' => 10], // Burpee
                ['id_gerakan' => 'GRK001', 'jumlah_repetisi' => 15], // Push Up
                ['id_gerakan' => 'GRK003', 'jumlah_repetisi' => 20], // Squat
                ['id_gerakan' => 'GRK006', 'jumlah_repetisi' => 25], // Jumping Jack
                ['id_gerakan' => 'GRK007', 'jumlah_repetisi' => 15], // Mountain Climber
            ],
            
            // Tools Weight Programs (kategori_program = true)
            'Dumbbell Upper Body' => [
                ['id_gerakan' => 'GRK011', 'jumlah_repetisi' => 12], // Bench Press
                ['id_gerakan' => 'GRK012', 'jumlah_repetisi' => 10], // Shoulder Press
                ['id_gerakan' => 'GRK013', 'jumlah_repetisi' => 12], // Dips
                ['id_gerakan' => 'GRK001', 'jumlah_repetisi' => 15], // Push Up
            ],
            'Barbell Strength' => [
                ['id_gerakan' => 'GRK010', 'jumlah_repetisi' => 8],  // Deadlift
                ['id_gerakan' => 'GRK011', 'jumlah_repetisi' => 10], // Bench Press
                ['id_gerakan' => 'GRK003', 'jumlah_repetisi' => 12], // Squat
                ['id_gerakan' => 'GRK012', 'jumlah_repetisi' => 8],  // Shoulder Press
            ],
            'Kettlebell Circuit' => [
                ['id_gerakan' => 'GRK010', 'jumlah_repetisi' => 10], // Deadlift
                ['id_gerakan' => 'GRK003', 'jumlah_repetisi' => 15], // Squat
                ['id_gerakan' => 'GRK008', 'jumlah_repetisi' => 12], // Lunges
                ['id_gerakan' => 'GRK015', 'jumlah_repetisi' => 20], // Russian Twist
            ],
            'Resistance Band Workout' => [
                ['id_gerakan' => 'GRK012', 'jumlah_repetisi' => 12], // Shoulder Press
                ['id_gerakan' => 'GRK003', 'jumlah_repetisi' => 15], // Squat
                ['id_gerakan' => 'GRK008', 'jumlah_repetisi' => 10], // Lunges
                ['id_gerakan' => 'GRK001', 'jumlah_repetisi' => 12], // Push Up
            ],
        ];

        try {
            $totalDetails = 0;

            foreach ($programs as $program) {
                if (isset($programDetails[$program->nama_program])) {
                    $details = $programDetails[$program->nama_program];
                    
                    foreach ($details as $detail) {
                        // Pastikan gerakan ada di database
                        $gerakan = $gerakans->where('id_gerakan', $detail['id_gerakan'])->first();
                        
                        if ($gerakan) {
                            DetailProgram::create([
                                'id_program' => $program->id_program,
                                'id_gerakan' => $detail['id_gerakan'],
                                'jumlah_repetisi' => $detail['jumlah_repetisi']
                            ]);
                            $totalDetails++;
                        } else {
                            $this->command->warn("Gerakan {$detail['id_gerakan']} tidak ditemukan untuk program {$program->nama_program}");
                        }
                    }
                } else {
                    $this->command->warn("Detail tidak ditemukan untuk program: {$program->nama_program}");
                }
            }

            $this->command->info("DetailProgram seeder completed! {$totalDetails} detail program created.");

        } catch (\Exception $e) {
            \Log::error('Error seeding detail programs: ' . $e->getMessage());
            $this->command->error('Error: ' . $e->getMessage());
            throw $e;
        }
    }
}