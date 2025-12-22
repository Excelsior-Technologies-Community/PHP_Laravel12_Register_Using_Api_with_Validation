@extends('layout')

@section('title', 'Login')

@section('content')
<div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Sign in to your account</h2>
        <p class="mt-2 text-sm text-gray-600">
            Or <a href="/register" class="font-medium text-blue-600 hover:text-blue-500">register for a new account</a>
        </p>
    </div>
    
    <div id="login-message" class="hidden mb-4"></div>
    
    <form id="login-form" class="space-y-6">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email or Mobile Number</label>
            <div class="mt-1">
                <input id="email" name="email" type="text" autocomplete="email" 
                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                       placeholder="Enter email or mobile number">
                <div id="email-error" class="hidden"></div>
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="mt-1 relative">
                <input id="password" name="password" type="password" autocomplete="current-password" 
                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                       placeholder="Enter password">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" onclick="togglePassword('password')" class="text-gray-400 hover:text-gray-500">
                        <i id="password-eye" class="far fa-eye"></i>
                    </button>
                </div>
                <div id="password-error" class="hidden"></div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember-me" name="remember-me" type="checkbox" 
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
            </div>
        </div>

        <div>
            <button type="submit" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Sign in
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId + '-eye');

            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.className = 'far fa-eye-slash';
            } else {
                field.type = 'password';
                eyeIcon.className = 'far fa-eye';
            }
        }

    document.getElementById('login-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        clearMessages();

        const formData = {
            login: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                localStorage.setItem('auth_token', data.token);
                window.location.href = '/dashboard';
            } else {
                showMessage('login-message', data.message || 'Login failed');
            }
        } catch (error) {
            showMessage('login-message', 'Server error. Try again.');
        }
    });

    </script>
@endsection