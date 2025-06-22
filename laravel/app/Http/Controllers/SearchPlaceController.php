<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SearchPlace;

class SearchPlaceController extends Controller
{
    // 🔍 GET /api/places => Search/filter places
    public function index(Request $request)
    {
        $query = SearchPlace::query();

        if ($request->has('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        return response()->json($query->get());
    }

    // 📝 POST /api/places => Add a new place
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $place = SearchPlace::create($validated);

        return response()->json([
            'message' => 'Place created successfully',
            'data' => $place
        ], 201);
    }
}