<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function addUser(Request $request)
    {
        $admin = auth()->user();
        if ($admin->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:user,admin'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return response()->json(['message' => 'User added successfully', 'user' => $user]);
    }
    public function getProfile(Request $request)
    {
        return response()->json(auth()->user());
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();

            // ✅ Log incoming request data (for debugging)
            \Log::info('Update Profile Request:', $request->all());

            // ✅ Validate input
            $request->validate([
                'name' => 'required|string',
                'password' => 'nullable|min:6',
                'profile_pic' => 'nullable|file|mimes:jpeg,png,jpg|max:2048' // Ensure it's an actual file
            ]);

            // ✅ Update profile picture if uploaded
            if ($request->hasFile('profile_pic')) {
                $path = $request->file('profile_pic')->store('profile_pics', 'public');
                $user->profile_pic = "/storage/{$path}";
            }

            // ✅ Update name & password
            $user->name = $request->name;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);

        } catch (\Exception $e) {
            \Log::error("Profile Update Error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }





}

