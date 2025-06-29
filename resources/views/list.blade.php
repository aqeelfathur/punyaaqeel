<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $program->nama_program ?? 'Workout' }} - FIT TRACK</title>
    <link rel="stylesheet" href="{{ asset('css/styleslist.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="workout-container">
        <!-- Header Section -->
        <div class="workout-header">
            <a href="{{ route('load') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="header-content">
                <h1>{{ $program->nama_program ?? 'Workout Movements' }}</h1>
                <div class="progress-info">
                    <span class="completed-count">0</span> / <span class="total-count">{{ $detailPrograms->count() }}</span> completed
                </div>
            </div>
            <div class="workout-stats">
                <div class="stat-item">
                    <i class="fas fa-dumbbell"></i>
                    <span>{{ $detailPrograms->count() }} Movements</span>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress-bar-container">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <span class="progress-text">0% Complete</span>
        </div>

        <!-- Workout Form -->
        <form method="POST" action="{{ route('programs.complete', $program->id_program) }}" id="workoutForm">
            @csrf
            <div class="movements-grid">
                @foreach($detailPrograms as $index => $detail)
                <div class="movement-card" data-movement="{{ $detail->gerakan->id_gerakan }}">
                    <div class="movement-image">
                        @php
                            // Default images based on movement name
                            $imageUrl = 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
                            
                            $movementName = strtolower($detail->gerakan->nama_gerakan);
                            if (str_contains($movementName, 'push')) {
                                $imageUrl = 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
                            } elseif (str_contains($movementName, 'crunch') || str_contains($movementName, 'sit')) {
                                $imageUrl = 'https://images.unsplash.com/photo-1594737626072-90dc274bc2dd?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
                            } elseif (str_contains($movementName, 'back') || str_contains($movementName, 'extension')) {
                                $imageUrl = 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
                            } elseif (str_contains($movementName, 'squat')) {
                                $imageUrl = 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
                            } elseif (str_contains($movementName, 'plank')) {
                                $imageUrl = 'https://images.unsplash.com/photo-1518611012118-696072aa579a?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
                            }
                            
                            // Determine difficulty based on repetitions
                            $difficulty = 'Beginner';
                            $difficultyClass = 'beginner';
                            if ($detail->jumlah_repetisi > 20) {
                                $difficulty = 'Advanced';
                                $difficultyClass = 'advanced';
                            } elseif ($detail->jumlah_repetisi > 10) {
                                $difficulty = 'Intermediate';
                                $difficultyClass = 'intermediate';
                            }
                        @endphp
                        
                        <img src="{{ $imageUrl }}" alt="{{ $detail->gerakan->nama_gerakan }}" loading="lazy">
                        <div class="movement-overlay">
                            <div class="check-icon">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="movement-info">
                        <div class="movement-details">
                            <h3>{{ $detail->gerakan->nama_gerakan }}</h3>
                            <div class="movement-meta">
                                <span class="reps">{{ $detail->jumlah_repetisi }} reps</span>
                                <span class="difficulty {{ $difficultyClass }}">{{ $difficulty }}</span>
                            </div>
                            <p class="movement-description">
                                @if($detail->gerakan->created_by)
                                    Created by: {{ $detail->gerakan->creator->username ?? 'Admin' }}
                                @else
                                    System Movement
                                @endif
                            </p>
                        </div>
                        <div class="movement-actions">
                            <div class="movement-number">
                                {{ $index + 1 }}
                            </div>
                            <label class="custom-checkbox">
                                <input type="checkbox" class="movement-checkbox" 
                                       name="completed_movements[]" 
                                       value="{{ $detail->id }}"
                                       id="chk-{{ $detail->gerakan->id_gerakan }}">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('programs.index') }}" class="pause-btn">
                    <i class="fas fa-pause"></i>
                    Pause & Save
                </a>
                <form action="{{ route('load.finish') }}" method="POST" id="finishWorkoutForm">
                    @csrf
                    <input type="hidden" name="program_id" value="{{ $program->id_program }}">
                    
                    <button type="submit" class="finish-btn" id="finishWorkout" disabled>
                        <i class="fas fa-trophy"></i>
                        Complete Workout
                    </button>
                </form>

            </div>
        </form>

        <!-- Program Info -->
        <div class="program-info">
            <div class="info-card">
                <h3><i class="fas fa-info-circle"></i> Program Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">Program:</span>
                        <span class="value">{{ $program->nama_program }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Total Movements:</span>
                        <span class="value">{{ $detailPrograms->count() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Total Repetitions:</span>
                        <span class="value">{{ $detailPrograms->sum('jumlah_repetisi') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Created:</span>
                        <span class="value">{{ $program->created_at ? $program->created_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Motivational Quote -->
        <div class="motivation-section">
            <div class="quote">
                <i class="fas fa-quote-left"></i>
                <p>"Success is the sum of small efforts repeated day in and day out."</p>
            </div>
        </div>
    </div>

    <!-- Simple JavaScript for checkbox functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.movement-checkbox');
            const finishBtn = document.getElementById('finishWorkout');
            const progressFill = document.getElementById('progressFill');
            const completedCount = document.querySelector('.completed-count');
            const progressText = document.querySelector('.progress-text');
            const totalCount = {{ $detailPrograms->count() }};

            function updateProgress() {
                const checkedCount = document.querySelectorAll('.movement-checkbox:checked').length;
                const percentage = (checkedCount / totalCount) * 100;

                // Update progress bar
                progressFill.style.width = percentage + '%';
                
                // Update counters
                completedCount.textContent = checkedCount;
                progressText.textContent = Math.round(percentage) + '% Complete';

                // Update finish button
                finishBtn.disabled = checkedCount !== totalCount;

                // Update card states
                checkboxes.forEach(checkbox => {
                    const card = checkbox.closest('.movement-card');
                    if (checkbox.checked) {
                        card.classList.add('completed');
                    } else {
                        card.classList.remove('completed');
                    }
                });
            }

            // Add event listeners to checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateProgress);
            });

            // Initial update
            updateProgress();

            // Form submission confirmation
            document.getElementById('workoutForm').addEventListener('submit', function(e) {
                const checkedCount = document.querySelectorAll('.movement-checkbox:checked').length;
                if (checkedCount === totalCount) {
                    const confirmed = confirm('Congratulations! You have completed all movements. Mark this workout as complete?');
                    if (!confirmed) {
                        e.preventDefault();
                    }
                } else {
                    e.preventDefault();
                    alert('Please complete all movements before finishing the workout.');
                }
            });
        });
    </script>
</body>
</html>