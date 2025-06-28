<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gerakan;
use App\Models\User;

class GerakanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user pertama sebagai creator (atau buat user dummy jika belum ada)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'username' => 'Testing',
                'password' => bcrypt('password'),
                'email' => 'admin@example.com',
                'phone_number' => '081234567890',
                'is_admin' => true
            ]);
        }

        // Data gerakan yang akan di-seed
        $gerakans = [
            [
                'id_gerakan' => 'GRK001',
                'nama_gerakan' => 'Push Up',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK002',
                'nama_gerakan' => 'Sit Up',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK003',
                'nama_gerakan' => 'Squat',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK004',
                'nama_gerakan' => 'Plank',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK005',
                'nama_gerakan' => 'Burpee',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK006',
                'nama_gerakan' => 'Jumping Jack',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK007',
                'nama_gerakan' => 'Mountain Climber',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK008',
                'nama_gerakan' => 'Lunges',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK009',
                'nama_gerakan' => 'Pull Up',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK010',
                'nama_gerakan' => 'Deadlift',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK011',
                'nama_gerakan' => 'Bench Press',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK012',
                'nama_gerakan' => 'Shoulder Press',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK013',
                'nama_gerakan' => 'Dips',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK014',
                'nama_gerakan' => 'High Knees',
                'created_by' => $user->id_nama
            ],
            [
                'id_gerakan' => 'GRK015',
                'nama_gerakan' => 'Russian Twist',
                'created_by' => $user->id_nama
            ]
        ];

        // Insert data ke database
        foreach ($gerakans as $gerakan) {
            Gerakan::create($gerakan);
        }

        $this->command->info('Gerakan seeder completed! ' . count($gerakans) . ' gerakan created.');
    }
}