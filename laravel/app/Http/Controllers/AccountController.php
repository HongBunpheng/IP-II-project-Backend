<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    // List all accounts
    public function index()
    {
        return response()->json(Account::all(), 200);
    }

    // Store a new account
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
}
