<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">Welcome to Dashboard</h1>
                </div>
                <div class="flex items-center">
                    <button onclick="logout()"
                        class="ml-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="border-4 border-dashed border-gray-200 rounded-lg h-96 flex items-center justify-center">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-700 mb-4">Welcome to Your Dashboard!</h2>
                    <p class="text-gray-600">You have successfully logged in.</p>
                    <div id="user-info" class="mt-4 p-4 bg-gray-100 rounded-lg"></div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let authToken = localStorage.getItem('auth_token');

        if (!authToken) {
            window.location.href = '/login';
        }

        async function loadUserProfile() {
            try {
                const response = await fetch('/api/profile', {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Unauthorized');
                }

                const data = await response.json();

                document.getElementById('user-info').innerHTML = `
                <p><strong>Name:</strong> ${data.user.name}</p>
                <p><strong>Email:</strong> ${data.user.email}</p>
                <p><strong>Mobile:</strong> ${data.user.mobile}</p>
            `;
            } catch (error) {
                localStorage.removeItem('auth_token');
                window.location.href = '/login';
            }
        }

        async function logout() {
            await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${authToken}`,
                    'Accept': 'application/json'
                }
            });

            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        }

        document.addEventListener('DOMContentLoaded', loadUserProfile);
    </script>

</body>

</html>