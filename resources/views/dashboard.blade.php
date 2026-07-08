<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-indigo-700 via-blue-600 to-cyan-600 shadow-xl">

        <div class="max-w-7xl mx-auto px-6">

            <div class="flex justify-between items-center h-16">

                <h1 class="text-white text-3xl font-bold tracking-wide">
                    🚀 Laravel 12 User Dashboard
                </h1>

                <button onclick="logout()"
                    class="bg-red-500 hover:bg-red-600 px-5 py-2 rounded-lg text-white font-semibold shadow-lg transition duration-300">
                    Logout
                </button>

            </div>

        </div>

    </nav>

    <div class="max-w-7xl mx-auto mt-8">

        <!-- Dashboard Statistics -->

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">

            <div
                class="bg-white rounded-2xl shadow-xl p-6 hover:scale-105 transition duration-300 border-l-4 border-blue-600">

                <h3 class="text-gray-500 text-sm">
                    Total Users
                </h3>

                <h1 id="totalUsers" class="text-3xl font-bold text-blue-600 mt-2">
                    0
                </h1>

            </div>

            <div
                class="bg-white rounded-2xl shadow-xl p-6 hover:scale-105 transition duration-300 border-l-4 border-green-500">

                <h3 class="text-gray-500 text-sm">
                    Today's Users
                </h3>

                <h1 id="todayUsers" class="text-3xl font-bold text-green-600 mt-2">
                    0
                </h1>

            </div>

            <div
                class="bg-white rounded-2xl shadow-xl p-6 hover:scale-105 transition duration-300 border-l-4 border-purple-500">

                <h3 class="text-gray-500 text-sm">
                    Verified Emails
                </h3>

                <h1 id="verifiedUsers" class="text-3xl font-bold text-purple-600 mt-2">
                    0
                </h1>

            </div>

            <div
                class="bg-white rounded-2xl shadow-xl p-6 hover:scale-105 transition duration-300 border-l-4 border-orange-500">

                <h3 class="text-gray-500 text-sm">
                    Latest User
                </h3>

                <h2 id="latestUser" class="text-lg font-semibold mt-2 text-gray-700">
                    --
                </h2>

            </div>

        </div>

        <!-- Logged In User -->

        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">

            <h2 class="text-2xl font-bold text-indigo-700 mb-6">
                👤 My Profile
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Full Name
                    </label>

                    <input
                        id="name"
                        type="text"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Address
                    </label>

                    <input
                        id="email"
                        type="email"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Mobile Number
                    </label>

                    <input
                        id="mobile"
                        type="text"
                        maxlength="10"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

            </div>

            <div class="mt-6 flex items-center gap-4">

                <button
                    onclick="updateProfile()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg transition duration-300">

                    💾 Update Profile

                </button>

                <span
                    id="profileMessage"
                    class="text-sm font-medium">
                </span>

            </div>

            <div class="mt-8 border-t pt-6">

                <h3 class="text-xl font-bold text-indigo-700 mb-4">
                    🔐 Change Password
                </h3>


                <input
                    id="current_password"
                    type="password"
                    placeholder="Current Password"
                    class="w-full border rounded-xl px-4 py-3 mb-3">


                <input
                    id="password"
                    type="password"
                    placeholder="New Password"
                    class="w-full border rounded-xl px-4 py-3 mb-3">


                <input
                    id="password_confirmation"
                    type="password"
                    placeholder="Confirm Password"
                    class="w-full border rounded-xl px-4 py-3 mb-3">


                <button
                    onclick="changePassword()"
                    class="bg-green-600 text-white px-6 py-3 rounded-xl">

                    Change Password

                </button>


                <div id="passwordMessage"></div>


            </div>

        </div>

        <!-- Login History -->

        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">

            <h2 class="text-2xl font-bold text-indigo-700 mb-5">
                🕒 Recent Login Activity
            </h2>

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-indigo-600 text-white">

                        <tr>

                            <th class="px-4 py-3 text-left">#</th>

                            <th class="px-4 py-3 text-left">Browser</th>

                            <th class="px-4 py-3 text-left">Platform</th>

                            <th class="px-4 py-3 text-left">IP Address</th>

                            <th class="px-4 py-3 text-left">Login Time</th>

                        </tr>

                    </thead>

                    <tbody id="loginHistoryTable">

                        <tr>

                            <td colspan="5" class="text-center py-6 text-gray-500">
                                Loading...
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

        <!-- Search + Sort -->

        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">

            <div class="grid md:grid-cols-2 gap-5">

                <input id="search" type="text" placeholder="🔍 Search Name, Email or Mobile..."
                    class="w-full border-2 border-blue-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                <select id="sort"
                    class="w-full border-2 border-blue-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="az">
                        Sort A-Z
                    </option>

                    <option value="za">
                        Sort Z-A
                    </option>

                </select>

            </div>

        </div>

        <!-- Users Table -->

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            <table class="min-w-full text-sm">
                <thead class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">

                    <tr class="border-b hover:bg-blue-50 transition">

                        <th class="py-3 px-4 text-left">
                            ID
                        </th>

                        <th class="py-3 px-4 text-left">
                            Name
                        </th>

                        <th class="py-3 px-4 text-left">
                            Email
                        </th>

                        <th class="py-3 px-4 text-left">
                            Mobile
                        </th>

                        <th class="py-3 px-4 text-center">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody id="userTable">

                </tbody>

            </table>

        </div>

        <!-- Pagination -->

        <div id="pagination" class="flex justify-center mt-6 gap-2">

        </div>

    </div>

    <script>
        let token = localStorage.getItem('auth_token');

        if (!token) {
            window.location.href = "/login";
        }

        let currentPage = 1;

        async function loadUsers(page = 1) {

            currentPage = page;

            let search = document.getElementById("search").value;
            let sort = document.getElementById("sort").value;

            const response = await fetch(`/api/users?page=${page}&search=${search}&sort=${sort}`);

            const data = await response.json();

            let rows = "";

            data.data.forEach(user => {

                rows += `
        <tr class="border-b hover:bg-blue-50 transition duration-200">

            <td class="px-4 py-3">${user.id}</td>

            <td class="px-4 py-3">${user.name}</td>

            <td class="px-4 py-3">${user.email}</td>

            <td class="px-4 py-3">${user.mobile}</td>

            <td class="px-4 py-3 text-center">

                <button
                    onclick="deleteUser(${user.id})"
                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-xl transition duration-300">

                    Delete

                </button>

            </td>

        </tr>
        `;
            });

            document.getElementById("userTable").innerHTML = rows;

            let pagination = "";

            for (let i = 1; i <= data.last_page; i++) {

                pagination += `
        <button
            onclick="loadUsers(${i})"
            class="px-4 py-2 rounded-lg font-semibold shadow ${i == data.current_page
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-200 hover:bg-blue-500 hover:text-white'
                    }">

            ${i}

        </button>
        `;
            }

            document.getElementById("pagination").innerHTML = pagination;
        }

        async function loadProfile() {

            const response = await fetch('/api/profile', {

                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }

            });

            const data = await response.json();

            document.getElementById("name").value = data.user.name;
            document.getElementById("email").value = data.user.email;
            document.getElementById("mobile").value = data.user.mobile;
        }

        async function updateProfile() {

            const response = await fetch('/api/profile/update', {

                method: "POST",

                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: "application/json",
                    "Content-Type": "application/json"
                },

                body: JSON.stringify({

                    name: document.getElementById("name").value,
                    email: document.getElementById("email").value,
                    mobile: document.getElementById("mobile").value

                })

            });

            const data = await response.json();

            const message = document.getElementById("profileMessage");

            if (response.ok) {

                message.innerHTML =
                    `<span class="text-green-600 font-semibold">${data.message}</span>`;

            } else {

                let errors = "";

                if (data.errors) {
                    Object.values(data.errors).forEach(error => {
                        errors += `<div class="text-red-600">${error[0]}</div>`;
                    });
                } else {
                    errors = `<div class="text-red-600">${data.message ?? 'Something went wrong.'}</div>`;
                }

                message.innerHTML = errors;
            }
        }

        async function changePassword() {

            const response = await fetch('/api/change-password', {

                method: "POST",

                headers: {

                    Authorization: `Bearer ${token}`,
                    Accept: "application/json",
                    "Content-Type": "application/json"

                },

                body: JSON.stringify({

                    current_password: document.getElementById("current_password").value,
                    password: document.getElementById("password").value,
                    password_confirmation: document.getElementById("password_confirmation").value

                })

            });

            const data = await response.json();

            document.getElementById("passwordMessage").innerHTML =
                response.ok ?
                `<p class="text-green-600">${data.message}</p>` :
                `<p class="text-red-600">${data.message}</p>`;


        }

        async function loadStats() {

            const response = await fetch('/api/dashboard-stats', {

                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }

            });

            const data = await response.json();

            document.getElementById("totalUsers").innerHTML = data.total_users;

            document.getElementById("todayUsers").innerHTML = data.today_users;

            document.getElementById("verifiedUsers").innerHTML = data.verified_emails;

            document.getElementById("latestUser").innerHTML =
                data.latest_user ? data.latest_user.name : "--";

        }

        async function loadLoginHistory() {

            const response = await fetch('/api/login-history', {

                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }

            });

            const data = await response.json();

            let rows = "";

            if (data.login_history.length === 0) {

                rows = `
            <tr>
                <td colspan="5" class="text-center py-6 text-gray-500">
                    No Login History Found
                </td>
            </tr>
        `;

            } else {

                data.login_history.forEach((history, index) => {

                    rows += `
                <tr class="border-b hover:bg-gray-50">

                    <td class="px-4 py-3">${index + 1}</td>

                    <td class="px-4 py-3">${history.browser}</td>

                    <td class="px-4 py-3">${history.platform}</td>

                    <td class="px-4 py-3">${history.ip_address}</td>

                    <td class="px-4 py-3">${new Date(history.login_at).toLocaleString()}</td>

                </tr>
            `;

                });

            }

            document.getElementById("loginHistoryTable").innerHTML = rows;

        }

        async function deleteUser(id) {

            if (!confirm("Delete this user?")) {
                return;
            }

            await fetch(`/api/users/${id}`, {

                method: "DELETE",

                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }

            });

            loadUsers(currentPage);

            loadStats();

        }

        async function logout() {

            await fetch('/api/logout', {

                method: 'POST',

                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }

            });

            localStorage.removeItem("auth_token");

            window.location.href = "/login";

        }

        document.getElementById("search").addEventListener("keyup", function() {

            loadUsers(1);

        });

        document.getElementById("sort").addEventListener("change", function() {

            loadUsers(1);

        });

        loadUsers();
        loadProfile();
        loadStats();
        loadLoginHistory();
    </script>

</body>

</html>