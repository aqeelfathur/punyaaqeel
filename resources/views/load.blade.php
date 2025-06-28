<!-- resources/views/load.blade.php -->

@extends('layouts.main')

@section('title', 'My Workout Load - FitTrack')

@section('additional_css')
<link rel="stylesheet" href="{{ asset('css/stylesload.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

@push('body_class', 'load-page')

@section('content')
    <main>
        <section id="workout-header" class="workout-header">
            <div class="container">
                <h1 class="section-title">My Workout Load</h1>
                
                <div class="search-and-filter">
                    <div class="search-bar">
                        <form action="{{ route('load') }}" method="GET">
                            <input type="text" name="search" placeholder="Search your programs..." value="{{ $search ?? '' }}">
                            <button type="submit"><span class="material-icons">search</span></button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
      
        <section class="workout-section">
            <div class="container">
                <!-- Toast Container -->
                <div id="toast-notification" class="toast-notification">
                    <div class="toast-content">
                        <span id="toast-message"></span>
                    </div>
                </div>
                
                @if(session('error'))
                    <div class="alert-error">
                        {{ session('error') }}
                    </div>
                @endif
                
                <!-- Load Info -->
                <div class="load-info">
                    <h4><span class="material-icons" style="vertical-align: middle; margin-right: 8px;">fitness_center</span>Your Workout Load</h4>
                    <p>Programs you've added to your load are ready to start. Click "Start Workout" to begin your fitness journey!</p>
                </div>
                
                <div class="workout-cards">
                    @if(isset($programs) && $programs->count() > 0)
                        @foreach($programs as $program)
                            <div class="card" data-category="{{ $program->kategori_program }}">
                                <div class="program-image">
                                    <img src="{{ $program->program_image_url ?? asset('images/default-workout.jpg') }}" 
                                         alt="{{ $program->nama_program }}">
                                </div>
                                <div class="card-content">
                                    <h3>{{ $program->nama_program }}</h3>
                                    <p>{{ $program->deskripsi_program }}</p>
                                    <p class="added-date">
                                        <span class="material-icons" style="font-size: 14px;">schedule</span>
                                        Added: {{ $program->added_at->format('M d, Y') }}
                                    </p>
                                    
                                    <div class="card-actions">
                                        <button class="workout-btn" 
                                                data-program-id="{{ $program->id_program }}">
                                            <span class="material-icons" style="font-size: 16px;">play_arrow</span>
                                            Start Workout
                                        </button>
                                        <button class="remove-btn" 
                                                data-workout-id="{{ $program->workout_id }}"
                                                data-program-name="{{ $program->nama_program }}">
                                            <span class="material-icons" style="font-size: 14px;">delete</span>
                                            Remove from Load
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-load">
                            <span class="material-icons">fitness_center</span>
                            <h3>No Programs in Your Load</h3>
                            <p>You haven't added any workout programs to your load yet.<br>Browse our programs and add some to get started!</p>
                            <a href="{{ route('workout.programs') }}" class="browse-programs-btn">
                                <span class="material-icons">explore</span>
                                Browse Workout Programs
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <!-- Confirmation Modal -->
    <div id="confirm-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Remove Program</h3>
                <button class="modal-close" onclick="closeConfirmModal()">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-icon">
                    <span class="material-icons">warning</span>
                </div>
                <p id="confirm-message"></p>
            </div>
            <div class="modal-footer">
                <button id="confirm-cancel" class="btn btn-secondary">Cancel</button>
                <button id="confirm-remove" class="btn btn-danger">Remove</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Add body class for load page
    document.body.classList.add('load-page');
    
    document.addEventListener('DOMContentLoaded', function() {
        const confirmModal = document.getElementById('confirm-modal');
        const confirmMessage = document.getElementById('confirm-message');
        const confirmCancel = document.getElementById('confirm-cancel');
        const confirmRemove = document.getElementById('confirm-remove');
        const toast = document.getElementById('toast-notification');
        const toastMessage = document.getElementById('toast-message');
        
        let currentWorkoutId = null;
        let currentRemoveBtn = null;
        
        // Toast function - matching workout page style
        function showToast(message, isSuccess) {
            toast.classList.remove('success', 'error');
            toast.classList.add(isSuccess ? 'success' : 'error');
            toastMessage.textContent = message;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
        
        // Close modal function
        window.closeConfirmModal = function() {
            const modal = document.getElementById('confirm-modal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
            currentWorkoutId = null;
            currentRemoveBtn = null;
        }
        
        // Start Workout functionality
        const workoutBtns = document.querySelectorAll('.workout-btn');
        workoutBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const programId = this.getAttribute('data-program-id');
                const button = this;
                
                // Show loading state
                const originalText = button.innerHTML;
                button.classList.add('btn-loading');
                button.disabled = true;
                
                // Send AJAX request
                $.ajax({
                    url: '{{ route("load.startWorkout") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        program_id: programId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            showToast(response.message, true);
                            // Redirect after short delay
                            setTimeout(() => {
                                window.location.href = response.redirect_url;
                            }, 1200);
                        } else {
                            showToast(response.message, false);
                            // Restore button
                            button.classList.remove('btn-loading');
                            button.disabled = false;
                            button.innerHTML = originalText;
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'An error occurred. Please try again.';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showToast(errorMessage, false);
                        
                        // Restore button
                        button.classList.remove('btn-loading');
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }
                });
            });
        });
        
        // Remove from Load functionality
        const removeBtns = document.querySelectorAll('.remove-btn');
        removeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const workoutId = this.getAttribute('data-workout-id');
                const programName = this.getAttribute('data-program-name');
                
                // Show confirmation modal
                currentWorkoutId = workoutId;
                currentRemoveBtn = this;
                confirmMessage.textContent = `Are you sure you want to remove "${programName}" from your workout load? This action cannot be undone.`;
                confirmModal.style.display = 'flex';
                setTimeout(() => {
                    confirmModal.classList.add('show');
                }, 10);
            });
        });
        
        // Modal functionality
        confirmCancel.addEventListener('click', function() {
            closeConfirmModal();
        });
        
        confirmRemove.addEventListener('click', function() {
            if (currentWorkoutId && currentRemoveBtn) {
                // Show loading on remove button
                const originalText = currentRemoveBtn.innerHTML;
                this.innerHTML = '<span class="material-icons">hourglass_empty</span> Removing...';
                this.disabled = true;
                confirmCancel.disabled = true;
                
                // Send AJAX request
                $.ajax({
                    url: '{{ route("load.removeFromLoad") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        workout_id: currentWorkoutId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            showToast(response.message, true);
                            closeConfirmModal();
                            // Remove the card from DOM
                            const card = currentRemoveBtn.closest('.card');
                            card.classList.add('fade-out');
                            setTimeout(() => {
                                card.remove();
                                // Check if no cards left
                                const remainingCards = document.querySelectorAll('.card');
                                if (remainingCards.length === 0) {
                                    location.reload(); // Reload to show empty state
                                }
                            }, 500);
                        } else {
                            showToast(response.message, false);
                            // Restore button
                            confirmRemove.innerHTML = 'Remove';
                            confirmRemove.disabled = false;
                            confirmCancel.disabled = false;
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'An error occurred. Please try again.';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showToast(errorMessage, false);
                        
                        // Restore button
                        confirmRemove.innerHTML = 'Remove';
                        confirmRemove.disabled = false;
                        confirmCancel.disabled = false;
                    }
                });
            }
        });
        
        // Close modal when clicking outside
        confirmModal.addEventListener('click', function(event) {
            if (event.target === confirmModal) {
                closeConfirmModal();
            }
        });
    });
</script>
@endsection