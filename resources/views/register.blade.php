@extends('layout')

@section('title', 'Register')

@section('content')
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Create your account</h2>
            <p class="mt-2 text-sm text-gray-600">
                Or <a href="/login" class="font-medium text-blue-600 hover:text-blue-500">sign in to existing account</a>
            </p>
        </div>

        <div id="register-message" class="hidden mb-4"></div>

        <form id="register-form" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <div class="mt-1">
                    <input id="name" name="name" type="text" autocomplete="name"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Enter your full name">
                    <div id="name-error" class="hidden"></div>
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" autocomplete="email"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Enter email address">
                    <div id="email-error" class="hidden"></div>
                </div>
            </div>

            <div>
                <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                <div class="mt-1">
                    <input id="mobile" name="mobile" type="number" autocomplete="tel"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Enter mobile number (10-15 digits)">
                    <div id="mobile-error" class="hidden"></div>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1 relative">
                    <input id="password" name="password" type="password" autocomplete="new-password"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Enter password (min. 8 characters)">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" onclick="togglePassword('password')"
                            class="text-gray-400 hover:text-gray-500">
                            <i id="password-eye" class="far fa-eye"></i>
                        </button>
                    </div>
                    <div id="password-error" class="hidden"></div>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <div class="mt-1 relative">
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        autocomplete="new-password"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Confirm password">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" onclick="togglePassword('password_confirmation')"
                            class="text-gray-400 hover:text-gray-500">
                            <i id="password_confirmation-eye" class="far fa-eye"></i>
                        </button>
                    </div>
                    <div id="password_confirmation-error" class="hidden"></div>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Account
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

        document.getElementById('register-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            clearMessages();

            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                mobile: document.getElementById('mobile').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value
            };


            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    showMessage('register-message', data.message, false);

                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1500);
                } else {
                    Object.keys(data.errors || {}).forEach(field => {
                        const el = document.getElementById(field + '-error');
                        if (el) {
                            el.className = 'error-message';
                            el.textContent = data.errors[field][0];
                            el.style.display = 'block';
                        }
                    });
                }
            } catch (error) {
                showMessage('register-message', 'Server error. Try again.');
            }
        });

        function clearMessages() {
            document.querySelectorAll('[id$="-error"]').forEach(el => {
                el.style.display = 'none';
            });
            document.getElementById('register-message').style.display = 'none';
        }
    </script>
@endsection