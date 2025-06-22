<?php

namespace App\Http\Controllers;

use App\Models\Authen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:authens,email',
            'password'        => 'required|string|min:6',
            'confirmPassword' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Authen::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Account created successfully', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = Authen::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        return response()->json(['message' => 'Login successful', 'user' => $user], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = Authen::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        $code = Str::random(6);
        $user->verification_code = $code;
        $user->save();

        // Here you would send email/SMS – for now just return it
        return response()->json([
            'message' => 'Verification code sent',
            'code'    => $code // 🔐 for testing; remove in production
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string'
        ]);

        $user = Authen::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        return response()->json(['message' => 'Code verified'], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'           => 'required|email',
            'code'            => 'required|string',
            'password'        => 'required|min:6',
            'confirmPassword' => 'required|same:password'
        ]);

        $user = Authen::where('email', $request->email)
            ->where('verification_code', $request->code)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid code or email'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->verification_code = null; // clear code after reset
        $user->save();

        return response()->json(['message' => 'Password reset successful'], 200);
    }
}
