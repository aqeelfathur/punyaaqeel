@extends('layouts.main')

@section('title', 'FitTrack - Calendar')

@section('additional_css')
<link rel="stylesheet" href="{{ asset('css/stylescalendar.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="calendar-page">
    <div class="calendar-container">
        <h1 class="calendar-title">Workout Calendar</h1>
        
        <div class="calendar-wrapper">
            <!-- Main Calendar -->
            <div class="calendar-main">
                <div class="calendar-header">
                    <div class="month-display">
                        <button class="month-nav" id="prevMonth">‹</button>
                        <span id="currentMonth">January 2025</span>
                        <button class="month-nav" id="nextMonth">›</button>
                    </div>
                </div>
                
                <div class="calendar-grid">
                    <!-- Day Headers -->
                    <div class="calendar-day-header">Sun</div>
                    <div class="calendar-day-header">Mon</div>
                    <div class="calendar-day-header">Tue</div>
                    <div class="calendar-day-header">Wed</div>
                    <div class="calendar-day-header">Thu</div>
                    <div class="calendar-day-header">Fri</div>
                    <div class="calendar-day-header">Sat</div>
                    
                    <!-- Calendar Days will be populated by JavaScript -->
                    <div id="calendarDays"></div>
                </div>
            </div>
            
            <!-- Workout Details Panel -->
            <div class="workout-details">
                <div class="workout-date" id="selectedDate">Select a date</div>
                
                <div id="workoutContent">
                    <div class="no-workouts">
                        <p>No date selected</p>
                        <p>Click on a date to view workouts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading" style="display: none;">
    <div class="loading-spinner"></div>
</div>

@endsection

@section('scripts')
<script>
class WorkoutCalendar {
    constructor() {
        this.currentDate = new Date();
        this.selectedDate = null;
        this.workoutData = {};
        this.programs = [];
        
        this.init();
    }
    
    init() {
        this.loadPrograms();
        this.renderCalendar();
        this.bindEvents();
        this.setupCSRF();
    }
    
    setupCSRF() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    }
    
    async loadPrograms() {
        try {
            const response = await fetch('/api/programs');
            this.programs = await response.json();
            this.populateProgramSelect();
        } catch (error) {
            console.error('Error loading programs:', error);
        }
    }
    
    populateProgramSelect() {
        const select = document.getElementById('programSelect');
        select.innerHTML = '<option value="">Select a program</option>';
        
        this.programs.forEach(program => {
            const option = document.createElement('option');
            option.value = program.id_program;
            option.textContent = program.nama_program;
            select.appendChild(option);
        });
    }
    
    async loadWorkoutData() {
        const month = this.currentDate.getMonth() + 1;
        const year = this.currentDate.getFullYear();
        
        this.showLoading();
        
        try {
            const response = await fetch(`/workout/calendar/data?month=${month}&year=${year}`);
            const data = await response.json();
            
            if (data.success) {
                this.workoutData = {};
                data.data.forEach(day => {
                    this.workoutData[day.date] = day;
                });
                this.renderCalendar();
            }
        } catch (error) {
            console.error('Error loading workout data:', error);
        } finally {
            this.hideLoading();
        }
    }
    
    renderCalendar() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        // Update month display
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
        
        // Clear previous calendar
        const calendarDays = document.getElementById('calendarDays');
        calendarDays.innerHTML = '';
        
        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        // Add empty cells for days before month starts
        for (let i = 0; i < firstDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day empty-day';
            calendarDays.appendChild(emptyDay);
        }
        
        // Add days of month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = this.createDayElement(day, month, year);
            calendarDays.appendChild(dayElement);
        }
        
        this.loadWorkoutData();
    }
    
    createDayElement(day, month, year) {
        const dayElement = document.createElement('div');
        dayElement.className = 'calendar-day';
        
        const dayNumber = document.createElement('div');
        dayNumber.className = 'day-number';
        dayNumber.textContent = day;
        dayElement.appendChild(dayNumber);
        
        // Check if it's today
        const today = new Date();
        if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
            dayElement.classList.add('today');
        }
        
        // Format date for data lookup
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        // Check if has workout
        if (this.workoutData[dateStr]) {
            dayElement.classList.add('has-workout');
            
            const indicator = document.createElement('div');
            indicator.className = 'workout-indicator';
            indicator.textContent = this.workoutData[dateStr].count;
            dayElement.appendChild(indicator);
        }
        
        // Add click event
        dayElement.addEventListener('click', () => {
            this.selectDate(dateStr, dayElement);
        });
        
        return dayElement;
    }
    
    async selectDate(dateStr, dayElement) {
        // Remove previous active class
        document.querySelectorAll('.calendar-day.active').forEach(day => {
            day.classList.remove('active');
        });
        
        // Add active class to selected day
        dayElement.classList.add('active');
        
        this.selectedDate = dateStr;
        this.updateSelectedDateDisplay();
        
        // Load workout details for selected date
        await this.loadWorkoutDetails(dateStr);
    }
    
    updateSelectedDateDisplay() {
        const date = new Date(this.selectedDate);
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        document.getElementById('selectedDate').textContent = date.toLocaleDateString('en-US', options);
    }
    
    async loadWorkoutDetails(dateStr) {
        this.showLoading();
        
        try {
            const response = await fetch(`/workout/calendar/date?date=${dateStr}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderWorkoutDetails(data.workouts);
            }
        } catch (error) {
            console.error('Error loading workout details:', error);
        } finally {
            this.hideLoading();
        }
    }
    
    renderWorkoutDetails(workouts) {
        const content = document.getElementById('workoutContent');
        
        if (workouts.length === 0) {
            content.innerHTML = `
                <div class="no-workouts">
                    <p>No workouts on this date</p>
                    
                </div>
            `;
            return;
        }
        
        let html = '<div class="workout-list">';
        
        workouts.forEach(workout => {
            html += `
                <div class="workout-item">
                    <div class="workout-title">${workout.program_name}</div>
                    <div class="workout-description">Time: ${workout.created_at}</div>
                    
                </div>
            `;
        });
        
        html += `
            </div>
            <div style="margin-top: 20px;">
                
            </div>
        `;
        
        content.innerHTML = html;
    }
    
    showAddWorkoutModal() {
        if (this.selectedDate) {
            document.getElementById('workoutDate').value = this.selectedDate;
        }
        document.getElementById('workoutModal').style.display = 'flex';
    }
    
    hideAddWorkoutModal() {
        document.getElementById('workoutModal').style.display = 'none';
        document.getElementById('workoutForm').reset();
    }
    
    async addWorkout(formData) {
        this.showLoading();
        
        try {
            const response = await fetch('/workout/log', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    program_id: formData.get('program_id'),
                    tanggal_workout: formData.get('tanggal_workout')
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.hideAddWorkoutModal();
                this.loadWorkoutData();
                if (this.selectedDate) {
                    this.loadWorkoutDetails(this.selectedDate);
                }
            } else {
                alert('Error adding workout: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error adding workout:', error);
            alert('Error adding workout');
        } finally {
            this.hideLoading();
        }
    }
    
    async removeWorkout(workoutId) {
        if (!confirm('Are you sure you want to remove this workout?')) {
            return;
        }
        
        this.showLoading();
        
        try {
            const response = await fetch(`/workout/log/${workoutId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.loadWorkoutData();
                if (this.selectedDate) {
                    this.loadWorkoutDetails(this.selectedDate);
                }
            } else {
                alert('Error removing workout: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error removing workout:', error);
            alert('Error removing workout');
        } finally {
            this.hideLoading();
        }
    }
    
    bindEvents() {
        // Month navigation
        document.getElementById('prevMonth').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.renderCalendar();
        });
        
        document.getElementById('nextMonth').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.renderCalendar();
        });
        
        // Modal events
        document.getElementById('closeModal').addEventListener('click', () => {
            this.hideAddWorkoutModal();
        });
        
        document.getElementById('cancelWorkout').addEventListener('click', () => {
            this.hideAddWorkoutModal();
        });
        
        // Form submission
        document.getElementById('workoutForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            this.addWorkout(formData);
        });
        
        // Close modal when clicking outside
        document.getElementById('workoutModal').addEventListener('click', (e) => {
            if (e.target.id === 'workoutModal') {
                this.hideAddWorkoutModal();
            }
        });
    }
    
    showLoading() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    }
    
    hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }
}

// Initialize calendar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.workoutCalendar = new WorkoutCalendar();
});
</script>
@endsection