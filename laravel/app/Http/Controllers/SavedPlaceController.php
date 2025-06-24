<?php

namespace App\Http\Controllers;

use App\Models\SavedPlace;
use Illuminate\Http\Request;

class SavedPlaceController extends Controller
{
    public function store(Request $request)
    {
        $saved = SavedPlace::firstOrCreate([
            'account_id' => $request->account_id,
            'saveable_id' => $request->saveable_id,
            'saveable_type' => $request->saveable_type,
        ]);
        return response()->json($saved->load('saveable'));
    }

    public function destroy(Request $request)
    {
        SavedPlace::where([
            'saveable_id' => $request->saveable_id,
            'saveable_type' => $request->saveable_type,
        ])->delete();

        return response()->json(['message' => 'Unsave successful']);
    }


    public function getUserSaved($account_id)
    {
        return SavedPlace::with('saveable')->where('account_id', $account_id)->get();
    }
}
