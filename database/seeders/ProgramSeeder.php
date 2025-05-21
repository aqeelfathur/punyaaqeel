<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array program body weight
        $bodyWeightPrograms = [
            [
                'nama_program' => 'Upper Body Calisthenics',
                'deskripsi_program' => 'Target arms, shoulders, chest, and back',
                'kategori_program' => false,
                'program_images' => 'programs/upper-body-calisthenics.jpg'
            ],
            [
                'nama_program' => 'Core Burner',
                'deskripsi_program' => 'Intense core workout for abs',
                'kategori_program' => false,
                'program_images' => 'programs/core-burner.jpg'
            ],
            [
                'nama_program' => 'Leg Destroyer',
                'deskripsi_program' => 'Bodyweight leg exercises',
                'kategori_program' => false,
                'program_images' => 'programs/leg-destroyer.jpg'
            ],
            [
                'nama_program' => 'Full Body Calisthenics',
                'deskripsi_program' => 'Complete body workout with no equipment',
                'kategori_program' => false,
                'program_images' => 'programs/full-body-calisthenics.jpg'
            ],
        ];

        // Array program tools weight
        $toolsWeightPrograms = [
            [
                'nama_program' => 'Dumbbell Upper Body',
                'deskripsi_program' => 'Upper body workout with dumbbells',
                'kategori_program' => True,
                'program_images' => 'programs/dumbbell-upper.jpg'
            ],
            [
                'nama_program' => 'Barbell Strength',
                'deskripsi_program' => 'Build strength with barbell exercises',
                'kategori_program' => True,
                'program_images' => 'programs/barbell-strength.jpg'
            ],
            [
                'nama_program' => 'Kettlebell Circuit',
                'deskripsi_program' => 'Full body kettlebell workout',
                'kategori_program' => True,
                'program_images' => 'programs/kettlebell-circuit.jpg'
            ],
            [
                'nama_program' => 'Resistance Band Workout',
                'deskripsi_program' => 'Home workout with resistance bands',
                'kategori_program' => True,
                'program_images' => 'programs/resistance-band.jpg'
            ],
        ];

        // Gabungkan semua program
        $allPrograms = array_merge($bodyWeightPrograms, $toolsWeightPrograms);

        try {
            // Masukkan program ke database
            foreach ($allPrograms as $program) {
                Program::create([
                    'id_program' => 'PRG' . Str::random(6),
                    'nama_program' => $program['nama_program'],
                    'deskripsi_program' => $program['deskripsi_program'],
                    'kategori_program' => $program['kategori_program'],
                    'program_images' => $program['program_images']
                ]);
            }
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error seeding programs: ' . $e->getMessage());
            dump($e->getMessage());
            throw $e;
        }
    }
}