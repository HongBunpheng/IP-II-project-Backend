<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        return response()->json(Restaurant::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'promotion' => 'required',
            'location' => 'required',
            'address' => 'required',
            'contact' => 'required',
            'rating' => 'required|numeric|min:0|max:5',
            'price' => 'required|numeric',
            'details' => 'required',
            'image.*' => 'nullable|image',
            'detail_image.*' => 'nullable|image',
        ]);

        $hotel = new Restaurant($request->except(['image', 'detail_image']));

        // Handle image array
        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $img) {
                $imagePaths[] = $img->store('uploads', 'public');
            }
        }
        $hotel->image = $imagePaths;

        // Handle detail image array
        $detailImagePaths = [];
        if ($request->hasFile('detail_image')) {
            foreach ($request->file('detail_image') as $img) {
                $detailImagePaths[] = $img->store('uploads', 'public');
            }
        }
        $hotel->detail_image = $detailImagePaths;

        $hotel->save();

        return response()->json(['message' => 'Hotel saved', 'hotel' => $hotel], 201);
    }

    public function show(Restaurant $hotel)
    {
        return response()->json($hotel);
    }

    public function update(Request $request, Restaurant $hotel)
    {
        $request->validate([
            'image.*' => 'nullable|image',
            'detail_image.*' => 'nullable|image',
        ]);

        $hotel->fill($request->except(['image', 'detail_image']));

        // Update image array
        if ($request->hasFile('image')) {
            $imagePaths = [];
            foreach ($request->file('image') as $img) {
                $imagePaths[] = $img->store('uploads', 'public');
            }
            $hotel->image = $imagePaths;
        }

        // Update detail image array
        if ($request->hasFile('detail_image')) {
            $detailImagePaths = [];
            foreach ($request->file('detail_image') as $img) {
                $detailImagePaths[] = $img->store('uploads', 'public');
            }
            $hotel->detail_image = $detailImagePaths;
        }

        $hotel->save();

        return response()->json($hotel);
    }

    public function destroy(Restaurant $hotel)
    {
        $hotel->delete();
        return response()->json(null, 204);
    }
}
