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
     * Dashboard Statistics
     */

    public function dashboardStats()
    {

        return response()->json([

            'total_users' => User::count(),

            'latest_user' => User::latest()->first(),

            'today_users' => User::whereDate('created_at', today())->count(),

            'verified_emails' => User::whereNotNull('email_verified_at')->count(),

        ]);
    }
}
