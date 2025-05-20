<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        return response()->json(\App\Models\Hotel::all(), 200);
    }

    public function store(Request $request)
    {
        $hotel = \App\Models\Hotel::create($request->all());

        return response()->json([
            'message' => 'Hotel created successfully',
            'data' => $hotel
        ], 201);
    }


    public function show(Hotel $hotel)
    {
        return $hotel;
    }

    public function update(Request $request, Hotel $hotel)
    {
        $hotel->update($request->all());
        return response()->json($hotel);
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return response()->json(null, 204);
    }
}
