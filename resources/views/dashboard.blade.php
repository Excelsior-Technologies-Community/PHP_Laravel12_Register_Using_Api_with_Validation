<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-indigo-700 via-blue-600 to-cyan-600 shadow-xl">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <h1 class="text-white text-2xl md:text-3xl font-bold tracking-wide">
                        🚀 Laravel 12 API Dashboard
                    </h1>
                    <span id="navRoleBadge" class="hidden px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-400 text-gray-900 shadow">
                        👑 Admin
                    </span>
                </div>

                <div class="flex items-center gap-4">
                    <button onclick="logout()"
                        class="bg-red-500 hover:bg-red-600 px-5 py-2 rounded-lg text-white font-semibold shadow-lg transition duration-300">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto mt-6 px-4">

        <!-- New Device / IP Login Alert Banner -->
        <div id="newDeviceAlertBox" class="hidden mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🛡️</span>
                    <div>
                        <h4 class="text-sm font-bold text-amber-800">Security Notice: New Device / IP Login Detected!</h4>
                        <p id="newDeviceInfoText" class="text-xs text-amber-700 mt-0.5">
                            A login from an unrecognized browser or IP location was recorded for your account. If this was not you, change your password immediately.
                        </p>
                    </div>
                </div>
                <button onclick="dismissNewDeviceAlert()" class="text-amber-600 hover:text-amber-800 text-sm font-bold px-2 py-1">
                    ✕ Dismiss
                </button>
            </div>
        </div>

        <!-- Dashboard Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
            <div class="bg-white rounded-2xl shadow-xl p-6 hover:scale-105 transition duration-300 border-l-4 border-blue-600">
                <h3 class="text-gray-500 text-sm">Total Users</h3>
                <h1 id="totalUsers" class="text-3xl font-bold text-blue-600 mt-2">0</h1>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-6 hover:scale-105 transition duration-300 border-l-4 border-green-500">
                <h3 class="text-gray-500 text-sm">Today's Users</h3>
                <h1 id="todayUsers" class="text-3xl font-bold text-green-600 mt-2">0</h1>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-6 hover:scale-105 transition duration-300 border-l-4 border-purple-500">
                <h3 class="text-gray-500 text-sm">Active Users</h3>
                <h1 id="activeUsers" class="text-3xl font-bold text-purple-600 mt-2">0</h1>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-6 hover:scale-105 transition duration-300 border-l-4 border-orange-500">
                <h3 class="text-gray-500 text-sm">Total Admins</h3>
                <h2 id="totalAdmins" class="text-3xl font-bold text-orange-600 mt-2">0</h2>
            </div>
        </div>

        <!-- Logged In User Profile & Avatar -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b">
                <div class="flex items-center gap-4">
                    <div class="relative group">
                        <img id="profileAvatarImg"
                            src="https://ui-avatars.com/api/?name=User&background=6366f1&color=fff"
                            alt="Avatar"
                            class="w-20 h-20 rounded-full object-cover border-4 border-indigo-100 shadow-md">
                        <label for="avatarInput"
                            class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition text-white text-xs font-semibold">
                            📷 Upload
                        </label>
                        <input id="avatarInput" type="file" accept="image/*" class="hidden" onchange="uploadAvatar(this)">
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-2xl font-bold text-indigo-700" id="profileHeaderName">My Profile</h2>
                            <span id="userRoleBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">User</span>
                            <span id="userStatusBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Manage your account details, photo, and security preferences</p>
                    </div>
                </div>

                <div>
                    <label for="avatarInput" class="cursor-pointer inline-flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-4 py-2 rounded-xl text-sm font-semibold border border-indigo-200 transition">
                        <i class="fas fa-camera"></i> Change Avatar
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                    <input id="name" type="text"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input id="email" type="email"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mobile Number</label>
                    <input id="mobile" type="text" maxlength="10"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <button onclick="updateProfile()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg transition duration-300">
                    💾 Update Profile
                </button>
                <span id="profileMessage" class="text-sm font-medium"></span>
            </div>

            <!-- Change Password -->
            <div class="mt-8 border-t pt-6">
                <h3 class="text-xl font-bold text-indigo-700 mb-4">🔐 Change Password</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                    <input id="current_password" type="password" placeholder="Current Password"
                        class="border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <input id="password" type="password" placeholder="New Password"
                        class="border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <input id="password_confirmation" type="password" placeholder="Confirm Password"
                        class="border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="changePassword()"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold shadow transition">
                        Change Password
                    </button>
                    <div id="passwordMessage" class="text-sm font-semibold"></div>
                </div>
            </div>
        </div>

        <!-- Personal Access Tokens (API Vault) -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5 border-b pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-indigo-700 flex items-center gap-2">
                        🔑 Personal Access Tokens (API Vault)
                    </h2>
                    <p class="text-gray-500 text-sm">Generate and revoke API access tokens for external clients and integrations</p>
                </div>

                <div class="flex gap-2">
                    <input id="newTokenName" type="text" placeholder="Token Name (e.g. Mobile App)"
                        class="border-2 border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button onclick="createToken()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow transition">
                        + Generate Token
                    </button>
                </div>
            </div>

            <!-- Newly Created Token Alert -->
            <div id="newlyCreatedTokenAlert" class="hidden mb-5 bg-green-50 border border-green-200 p-4 rounded-xl">
                <p class="text-xs font-bold text-green-800 mb-1">✅ New Token Generated! Copy it now (it will not be shown again):</p>
                <div class="flex items-center gap-2">
                    <input id="generatedTokenVal" readonly class="w-full bg-white font-mono text-xs p-2.5 border rounded-lg text-gray-700">
                    <button onclick="copyGeneratedToken()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-xs rounded-lg font-semibold whitespace-nowrap">
                        📋 Copy Token
                    </button>
                </div>
            </div>

            <!-- Tokens List Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Token ID</th>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Created At</th>
                            <th class="px-4 py-3 text-left">Last Used</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tokensTableBody">
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-400">Loading tokens...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Login History -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
            <h2 class="text-2xl font-bold text-indigo-700 mb-5">
                🕒 Recent Login Activity
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
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

        <!-- Search + Sort for Users List -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800">👥 Users Management</h3>
                <span id="adminHintText" class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-3 py-1 rounded-full">
                    Live User Directory
                </span>
            </div>
            <div class="grid md:grid-cols-2 gap-5">
                <input id="search" type="text" placeholder="🔍 Search Name, Email or Mobile..."
                    class="w-full border-2 border-blue-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                <select id="sort"
                    class="w-full border-2 border-blue-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="az">Sort A-Z</option>
                    <option value="za">Sort Z-A</option>
                </select>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Avatar</th>
                            <th class="py-3 px-4 text-left">Name</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Mobile</th>
                            <th class="py-3 px-4 text-left">Role</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTable">
                        <tr>
                            <td colspan="8" class="text-center py-6 text-gray-400">Loading user directory...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="flex justify-center mb-12 gap-2"></div>

    </div>

    <script>
        let token = localStorage.getItem('auth_token');

        if (!token) {
            window.location.href = "/login";
        }

        let currentUser = null;
        let currentPage = 1;

        // Security Check: New Device Detection from Login
        const storedDeviceAlert = localStorage.getItem('new_device_alert');
        if (storedDeviceAlert) {
            try {
                const alertData = JSON.parse(storedDeviceAlert);
                const alertBox = document.getElementById('newDeviceAlertBox');
                const infoText = document.getElementById('newDeviceInfoText');
                if (alertBox && infoText) {
                    infoText.innerHTML = `Login from <b>${alertData.browser}</b> on <b>${alertData.platform}</b> (IP: <code>${alertData.ip_address}</code>) at ${alertData.time}.`;
                    alertBox.classList.remove('hidden');
                }
            } catch (e) {}
        }

        function dismissNewDeviceAlert() {
            localStorage.removeItem('new_device_alert');
            const alertBox = document.getElementById('newDeviceAlertBox');
            if (alertBox) alertBox.classList.add('hidden');
        }

        // Load Current User Profile
        async function loadProfile() {
            try {
                const response = await fetch('/api/profile', {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json'
                    }
                });

                if (!response.ok) {
                    localStorage.removeItem('auth_token');
                    window.location.href = "/login";
                    return;
                }

                const data = await response.json();
                currentUser = data.user;

                document.getElementById("name").value = currentUser.name;
                document.getElementById("email").value = currentUser.email;
                document.getElementById("mobile").value = currentUser.mobile;
                document.getElementById("profileHeaderName").textContent = currentUser.name;

                // Avatar Display
                const avatarImg = document.getElementById("profileAvatarImg");
                if (currentUser.avatar) {
                    avatarImg.src = `/uploads/avatars/${currentUser.avatar}`;
                } else {
                    avatarImg.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(currentUser.name)}&background=6366f1&color=fff`;
                }

                // Role and Status Badges
                const roleBadge = document.getElementById("userRoleBadge");
                const navRoleBadge = document.getElementById("navRoleBadge");
                const statusBadge = document.getElementById("userStatusBadge");

                const userRole = currentUser.role || 'user';
                roleBadge.textContent = userRole.toUpperCase();
                roleBadge.className = userRole === 'admin' 
                    ? 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300'
                    : 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700';

                if (userRole === 'admin') {
                    navRoleBadge.classList.remove('hidden');
                    document.getElementById('adminHintText').textContent = '👑 Admin Access Enabled';
                }

                const userStatus = currentUser.status || 'active';
                statusBadge.textContent = userStatus.toUpperCase();
                statusBadge.className = userStatus === 'active'
                    ? 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700'
                    : 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700';

            } catch (err) {
                console.error("Profile load error", err);
            }
        }

        // Upload Profile Avatar
        async function uploadAvatar(input) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];
            const formData = new FormData();
            formData.append('avatar', file);

            const msgEl = document.getElementById("profileMessage");
            msgEl.innerHTML = `<span class="text-blue-600 font-semibold">Uploading photo...</span>`;

            try {
                const response = await fetch('/api/profile/avatar', {
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    document.getElementById("profileAvatarImg").src = data.avatar_url;
                    msgEl.innerHTML = `<span class="text-green-600 font-semibold">Avatar updated successfully!</span>`;
                    loadUsers(currentPage);
                } else {
                    msgEl.innerHTML = `<span class="text-red-600 font-semibold">${data.message || 'Upload failed'}</span>`;
                }
            } catch (err) {
                msgEl.innerHTML = `<span class="text-red-600 font-semibold">Upload error occurred.</span>`;
            }
        }

        // Update Profile Details
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
                message.innerHTML = `<span class="text-green-600 font-semibold">${data.message}</span>`;
                loadProfile();
                loadUsers(currentPage);
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

        // Change Password
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
            document.getElementById("passwordMessage").innerHTML = response.ok
                ? `<span class="text-green-600">${data.message}</span>`
                : `<span class="text-red-600">${data.message}</span>`;
        }

        // Personal Access Tokens Management (API Vault)
        async function loadTokens() {
            try {
                const response = await fetch('/api/tokens', {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json'
                    }
                });

                const data = await response.json();
                const tbody = document.getElementById('tokensTableBody');

                if (!data.tokens || data.tokens.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-400">No active API tokens found. Generate one above.</td></tr>`;
                    return;
                }

                let html = '';
                data.tokens.forEach(tok => {
                    html += `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2.5 font-mono text-xs text-gray-500">#${tok.id}</td>
                            <td class="px-4 py-2.5 font-semibold text-gray-800">${tok.name}</td>
                            <td class="px-4 py-2.5 text-xs text-gray-500">${new Date(tok.created_at).toLocaleString()}</td>
                            <td class="px-4 py-2.5 text-xs text-gray-500">${tok.last_used_at ? new Date(tok.last_used_at).toLocaleString() : 'Never'}</td>
                            <td class="px-4 py-2.5 text-center">
                                <button onclick="revokeToken(${tok.id})" class="text-red-600 hover:text-red-800 text-xs font-semibold px-2 py-1 rounded hover:bg-red-50">
                                    🗑️ Revoke
                                </button>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            } catch (err) {
                console.error("Token load error", err);
            }
        }

        async function createToken() {
            const tokenNameInput = document.getElementById('newTokenName');
            const tokenName = tokenNameInput.value.trim();

            if (!tokenName) {
                alert("Please provide a token name!");
                return;
            }

            try {
                const response = await fetch('/api/tokens', {
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ name: tokenName })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    tokenNameInput.value = '';
                    document.getElementById('generatedTokenVal').value = data.token;
                    document.getElementById('newlyCreatedTokenAlert').classList.remove('hidden');
                    loadTokens();
                } else {
                    alert(data.message || 'Token creation failed');
                }
            } catch (err) {
                alert("Error generating token");
            }
        }

        function copyGeneratedToken() {
            const copyInput = document.getElementById('generatedTokenVal');
            copyInput.select();
            navigator.clipboard.writeText(copyInput.value);
            alert("Token copied to clipboard! 📋");
        }

        async function revokeToken(tokenId) {
            if (!confirm("Are you sure you want to revoke this API token? Any application using it will lose access immediately.")) return;

            try {
                const response = await fetch(`/api/tokens/${tokenId}`, {
                    method: 'DELETE',
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json'
                    }
                });

                if (response.ok) {
                    loadTokens();
                } else {
                    alert("Failed to revoke token");
                }
            } catch (e) {
                alert("Error revoking token");
            }
        }

        // Load Dashboard Statistics
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
            document.getElementById("activeUsers").innerHTML = data.active_users ?? data.total_users;
            document.getElementById("totalAdmins").innerHTML = data.total_admins ?? 0;
        }

        // Load Recent Login History
        async function loadLoginHistory() {
            const response = await fetch('/api/login-history', {
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }
            });

            const data = await response.json();
            let rows = "";

            if (!data.login_history || data.login_history.length === 0) {
                rows = `<tr><td colspan="5" class="text-center py-6 text-gray-500">No Login History Found</td></tr>`;
            } else {
                data.login_history.forEach((history, index) => {
                    rows += `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">${index + 1}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">${history.browser}</td>
                            <td class="px-4 py-3 text-gray-600">${history.platform}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-500">${history.ip_address}</td>
                            <td class="px-4 py-3 text-gray-600">${new Date(history.login_at).toLocaleString()}</td>
                        </tr>
                    `;
                });
            }
            document.getElementById("loginHistoryTable").innerHTML = rows;
        }

        // Load Users Table with Role and Status Badges & Admin Controls
        async function loadUsers(page = 1) {
            currentPage = page;

            let search = document.getElementById("search").value;
            let sort = document.getElementById("sort").value;

            const response = await fetch(`/api/users?page=${page}&search=${search}&sort=${sort}`);
            const data = await response.json();

            let rows = "";
            const isAdmin = currentUser && currentUser.role === 'admin';

            data.data.forEach(user => {
                const userRole = user.role || 'user';
                const userStatus = user.status || 'active';
                const avatarSrc = user.avatar 
                    ? `/uploads/avatars/${user.avatar}` 
                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=random`;

                const roleBadgeClass = userRole === 'admin'
                    ? 'bg-amber-100 text-amber-800 border border-amber-300'
                    : 'bg-gray-100 text-gray-700';

                const statusBadgeClass = userStatus === 'active'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700';

                let adminActions = '';
                if (isAdmin) {
                    adminActions = `
                        <button onclick="toggleUserStatus(${user.id})"
                            title="${userStatus === 'active' ? 'Block User' : 'Activate User'}"
                            class="px-2.5 py-1 text-xs rounded-lg font-semibold ${userStatus === 'active' ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-green-600 hover:bg-green-700 text-white'} transition">
                            ${userStatus === 'active' ? '⛔ Block' : '✅ Unblock'}
                        </button>
                        <button onclick="changeUserRole(${user.id})"
                            title="Toggle Admin/User Role"
                            class="px-2.5 py-1 text-xs rounded-lg font-semibold bg-indigo-500 hover:bg-indigo-600 text-white transition">
                            🔄 ${userRole === 'admin' ? 'Make User' : 'Make Admin'}
                        </button>
                    `;
                }

                rows += `
                    <tr class="border-b hover:bg-blue-50 transition duration-200">
                        <td class="px-4 py-3 font-mono text-xs text-gray-500">#${user.id}</td>
                        <td class="px-4 py-3">
                            <img src="${avatarSrc}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-900">${user.name}</td>
                        <td class="px-4 py-3 text-gray-600">${user.email}</td>
                        <td class="px-4 py-3 text-gray-600">${user.mobile}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold ${roleBadgeClass}">${userRole.toUpperCase()}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold ${statusBadgeClass}">${userStatus.toUpperCase()}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                ${adminActions}
                                <button onclick="deleteUser(${user.id})"
                                    class="bg-red-500 hover:bg-red-600 text-white px-2.5 py-1 text-xs rounded-lg font-semibold shadow transition">
                                    🗑️ Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            document.getElementById("userTable").innerHTML = rows || `<tr><td colspan="8" class="text-center py-6 text-gray-400">No users found.</td></tr>`;

            let pagination = "";
            for (let i = 1; i <= data.last_page; i++) {
                pagination += `
                    <button onclick="loadUsers(${i})"
                        class="px-3.5 py-1.5 text-xs rounded-lg font-semibold shadow ${i == data.current_page ? 'bg-indigo-600 text-white' : 'bg-gray-200 hover:bg-blue-500 hover:text-white'}">
                        ${i}
                    </button>
                `;
            }
            document.getElementById("pagination").innerHTML = pagination;
        }

        // Admin Action: Toggle User Status
        async function toggleUserStatus(id) {
            try {
                const response = await fetch(`/api/users/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json'
                    }
                });

                const data = await response.json();
                if (response.ok) {
                    loadUsers(currentPage);
                    loadStats();
                } else {
                    alert(data.message || 'Action failed');
                }
            } catch (e) {
                alert('Network error');
            }
        }

        // Admin Action: Change User Role
        async function changeUserRole(id) {
            try {
                const response = await fetch(`/api/users/${id}/change-role`, {
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json'
                    }
                });

                const data = await response.json();
                if (response.ok) {
                    loadUsers(currentPage);
                    loadStats();
                } else {
                    alert(data.message || 'Action failed');
                }
            } catch (e) {
                alert('Network error');
            }
        }

        // Delete User
        async function deleteUser(id) {
            if (!confirm("Are you sure you want to delete this user?")) return;

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

        // Logout
        async function logout() {
            await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }
            });

            localStorage.removeItem("auth_token");
            localStorage.removeItem("new_device_alert");
            window.location.href = "/login";
        }

        document.getElementById("search").addEventListener("keyup", () => loadUsers(1));
        document.getElementById("sort").addEventListener("change", () => loadUsers(1));

        // Initial Boot
        (async () => {
            await loadProfile();
            loadUsers();
            loadStats();
            loadLoginHistory();
            loadTokens();
        })();
    </script>
</body>
</html>