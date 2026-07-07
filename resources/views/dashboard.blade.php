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

            <h2 class="text-2xl font-bold text-indigo-700 mb-4">
                👤 Logged In User
            </h2>

            <div id="profileInfo">

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

        document.getElementById("profileInfo").innerHTML = `
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    <div class="bg-blue-50 p-4 rounded-xl">
        <p class="text-gray-500">Name</p>
        <h3 class="font-bold text-lg">${data.user.name}</h3>
    </div>

    <div class="bg-green-50 p-4 rounded-xl">
        <p class="text-gray-500">Email</p>
        <h3 class="font-bold text-lg">${data.user.email}</h3>
    </div>

    <div class="bg-purple-50 p-4 rounded-xl">
        <p class="text-gray-500">Mobile</p>
        <h3 class="font-bold text-lg">${data.user.mobile}</h3>
    </div>

</div>
`;
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
    </script>

</body>

</html>