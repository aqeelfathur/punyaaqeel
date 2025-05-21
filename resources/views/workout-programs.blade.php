<!-- resources/views/workout-programs.blade.php (Fixed AJAX Error) -->

@extends('layouts.main')

@section('title', 'Workout Programs - FitTrack')

@section('additional_css')
<link rel="stylesheet" href="{{ asset('css/stylesworkout.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    /* Tambahan style untuk debugging */
    .debug-toast {
        position: fixed;
        bottom: 10px;
        left: 10px;
        background-color: #333;
        color: #fff;
        padding: 10px 15px;
        border-radius: 4px;
        max-width: 80%;
        z-index: 9999;
        display: none;
    }
    
    /* Toast styling */
    .toast-notification {
        position: fixed;
        bottom: 30px;
        right: 30px;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s;
        z-index: 1000;
        pointer-events: none;
    }

    .toast-notification.show {
        transform: translateY(0);
        opacity: 1;
    }

    .toast-content {
        background: #333;
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        font-size: 1rem;
        max-width: 300px;
    }

    .toast-notification.success .toast-content {
        border-left: 4px solid #4CAF50;
    }

    .toast-notification.error .toast-content {
        border-left: 4px solid #F44336;
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
                        <button class="filter-btn {{ ($filter ?? '') == 'body-weight' ? 'active' : '' }}" data-filter=0>Body Weight</button>
                        <button class="filter-btn {{ ($filter ?? '') == 'tools-weight' ? 'active' : '' }}" data-filter=1>Tools Weight</button>
                    </div>
                </div>
            </div>
        </section>
      
        <section class="workout-section">
            <div class="container">
                <div class="workout-cards">
                    @if(isset($programs) && $programs->count() > 0)
                        @foreach($programs as $program)
                            <div class="card" data-category="{{ $program->kategori_program ?? 'all' }}">
                                <div class="program-image">
                                    <img src="{{ $program->program_image_url }}" alt="{{ $program->nama_program }}">
                                </div>
                                <div class="card-content">
                                    <h3>{{ $program->nama_program }}</h3>
                                    <p>{{ $program->deskripsi_program }}</p>
                                    <button class="load-btn" data-program-id="{{ $program->id_program }}">
                                        @auth
                                            Add to Load
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                        if (card.dataset.category === filterValue) {
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
                @guest
                    // Jika user belum login, redirect ke halaman login
                    showToast('Please login to add programs to your workout.', false);
                    // Optional: redirect ke halaman login setelah beberapa detik
                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}";
                    }, 2000);
                    return;
                @endguest
                
                const programId = this.getAttribute('data-program-id');
                const button = this; // Simpan referensi ke button
                
                showDebug("Memulai request AJAX untuk program ID: " + programId);
                
                // Ubah tampilan button selama proses
                button.textContent = "Adding...";
                button.disabled = true;
                
                // Kirim request AJAX ke backend
                $.ajax({
                    url: '{{ route("workout.addToWorkout") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        program_id: programId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showDebug("AJAX Success: " + JSON.stringify(response));
                        if(response.success) {
                            showToast(response.message, true);
                            // Ubah tampilan button setelah berhasil
                            button.textContent = "Added ✓";
                            button.style.backgroundColor = "#4CAF50";
                            setTimeout(() => {
                                button.textContent = "Add to Load";
                                button.style.backgroundColor = "#FFD700";
                                button.disabled = false;
                            }, 2000);
                        } else {
                            showToast(response.message, false);
                            // Kembalikan tampilan button
                            button.textContent = "Add to Load";
                            button.disabled = false;
                        }
                    },
                    error: function(xhr, status, error) {
                        showDebug("AJAX Error: " + status + " - " + error + " - " + xhr.responseText);
                        
                        let errorMessage = 'An error occurred. Please try again.';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        if(xhr.status === 401) {
                            errorMessage = 'Please login to add programs to your workout.';
                        }
                        showToast(errorMessage, false);
                        
                        // Kembalikan tampilan button
                        button.textContent = "Add to Load";
                        button.disabled = false;
                    }
                });
            });
        });
    });
</script>
@endsection