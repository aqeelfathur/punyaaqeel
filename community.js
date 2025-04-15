// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('createPostModal');
    const createPostBtn = document.getElementById('createPostBtn');
    const closeBtn = document.getElementById('closeModal');
    
    // Open modal when clicking the create post button
    createPostBtn.addEventListener('click', function() {
      modal.style.display = 'flex';
    });
    
    // Close modal when clicking the close button
    closeBtn.addEventListener('click', function() {
      modal.style.display = 'none';
    });
    
    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
    
    // Function to handle post button click
    document.querySelector('.post-button').addEventListener('click', function() {
      const content = document.querySelector('.post-textarea').value;
      if (content.trim() !== '') {
        addNewPost(content);
        modal.style.display = 'none';
        document.querySelector('.post-textarea').value = '';
      }
    });
  });
  
  // Function to toggle replies visibility
  function toggleReplies(replyId) {
    const repliesSection = document.getElementById(replyId);
    repliesSection.classList.toggle('hidden');
  }
  
  // Function to toggle like button
  function toggleLike(button) {
    const likeIcon = button.querySelector('.like-icon');
    const countSpan = button.querySelector('span');
    const count = parseInt(countSpan.textContent);
    
    if (likeIcon.classList.contains('active')) {
      likeIcon.classList.remove('active');
      countSpan.textContent = count - 1;
    } else {
      likeIcon.classList.add('active');
      countSpan.textContent = count + 1;
    }
  }
  
  // Function to add a new post
  function addNewPost(content) {
    const postsContainer = document.querySelector('.posts-container');
    const newPostId = 'replies' + (document.querySelectorAll('.post-card').length + 1);
    
    const newPost = document.createElement('div');
    newPost.className = 'post-card';
    newPost.innerHTML = `
      <div class="post-header">
        <div class="user-avatar">
          <img src="/api/placeholder/40/40" alt="User avatar">
        </div>
        <div class="username">You</div>
      </div>
      <div class="post-content">
        <p>${content}</p>
      </div>
      <div class="post-actions">
        <div class="action-button comment-btn" onclick="toggleReplies('${newPostId}')">
          <svg class="comment-icon" viewBox="0 0 24 24">
            <path fill="currentColor" d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM18 14H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
          </svg>
          <span>0</span>
        </div>
        <div class="action-button like-btn" onclick="toggleLike(this)">
          <svg class="like-icon" viewBox="0 0 24 24">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
          <span>0</span>
        </div>
      </div>
      <div id="${newPostId}" class="reply-section hidden">
        <div class="no-comments">No comments yet.</div>
      </div>
    `;
    
    // Insert the new post at the beginning of the container
    postsContainer.insertBefore(newPost, postsContainer.firstChild);
  }
  