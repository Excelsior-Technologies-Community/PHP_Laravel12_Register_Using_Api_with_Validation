<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Return all registered users
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    // Return profile of logged-in user
    public function profile(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }
}
