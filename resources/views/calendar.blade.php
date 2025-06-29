@extends('layouts.main')

@section('title', 'FitTrack - Calendar')

@section('additional_css')
<link rel="stylesheet" href="{{ asset('css/stylescalendar.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<!-- Calendar Container -->
<div class="calendar-container">
    <h1 class="calendar-title">Calendar</h1>
    
    <div class="calendar-wrapper">
        <!-- Main Calendar -->
        <div class="calendar-main">
            <div class="calendar-header">
                <button class="month-nav prev-month" onclick="navigateMonth('prev')">&#8249;</button>
                <div class="month-display">{{ $currentDate->format('F Y') }}</div>
                <button class="month-nav next-month" onclick="navigateMonth('next')">&#8250;</button>
            </div>
            
            <!-- Calendar Days Header -->
            <div class="calendar-grid">
                <div class="calendar-day-header">Sun</div>
                <div class="calendar-day-header">Mon</div>
                <div class="calendar-day-header">Tue</div>
                <div class="calendar-day-header">Wed</div>
                <div class="calendar-day-header">Thu</div>
                <div class="calendar-day-header">Fri</div>
                <div class="calendar-day-header">Sat</div>
                
                <!-- Empty cells for days before month starts -->
                @for ($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="calendar-day empty-day">
                        <div class="day-number"></div>
                    </div>
                @endfor
                
                <!-- Days of the month -->
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDateFormatted = $currentDate->copy()->day($day)->format('Y-m-d');
                        $hasWorkout = isset($workoutDays[$day]);
                        $isToday = $currentDate->copy()->day($day)->isToday();
                        $isSelected = request('selected_date') == $currentDateFormatted;
                    @endphp
                    
                    <div class="calendar-day {{ $hasWorkout ? 'has-workout' : '' }} {{ $isToday ? 'today' : '' }} {{ $isSelected ? 'active' : '' }}" 
                         data-date="{{ $currentDateFormatted }}" 
                         onclick="selectDate('{{ $currentDateFormatted }}', {{ $day }})">
                        <div class="day-number">{{ $day }}</div>
                        @if($hasWorkout)
                            <div class="workout-indicator">{{ count($workoutDays[$day]) }}</div>
                        @endif
                    </div>
                @endfor
                
                <!-- Fill remaining empty cells -->
                @php
                    $totalCells = $firstDayOfWeek + $daysInMonth;
                    $remainingCells = (7 - ($totalCells % 7)) % 7;
                @endphp
                @for ($i = 0; $i < $remainingCells; $i++)
                    <div class="calendar-day empty-day">
                        <div class="day-number"></div>
                    </div>
                @endfor
            </div>
        </div>
        
        <!-- Workout Details -->
        <div class="workout-details">
            <div class="workout-date" id="selected-date">
                @if(request('selected_date'))
                    {{ \Carbon\Carbon::parse(request('selected_date'))->format('j F Y') }}
                @else
                    {{ \Carbon\Carbon::today()->format('j F Y') }}
                @endif
            </div>
            
            <div class="workout-list" id="workout-list">
                @php
                    $selectedDay = request('selected_date') ? \Carbon\Carbon::parse(request('selected_date'))->day : \Carbon\Carbon::today()->day;
                    $selectedWorkouts = $workoutDays[$selectedDay] ?? [];
                @endphp
                
                @if(count($selectedWorkouts) > 0)
                    @foreach($selectedWorkouts as $workout)
                        <div class="workout-item" data-workout-id="{{ $workout->id }}">
                            <div class="workout-title">{{ $workout->program->nama_program ?? 'Unknown Program' }}</div>
                            <div class="workout-description">{{ $workout->program->deskripsi_program ?? '' }}</div>
                            <div class="workout-actions">
                                @if($workout->status == 'scheduled')
                                    <button class="btn-complete" onclick="completeWorkout({{ $workout->id }})">Complete</button>
                                @else
                                    <span class="status-completed">✓ Completed</span>
                                @endif
                                <button class="btn-remove" onclick="removeWorkout({{ $workout->id }})">Remove</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-workouts">
                        <p>No workouts scheduled for this date.</p>
                        <button class="btn-add-workout" onclick="showAddWorkoutModal()">Add Workout</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Workout Modal -->
<div id="addWorkoutModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Workout</h3>
            <span class="close" onclick="closeAddWorkoutModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addWorkoutForm">
                <div class="form-group">
                    <label for="program_id">Select Program:</label>
                    <select id="program_id" name="program_id" required>
                        <option value="">Choose a program...</option>
                        <!-- Programs will be loaded via AJAX or passed from controller -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="workout_date">Date:</label>
                    <input type="date" id="workout_date" name="date" required readonly>
                </div>
                <div class="form-actions">
                    <button type="button" onclick="closeAddWorkoutModal()">Cancel</button>
                    <button type="submit">Add Workout</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Indicator -->
<div id="loading" class="loading" style="display: none;">
    <div class="loading-spinner"></div>
</div>

@endsection

@section('additional_js')
<script>
// Global variables
let currentMonth = {{ $month }};
let currentYear = {{ $year }};
let selectedDate = null;

// CSRF Token setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Navigate between months
function navigateMonth(direction) {
    const url = direction === 'prev' 
        ? "{{ route('calendar.index') }}?month={{ $prevMonth->month }}&year={{ $prevMonth->year }}"
        : "{{ route('calendar.index') }}?month={{ $nextMonth->month }}&year={{ $nextMonth->year }}";
    
    window.location.href = url;
}

// Select a date and load workouts
function selectDate(dateStr, day) {
    // Remove active class from all days
    document.querySelectorAll('.calendar-day').forEach(el => el.classList.remove('active'));
    
    // Add active class to selected day
    event.target.closest('.calendar-day').classList.add('active');
    
    selectedDate = dateStr;
    
    // Update selected date display
    const dateObj = new Date(dateStr);
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    document.getElementById('selected-date').textContent = dateObj.toLocaleDateString('en-US', options);
    
    // Load workouts for selected date
    loadWorkoutDetails(dateStr);
}

// Load workout details via AJAX
function loadWorkoutDetails(date) {
    showLoading();
    
    fetch(`{{ route('calendar.workout.details') }}?date=${date}`)
        .then(response => response.json())
        .then(data => {
            updateWorkoutList(data.workouts);
            hideLoading();
        })
        .catch(error => {
            console.error('Error loading workout details:', error);
            hideLoading();
        });
}

// Update workout list display
function updateWorkoutList(workouts) {
    const workoutList = document.getElementById('workout-list');
    
    if (workouts.length === 0) {
        workoutList.innerHTML = `
            <div class="no-workouts">
                <p>No workouts scheduled for this date.</p>
                <button class="btn-add-workout" onclick="showAddWorkoutModal()">Add Workout</button>
            </div>
        `;
    } else {
        let html = '';
        workouts.forEach(workout => {
            html += `
                <div class="workout-item" data-workout-id="${workout.id}">
                    <div class="workout-title">${workout.title}</div>
                    <div class="workout-description">${workout.description}</div>
                    <div class="workout-actions">
                        ${workout.status === 'scheduled' 
                            ? `<button class="btn-complete" onclick="completeWorkout(${workout.id})">Complete</button>`
                            : `<span class="status-completed">✓ Completed</span>`
                        }
                        <button class="btn-remove" onclick="removeWorkout(${workout.id})">Remove</button>
                    </div>
                </div>
            `;
        });
        workoutList.innerHTML = html;
    }
}

// Complete workout
function completeWorkout(workoutId) {
    if (!confirm('Mark this workout as completed?')) return;
    
    showLoading();
    
    fetch(`{{ route('calendar.workout.complete') }}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ workout_id: workoutId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh workout details
            if (selectedDate) {
                loadWorkoutDetails(selectedDate);
            }
            // Show success message
            showMessage('Workout completed successfully!', 'success');
        } else {
            showMessage(data.message || 'Error completing workout', 'error');
        }
        hideLoading();
    })
    .catch(error => {
        console.error('Error completing workout:', error);
        showMessage('Error completing workout', 'error');
        hideLoading();
    });
}

// Remove workout
function removeWorkout(workoutId) {
    if (!confirm('Are you sure you want to remove this workout?')) return;
    
    showLoading();
    
    fetch(`{{ route('calendar.workout.remove') }}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ workout_id: workoutId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh the page to update calendar
            window.location.reload();
        } else {
            showMessage(data.message || 'Error removing workout', 'error');
        }
        hideLoading();
    })
    .catch(error => {
        console.error('Error removing workout:', error);
        showMessage('Error removing workout', 'error');
        hideLoading();
    });
}

// Show add workout modal
function showAddWorkoutModal() {
    if (!selectedDate) {
        showMessage('Please select a date first', 'error');
        return;
    }
    
    document.getElementById('workout_date').value = selectedDate;
    document.getElementById('addWorkoutModal').style.display = 'block';
    
    // Load available programs
    loadPrograms();
}

// Close add workout modal
function closeAddWorkoutModal() {
    document.getElementById('addWorkoutModal').style.display = 'none';
    document.getElementById('addWorkoutForm').reset();
}

// Load available programs
function loadPrograms() {
    // This should be implemented based on your program structure
    // For now, using placeholder data
    const select = document.getElementById('program_id');
    select.innerHTML = '<option value="">Loading programs...</option>';
    
    // You might want to create a route to get available programs
    // fetch('/api/programs')...
}

// Handle add workout form submission
document.getElementById('addWorkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        date: formData.get('date'),
        program_id: formData.get('program_id')
    };
    
    showLoading();
    
    fetch(`{{ route('calendar.workout.add') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddWorkoutModal();
            // Refresh the page to update calendar
            window.location.reload();
        } else {
            showMessage(data.message || 'Error adding workout', 'error');
        }
        hideLoading();
    })
    .catch(error => {
        console.error('Error adding workout:', error);
        showMessage('Error adding workout', 'error');
        hideLoading();
    });
});

// Utility functions
function showLoading() {
    document.getElementById('loading').style.display = 'block';
}

function hideLoading() {
    document.getElementById('loading').style.display = 'none';
}

function showMessage(message, type) {
    // You can implement a toast notification system here
    alert(message); // Simple alert for now
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-select today's date if no date is selected
    const today = new Date().toISOString().split('T')[0];
    const todayElement = document.querySelector(`[data-date="${today}"]`);
    if (todayElement && !document.querySelector('.calendar-day.active')) {
        todayElement.click();
    }
});
</script>
@endsection