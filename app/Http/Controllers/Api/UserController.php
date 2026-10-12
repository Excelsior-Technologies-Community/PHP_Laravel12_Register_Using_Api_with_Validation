<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\LoginHistory;

class UserController extends Controller
{

    /**
     * Users List
     * Search
     * Sorting
     * Pagination
     */

    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('mobile', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        $sort = strtolower($request->sort);

        if ($sort == 'za') {
            $query->orderBy('id', 'DESC');
        } else {
            $query->orderBy('id', 'ASC');
        }

        // Pagination
        $users = $query->paginate(4);

        return response()->json($users);
    }

    /**
     * Logged In User Profile
     */

    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    /**
     * Delete User
     */

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {

            return response()->json([

                'success' => false,

                'message' => 'User not found.'

            ], 404);
        }

        $user->delete();

        return response()->json([

            'success' => true,

            'message' => 'User deleted successfully.'

        ]);
    }

    /**
     * Update Logged In User Profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],

            'mobile' => [
                'required',
                'digits:10',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => $user
        ]);
    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();


        $request->validate([

            'current_password' => [
                'required'
            ],

            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/'
            ],

        ]);

        if (!Hash::check($request->current_password, $user->password)) {

            return response()->json([

                'success' => false,

                'message' => 'Current password is incorrect.'

            ], 422);
        }

        $user->update([

            'password' => Hash::make($request->password)

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Password changed successfully.'

        ]);
    }

    /**
     * Login History
     */
    public function loginHistory(Request $request)
    {
        $histories = LoginHistory::where('user_id', $request->user()->id)
            ->latest('login_at')
            ->get([
                'id',
                'ip_address',
                'browser',
                'platform',
                'login_at'
            ]);

        return response()->json([
            'success' => true,
            'login_history' => $histories
        ]);
    }
    /**
     * Upload Profile Avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/avatars');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            if ($user->avatar && file_exists(public_path('uploads/avatars/' . $user->avatar))) {
                @unlink(public_path('uploads/avatars/' . $user->avatar));
            }

            $file->move($destinationPath, $filename);

            $user->avatar = $filename;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile avatar uploaded successfully.',
                'avatar' => $filename,
                'avatar_url' => asset('uploads/avatars/' . $filename),
                'user' => $user
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image file uploaded.'
        ], 400);
    }

    /**
     * Toggle User Status (Active / Blocked) - Admin Only
     */
    public function toggleStatus(Request $request, $id)
    {
        $currentUser = $request->user();
        if ($currentUser->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin privileges required.'
            ], 403);
        }

        $targetUser = User::find($id);
        if (!$targetUser) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        if ($targetUser->id === $currentUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change your own account status.'
            ], 400);
        }

        $newStatus = ($targetUser->status === 'blocked') ? 'active' : 'blocked';
        $targetUser->status = $newStatus;
        $targetUser->save();

        if ($newStatus === 'blocked') {
            $targetUser->tokens()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => "User status updated to {$newStatus}.",
            'status' => $newStatus,
            'user' => $targetUser
        ]);
    }

    /**
     * Change User Role (User / Admin) - Admin Only
     */
    public function changeRole(Request $request, $id)
    {
        $currentUser = $request->user();
        if ($currentUser->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin privileges required.'
            ], 403);
        }

        $targetUser = User::find($id);
        if (!$targetUser) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        if ($targetUser->id === $currentUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot alter your own admin role.'
            ], 400);
        }

        $newRole = ($targetUser->role === 'admin') ? 'user' : 'admin';
        $targetUser->role = $newRole;
        $targetUser->save();

        return response()->json([
            'success' => true,
            'message' => "User role updated to {$newRole}.",
            'role' => $newRole,
            'user' => $targetUser
        ]);
    }

    /**
     * List Authenticated User's API Tokens
     */
    public function listTokens(Request $request)
    {
        $tokens = $request->user()->tokens()->orderBy('created_at', 'desc')->get([
            'id', 'name', 'last_used_at', 'created_at'
        ]);

        return response()->json([
            'success' => true,
            'tokens' => $tokens
        ]);
    }

    /**
     * Generate New Personal Access Token
     */
    public function createToken(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:50'
        ]);

        $plainTextToken = $request->user()->createToken($request->name)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Personal access token generated successfully.',
            'token' => $plainTextToken,
            'name' => $request->name
        ]);
    }

    /**
     * Revoke / Delete API Token
     */
    public function revokeToken(Request $request, $id)
    {
        $deleted = $request->user()->tokens()->where('id', $id)->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found or already revoked.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'API Token revoked successfully.'
        ]);
    }

    /**
     * Dashboard Statistics
     */
    public function dashboardStats()
    {
        return response()->json([
            'total_users' => User::count(),
            'latest_user' => User::latest()->first(),
            'today_users' => User::whereDate('created_at', today())->count(),
            'verified_emails' => User::whereNotNull('email_verified_at')->count(),
            'active_users' => User::where('status', 'active')->count(),
            'blocked_users' => User::where('status', 'blocked')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
        ]);
    }
}
