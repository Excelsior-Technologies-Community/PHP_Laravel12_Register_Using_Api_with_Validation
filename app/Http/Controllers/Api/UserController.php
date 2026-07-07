<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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