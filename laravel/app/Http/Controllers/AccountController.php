<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        return response()->json(Account::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email',
            'password' => 'required|string|min:6',
        ]);

        $account = Account::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => null,
            'dob' => null,
            'address' => null,
            'profile_picture' => '',
            'featured_picture' => [],
            'social_links' => [],
            'bio' => '',
            'nickname' => '',
        ]);

        $token = $account->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Account created successfully',
            'token' => $token,
            'account' => $account
        ], 201);
    }

    public function show(Account $account)
    {
        return response()->json($account, 200);
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:accounts,email,' . $account->id,
            'password' => 'sometimes|string|min:6',
            'phone' => 'nullable|string',
            'dob' => 'nullable|date',
            'address' => 'nullable|string',
            'profile_picture' => 'nullable|string',
            'featured_picture' => 'nullable|array',
            'social_links' => 'nullable|array',
            'bio' => 'nullable|string',
            'nickname' => 'nullable|string',
        ]);

        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $account->update($data);

        return response()->json([
            'message' => 'Account updated',
            'data' => $account
        ]);
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return response()->json(['message' => 'Account deleted'], 204);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $account = Account::where('email', $request->email)->first();

        if (!$account || !Hash::check($request->password, $account->password)) {
            return response()->json([
                'message' => ['email' => 'Invalid email or password']
            ], 401);
        }

        $token = $account->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'account' => $account
        ]);
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $account = Account::where('email', $request->email)->first();
        if (!$account) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        $code = rand(100000, 999999);
        $account->reset_code = $code;
        $account->reset_code_expires_at = now()->addMinutes(10);
        $account->save();

        return response()->json(['message' => 'Verification code sent', 'code' => $code]);
    }

    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required'
        ]);

        $account = Account::where('email', $request->email)
            ->where('reset_code', $request->code)
            ->where('reset_code_expires_at', '>', now())
            ->first();

        if (!$account) {
            return response()->json(['message' => 'Invalid or expired code'], 422);
        }

        return response()->json(['message' => 'Code verified']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $account = Account::where('email', $request->email)
            ->where('reset_code', $request->code)
            ->where('reset_code_expires_at', '>', now())
            ->first();

        if (!$account) {
            return response()->json(['message' => 'Invalid or expired code'], 422);
        }

        $account->password = Hash::make($request->password);
        $account->reset_code = null;
        $account->reset_code_expires_at = null;
        $account->save();

        return response()->json(['message' => 'Password reset successfully']);
    }

    // ✅ Get current logged-in user
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    // ✅ Update current user's profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'address' => 'nullable|string',
            'social_links' => 'nullable|array',
            'featured_picture' => 'nullable|array',
            'profile_picture' => 'nullable|string',
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'account' => $user
        ]);
    }

    // ✅ Handle profile image upload
    public function uploadImage(Request $request)
    {
        $user = $request->user();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('uploads', 'public'); // ✅ same as your existing usage

            $user->profile_picture = '/storage/' . $path;
            $user->save();

            return response()->json([
                'message' => 'Profile picture updated',
                'profile_picture' => $user->profile_picture
            ]);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }

    // ✅ Handle featured photo upload
    public function uploadFeaturedPhoto(Request $request)
    {
        $user = $request->user();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('uploads', 'public'); // ✅ consistent with your detail image logic

            $url = '/storage/' . $path;

            $current = $user->featured_picture ?? [];
            $current[] = $url;

            $user->featured_picture = $current;
            $user->save();

            return response()->json([
                'message' => 'Featured photo added',
                'path' => $url
            ]);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }

    // ✅ Delete current user account
    public function deleteProfile(Request $request)
    {
        $user = $request->user();
        $user->delete();

        return response()->json(['message' => 'Your account has been deleted.']);
    }
}
