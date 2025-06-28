<!-- resources/views/workout-programs.blade.php (Updated with Fixed JavaScript) -->

@extends('layouts.main')

@section('title', 'Workout Programs - FitTrack')

@section('additional_css')
<link rel="stylesheet" href="{{ asset('css/stylesworkout.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    /* Tambahan CSS untuk button loaded */
    .load-btn.loaded {
        background-color: #4CAF50 !important;
        color: white;
        cursor: not-allowed;
        opacity: 0.8;
    }
    
    .load-btn.loaded:hover {
        background-color: #4CAF50 !important;
        opacity: 0.8;
    }
    
    .load-btn:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
</style>
@endsection

@section('content')
    <main>
        <section id="workout-header" class="workout-header">
            <div class="container">
                <h1 class="section-title">Workout Programs</h1>
                
                <div class="search-and-filter">
                    <div class="search-bar">
                        <form action="{{ route('workout.programs') }}" method="GET">
                            <input type="text" name="search" placeholder="Search" value="{{ $search ?? '' }}">
                            <button type="submit"><span class="material-icons">search</span></button>
                        </form>
                    </div>
                    
                    <div class="filter-bar">
                        <button class="filter-btn {{ ($filter ?? '') == 'all' ? 'active' : '' }}" data-filter="all">All</button>
                        <button class="filter-btn {{ ($filter ?? '') == 'body-weight' ? 'active' : '' }}" data-filter="body-weight">Body Weight</button>
                        <button class="filter-btn {{ ($filter ?? '') == 'tools-weight' ? 'active' : '' }}" data-filter="tools-weight">Tools Weight</button>
                    </div>
                </div>
            </div>
        </section>
      
        <section class="workout-section">
            <div class="container">
                <div class="workout-cards">
                    @if(isset($programs) && $programs->count() > 0)
                        @foreach($programs as $program)
                            @php
                                $isLoaded = in_array($program->id_program, $loadedProgramIds ?? []);
                            @endphp
                            <div class="card" data-category="{{ $program->kategori_program }}">
                                <div class="program-image">
                                    <img src="{{ $program->program_image_url ?? asset('images/default-workout.jpg') }}" alt="{{ $program->nama_program }}">
                                </div>
                                <div class="card-content">
                                    <h3>{{ $program->nama_program }}</h3>
                                    <p>{{ $program->deskripsi_program }}</p>
                                    <button class="load-btn {{ $isLoaded ? 'loaded' : '' }}" 
                                            data-program-id="{{ $program->id_program }}"
                                            {{ $isLoaded ? 'disabled' : '' }}>
                                        @auth
                                            {{ $isLoaded ? 'Added ✓' : 'Add to Load' }}
                                        @else
                                            Login to Add
                                        @endauth
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="no-programs">
                            <p>No workout programs found. Try a different search or filter.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <!-- Notifikasi toast -->
    <div id="toast-notification" class="toast-notification">
        <div class="toast-content">
            <span id="toast-message"></span>
        </div>
    </div>
    
    <!-- Debug toast -->
    <div id="debug-toast" class="debug-toast"></div>

    @guest
        <script>
            // Jika user belum login, ubah behavior button
            document.addEventListener('DOMContentLoaded', function() {
                const loadBtns = document.querySelectorAll('.load-btn');
                loadBtns.forEach(btn => {
                    if (!btn.classList.contains('loaded')) {
                        btn.textContent = 'Login to Add';
                    }
                });
            });
        </script>
    @endguest
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Setup CSRF token untuk AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Filter functionality
        const filterBtns = document.querySelectorAll('.filter-btn');
        const cards = document.querySelectorAll('.card');
        const debugToast = document.getElementById('debug-toast');
        
        // Debug function
        function showDebug(message) {
            debugToast.textContent = message;
            debugToast.style.display = 'block';
            setTimeout(() => {
                debugToast.style.display = 'none';
            }, 5000);
            console.log("Debug:", message);
        }
        
        // Filter functionality
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update URL with filter parameter
                const filterValue = this.getAttribute('data-filter');
                const url = new URL(window.location.href);
                url.searchParams.set('filter', filterValue);
                history.pushState({}, '', url);
                
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                // Filter cards
                cards.forEach(card => {
                    if (filterValue === 'all') {
                        card.style.display = 'block';
                    } else {
                        const categoryValue = filterValue === 'body-weight' ? '0' : '1';
                        if (card.dataset.category === categoryValue) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        });
        
        // Add to Load functionality
        const loadBtns = document.querySelectorAll('.load-btn');
        const toast = document.getElementById('toast-notification');
        const toastMessage = document.getElementById('toast-message');
        
        function showToast(message, isSuccess) {
            toast.classList.remove('success', 'error');
            toast.classList.add(isSuccess ? 'success' : 'error');
            toastMessage.textContent = message;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
        
        loadBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Skip jika button sudah dalam status loaded (disabled)
                if (this.classList.contains('loaded') || this.disabled) {
                    showToast('Program sudah ada di Load Anda.', false);
                    return;
                }
                
                @guest
                    // Jika user belum login, redirect ke halaman login
                    showToast('Please login to add programs to your workout.', false);
                    setTimeout(() => {
                        window.location.href = "{{ route('login.form') }}";
                    }, 2000);
                    return;
                @endguest
                
                const programId = this.getAttribute('data-program-id');
                const button = this; // Simpan referensi ke button
                
                showDebug("Memulai request AJAX untuk program ID: " + programId);
                
                // Ubah tampilan button selama proses
                const originalText = button.textContent;
                button.textContent = "Adding...";
                button.disabled = true;
                
                // Kirim request AJAX ke backend
                $.ajax({
                    url: '{{ route("workout.addToWorkout") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        program_id: programId
                    },
                    success: function(response) {
                        showDebug("AJAX Success: " + JSON.stringify(response));
                        if(response.success) {
                            showToast(response.message, true);
                            // Ubah button menjadi status loaded permanently
                            button.textContent = "Added ✓";
                            button.classList.add('loaded');
                            button.disabled = true;
                            button.style.backgroundColor = "#4CAF50";
                            button.style.color = "white";
                        } else {
                            showToast(response.message, false);
                            
                            // Handle specific error codes
                            if (response.error_code === 'UNAUTHENTICATED' && response.redirect) {
                                setTimeout(() => {
                                    window.location.href = response.redirect;
                                }, 2000);
                            }
                            
                            // Kembalikan tampilan button
                            button.textContent = originalText;
                            button.disabled = false;
                        }
                    },
                    error: function(xhr, status, error) {
                        showDebug("AJAX Error: " + status + " - " + error + " - Response: " + xhr.responseText);
                        
                        let errorMessage = 'An error occurred. Please try again.';
                        let shouldRedirect = false;
                        let redirectUrl = "{{ route('login.form') }}";
                        
                        // Handle different HTTP status codes
                        if(xhr.status === 401) {
                            errorMessage = 'Please login to add programs to your workout.';
                            shouldRedirect = true;
                            
                            // Check if response has redirect URL
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.redirect) {
                                    redirectUrl = response.redirect;
                                }
                            } catch (e) {
                                // Use default redirect URL
                            }
                            
                        } else if(xhr.status === 419) {
                            errorMessage = 'Session expired. Please refresh the page.';
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                            
                        } else if(xhr.status === 422) {
                            errorMessage = 'Invalid data submitted. Please try again.';
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMessage = response.message;
                                }
                            } catch (e) {
                                // Use default message
                            }
                            
                        } else if(xhr.status === 500) {
                            errorMessage = 'Server error. Please try again later.';
                            
                        } else if(xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        showToast(errorMessage, false);
                        
                        // Redirect if needed
                        if (shouldRedirect) {
                            setTimeout(() => {
                                window.location.href = redirectUrl;
                            }, 2000);
                        }
                        
                        // Kembalikan tampilan button
                        button.textContent = originalText;
                        button.disabled = false;
                    }
                });
            });
        });
    });
</script>
@endsection