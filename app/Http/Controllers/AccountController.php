<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    // List all accounts
    public function index()
    {
        return response()->json(Account::all(), 200);
    }

    // Store a new account (Registration)
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
        ]);

        return response()->json([
            'message' => 'Account created successfully',
            'data' => $account
        ], 201);
    }

    // Show a single account
    public function show(Account $account)
    {
        return response()->json($account, 200);
    }

    // Update an account
    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:accounts,email,' . $account->id,
            'password' => 'sometimes|string|min:6',
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

    // Delete an account
    public function destroy(Account $account)
    {
        $account->delete();

        return response()->json(['message' => 'Account deleted'], 204);
    }

    // ✅ Login method
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $account = Account::where('email', $request->email)->first();

        if (!$account || !Hash::check($request->password, $account->password)) {
            return response()->json([
                'message' => [
                    'email' => 'Invalid email or password'
                ]
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful!',
            'user' => $account
        ]);
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $account = Account::where('email', $request->email)->first();
        if (!$account) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        $code = rand(100000, 999999); // 6-digit code
        $account->reset_code = $code;
        $account->reset_code_expires_at = now()->addMinutes(10);
        $account->save();

        // For real apps, send via email
        return response()->json(['message' => 'Verification code sent', 'code' => $code]); // Show code for dev
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
}
