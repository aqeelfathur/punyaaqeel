<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - FitTrack</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/stylesprofil.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Existing CSS styles */
        .user-dropdown {
            position: relative;
            display: inline-block;
            margin-right: 20px;
        }
        
        .user-button {
            font-family: 'Poppins', sans-serif;
            padding: 10px 20px;
            border: none;
            background: #ffffff;
            color: #0a0a0a;
            font-weight: 400;
            border-radius: 1000px;
            cursor: pointer;
            box-shadow: 0px 0px 80px rgba(255, 255, 255, 0.5);
            transition: box-shadow 0.3s ease-in-out;
            display: flex;
            align-items: center;
        }
        
        .user-button:hover {
            box-shadow: 40px 0px 100px #ffd900a7, -40px 0px 100px #f7eaa5a7;
        }
        
        .user-button:after {
            content: " ▼";
            font-size: 10px;
            margin-left: 5px;
        }
        
        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: #181717;
            display: none;
            width: 200px;
            padding: 0;
            border-radius: 40px;
            z-index: 10;
            list-style: none;
            margin-top: 10px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        }
        
        .user-dropdown:hover .user-dropdown-menu {
            display: block;
        }
        
        .user-dropdown-menu a {
            padding: 15px 20px;
            text-decoration: none;
            color: white;
            display: block;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: background 0.3s;
        }
        
        .user-dropdown-menu a:hover {
            background: #333;
        }
        
        .user-dropdown-menu a:first-child {
            border-top-left-radius: 40px;
            border-top-right-radius: 40px;
        }
        
        .user-dropdown-menu a:last-child {
            border-bottom: none;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
        }

        /* CSS for Settings Page */
        .settings-container {
            margin-top: 150px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            padding: 30px;
            background: rgba(20, 20, 20, 0.8);
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.2);
        }

        .settings-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .settings-header h1 {
            font-size: 2.5rem;
            color: #FFD700;
            margin-bottom: 10px;
        }

        .settings-header p {
            font-size: 1.2rem;
            color: #ccc;
        }

        /* Profile Image Section */
        .profile-image-section {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: rgba(40, 40, 40, 0.3);
            border-radius: 15px;
        }

        .profile-image-container {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .profile-image-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #FFD700;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
        }

        .profile-image-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(60, 60, 60, 0.5);
            border: 2px dashed #FFD700;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFD700;
            font-size: 0.9rem;
        }

        .image-upload-controls {
            display: flex;
            gap: 15px;
            justify-content: center;
            align-items: center;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .upload-btn {
            font-family: 'Poppins', sans-serif;
            padding: 10px 20px;
            background: #FFD700;
            color: black;
            font-weight: 500;
            border: none;
            border-radius: 1000px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            font-size: 0.9rem;
        }

        .upload-btn:hover {
            background: #e6c200;
            transform: translateY(-2px);
        }

        .delete-image-btn {
            font-family: 'Poppins', sans-serif;
            padding: 10px 20px;
            background: transparent;
            color: #ff6b6b;
            font-weight: 400;
            border: 1px solid #ff6b6b;
            border-radius: 1000px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            font-size: 0.9rem;
        }

        .delete-image-btn:hover {
            background: rgba(255, 107, 107, 0.1);
        }

        .settings-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 1.1rem;
            color: #FFD700;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: none;
            border-radius: 1000px;
            background: rgba(40, 40, 40, 0.7);
            color: white;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.5);
        }

        .settings-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .cancel-btn {
            font-family: 'Poppins', sans-serif;
            padding: 12px 30px;
            background: transparent;
            color: white;
            font-weight: 400;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1000px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
        }

        .cancel-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .save-btn {
            font-family: 'Poppins', sans-serif;
            padding: 12px 30px;
            background: #FFD700;
            color: black;
            font-weight: 600;
            border: none;
            border-radius: 1000px;
            cursor: pointer;
            box-shadow: 0px 0px 20px rgba(255, 215, 0, 0.5);
            transition: box-shadow 0.3s ease-in-out;
            font-size: 1rem;
        }

        .save-btn:hover {
            box-shadow: 40px 0px 60px #ffd900a7, -40px 0px 60px #f7eaa5a7;
        }

        .save-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-weight: 400;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.5);
            color: #2ecc71;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #e74c3c;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">FIT TRACK</div>
            <ul class="nav-links">
                <li><a href="/">Home</a></li>
                <li class="dropdown">
                    <a href="#">Programs &#9662;</a>
                    <ul class="dropdown-menu">
                        <li><a href="/workout-programs">Workout Programs</a></li>
                        <li><a href="/load">Load</a></li>
                        <li><a href="/calendar">Calendar</a></li>
                        <li><a href="/customworkout">Custom</a></li>
                    </ul>
                </li>
                <li><a href="/community">Community</a></li>
                <li><a href="/about-us">About Us</a></li>
            </ul>
            
            @auth
                <div class="user-dropdown">
                    <button class="user-button">{{ Auth::user()->username }}</button>
                    <div class="user-dropdown-menu">
                        <a href="/profile">Profile</a>
                        <a href="/settings">Settings</a>
                        <a href="{{ route('logout') }}" 
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            @else
                <a href="/login"><button class="sign-in" aria-label="Sign in">Sign in</button></a>
            @endauth
        </nav>
    </header>

    <div class="settings-container">
        <div class="settings-header">
            <h1>Account Settings</h1>
            <p>Update your personal information and profile image</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Image Section -->
        <div class="profile-image-section">
            <h3 style="color: #FFD700; margin-bottom: 20px;">Profile Image</h3>

            <div class="profile-image-container">
                @if(Auth::user()->profile_image)
                    <img src="{{ Storage::url(Auth::user()->profile_image) }}" 
                        alt="Profile Image" 
                        class="profile-image-preview" 
                        id="profileImagePreview">
                @else
                    <div class="profile-image-placeholder" id="profileImagePlaceholder">
                        No Image
                    </div>
                @endif
            </div>

            <div class="image-upload-controls">
                <div class="file-input-wrapper">
                    <input type="file" 
                        id="profileImageInput" 
                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                        name="profile_image">
                    <label for="profileImageInput" class="upload-btn">
                        <span id="uploadBtnText">Choose Image</span>
                    </label>
                </div>

                @if(Auth::user()->profile_image)
                    <button type="button" class="delete-image-btn" id="deleteImageBtn">
                        Delete Image
                    </button>
                @endif
            </div>
        </div>



        <!-- Settings Form -->
        <form class="settings-form" action="{{ route('settings.update') }}" method="POST" id="settingsForm">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username', Auth::user()->username) }}" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
            </div>
            
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}">
            </div>
            
            <div class="form-group">
                <label for="current_password">Current Password (required to save changes)</label>
                <input type="password" id="current_password" name="current_password" placeholder="Enter your current password" required>
            </div>
            
            <div class="form-group">
                <label for="password">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password" placeholder="Enter new password">
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
            </div>
            
            <div class="settings-actions">
                <a href="{{ route('profile') }}" class="cancel-btn">Cancel</a>
                <button type="submit" class="save-btn" id="saveBtn">
                    <span id="saveBtnText">Save Changes</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        // CSRF Token Setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // DOM Elements
        const profileImageInput = document.getElementById('profileImageInput');
        const profileImagePreview = document.getElementById('profileImagePreview');
        const profileImagePlaceholder = document.getElementById('profileImagePlaceholder');
        const deleteImageBtn = document.getElementById('deleteImageBtn');
        const uploadBtnText = document.getElementById('uploadBtnText');
        const settingsForm = document.getElementById('settingsForm');
        const saveBtn = document.getElementById('saveBtn');
        const saveBtnText = document.getElementById('saveBtnText');

        // Profile Image Upload Handler
        profileImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Image size must be less than 2MB');
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, JPG, GIF, WebP)');
                return;
            }

            // Show loading state
            uploadBtnText.innerHTML = '<span class="spinner"></span> Uploading...';
            
            // Create FormData and upload
            const formData = new FormData();
            formData.append('profile_image', file);

            fetch('{{ route("settings.image.upload") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update preview
                    updateImagePreview(data.image_url);
                    showAlert('success', data.message);
                    
                    // Show delete button if not visible
                    if (deleteImageBtn) {
                        deleteImageBtn.style.display = 'inline-block';
                    }
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Failed to upload image. Please try again.');
            })
            .finally(() => {
                uploadBtnText.textContent = 'Choose Image';
                profileImageInput.value = '';
            });
        });

        // Delete Image Handler
        if (deleteImageBtn) {
            deleteImageBtn.addEventListener('click', function() {
                if (!confirm('Are you sure you want to delete your profile image?')) {
                    return;
                }

                this.innerHTML = '<span class="spinner"></span> Deleting...';
                
                fetch('{{ route("settings.image.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update preview to placeholder
                        updateImagePreview(null);
                        showAlert('success', data.message);
                        
                        // Hide delete button
                        this.style.display = 'none';
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Failed to delete image. Please try again.');
                })
                .finally(() => {
                    this.textContent = 'Delete Image';
                });
            });
        }

        // Form Submit Handler
        settingsForm.addEventListener('submit', function(e) {
            // Show loading state
            saveBtn.disabled = true;
            saveBtnText.innerHTML = '<span class="spinner"></span> Saving...';
        });

        // Helper Functions
        function updateImagePreview(imageUrl) {
            const container = document.querySelector('.profile-image-container');
            
            if (imageUrl) {
                // Show image
                if (profileImagePreview) {
                    profileImagePreview.src = imageUrl;
                } else {
                    // Create new image element
                    const img = document.createElement('img');
                    img.src = imageUrl;
                    img.alt = 'Profile Image';
                    img.className = 'profile-image-preview';
                    img.id = 'profileImagePreview';
                    
                    // Replace placeholder with image
                    if (profileImagePlaceholder) {
                        profileImagePlaceholder.replaceWith(img);
                    } else {
                        container.appendChild(img);
                    }
                }
            } else {
                // Show placeholder
                if (profileImagePreview) {
                    const placeholder = document.createElement('div');
                    placeholder.className = 'profile-image-placeholder';
                    placeholder.id = 'profileImagePlaceholder';
                    placeholder.textContent = 'No Image';
                    
                    profileImagePreview.replaceWith(placeholder);
                }
            }
        }

        function showAlert(type, message) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());
            
            // Create new alert
            const alert = document.createElement('div');
            alert.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
            alert.textContent = message;
            
            // Insert alert at the top of settings container
            const container = document.querySelector('.settings-container');
            const header = document.querySelector('.settings-header');
            container.insertBefore(alert, header.nextSibling);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>