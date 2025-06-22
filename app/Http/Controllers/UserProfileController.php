<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Illuminate\Support\Facades\Validator; // For validation

class UserProfileController extends Controller
{
    // Register new user
    public function register(Request $request) {
        // Validate incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nickname' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:100',
            'instagram' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
        ]);

        // Hash the password
        $validated['password'] = Hash::make($validated['password']); // Use Hash facade

        // Create the user profile
        $user = UserProfile::create($validated);

        // Return success message with user info
        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user
        ], 201);
    }

    // Show user profile
    public function show($id) {
        return UserProfile::findOrFail($id);
    }

    // Update user profile
    public function update(Request $request, $id) {
        $user = UserProfile::findOrFail($id);

        // Validate incoming request fields if necessary
        $validated = $request->validate([
            'nickname' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:100',
            'instagram' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
        ]);

        // Update the user with validated data
        $user->update($validated);

        // Return success message
        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    // Upload profile image
    public function uploadProfile(Request $request) {
        // Validate file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required|exists:users,id', // Validate that user_id exists
        ]);

        // Handle file upload
        $file = $request->file('image');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/profile'), $filename);

        // Find user and save the profile image path
        $user = UserProfile::find($request->user_id);
        $user->profile_image = 'uploads/profile/'.$filename;
        $user->save();

        // Return the updated profile image URL
        return response()->json(['profile_image' => $user->profile_image], 200);
    }

    // Upload cover image
    public function uploadCover(Request $request) {
        // Validate file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required|exists:users,id', // Validate that user_id exists
        ]);

        // Handle file upload
        $file = $request->file('image');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/cover'), $filename);

        // Find user and save the cover image path
        $user = UserProfile::find($request->user_id);
        $user->cover_image = 'uploads/cover/'.$filename;
        $user->save();

        // Return the updated cover image URL
        return response()->json(['cover_image' => $user->cover_image], 200);
    }
}
