<!-- resources/views/layouts/main.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FitTrack')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @yield('additional_css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Header/Navbar -->
    @include('partials.navbar')
    
    <!-- Main Content -->
    @yield('content')
    
    <!-- Footer -->
    @include('partials.footer')
    
    <!-- Scripts -->
    @yield('scripts')
    <script>
        // Common scripts here
        document.addEventListener('DOMContentLoaded', function() {
            // Any common JavaScript functionality
        });
    </script>
</body>
</html>