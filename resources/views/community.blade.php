@extends('layouts.main')

@section('title', 'FitTrack - Community')

@section('additional_css')
<link rel="stylesheet" href="{{ asset('css/stylescommunity.css') }}">
@endsection

@section('content')
    <!-- Community Header Section -->
    <section class="section community-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="animated-title">Join Our <span class="highlight">Community</span></h1>
            <p class="hero-tagline">Share your progress. Get motivated. Inspire others.</p>
        </div>
    </section>

    <!-- Main Community Content -->
    <div class="content community-section">
        <!-- Search Bar -->
        <div class="search-container">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Search Posts">
                <button>
                    <svg class="search-icon" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Create Post Inline -->
        <form id="createPostForm" class="create-post-inline" style="margin-top: 20px;" method="POST" action="{{ route('community.post') }}">
            @csrf
            <div class="post-input-group">
                <textarea name="isi" class="post-textarea" placeholder="Share something..." required></textarea>
                <button class="post-button" type="submit">Post</button>
            </div>
        </form>

        <!-- Posts Container -->
        <div id="postsContainer">
            @foreach($posts as $post)
                <div class="post-card" data-post-id="{{ $post->id }}">
                    <div class="post-header">
                        <div class="user-avatar">
                              <img src="{{ $post->user->profile_image ? Storage::url($post->user->profile_image) : asset('images/default-avatar.png') }}" 
                                  alt="User avatar" 
                                  onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                          </div>
                        <div class="username">{{ $post->user->username ?? 'Unknown User' }}</div>
                    </div>
                    <div class="post-content">
                        <p>{{ $post->isi }}</p>
                    </div>
                    <div class="post-actions">
                        <div class="action-button comment-btn" onclick="toggleReplies('replies{{ $post->id }}')">
                            💬 <span>{{ $post->comments->count() }}</span>
                        </div>
                        <div class="action-button like-btn" onclick="toggleLike({{ $post->id }}, this)">
                            <svg class="like-icon" viewBox="0 0 24 24" width="20" height="20">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
                                        2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09
                                        C13.09 3.81 14.76 3 16.5 3 
                                        19.58 3 22 5.42 22 8.5
                                        c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            <span class="like-count">{{ $post->likes->count() }}</span>
                        </div>
                    </div>

                    <div id="replies{{ $post->id }}" class="reply-section hidden">
                        @foreach($post->comments as $comment)
                            <div class="reply">
                                <div class="reply-avatar">
                                    <img src="{{ $comment->user->profile_image ? Storage::url($comment->user->profile_image) : asset('images/default-avatar.png') }}" 
                                  alt="User avatar" 
                                  onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                </div>
                                <div class="reply-content">
                                    <div class="reply-username">{{ $comment->user->username ?? 'Unknown User' }}</div>
                                    <div class="reply-text">{{ $comment->isi_komentar }}</div>
                                </div>
                            </div>
                        @endforeach
                        <form class="comment-form" data-post-id="{{ $post->id }}">
                            @csrf
                            <input type="text" name="isi_komentar" placeholder="Add a comment..." required>
                            <button type="submit">Reply</button>
                        </form>
                    </div>
                </div>
            @endforeach
            
            @if ($posts->isEmpty())
                <p style="text-align:center; color:gray; margin-top:20px;">No posts yet. Be the first to share something!</p>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
function toggleReplies(replyId) {
    const replySection = document.getElementById(replyId);
    if (replySection) {
        replySection.classList.toggle('hidden');
    }
}

function toggleLike(postId, button) {
    // Disable button to prevent double clicks
    button.disabled = true;
    
    fetch(`/community/${postId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        console.log('Like response status:', response.status);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Like error response:', text);
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Like success:', data);
        
        if (data.success) {
            const likeIcon = button.querySelector('.like-icon');
            const count = button.querySelector('.like-count');
            
            if (data.liked) {
                button.classList.add('active');
                likeIcon.style.fill = '#e74c3c'; // Red color for liked
            } else {
                button.classList.remove('active');
                likeIcon.style.fill = 'currentColor'; // Default color
            }
            
            // Update count from server response
            count.textContent = data.count || 0;
        } else {
            throw new Error(data.error || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Like Error:', error);
        alert(`Failed to toggle like: ${error.message}`);
    })
    .finally(() => {
        // Re-enable button
        button.disabled = false;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM Content Loaded');
    
    // Check if CSRF token exists
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found! Make sure you have <meta name="csrf-token" content="{{ csrf_token() }}"> in your layout head');
    }
    
    // Check if form exists
    const form = document.getElementById('createPostForm');
    if (!form) {
        console.error('createPostForm not found!');
        return;
    }
    console.log('Form found:', form);
    
    // Create Post
    form.addEventListener('submit', function(e) {
        console.log('Form submit event triggered');
        e.preventDefault();
        console.log('Form submission prevented, processing with AJAX');
        
        const form = e.target;
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const textarea = form.querySelector('textarea[name="isi"]');
        
        // Check if textarea has content
        if (!textarea.value.trim()) {
            alert('Please enter some content');
            return;
        }
        
        // Debug: Log form data
        console.log('Form Data:', {
            isi: formData.get('isi'),
            csrf: formData.get('_token'),
            textarea_value: textarea.value
        });
        
        // Get route URL
        const routeUrl = form.getAttribute('action');
        console.log('Route URL:', routeUrl);
        
        // Disable button to prevent double submission
        submitButton.disabled = true;
        submitButton.textContent = 'Posting...';

        fetch(routeUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                return response.text().then(text => {
                    console.log('Error response:', text);
                    throw new Error(`Post failed: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Success response:', data);
            if (data.success) {
                // Clear form
                form.reset();
                // Reload page to show new post
                location.reload();
            } else {
                throw new Error(data.error || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Full Error:', error);
            alert(`Failed to create post: ${error.message}`);
        })
        .finally(() => {
            // Re-enable button
            submitButton.disabled = false;
            submitButton.textContent = 'Post';
        });
    });

    // Comment submission - FIXED
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Comment form submitted');
            
            const postId = form.dataset.postId;
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const input = form.querySelector('input[name="isi_komentar"]');
            
            // Check if input has content
            if (!input.value.trim()) {
                alert('Please enter a comment');
                return;
            }
            
            console.log('Comment data:', {
                postId: postId,
                comment: formData.get('isi_komentar')
            });
            
            // Disable button
            submitButton.disabled = true;
            submitButton.textContent = 'Posting...';

            fetch(`/community/${postId}/comment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                console.log('Comment response status:', response.status);
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Comment error response:', text);
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Comment success:', data);
                
                if (data.success) {
                    // Clear form
                    form.reset();
                    // Reload to show new comment
                    location.reload();
                } else {
                    throw new Error(data.error || 'Unknown error');
                }
            })
            .catch(error => {
                console.error('Comment Error:', error);
                alert(`Failed to post comment: ${error.message}`);
            })
            .finally(() => {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.textContent = 'Reply';
            });
        });
    });
});
</script>
@endsection