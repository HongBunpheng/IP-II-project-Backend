<?php

namespace App\Http\Controllers;

use App\Models\SavedPlace;
use Illuminate\Http\Request;

class SavedPlaceController extends Controller
{
    public function store(Request $request) {
        $saved = SavedPlace::firstOrCreate([
            'user_id' => $request->user_id,
            'place_id' => $request->place_id,
        ]);
        return response()->json($saved);
    }

    public function destroy($place_id) {
        SavedPlace::where('place_id', $place_id)->delete();
        return response()->json(['message' => 'Unsave successful']);
    }

    public function getUserSaved($user_id) {
        return SavedPlace::with('place')->where('user_id', $user_id)->get();
    }
}