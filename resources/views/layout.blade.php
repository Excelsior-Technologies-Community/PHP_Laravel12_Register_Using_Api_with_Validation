<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel Auth')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .success-message {
            color: #10b981;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            @yield('content')
        </div>
    </div>
    
    <script>
        const API_BASE_URL = 'http://localhost:8000/api/v1';
        let authToken = localStorage.getItem('auth_token');
        
        if (authToken) {
            document.addEventListener('DOMContentLoaded', function() {
                checkAuth();
            });
        }
        
        function checkAuth() {
            fetch(`${API_BASE_URL}/profile`, {
                headers: {
                    'Authorization': `Bearer ${authToken}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    localStorage.removeItem('auth_token');
                    window.location.href = '/login';
                }
            })
            .catch(() => {
                localStorage.removeItem('auth_token');
                window.location.href = '/login';
            });
        }
        
        function showMessage(elementId, message, isError = true) {
            const element = document.getElementById(elementId);
            element.className = isError ? 'error-message' : 'success-message';
            element.textContent = message;
            element.style.display = 'block';
            
            if (!isError) {
                setTimeout(() => {
                    element.style.display = 'none';
                }, 5000);
            }
        }
        
        function clearMessages() {
            document.querySelectorAll('.error-message, .success-message').forEach(el => {
                el.style.display = 'none';
            });
        }
    </script>
    
    @yield('scripts')
</body>
</html>